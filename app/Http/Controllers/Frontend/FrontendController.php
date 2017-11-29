<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use GatewayClient\Gateway;
/**
 * Class FrontendController.
 */
class FrontendController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.index');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function macros()
    {
        return view('frontend.macros');
    }


    public function im()
    {
        // Gateway::$registerAddress = '127.0.0.1:1236';
        // Gateway::sendToAll(array());
        return view('frontend.im');
        
    }
}
