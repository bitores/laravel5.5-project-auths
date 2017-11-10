<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Config;
use Yajra\DataTables\Facades\DataTables;
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
     * @description:ÉÏ´«Í·Ïñ
     * @author wuyanwen(2017Äê9ÔÂ20ÈÕ)
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

        
        //  记录操作历史-----todo
    
        return ['code' => 0, 'data'=>[
            'product_id' => $product->id
        ], 'msg' => 'success'];
    }

    public function findData()
    {
        $brands = $this->brand->findDataById(access()->id());

        return $brands;
    }

    public function table()
    {
        return DataTables::of($this->product->getForDataTable())
            ->escapeColumns(['fee'])
            ->addColumn('product_no', function ($product) {
                if(is_null($product->product_no)) {
                    return '<a class="col-md-12" href="'.route("frontend.user.demandside.product.show", $product->id).'">未命名</a>';
                }

                return '<a class="col-md-12" href="'.route("frontend.user.demandside.product.show", $product->id).'">'.$product->product_no.'</a>';
            })
            ->addColumn('cycle', function ($product) {
                if(is_null($product->cycle)) {
                    return '未确定';
                }

                return $product->cycle;
            })
            ->addColumn('status_no', function ($product) {
                // 1000 需求未提交（已保存/未提交）
                // 1001 需求审核中（已提交，禁止编辑）
                // 1002 需求审核未通过（开放编辑）
                // 1003 需求审核已通过（开放编辑）
             
                // 1005 等待接单（提交制作需求/新需求/已取消订单）
                // 1006 模型制作中（接单成功/开始制作）
                // 1007 模型审核中（模型已上传，禁止上传）
                // 1008 模型审核未通过（开放上传）
                // 1009 模型审核已通过（禁止上传，制作已完成）
                // 1010 模型已入库（完成制作）

                if($product->status_no === 1000)
                {
                    return "未提交审核";
                } else if($product->status_no === 1001) {
                    return "审核中";
                } else if($product->status_no === 1002) {
                    return "审核未通过";
                } else if($product->status_no === 1003) {
                    return "审核已通过";
                } else if($product->status_no === 1005) {
                    return "等待接单";
                } else if($product->status_no === 1006) {
                    return "制作中";
                } else if($product->status_no === 1007) {
                    return "模型审核中";
                } else if($product->status_no === 1008) {
                    return "模型审核未通过";
                } else if($product->status_no === 1009) {
                    return "模型审核已通过";
                } else if($product->status_no === 1010) {
                    return "模型已入库";
                }
            })
            ->addColumn('actions', function ($product) {
                if($product->status_no > 1000 )
                {
                    return "撤单";
                } else {
                    return "";
                }
                
            })
            ->make(true);
    }
}
