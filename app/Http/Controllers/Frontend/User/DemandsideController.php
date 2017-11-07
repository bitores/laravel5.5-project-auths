<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Access\User\BrandRepository;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Repositories\Frontend\Access\User\CategoryARepository;
use App\Repositories\Frontend\Access\User\CategoryBRepository;
use App\Repositories\Frontend\Access\User\StyleRepository;

/**
 * Class DashboardController.
 */
class DemandsideController extends Controller
{
    public function __construct(BrandRepository $brand)
    {
        $this->brand = $brand;
    }

    public function index()
    {
        return view('frontend.user.demandside.index');
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




        // $styles = [];

        return view('frontend.user.demandside.product.create',[
            'brands'=>$brands,
            'categories_a'=>$categories_a,
            'categories_b'=>$categories_b,
            'styles'=>$style->getAll()
        ]);
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