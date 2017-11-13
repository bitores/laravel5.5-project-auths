<?php

namespace App\Http\Controllers\Frontend\MLM;

use App\Http\Controllers\Controller;
use Config;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Frontend\MLM\ProductRequest;
use App\Repositories\Frontend\MLM\ProductRepository;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Repositories\Frontend\MLM\UImageRepository;
use App\Repositories\Frontend\MLM\StyleRepository;

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
            'images',
            'fee',
            'introduction'
        );

        $images = explode(',',$request->get('images'));

        if($request->get('current_pro'))
        {
            $product = $this->product->update($request->get('current_pro'), $data, 1000);

            if(is_null($product))
            {
                return null;
            }

        } else {

            $product = $this->product->create(access()->id(),$data, 1000);
        }

        $uimageRes->resetProductId($product->id);

        if($request->get('images'))
        {
            $images = $uimageRes->updateProductId($images,$product->id);
        }

        
        //  记录操作历史-----todo
    
        return ['code' => 0, 'data'=>[
            'product_id' => $product->id
        ], 'msg' => 'success'];
    }

    public function oncesubmit(ProductRequest $request, UImageRepository $uimageRes)
    {

            if($request->get('current_pro'))
            {
                $product = $this->product->find($request->get('current_pro'));

                if($product)
                {   
                    $images = $uimageRes->findAllDataByProductId($product->id);

                    if(isset($product->product_no) 
                        && isset($product->style_id) 
                        && isset($product->a_id) 
                        && isset($product->b_id) 
                        && isset($product->brand_id) 
                        && (count($images)>0)
                        && isset($product->fee) 
                        && isset($product->introduction)){


                        $this->product->updateStatus($product->id, 1001);

                        return ['code' => 0, 'data'=>[], 'msg' => 'success'];
                    } else {
                        return ['code' => -1, 'data'=>[], 'msg' => '产品信息不完整'];
                    }
                } 

                return ['code' => -1, 'data'=>[], 'msg' => '产品信息不完整'];
            } else {

                return ['code' => -1, 'data'=>[], 'msg' => '产品信息不完整'];
            }
    }

    public function submit(ProductRequest $request, UImageRepository $uimageRes)
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
            'images',
            'fee',
            'introduction'
        );

        if(isset($data['product_no']) 
            && isset($data['style_id']) 
            && isset($data['a_id']) 
            && isset($data['b_id']) 
            && isset($data['brand_id']) 
            // && isset($data['cad_id']) 
            // && isset($data['file_id']) 
            && isset($data['fee']) 
            && isset($data['introduction']))
        {
            if($request->get('current_pro'))
            {
                $product = $this->product->update($request->get('current_pro'),$data,1001);

                if(is_null($product))
                {
                    return null;
                }

            } else {

                $product = $this->product->create(access()->id(),$data, 1001);
            }

            $uimageRes->resetProductId($product->id);

            if($request->get('images'))
            {
                $images = $uimageRes->updateProductId(explode(',',$request->get('images')),$product->id);
            }

            
            //  记录操作历史-----todo
        
            return ['code' => 0, 'data'=>[], 'msg' => 'success'];
        } else {
            return ['code' => -1, 'data'=>[], 'msg' => '产品信息不完整'];
        }
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
                    return '<a class="col-md-12" href="'.route("frontend.mlm.demandside.product.show", $product->id).'">未命名</a>';
                }

                return '<a class="col-md-12" href="'.route("frontend.mlm.demandside.product.show", $product->id).'">'.$product->product_no.'</a>';
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

    public function demandsidproducts(StyleRepository $styleRes)
    {

        // $styles = $styleRes->getAll();
        return DataTables::of($this->product->getForAuditorDataTable())
            ->escapeColumns(['fee'])
            ->addColumn('product_no', function ($product) {
                if(is_null($product->product_no)) {
                    return '<a class="col-md-12" href="'.route("frontend.mlm.demandside.product.show", $product->id).'">未命名</a>';
                }

                return '<a class="col-md-12" href="'.route("frontend.mlm.demandside.product.show", $product->id).'">'.$product->product_no.'</a>';
            })
            ->addColumn('cycle', function ($product) {
                if(is_null($product->cycle)) {
                    return '未确定';
                }

                return $product->cycle;
            })
            ->addColumn('resource', function ($product) {
                $resource = ($product->images . '张图片 +');
                if($product->cad_id) {
                    $resource .= '有cad ';
                } else {
                    $resource .= '无cad ';
                }


                return $resource;
            }) 
            ->addColumn('style', function ($product) {
                // $style = $styleRes->find($product->style_id);

                return "$product->style_name";
            })
            ->addColumn('actions', function ($product) {

                return '<div data-proid="'.$product->id.'" class="btn btn-warning nopass">不通过</div> <div data-proid="'.$product->id.'" class="btn btn-success pass">通过</div> ';
            })
            ->addColumn('download', function($product) {
                return '<div data-proid="'.$product->id.'" class="btn btn-info">下载资料包</div>';
            })
            ->make(true);
    }


    public function nopass(ProductRequest $request, ProductRepository $productRes)
    {

        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->find($productid);
            if($product)
            {
                if($product->status_no==1001)
                {
                    $this->product->updateStatus($product->id,1002);

                    return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }

    public function pass(ProductRequest $request, ProductRepository $productRes)
    {

        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->find($productid);
            if($product)
            {
                if($product->status_no==1001)
                {
                    $this->product->updateStatus($product->id,1003);

                    return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }
}
