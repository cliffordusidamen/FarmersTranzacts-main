<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Get content of the about page from the database
     * 
     * @return  \Inertia\Response
     */
    public function about()
    {
        return $this->_getPage(Page::ABOUT);
    }

    /**
     * Get content of the terms page from the database
     * 
     * @return  \Inertia\Response
     */
    public function terms()
    {
        return $this->_getPage(Page::TERMS);
    }

    /**
     * Get content of the privacyPolicy page from the database
     * 
     * @return  \Inertia\Response
     */
    public function privacyPolicy()
    {
        return $this->_getPage(Page::PRIVACY_POLICY);
    }

    /**
     * Get the requested page
     * 
     * @param  string  $name
     * @return \Inertia\Response
     */
    public function _getPage($name)
    {
        $pageSetup = Page::byName($name)->first();
        $showMenu = false;
        return Inertia::render('ManagedPage', compact('pageSetup', 'showMenu'));
    }
}
