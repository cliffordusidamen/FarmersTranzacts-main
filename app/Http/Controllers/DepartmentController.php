<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
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
        $departments = Department::with('sections')->orderByDesc('id')->get();
        return response()->json(compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['name', 'description']);
        Validator::make($data, Department::validationRules())->validate();

        $department = Department::create($data);

        if (!$department) {
            throw ValidationException::withMessages([
                'name' => 'Department not saved! Please, try again.',
            ]);
        }

        return response()->json(compact('department'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $department = Department::with('sections')->findOrFail((int) $id);
        return response()->json(compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $department = Department::with('sections')->findOrFail((int) $id);
        $data = $request->only(['name', 'description']);
        Validator::make($data, Department::validationRules((int) $id))->validate();

        $department->forceFill($data);

        if (!$department->save()) {
            return response()->json([
                'save_failed' => true,
                'message' => 'Department not saved! Please, try again.'
            ]);
        }

        return response()->json(compact('department'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
