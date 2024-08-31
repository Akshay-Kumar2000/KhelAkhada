<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables,Auth;
use Illuminate\Support\Facades\Storage;
use DB;

class UserController extends Controller
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
        return view('admin/user/users');
    }

    public function getUserList(Request $request)
    {
        $data  =    User::select('id','name','email')->where('admin_added', 1);

        $data   =   User::select('id','name','email')->rightJoin('user_roles','users.id','=','user_roles.user_id')
                    ->where('user_roles.role_id', 3)->union($data)->orderBy('id','desc')->get();

        return Datatables::of($data)
                ->addColumn('roles', function($data){
                    $roles = $data->getRoleNames()->toArray();
                    $badge = '';
                    if($roles){
                        $badge = implode(' , ', $roles);
                    }

                    return $badge;
                })
                ->addColumn('permissions', function($data){
                    $roles = $data->getAllPermissions();
                    $badges = '';
                    foreach ($roles as $key => $role) {
                        $badges .= '<span class="badge badge-dark m-1">'.$role->name.'</span>';
                    }

                    return $badges;
                })
                ->addColumn('action', function($data){
                    if($data->name == 'Super Admin'){
                        return '';
                    }
                    if (Auth::user()->can('manage_user')){
                        $msg    =   "'Are you sure want to take this action?'";
                        if($data->status)
                            $sHtml  =   '<a title="Make Admin Inactive" onclick="return confirm('.$msg.')" href="'.url('admin/user/status/0/'.$data->id).'"><i class="ik ik-x f-16 ml-10 text-yellow"></i></a>';
                        else
                            $sHtml  =   '<a title="Make Admin Active" onclick="return confirm('.$msg.')" href="'.url('admin/user/status/1/'.$data->id).'"><i class="ik ik-check f-16 ml-10 text-blue"></i></a>';

                        return '<div class="table-actions">
                                <a href="'.url('admin/user/profile/'.$data->id).'"><i class="ik ik-eye f-16 text-blue" title="View Details"></i></a>
                                <a href="'.url('admin/user/'.$data->id).'" ><i class="ik ik-edit-2 f-16 mr-15 text-green" title="Edit User"></i></a>
                                <a href="'.url('admin/user/upload-images/'.$data->id).'"><i class="ik ik-image f-16" title="Upload images"></i></a>
                                <a href="'.url('admin/user/upload-videos/'.$data->id).'"><i class="ik ik-video f-16" title="Upload videos"></i></a>
                                <a href="'.url('admin/user/settings/'.$data->id).'"><i class="ik ik-settings f-16" title="Change settings"></i></a>'.$sHtml.'
                            </div>';
                            //<a href="'.url('admin/user/delete/'.$data->id).'"><i class="ik ik-trash-2 f-16 text-red" title="delete User"></i></a>
                    }else{
                        return '';
                    }
                })
                ->rawColumns(['roles','permissions','action'])
                ->make(true);
    }

    public function create()
    {
        try
        {
            $roles = Role::pluck('name','id');
            return view('admin/user/create-user', compact('roles'));

        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);

        }
    }

    public function store(Request $request)
    {
        // create user
        $validator = Validator::make($request->all(), [
            'name'     => 'required | string ',
            'email'    => 'required | email | unique:users',
            'mobile_no'    => 'required | digits:10 | unique:users',
            'password' => 'required | confirmed',
            'profile_pic' => 'image|mimes:jpeg,png,jpg|max:2048',
            'role'     => 'required'
        ]);
        //echo "<pre>"; print_r($request->all());die;
        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try
        {
            $imageName  =   '';
            //Upload image on AWS S3
            $file = $request->file('profile_pic');
            if($file){
                $imageName=$file->getClientOriginalName();
                $filePath = time().'-'. $imageName;
                Storage::disk('s3')->put($filePath, file_get_contents($file));
            }

            $slug   =   strtolower(str_replace(' ', '-', $request->name)).'-'.time();

            $firebase = Http::post('https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=AIzaSyBcgxIu2bAtBzLhqjahJxsHsojmYwzblto', [
                'dynamicLinkInfo' => ([
                    'domainUriPrefix' => 'https://creator.rawmag.in',
                    'link' => 'https://creator.rawmag.in/'.$slug,
                    'androidInfo' => ([
                        'androidPackageName' => 'in.rawmag.app'
                    ]),
                    'iosInfo' => ([
                        'iosBundleId' => 'in.rawmag.app'
                    ]),
                ]),
            ]);
            $lnkData    =   json_decode($firebase, true);

            $response = Http::post('https://app.rawmag.in/api/get-password-hash', [
                'password' => $request->password,
            ]);
            $arrData    =   json_decode($response, true);

            if($arrData['success']){
                $password   =   $arrData['data'];
            }else{
                return redirect()->back()->withInput()->with('error', 'System error!');
            }

            // store user information
            $user = User::create([
                        'name'              => $request->name,
                        'slug'              => $slug,
                        'creator_link'      => $lnkData['shortLink'],
                        'email'             => $request->email,
                        'admin_added'       => 1,
                        'mobile_no'         => $request->mobile_no,
                        'profile_pic'       => $imageName,
                        'password'          => $password,
                        'created_at'        => date('Y-m-d'),
                    ]);

            if($request->role == 6){
                DB::table('user_roles')->insert(
                    ['user_id' => $user->id, 'role_id' => 3 , 'role_name' => 'CREATOR','created_at' => date('Y-m-d'), 'created_at' => date('Y-m-d h:i:s')]
                );
            }
            // assign new role to the user
            $user->syncRoles($request->role);

            if($user){
                return redirect('admin\users')->with('success', 'New user created!');
            }else{
                return redirect('admin\users')->with('error', 'Failed to create new user! Try again.');
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function edit($id)
    {
        try
        {
            $user  = User::with('roles','permissions')->find($id);

            if($user){
                $user_role = $user->roles->first();
                $roles     = Role::pluck('name','id');

                return view('admin/user/user-edit', compact('user','user_role','roles'));
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
            'id'       => 'required',
            'name'     => 'required | string ',
            'email'    => 'required | email',
            'role'     => 'required',
            'mobile_no'    => 'required | digits:10',
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

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try{

            $user = User::find($request->id);

            $update = $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile_no'    => $request->mobile_no,
            ]);

            // update password if user input a new password
            if(isset($request->password)){
                $response = Http::post('https://app.rawmag.in/api/get-password-hash', [
    				'password' => $request->password,
    			]);
                $arrData    =   json_decode($response, true);

                if($arrData['success']){
                    $update = $user->update([
                        'password' => $arrData['data']
                    ]);
                }
            }

            if(!$user->slug){
                $slug   =   strtolower(str_replace(' ', '-', $request->name)).'-'.time();

                $firebase = Http::post('https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=AIzaSyBcgxIu2bAtBzLhqjahJxsHsojmYwzblto', [
                    'dynamicLinkInfo' => ([
                        'domainUriPrefix' => 'https://creator.rawmag.in',
                        'link' => 'https://creator.rawmag.in/'.$slug,
                        'androidInfo' => ([
                            'androidPackageName' => 'in.rawmag.app'
                        ]),
                        'iosInfo' => ([
                            'iosBundleId' => 'in.rawmag.app'
                        ]),
                    ]),
                ]);
                $lnkData    =   json_decode($firebase, true);
                $user->update([
                    'slug' => $slug,
                    'creator_link' => $lnkData['shortLink'],
                ]);
            }
            // update profile image of user
            $file = $request->file('profile_pic');
            if($file){

                $imageName=$file->getClientOriginalName();
                $filePath = time().'-'. $imageName;
                Storage::disk('s3')->put($filePath, file_get_contents($file));
                Storage::disk('s3')->delete($user->profile_pic);

                $update = $user->update([
                    'profile_pic' => $filePath
                ]);

            }

            if($request->role == 6){
                $roleCount  =   DB::table('user_roles')->where('user_id',$user->id)->count();
                if(!$roleCount){
                    DB::table('user_roles')->insert(
                        ['user_id' => $user->id, 'role_id' => 3 , 'role_name' => 'CREATOR','created_at' => date('Y-m-d'), 'created_at' => date('Y-m-d h:i:s')]
                    );
                }
            }

            // sync user role
            //$user->syncRoles($request->role);
            return redirect()->back()->with('success', 'User information updated succesfully!');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function settings($id)
    {
        try
        {
            $user  = User::with('roles','permissions')->find($id);
            return view('admin/user/user-settings', compact('user'));
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function updateSettings(Request $request)
    {
        // update user info
        $validator = Validator::make($request->all(), [
            'id'                    => 'required',
            'salary'                => 'nullable|numeric',
            'stream_moth_limit'     => 'nullable|numeric',
            'stream_day_limit'      => 'nullable|numeric',
            'stream_maximum_time'   => 'nullable|numeric',
            'gift_limit'            => 'nullable|numeric',
        ]);

        // check validation for password match
        if($request->salary > 0){
            $validator = Validator::make($request->all(), [
                'video_commission'              => 'nullable|numeric|min:0|max:100',
                'gift_commission'               => 'nullable|numeric|min:0|max:100',
                'stream_entry_commission'       => 'nullable|numeric|min:0|max:100',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        //echo "<pre>"; print_r($request->all());die;
        try{

            $user = User::find($request->id);

            if($request->status)
                $status =   1;
            else
                $status =   0;

            if($request->stream_only)
                $stream_only =   1;
            else
                $stream_only =   0;

            if($request->is_premium)
                $is_premium =   1;
            else
                $is_premium =   0;

            $update = $user->update([
                'salary'            => $request->salary,
                'event_time'        => $request->stream_moth_limit * 3600,
                'day_time_limit'    => $request->stream_day_limit * 60,
                'e_max_time'        => $request->stream_maximum_time * 60,
                'g_limit'           => $request->gift_limit,
                'v_commission'      => $request->video_commission,
                'g_commission'      => $request->gift_commission,
                'e_commission'      => $request->stream_entry_commission,
                'status'            => $status,
                'is_premium'        => $is_premium,
                'stream_only'       => $stream_only,
            ]);

            return redirect('admin\users')->with('success', 'User settings updated succesfully!');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);

        }
    }

    public function changeStatus($status,$uid)
    {
        $data   = User::find($uid);
        if($data){
            $data->update(['status' => $status]);
            return redirect()->back()->with('success', 'Staus updated successfully!');
        }else{
            return redirect()->back()->with('error', 'Record not found');
        }
    }

    public function delete($id)
    {
        $user   = User::find($id);
        if($user){
            $user->delete();
            return redirect('admin\users')->with('success', 'User removed!');
        }else{
            return redirect('admin\users')->with('error', 'User not found');
        }
    }

}
