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

class Awesome_AdminRoleController extends Controller
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
            $role = Role::create(['name' => $request->input('role_name')]);

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
    public function update(Request $request, Awesome_Admin $awesome_Admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Awesome_Admin $awesome_Admin)
    {
        //
    }

    public function listdata()
    {
        $roles = Roles::get();

        return response()->json(['success' => true, 'status' => 'success', 'message' => 'Data found', 'data' => $roles]);
    }
}
