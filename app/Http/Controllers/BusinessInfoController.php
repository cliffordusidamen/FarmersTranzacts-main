<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusinessInfoController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vendor = $request->user()->vendors()->first();
        $vendor->append([
            'logo_url',
            'cover_picture_url',
        ]);

        return response()->json(compact('vendor'));
    }

    /**
     * Update the about field of the users vendor
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAbout(Request $request)
    {
        $this->validate($request, [
            'about' => 'required|max:2000',
        ]);
        $vendor = $request->user()->vendors()->first();

        $vendor->about = $request->input('about', null);
        if ($vendor->save()) {
            return response()->json(compact('vendor'));
        }

        throw new \Exception('About information was not saved! Please, try again.');
    }

    /**
     * Update the logo_image of the vendor
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateLogo(Request $request)
    {
        $this->validate($request, [
            'logo' => 'required|mimes:png,jpg|max:1024',
        ]);
        $vendor = $request->user()->vendors()->first();
        $saved = $vendor->updateLogo($request->file('logo', null));

        if ($saved) {
            $vendor->append('logo_url');
            return response()->json(compact('vendor'));
        }

        throw new \Exception('Logo not updated. Please, try again!');
    }

    /**
     * Update the cover picture of the vendor
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCoverPicture(Request $request)
    {
        $this->validate($request, [
            'cover_picture' => 'required|mimes:png,jpg|max:1024',
        ]);
        $vendor = $request->user()->vendors()->first();
        $saved = $vendor->updateCoverPicture($request->file('cover_picture', null));

        if ($saved) {
            $vendor->append('cover_picture_url');
            return response()->json(compact('vendor'));
        }

        throw new \Exception('Cover picture not updated. Please, try again!');
    }
}
