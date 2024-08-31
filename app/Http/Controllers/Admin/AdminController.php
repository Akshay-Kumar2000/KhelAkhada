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

class AdminController extends Controller
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
        return view('admin/admin/admins');
    }

    public function getAdminList(Request $request)
    {
        $data   =   User::select('id','name','email','users.status')
                    ->where('is_admin', 1)->orderBy('id','desc')->get();
        
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
                ->addColumn('status', function($data){
                    return $data->status ? 'Active' : 'Inactive';
                })
                ->addColumn('action', function($data){
                    if($data->name == 'Super Admin'){
                        return '';
                    }
                    if (Auth::user()->can('manage_admin')){
                        $msg    =   "'Are you sure want to take this action?'";
                        if($data->status)
                            $sHtml  =   '<a title="Make Admin Inactive" onclick="return confirm('.$msg.')" href="'.url('admin/admin/status/0/'.$data->id).'"><i class="ik ik-x f-16 ml-10 text-yellow"></i></a>';
                        else
                            $sHtml  =   '<a title="Make Admin Active" onclick="return confirm('.$msg.')" href="'.url('admin/admin/status/1/'.$data->id).'"><i class="ik ik-check f-16 ml-10 text-blue"></i></a>';
                        
                        return '<div class="table-actions" style="text-align:left">
                                <a href="'.url('admin/admin/'.$data->id).'" ><i class="ik ik-edit-2 f-16 mr-15 text-green" title="Edit User"></i></a>'.$sHtml.'
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
            return view('admin/admin/create-admin', compact('roles'));

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

            // store user information
            $user = User::create([
                    'name'              => $request->name,
                    'email'             => $request->email,
                    'is_admin'          => 1,
                    'mobile_no'         => $request->mobile_no,
                    'profile_pic'       => $imageName,
                    'password'          => Hash::make($request->password),
                    'created_at'        => date('Y-m-d'),
                ]);
  
            // assign new role to the user
            $user->syncRoles($request->role);

            if($user){ 
                return redirect('admin\admins')->with('success', 'New admin created!');
            }else{
                return redirect('admin\admins')->with('error', 'Failed to create new admin! Try again.');
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

                return view('admin/admin/admin-edit', compact('user','user_role','roles'));
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
                $update = $user->update([
                    'password' => Hash::make($request->password)
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

            return redirect('admin\admins')->with('success', 'Admin information updated succesfully!');
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