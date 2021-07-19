<?php

namespace App\Http\Controllers;

use App\Events\VendorApprovalStatusChangedEvent;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VendorController extends Controller
{
    /**
     * Create instance
     * 
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::with([
            'users', 'address', 'address.state',
            'productCategories',
        ])->paginate(10);
        return Inertia::render('Vendors/Index', compact('vendors'));
    }

    /**
     * Change vendor approval status
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, Vendor $vendor)
    {
        $data = $request->only(['approval_status', 'reject_reason']);

        $validator = Validator::make($data, [
            'approval_status' => [
                'required',
                Rule::in(Vendor::getApprovalStatuses()),
            ],

            'reject_reason' => 'sometimes|nullable|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'failed' => true,
            ]);
        }

        if ($vendor->approval_status == $request->input('approval_status')) {
            return response()->json(compact(
                'vendor'
            ));
        }

        $vendor->approval_status = $request->input('approval_status');
        $vendor->reject_reason = $vendor->approval_status == Vendor::STATUS_DISAPPROVED
            ? $request->input('reject_reason')
            : null;

        if ($vendor->save()) {
            event(new VendorApprovalStatusChangedEvent(
                $vendor, $vendor->users()->first()
            ));
            return response()->json(compact(
                'vendor'
            ));
        }

        return response()->json([
            'failed' => true,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendor = Vendor::with([
            'users', 'address', 'address.state',
        ])->findOrFail((int) $id);

        $vendorProductCategories = $vendor->productCategories->pluck('name', 'id');

        return Inertia::render('Vendors/Show', compact('vendor', 'vendorProductCategories'));
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
