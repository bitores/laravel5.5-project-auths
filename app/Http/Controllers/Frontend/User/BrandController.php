<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Config;
use App\Http\Requests\Frontend\User\BrandRequest;
use App\Repositories\Frontend\Access\User\BrandRepository;
use App\Repositories\Frontend\Access\User\UserRepository;

/**
 * Class AccountController.
 */
class BrandController extends Controller
{

    public function __construct(UserRepository $user, BrandRepository $brand)
    {
        $this->user = $user;
        $this->brand = $brand;
    }


    /**
     * @description:上传头像
     * @author wuyanwen(2017年9月20日)
     * @param Request $request
     */
    public function create(BrandRequest $request)
    {	
        
        // $this->brand->

        $brand = $this->brand->create([
            'name' => $request->get('brd_name'),
            'user_id' => access()->id()
        ]);
  
            
        return ['code' => 0, 'data'=>[
            'brd_name' => $brand->name,
            'id' => $brand->id
        ], 'msg' => 'success'];
    }

    public function findData()
    {
        $brands = $this->brand->findDataById(access()->id());

        return $brands;
    }
}
