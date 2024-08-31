<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Event;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables,Auth;
use Illuminate\Support\Facades\Storage;

use App\UserSetting;
use Illuminate\Support\Facades\Log;
class CreatorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin/creator/creators');
    }

    public function getCreatorList(Request $request)
    {
        $admin_id   =   Auth::user()->id;
        $data   =   User::role('Creator')->orderBy('id','desc')->get();
        //echo "<pre>";print_r($data);die;
        return Datatables::of($data)
                ->addColumn('status', function($data){
                    return $data->status ? 'Active' : 'Inactive';
                })
                ->addColumn('action', function($data){
                    if($data->name == 'Super Admin'){
                        return '';
                    }
                    if (Auth::user()->can('manage_creator')){
                        $msg    =   "'Are you sure want to take this action?'";
                        if($data->status)
                            $sHtml  =   '<a title="Make Creator Inactive" onclick="return confirm('.$msg.')" href="'.url('admin/creator/status/0/'.$data->id).'"><i class="ik ik-x f-16 ml-10 "></i></a>';
                        else
                            $sHtml  =   '<a title="Make Creator Active" onclick="return confirm('.$msg.')" href="'.url('admin/creator/status/1/'.$data->id).'"><i class="ik ik-check f-16 ml-10"></i></a>';

                        return '<div class="table-actions" style="text-align:left">
                                <a href="'.url('admin/creator/profile/'.$data->id).'"><i class="ik ik-eye f-16" title="View Details"></i></a>
                                <a href="'.url('admin/creator/'.$data->id).'" ><i class="ik ik-edit-2 f-16 mr-15" title="Edit User"></i></a> '.$sHtml.'
                            </div>';
                    }else{
                        return '';
                    }
                })
                ->rawColumns(['roles','permissions','action'])
                ->make(true);
    }

    public function store(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'mobile' => [
                'required', 'digits:10',
                'unique:users,mobile',
            ],
            'referral' => [
                'nullable', 'exists:user_settings,referral',
            ]
        ]);

        \Log::info('Store method called with mobile: ' . $request->mobile);

        // Create user
        $user = User::create([
            'username' => ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)) . ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)),
            'mobile' => $request->mobile,
            'ip' => $request->ip()
        ]);

        if ($user) {
            Log::info('User created successfully with ID: ' . $user->id);
            $user->syncRoles('Member');

            $used_referral = 0;
            $rf_user_id = 0;
            if (isset($request->referral)) {
                $rf_user_data = UserSetting::where('referral', $request->referral)->first();
                if ($rf_user_data) {
                    $rf_user_id = $rf_user_data->user_id;
                    $used_referral = $request->referral;
                }
            }

            // Create user settings
            $userSetting = UserSetting::create([
                'user_id' => $user->id,
                'used_referral' => $used_referral,
                'rf_user_id' => $rf_user_id,
                'referral' => bin2hex(random_bytes(4)),
            ]);

            \Log::info('UserSetting creation attempted for user ID: ' . $user->id);
            \Log::info('UserSetting Created:', ['user_id' => $user->id, 'result' => $userSetting]);

            return redirect()->back()->with('success', 'User added successfully!');
        }

        \Log::error('Failed to create user.');
        return redirect()->back()->with('error', 'Failed to create user.');
    }


    public function create()
    {
        try
        {
            return view('admin/creator/create-creator');

        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);

        }
    }

    public function changeStatus($status,$uid)
    {
        $data   = User::find($uid);
        if($data){
            if($status)
                $data->update(['status' => $status]);
            else
            $data->update(['status' => $status,'token' => null]);
            return redirect()->back()->with('success', 'Staus updated successfully!');
        }else{
            return redirect()->back()->with('error', 'Record not found');
        }
    }

    public function edit($id)
    {
        try
        {
            $user  = User::find($id);
            if($user){
                return view('admin/creator/creator-edit', compact('user'));
            }else{
                return redirect('404');
            }

        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function update(Request $request)
    {
        // update user info
        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'name'          => 'required | string ',
            'email'         => 'required | email',
            'mobile'        => 'required | digits:10',
            'description'   => 'required ',
        ]);

        // check validation for password match
        if(isset($request->password)){
            $validator = Validator::make($request->all(), [
                'password' => 'required | confirmed'
            ]);
        }

        // check validation for image upload
        if(isset($request->profile_pic)){
            $validator = Validator::make($request->all(), [
                'profile_pic' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }

        if(isset($request->cover_pic)){
            $validator = Validator::make($request->all(), [
                'cover_pic' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try{
            $user = User::find($request->id);

            $update = $user->update([
                'name'              => $request->name,
                'email'             => $request->email,
                'mobile'            => $request->mobile,
                'description'       => $request->description,
            ]);

            // update password if user input a new password
            if(isset($request->password)){
                $update = $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            // update profile image of user
            $file = $request->file('profile_pic');
            if($file){
                $imageName=$file->getClientOriginalName();
                $filePath = 'profile/'.time().'-'. $imageName;
                Storage::disk('s3')->put($filePath, file_get_contents($file));
                Storage::disk('s3')->delete($user->profile_pic);

                $update = $user->update([
                    'profile_pic' => $filePath
                ]);
            }

            $cover = $request->file('cover_pic');
            if($cover){
                $imageName=$cover->getClientOriginalName();
                $filePath = time().'-'. $imageName;
                Storage::disk('s3')->put($filePath, file_get_contents($cover));
                Storage::disk('s3')->delete($user->cover_pic);

                $update = $user->update([
                    'profile_pic' => $filePath
                ]);
            }

            return redirect('admin/creators')->with('success', 'Creator information updated succesfully!');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function userProfile($id)
    {
        try
        {
            $user  = User::find($id);
            //$events =   Event::with('joinusers')->withSum('earning','amount')->where('user_id',$id)->paginate(20);
            $events =   Event::where('user_id',$id)->latest()->paginate(20);
            //echo "<pre>";print_r($events);die;
            return view('admin/creator/profile', compact('user','events'));
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function delete($id)
    {
        $user   = User::find($id);
        if($user){
            $user->delete();
            return redirect('admin\creators')->with('success', 'User removed!');
        }else{
            return redirect('admin\creators')->with('error', 'User not found');
        }
    }
}
