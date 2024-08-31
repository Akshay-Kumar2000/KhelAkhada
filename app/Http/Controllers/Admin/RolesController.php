<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use DataTables,Auth;

class RolesController extends Controller
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
     * Show the roles page
     *
     */
    public function index()
    {
        try{
            $permissions = Permission::pluck('name','id');

            return view('admin/role/roles', compact('permissions'));
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);

        }
    }

    /**
     * Show the role list with associate permissions.
     * Server side list view using yajra datatables
     */

    public function getRoleList(Request $request)
    {
        
        $data  = Role::get();

        return Datatables::of($data)
                ->addColumn('permissions', function($data){
                    $roles = $data->permissions()->get();
                    $badges = '';
                    foreach ($roles as $key => $role) {
                        $badges .= '<span class="badge badge-dark m-1">'.$role->name.'</span>';
                    }
                    if($data->name == 'Super Admin'){
                        return '<span class="badge badge-success m-1">All permissions</span>';
                    }

                    return $badges;
                })
                ->addColumn('action', function($data){
                    if($data->name == 'Super Admin'){
                        return '';
                    }
                    if (Auth::user()->can('manage_roles')){
                        return '<div class="table-actions">
                                    <a href="'.url('admin/role/edit/'.$data->id).'" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                                    <a href="'.url('admin/role/delete/'.$data->id).'"  ><i class="ik ik-trash-2 f-16 text-red"></i></a>
                                </div>';
                    }else{
                        return '';
                    }
                })
                ->rawColumns(['permissions','action'])
                ->make(true);
    }

    /**
     * Store new roles with assigned permission
     * Associate permissions will be stored in table
     */

    public function create(Request $request)
    {
        // laravel default validator
        $validator = Validator::make($request->all(), [
            'role' => 'required'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try{

            $role = Role::create(['name' => $request->role]);
            $role->syncPermissions($request->permissions);

            if($role){ 
                return redirect('admin\roles')->with('success', 'Role created succesfully!');
            }else{
                return redirect('admin\roles')->with('error', 'Failed to create role! Try again.');
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function edit($id)
    {
        $role  = Role::where('id',$id)->first();
        // if role exist
        if($role){
            $role_permission = $role->permissions()
                                    ->pluck('id')
                                    ->toArray();

            $permissions = Permission::pluck('name','id');

            return view('admin/role/edit-roles', compact('role','role_permission','permissions'));
        }else{
            return redirect('404');
        }
    }

    public function update(Request $request)
    {
        

        // update role
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'id'   => 'required'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try{
            
            $role = Role::find($request->id);

            $update = $role->update([
                          'name' => $request->role
                      ]);

            // Sync role permissions
            $role->syncPermissions($request->permissions);

            return redirect('admin\roles')->with('success', 'Role info updated succesfully!');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);

        }
    }


    public function delete($id)
    {
        $role   = Role::find($id);
        if($role){
            $delete = $role->delete();
            $perm   = $role->permissions()->delete();

            return redirect('admin\roles')->with('success', 'Role deleted!');
        }else{
            return redirect('404');
        }
    }
}
