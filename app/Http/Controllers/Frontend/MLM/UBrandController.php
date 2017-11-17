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
     * @description:�ϴ�ͷ��
     * @author wuyanwen(2017��9��20��)
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
