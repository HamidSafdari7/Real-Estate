<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\MultiImage;
use App\Models\Facility;
use App\Models\Amenities;
use App\Models\PropertyType;
use App\Models\User;
use Intervention\Image\Facades\Image;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;
use DB;
use App\Models\PackagePlan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PropertyMessage;
use App\Models\State;
use App\Models\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScheduleMail;
use App\Models\SmtpSetting;
use App\Models\SiteSetting;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PermissionsExport;
use App\Imports\PermissionImport;

class RoleController extends Controller
{

    public function AllPermission()
    {
        $permissions = Permission::all();

        return view('backend.pages.permission.all_permission',compact('permissions'));
    }


    public function Addpermission()
    {
        
        return view('backend.pages.permission.add_permission');
    }


    public function StorePermission(Request $request)
    {
        
        $permission = Permission::create([

            'name' => $request->name,

            'group_name' => $request->group_name,

        ]);


        $notification = array(

                'message' => 'Permission Created Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->route('all.permission')->with($notification);
    }


    public function EditPermission($id)
    {
        
        $permission = Permission::findOrFail($id);

        return view('backend.pages.permission.edit_permission',compact('permission'));
    }



    public function UpdatePermission(Request $request)
    {

        $per_id = $request->id;
        
        Permission::findOrFail($per_id)->update([

            'name' => $request->name,

            'group_name' => $request->group_name,

        ]);


        $notification = array(

                'message' => 'Permission Updated Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->route('all.permission')->with($notification);
    }


    public function DeletePermission($id)
    {
        Permission::findOrFail($id)->delete();

        $notification = array(

                'message' => 'Permission Deleted Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->back()->with($notification);
    }


    public function ImportPermission()
    {
        
        return view('backend.pages.permission.import_permission');
    }


    public function Export()
    {
        
        return Excel::download(new PermissionsExport, 'permission.xlsx');
    }


    public function Import(Request $request)
    {
        
        Excel::import(new PermissionImport, $request->file('import_file'));


        $notification = array(

                'message' => 'Permissions Imported Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->back()->with($notification);
    }

////////// Roles

    public function AllRoles()
    {
        
        $roles = Role::all();

        return view('backend.pages.roles.all_roles',compact('roles'));
    }


    public function AddRoles()
    {
        
        return view('backend.pages.roles.add_roles');
    }


    public function StoreRoles(Request $request)
    {
        
        Role::create([

            'name' => $request->name,

        ]);


        $notification = array(

                'message' => 'Role Created Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->route('all.roles')->with($notification);

    }


    public function EditRoles($id)
    {
        
        $roles = Role::findOrFail($id);

        return view('backend.pages.roles.edit_roles',compact('roles'));
    }



    public function UpdateRoles(Request $request)
    {

        $role_id = $request->id;
        
        Role::findOrFail($role_id)->update([

            'name' => $request->name,

        ]);


        $notification = array(

                'message' => 'Role Updated Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->route('all.roles')->with($notification);
    }



    public function DeleteRoles($id)
    {
        Role::findOrFail($id)->delete();

        $notification = array(

                'message' => 'Role Deleted Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->back()->with($notification);
    }



    public function AddRolesPermission()
    {

        $roles = Role::all();

        $permissions = Permission::all();

        $permission_groups = User::getpermissionGroups();
        
        return view('backend.pages.rolesetup.add_roles_permission',compact('roles','permissions','permission_groups'));
    }



    public function RolePermissionStore(Request $request)
    {
        
        $data = array();

        $permissions = $request->permission;

        foreach($permissions as $key => $item){

            $data['role_id'] = $request->role_id;

            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);
        }

        $notification = array(

                'message' => 'Role Permission Added Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->route('all.roles.permission')->with($notification);
    }



    public function AllRolesPermission()
    {
        
        $roles = Role::all();

        return view('backend.pages.rolesetup.all_roles_permission',compact('roles'));
    }


    public function AdminEditRoles($id)
    {
        
        $role = Role::findOrFail($id);

        $permissions = Permission::all();

        $permission_groups = User::getpermissionGroups();
        
        return view('backend.pages.rolesetup.edit_roles_permission',compact('role','permissions','permission_groups'));
    }



    public function AdminRolesUpdate(Request $request,$id)
    {
        
        $role = Role::findOrFail($id);

        $permissions = $request->permission;

        if (!empty($permissions)) {
            
            $role->syncPermissions($permissions);
        }

        $notification = array(

                'message' => 'Role Permission Updated Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->route('all.roles.permission')->with($notification);
    }


    public function AdminDeleteRoles($id)
    {
        
        $role = Role::findOrFail($id);

        if (!is_null($role)) {
            
            $role->delete();
        }

        $notification = array(

                'message' => 'Role Permission Deleted Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->back()->with($notification);
    }
}
