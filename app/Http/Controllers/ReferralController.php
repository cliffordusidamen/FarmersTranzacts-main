<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReferralController extends Controller
{
    
    /**
     * Create instance of the controller
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    // /**
    //  * Display a listing of current user referrals.
    //  * 
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index()
    // {
    //     $referrals = auth()->user()->referrals()->with([
    //         ''
    //     ])->paginate(10);

    //     return Inertia::render('Referral/Index', compact('referrals'));
    // }
}
