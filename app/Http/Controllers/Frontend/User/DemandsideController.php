<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Access\User\BrandRepository;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Repositories\Frontend\Access\User\CategoryARepository;
use App\Repositories\Frontend\Access\User\CategoryBRepository;
use App\Repositories\Frontend\Access\User\StyleRepository;
use App\Repositories\Frontend\Access\User\UImageRepository;
use App\Repositories\Frontend\Access\User\ProductRepository;

/**
 * Class DashboardController.
 */
class DemandsideController extends Controller
{
    public function __construct(BrandRepository $brand)
    {
        $this->brand = $brand;
    }

    public function index(ProductRepository $productRes)
    {   
        $products = $productRes->findAll();

        return view('frontend.user.demandside.index',[
            'products' => $products
        ]);
    }

    public function readme()
    {
        return view('frontend.user.demandside.readme');
    }

    public function create(CategoryARepository $categorya,CategoryBRepository $categoryb,StyleRepository $style)
    {
        $brands = $this->brand->findDataById(access()->id());
        $categories_a = $categorya->findAllData();
        $categories_b = $categoryb->findAllData();


        return view('frontend.user.demandside.product.create',[
            'brands'=>$brands,
            'categories_a'=>$categories_a,
            'categories_b'=>$categories_b,
            'styles'=>$style->getAll()
        ]);
    }

    public function edit(CategoryARepository $categorya,CategoryBRepository $categoryb,StyleRepository $style, UImageRepository $imageRes, ProductRepository $productRes, $productid)
    {
        $product = $productRes->findDataById($productid);

        $brands = $this->brand->findDataById(access()->id());
        $categories_a = $categorya->findAllData();
        $categories_b = $categoryb->findAllData();

        $images = $imageRes->findAllDataByProductId($productid);

        if($product) {
            return view('frontend.user.demandside.product.edit',[
                'product' => $product,

                'brands'=>$brands,
                'categories_a'=>$categories_a,
                'categories_b'=>$categories_b,
                'styles'=>$style->getAll(),
                'images'=> $images
            ]);
        } else {
            return redirect()->route('frontend.user.demandside.index')->withFlashSuccess('无此产品');
        }
        
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