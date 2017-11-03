<?php

namespace App\Http\Controllers\Frontend\User;

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
        return view('frontend.user.auditor.index');
    }

    public function demands()
    {
        return view('frontend.user.auditor.demandlist');
    }

    public function models()
    {
        return view('frontend.user.auditor.modellist');
    }
}