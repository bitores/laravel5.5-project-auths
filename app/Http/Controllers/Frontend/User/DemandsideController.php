<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;

/**
 * Class DashboardController.
 */
class DemandsideController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.user.demandside.index');
    }

    public function readme()
    {
        return view('frontend.user.demandside.readme');
    }

    public function create()
    {
        return view('frontend.user.demandside.product.create');
    }

    public function edit()
    {
        return view('frontend.user.demandside.product.edit');
    }

    public function show()
    {
        return view('frontend.user.demandside.product.show',[
            'status' => 1
        ]);
    }

    public function assessment()
    {
        return view('frontend.user.demandside.product.assessment');
    }
}