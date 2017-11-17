<?php

namespace App\Http\Controllers\Frontend\MLM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\MLM\UBrandRequest;
use App\Repositories\Frontend\MLM\UBrandRepository;
use App\Repositories\Frontend\Access\User\UserRepository;

/**
 * Class AccountController.
 */
class UBrandController extends Controller
{

    public function __construct(UserRepository $user, UBrandRepository $brand)
    {
        $this->user = $user;
        $this->brand = $brand;
    }


    /**
     * @description:上传头像
     * @author wuyanwen(2017年9月20日)
     * @param Request $request
     */
    public function create(UBrandRequest $request)
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
}
