<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBankAccountRequest;
use App\Models\VendorBankAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VendorBankAccountController extends Controller
{

    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'frontend']);
    }

    /**
     * Display a listing bank accounts for the current users vendor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $vendor = $request->user()->vendors()->first();
        $bankAccounts = $vendor->bankAccounts()->with('bank')->get();
        return response()->json(compact('bankAccounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banksList = \App\Models\Bank::pluck('name', 'id');
        return response()->json(compact('banksList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBankAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankAccountRequest $request)
    {
        $vendor = $request->user()->vendors()->first();
        $this->_checkDuplicateAccount(
            $vendor,
            $request->bank_id,
            $request->account_number
        );

        $bankAccount = $vendor->bankAccounts()->create($request->only(
            array_keys(VendorBankAccount::defaultValidationRules())
        ));

        if ($bankAccount) {
            $vendor->updateDefaultBankAccount($bankAccount);
            return response()->json(compact('bankAccount'));
        }

        throw new \Exception('Failed to create bank account');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $vendor = $request->user()->vendors()->first();
        $bankAccount = $vendor->bankAccounts()->findOrFail($id);
        $banksList = \App\Models\Bank::pluck('name', 'id');

        return response()->json(compact('banksList', 'bankAccount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreBankAccountRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreBankAccountRequest $request, $id)
    {
        $vendor = $request->user()->vendors()->first();
        $this->_checkDuplicateAccount(
            $vendor,
            $request->bank_id,
            $request->account_number,
            $id
        );
        $bankAccount = $vendor->bankAccounts()->findOrFail($id);
        $data = $request->only(
            array_keys(VendorBankAccount::defaultValidationRules())
        );
        $saved = $bankAccount->forceFill($data)->save();

        if ($saved) {
            $vendor->updateDefaultBankAccount($bankAccount);
            return response()->json(compact('bankAccount'));
        }

        throw new \Exception('Failed to update bank account');
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
     * Check for duplicate bank account
     * 
     * @param  \App\Models\Vendor  $vendor
     * @param  int  $bankId
     * @param  string  $accountNumber
     * @param  int  $id
     * @throws  \Illuminate\Validation\ValidationException
     * @return void
     */
    public function _checkDuplicateAccount(
        \App\Models\Vendor $vendor,
        int $bankId,
        string $accountNumber,
        ?int $id = null
    ) {
        $isDuplicate = $vendor->isDuplicateBankAccount(
            $bankId,
            $accountNumber,
            $id
        );

        if ($isDuplicate) {
            throw ValidationException::withMessages([
                'account_number' => ['Account already exists'],
            ]);
        }
    }
}
