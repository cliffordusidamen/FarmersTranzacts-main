<?php

namespace App\Http\Controllers;

use App\Models\Faq;
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

        if (request()->wantsJson()) {
            return response()->json($helpPages);
        }
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
        $helpPage = HelpPage::with('faqs')->where('slug', 'LIKE', $slug)->firstOrFail();
        return Inertia::render('Help/ShowPage', compact('helpPage'));
    }

    /**
     * Fetch the list of FAQs
     * 
     * @return \Inertia\Response
     */
    public function faq()
    {
        $faqs = Faq::get();

        return Inertia::render('Help/Faq', compact('faqs'));
    }
}
