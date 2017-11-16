<?php

namespace App\Http\Controllers\Frontend\MLM;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\MLM\ProductReviewRepository;

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

    public function assessment(ProductReviewRepository $productReview, $productid)
    {
        $review = $productReview->findDataById($productid);
        if($review) {
            return view('frontend.mlm.producer.product.assessment',[
                'content' => $review->comments
            ]);
        } else {
            return view('frontend.mlm.producer.product.assessment',[
                'content' => "无修改意见"
            ]);
        }
        
    }
}