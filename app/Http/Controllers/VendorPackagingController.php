<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavePackagingRequest;
use Illuminate\Http\Request;

class VendorPackagingController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'frontend']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packagings = request()->user()->vendorData
            ->packagings()
            ->paginate(10);

        return response()->json(compact('packagings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SavePackagingRequest $request)
    {
        $data = $request->only([
            'name',
            'description',
        ]);

        $packaging = $request->user()->vendorData->packagings()->create($data);
        if (!$packaging) {
            throw new \Exception('Packaging not saved! Please, try again.');
        }

        return response()->json(compact('packaging'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
