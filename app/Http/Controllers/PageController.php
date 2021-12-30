<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Create instance
     */
    public function __construct()
    {
        $this->middleware(['guest'])->only('becomeAnAgent');
    }

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
     * @param  string  $slug
     * @return \Inertia\Response
     */
    public function _getPage($slug)
    {
        $pageSetup = Page::bySlug($slug)->first();
        $showMenu = false;
        return Inertia::render('ManagedPage', compact('pageSetup', 'showMenu'));
    }

    /**
     * Send message from the contact us form
     * 
     * @param  \App\Http\Requests\ContactUsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function sendContactUs(ContactUsRequest  $request)
    {
        $contactUsMail = new \App\Mail\ContactUsNotifyAdminMail($request->all());
        Mail::to(config('mail.from.address'))->send($contactUsMail);

        if (!empty($request->email)) {
            $contactUsResponse = new \App\Mail\ContactUsResponseMail($request->all());
            Mail::to($request->email)->send($contactUsResponse);
        }

        flashMessage('success', 'Message sent!');
        return redirect()->route('contact_us');
    }

    /**
     * Display the landing page, with details on becoming an agent
     * 
     * @return \Inertia\Response
     */
    public function becomeAnAgent()
    {
        return Inertia::render('BecomeAnAgent');
    }

    /**
     * Display vendor page
     * 
     * @param  string  $slug
     * @return \Inertia\Response
     */
    public function vendorPage($slug)
    {
        $vendor = \App\Models\Vendor::where('slug', $slug)->firstOrFail();
        $vendor->append([
            'cover_picture_url',
            'logo_url',
        ]);
        $productsResult = \App\Models\Product::with([
                'category', 'images', 'vendor', 'packaging',
            ])
            ->availableForSale()
            ->forVendor($vendor->id)
            ->paginate(12);

        $vendorProducts = $productsResult->total() > 0
            ? $productsResult
            : [];

        return Inertia::render('VendorPage', compact('vendor', 'vendorProducts'));
    }
}
