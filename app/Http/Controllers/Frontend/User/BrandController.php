<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Config;
use App\Http\Requests\Frontend\User\BrandRequest;
use App\Repositories\Frontend\Access\User\BrandRepository;
use Auth;

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
        // $request->only(
        //     'name'
        // )
        
        $brand = $this->brand->create([
            'name' => 'test5',
            'user_id' => access()->id()
        ]);
  
            
        return ['code' => 0, 'data'=>[
            'name' => $brand->name,
            'id' => $brand->id
        ], 'msg' => 'success'];
    }

    public function findData()
    {
        $brands = $this->brand->findDataById(access()->id());

        return $brands;
    }
}
