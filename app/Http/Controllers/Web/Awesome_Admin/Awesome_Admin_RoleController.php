<?php

namespace App\Http\Controllers\Web\Awesome_Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitRoleRequest;

use App\Models\Account;
use App\Models\Roles;
use App\Models\Awesome_Admin\Awesome_Admin;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use App\Services\Awesome_AdminService;

use Illuminate\Http\Request;

class Awesome_Admin_RoleController extends Controller
{
    public Awesome_AdminService $Awesome_AdminService;

    public Account $Account;

    public function __construct()
    {
        $this->Awesome_AdminService = new Awesome_AdminService(new Awesome_Admin());

        $this->Account = new Account();

        $this->Roles = new Roles();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Account $user)
    {
        if ($user->isAdmin())
        {
            return view('awesome_admin.awesome_admin_role');
        }
        
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubmitRoleRequest $request)
    {
        if ($request->validated())
        {
            $permissions = explode(",", $request->input('permissions'));

            $role = Role::create(['name' => $request->input('role_name')]);
            $role->givePermissionTo($permissions);

            return response()->json(['success' => true, 'status' => 'success', 'message' => 'Data added']);
        }   
    }

    /**
     * Display the specified resource.
     */
    public function show(Awesome_Admin $awesome_Admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Awesome_Admin $awesome_Admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubmitRoleRequest $request)
    {
        if ($request->validated())
        {
            $role = Role::find($request->route('idOrSlug'));

            if ($role !== null)
            {
                $permissions = explode(",", $request->input('permissions'));

                $role->name = $request->input('role_name');
                $role->syncPermissions($permissions);
                $role->save(); 

                return response()->json(['success' => true, 'status' => 'success', 'message' => 'Data edited']);
            }

            return response()->json(['success' => false, 'status' => 'failed', 'message' => 'Data not found']);
        }  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->route('idOrSlug') > 1)
        {
            $role = Role::find($request->route('idOrSlug'));

            if ($role !== null)
            {
                $role->delete(); 

                return response()->json(['success' => true, 'status' => 'success', 'message' => 'Data deleted']);
            }

            return response()->json(['success' => false, 'status' => 'failed', 'message' => 'Data not found']);
        }  
        else
        {
            return response()->json(['success' => false, 'status' => 'failed', 'message' => 'Id or Slug is empty']);
        }
    }

    public function listData()
    {
        $roles = Roles::get();

        return response()->json(['success' => true, 'status' => 'success', 'message' => 'Data found', 'data' => $roles]);
    }

    public function listDataPermission()
    {
        $roles = Permission::get();

        foreach ($roles as $key => $value) 
        {
            $new_output[$key] = $value['name'];
        }

        return response()->json(['success' => true, 'status' => 'success', 'message' => 'Data found', 'data' => $new_output]);
    }

    public function detailData($role_id)
    {
        $role = Role::find($role_id);

        if ($role !== null)
        {
            if (count($role->getAllPermissions()) !== 0)
            {
                foreach ($role->getAllPermissions() as $key => $value) 
                {
                    $permissions[$key] = $value['name'];
                }
            }
            else
            {
                $permissions = [];
            }

            foreach ($role->toArray() as $key => $value) 
            {
                $new_output[$key] = $value;
            }

            $new_output['permissions'] = $permissions;

            return response()->json(['success' => true, 'status' => 'success', 'message' => 'Data found', 'data' => $new_output]);
        }

        return response()->json(['success' => false, 'status' => 'failed', 'message' => 'Data not found']);
    }

    public function test()
    {
        $data = 
        [
            'modelSubmit' => 'App\Models\Account',
            'schema' =>
            [
                'username' =>
                [
                    'type' => 'text',
                    'placeholder' => 'Username'
                ],
                'fullname' =>
                [
                    'type' => 'text',
                    'placeholder' => 'Fullname'
                ],
                'email' =>
                [
                    'type' => 'text',
                    'placeholder' => 'Email'
                ]
            ]
        ];

        return view('awesome_admin.awesome_admin_test', ['forms' => $data]); 
    }
}
