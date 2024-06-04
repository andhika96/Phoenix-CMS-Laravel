<?php

namespace App\Http\Controllers\Web\Awesome_Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitPermissionRequest;

use App\Models\Account;
use App\Models\Permissions;
use App\Models\Awesome_Admin\Awesome_Admin;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use App\Services\Awesome_AdminService;

use Illuminate\Http\Request;

class Awesome_Admin_PermissionController extends Controller
{
    public Awesome_AdminService $Awesome_AdminService;

    public Account $Account;

    public function __construct()
    {
        $this->Awesome_AdminService = new Awesome_AdminService(new Awesome_Admin());

        $this->Account = new Account();

        $this->Permissions = new Permissions();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Account $user)
    {
        if ($user->isAdmin())
        {
            return view('awesome_admin.awesome_admin_permission');
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
    public function store(SubmitPermissionRequest $request)
    {
        if ($request->validated())
        {
            $role = Permission::create(['name' => $request->input('permission_name')]);

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
    public function update(SubmitPermissionRequest $request)
    {
        if ($request->validated())
        {
            $permission = Permission::find($request->route('idOrSlug'));

            if ($permission !== null)
            {
                $permission->name = $request->input('permission_name');
                $permission->save(); 

                return response()->json(['success' => true, 'status' => 'success', 'message' => 'Data edited']);
            }

            return response()->json(['success' => false, 'status' => 'failed', 'message' => 'Data not found']);
        }  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Awesome_Admin $awesome_Admin)
    {
        if ($request->route('idOrSlug') > 1)
        {
            $permission = Permission::find($request->route('idOrSlug'));

            if ($permission !== null)
            {
                $permission->delete(); 

                return response()->json(['success' => true, 'status' => 'success', 'message' => 'Data deleted']);
            }

            return response()->json(['success' => false, 'status' => 'failed', 'message' => 'Data not found']);
        }  
        else
        {
            return response()->json(['success' => false, 'status' => 'failed', 'message' => 'Id or Slug is empty']);
        }
    }

    public function listdata()
    {
        $roles = Permissions::get();

        return response()->json(['success' => true, 'status' => 'success', 'message' => 'Data found', 'data' => $roles]);
    }

    public function detailData($role_id)
    {
        $permission = Permissions::find($role_id);

        if ($permission !== null)
        {
            return response()->json(['success' => true, 'status' => 'success', 'message' => 'Data found', 'data' => $permission]);
        }

        return response()->json(['success' => false, 'status' => 'failed', 'message' => 'Data not found']);
    }
}
