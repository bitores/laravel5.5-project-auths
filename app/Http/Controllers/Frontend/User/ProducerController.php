<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;

/**
 * Class DashboardController.
 */
class ProducerController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.user.producer.index');
    }

    public function modelingTutorial()
    {
        return view('frontend.user.producer.tutorial.modeling');
    }

    public function reviewTutorial()
    {
        return view('frontend.user.producer.tutorial.review');
    }

    public function show()
    {
        return view('frontend.user.producer.product.show',[
            'status' => 1
        ]);
    }

    public function assessment()
    {
        return view('frontend.user.producer.product.assessment');
    }
}