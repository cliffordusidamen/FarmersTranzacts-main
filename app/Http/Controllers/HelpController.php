<?php

namespace App\Http\Controllers;

use App\Models\HelpPage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HelpController extends Controller
{
    /**
     * Display a listing of all help pages
     * 
     * @return \Inertia\Response
     */
    public function index()
    {
        $helpPages = HelpPage::published()->get();
        return Inertia::render('Help/Index', compact('helpPages'));
    }
}
