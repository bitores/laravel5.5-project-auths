<?php

namespace App\Http\Controllers\Frontend\MLM;

use App\Http\Controllers\Controller;

/**
 * Class DashboardController.
 */
class AuditorController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.mlm.auditor.index');
    }

    public function demands()
    {
        return view('frontend.mlm.auditor.demandlist');
    }

    public function models()
    {
        return view('frontend.mlm.auditor.modellist');
    }


    //---------------API

    
}