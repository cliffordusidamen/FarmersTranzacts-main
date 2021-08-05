<?php

namespace App\Http\Controllers;

use App\Models\Role as AppRole;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        // To do: add middleware for adminstrators only
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::orderByDesc('id')->get();
        $roles->map(fn ($role, $key) => $role->loadCount('users'));

        return response()->json(compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['name', 'description', 'is_active', 'role_permissions']);
        Validator::make($data, AppRole::validationRules())->validate();

        $role = null;

        DB::transaction(function() use ($data, &$role) {
            $role = AppRole::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'is_active' => $data['is_active'],
                'guard_name' => 'web',
            ]);

            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            $role->syncPermissions($data['role_permissions']);
        });


        if (!$role) {
            return response()->json([
                'save_failed' => true,
                'message' => 'Role not saved! Please, try again.'
            ]);
        }

        return response()->json(compact('role'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $role->loadCount('users');
        $role->load('permissions');

        return response()->json(compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $data = $request->only(['name', 'description', 'is_active', 'role_permissions']);
        Validator::make($data, AppRole::validationRules($role->id))->validate();

        $saved = false;
        DB::transaction(function() use ($data, &$saved, $role) {
            $role->forceFill([
                'name' => $data['name'],
                'description' => $data['description'],
                'is_active' => $data['is_active'],
                'guard_name' => 'web',
            ]);
            $saved = $role->save();

            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            $role->syncPermissions($data['role_permissions']);
        });

        if (!$saved) {
            return response()->json([
                'save_failed' => true,
                'message' => 'Role not saved! Please, try again.'
            ]);
        }

        return response()->json(compact('role'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }

    /**
     * Get list of all permissions in the application
     * 
     * @return \Illuminate\Http\Response
     */
    public function permissions()
    {
        $model = getModelPermissions();
        return response()->json(compact('model'));
    }
}
