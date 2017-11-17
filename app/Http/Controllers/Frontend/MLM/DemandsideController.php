<?php

namespace App\Http\Controllers\Frontend\MLM;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Repositories\Frontend\MLM\UBrandRepository;
use App\Repositories\Frontend\MLM\PCategoryARepository;
use App\Repositories\Frontend\MLM\PCategoryBRepository;
use App\Repositories\Frontend\MLM\PStyleRepository;
use App\Repositories\Frontend\MLM\UImageRepository;
use App\Repositories\Frontend\MLM\ProductRepository;
use App\Repositories\Frontend\MLM\VProductRepository;
use App\Repositories\Frontend\MLM\PReviewRepository;

/**
 * Class DashboardController.
 */
class DemandsideController extends Controller
{
    public function __construct(UserRepository $user, UBrandRepository $brand)
    {
        $this->user = $user;
        $this->brand = $brand;
    }

    public function index(ProductRepository $productRes)
    {   
        $products = $productRes->getAllByUserId(access()->id());

        return view('frontend.mlm.demandside.index',[
            'products' => $products
        ]);
    }

    public function readme()
    {
        return view('frontend.mlm.demandside.readme');
    }

    public function create(PCategoryARepository $categorya,PCategoryBRepository $categoryb,PStyleRepository $style)
    {
        $brands = $this->brand->getAllByUserId(access()->id());

        return view('frontend.mlm.demandside.product.create',[
            'brands'=>$brands,
            'categories_a'=>$categorya->getAll(),
            'categories_b'=>$categoryb->getAll(),
            'styles'=>$style->getAll()
        ]);
    }

    public function edit(PCategoryARepository $categorya,PCategoryBRepository $categoryb,PStyleRepository $style, UImageRepository $imageRes, ProductRepository $productRes, $productid)
    {
        $product = $productRes->findByUserIdAndProductId(access()->id(), $productid);

        if($product) {
            $brands = $this->brand->getAllByUserId(access()->id());
            $images = $imageRes->getAllByProductId($productid);

            return view('frontend.mlm.demandside.product.edit',[
                'product' => $product,
                'brands'=>$brands,
                'categories_a'=>$categorya->getAll(),
                'categories_b'=>$categoryb->getAll(),
                'styles'=>$style->getAll(),
                'images'=> $images
            ]);
        } else {
            return redirect()->route('frontend.mlm.demandside.index')->withFlashSuccess('您无此产品');
        }
        
    }

    public function show( UImageRepository $imageRes, VProductRepository $productRes, $productid)
    {
        $product = $productRes->find( $productid);

        if($product) {

            $images = $imageRes->getAllByProductId($productid);

            return view('frontend.mlm.demandside.product.show',[
                'product' => $product,
                'images'=> $images
            ]);
        } else {
            return redirect()->route('frontend.mlm.demandside.index')->withFlashSuccess('您无此产品');
        }
        
    }

    public function assessment(PReviewRepository $productReview, $productid)
    {
        $review = $productReview->findLastByProductId($productid);
        if($review) {
            return view('frontend.mlm.demandside.product.assessment',[
                'content' => $review->comments
            ]);
        } else {
            return view('frontend.mlm.demandside.product.assessment',[
                'content' => "无修改意见"
            ]);
        }
        
    }

    
}