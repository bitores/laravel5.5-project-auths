<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Config;
use App\Http\Requests\Frontend\User\ProductRequest;
use App\Repositories\Frontend\Access\User\ProductRepository;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Repositories\Frontend\Access\User\UImageRepository;

/**
 * Class AccountController.
 */
class ProductController extends Controller
{

    public function __construct(UserRepository $user, ProductRepository $product)
    {
        $this->user = $user;
        $this->product = $product;
    }


    /**
     * @description:上传头像
     * @author wuyanwen(2017年9月20日)
     * @param Request $request
     */
    public function save(ProductRequest $request, UImageRepository $uimageRes)
    {	
        $data = $request->only(
            'current_pro',
            'product_no',
            'style_id',
            'a_id',
            'b_id',
            'brand_id',
            'cad_id',
            'file_id',
            'model_id',
            'fee',
            'introduction'
        );

        if($request->get('current_pro'))
        {
            $product = $this->product->update($request->get('current_pro'),$data);

            if(is_null($product))
            {
                return null;
            }

        } else {

            $product = $this->product->create(access()->id(),$data);
        }

        $uimageRes->resetProductId($product->id);

        if($request->get('images'))
        {
            $images = $uimageRes->updateProductId(explode(',',$request->get('images')),$product->id);
        }

        
  
    
        return ['code' => 0, 'data'=>[
            'product_id' => $product->id
        ], 'msg' => 'success'];
    }

    public function findData()
    {
        $brands = $this->brand->findDataById(access()->id());

        return $brands;
    }
}
