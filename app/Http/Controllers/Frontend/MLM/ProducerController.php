<?php

namespace App\Http\Controllers\Frontend\MLM;

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
        return view('frontend.mlm.producer.index');
    }

    public function demandlist()
    {
        return view('frontend.mlm.producer.demandlist');
    }

    public function modelingTutorial()
    {
        return view('frontend.mlm.producer.tutorial.modeling');
    }

    public function reviewTutorial()
    {
        return view('frontend.mlm.producer.tutorial.review');
    }

    public function show()
    {
        return view('frontend.mlm.producer.product.show',[
            'status' => 1
        ]);
    }

    public function assessment()
    {
        return view('frontend.mlm.producer.product.assessment');
    }
}