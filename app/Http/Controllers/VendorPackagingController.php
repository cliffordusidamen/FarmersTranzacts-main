<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavePackagingRequest;
use App\Models\VendorPackaging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            ->withCount(['products'])
            ->orderByDesc('id')
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
     * @param  \App\Models\VendorPackaging  $packaging
     * @return \Illuminate\Http\Response
     */
    public function edit(VendorPackaging $packaging)
    {
        $this->_abortIfNotVendorPackaging($packaging);
        return response()->json(compact('packaging'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorPackaging  $packaging
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VendorPackaging $packaging)
    {
        $data = $request->only([
            'name', 'description'
        ]);

        Validator::make($data, VendorPackaging::validationRules($packaging->id))
            ->validate();

        $this->_abortIfNotVendorPackaging($packaging);
        $saved = $packaging->forceFill($data)->save();

        if (!$saved) {
            throw new \Exception('Packaging information not saved! Please, try again.');
        }

        return response()->json(compact('packaging'));
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

    /**
     * Prevent access to product if it does not belong to the current user
     * 
     * @param  VendorPackaging  $vendorPackaging
     * @throws \Exception
     * @return void
     */
    public function _abortIfNotVendorPackaging(VendorPackaging $vendorPackaging)
    {
        if (authUserIsVendor()) {
            abort_if(
                $vendorPackaging->vendor_id != request()->user()->vendorData->id,
                404
            );
        }
    }
}
