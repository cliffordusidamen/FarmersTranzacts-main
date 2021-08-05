<?php

namespace App\Http\Controllers;

use App\Models\Role as AppRole;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
        $roles = Role::get();
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
        $data = $request->only(['name', 'description', 'is_active']);
        Validator::make($data, AppRole::validationRules())->validate();

        $role = AppRole::create(array_merge($data, [
            'guard_name' => 'web',
        ]));

        if (!$role) {
            throw ValidationException::withMessages([
                'name' => 'ROle not saved! Please, try again.',
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
        $data = $request->only(['name', 'description', 'is_active']);
        Validator::make($data, AppRole::validationRules($role->id))->validate();

        $role->forceFill($data);

        if (!$role->save()) {
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
