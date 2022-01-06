<?php

namespace App\Http\Controllers;

use App\Models\HelpPage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PHPUnit\TextUI\Help;

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

    /**
     * Display help page
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Inertia\Response
     */
    public function page(string $slug)
    {
        $helpPage = HelpPage::where('slug', 'LIKE', $slug)->firstOrFail();
        return Inertia::render('Help/ShowPage', compact('helpPage'));
    }
}
