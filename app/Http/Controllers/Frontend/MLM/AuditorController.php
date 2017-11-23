<?php

namespace App\Http\Controllers\Frontend\MLM;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Http\Requests\Frontend\MLM\ProductRequest;
use App\Repositories\Frontend\MLM\ProductRepository;
use App\Repositories\Frontend\MLM\HisReviewRepository;
use App\Repositories\Frontend\MLM\UImageRepository;
use App\Repositories\Frontend\MLM\PStyleRepository;
use App\Repositories\Frontend\MLM\UModelRepository;
use App\Repositories\Frontend\MLM\VProductRepository;

/**
 * Class DashboardController.
 */
class AuditorController extends Controller
{
    public function __construct(UserRepository $user, ProductRepository $product)
    {
        $this->user = $user;
        $this->product = $product;
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.mlm.auditor.index');
    }

    public function demands()
    {
        return view('frontend.mlm.auditor.demandlist');
    }

    public function models()
    {
        return view('frontend.mlm.auditor.modellist');
    }


    //---------------API

    public function demandsidproducts(PStyleRepository $styleRes)
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
                $resource = ($product->image_count . '张图片 +');
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

                return '<div data-proid="'.$product->id.'" class="btn btn-warning nopass" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#alert-editor">'
                .($product->review_demand_count==0?'不通过':('打回'.$product->review_demand_count.'次')).
                '</div> <div data-proid="'.$product->id.'" class="btn btn-success pass">通过</div> ';
            })
            ->addColumn('download', function($product) {
                return '<div data-proid="'.$product->id.'" class="btn btn-info download">下载资料包</div>';
            })
            ->make(true);
    }


    public function producermodels(PStyleRepository $styleRes)
    {

        // $styles = $styleRes->getAll();
        return DataTables::of($this->product->getProducerModelsDataTable())
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

                return '<div data-proid="'.$product->id.'" class="btn btn-warning nopass" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#alert-editor">'
                .($product->review_model_count==0?'不通过':('打回'.$product->review_model_count.'次')).
                '</div> <div data-proid="'.$product->id.'" class="btn btn-success pass">通过</div> ';
            })
            ->addColumn('download', function($product) {
                return '<div data-proid="'.$product->id.'" class="btn btn-info icon-circle-arrow-down download">下载资料包</div><div data-proid="'.$product->id.'" class="btn btn-info icon-circle-arrow-down downloadmodel">下载模型</div>';
            })
            ->make(true);
    }


    
    public function nopass(ProductRequest $request, ProductRepository $productRes,HisReviewRepository $productreview)
    {

        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->find($productid);
            if($product)
            {
                if($product->status_no==1001)
                {
                    
                    $review = $productreview->create([
                        'type'=>1,
                        'comments'=>$request->get('content'),
                        'product_id'=>$product->id
                    ]);

                    $this->product->updateDemandReviewID($product->id,1002, $review->id);

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
                    $this->product->updateCycle($product->id, $request->get('cycle'));

                    return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }

    public function modelnopass(ProductRequest $request, ProductRepository $productRes,HisReviewRepository $productreview)
    {

        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->find($productid);
            if($product)
            {
                if($product->status_no==1007)
                {
                    // $this->product->updateStatus($product->id,1008);
                    $review = $productreview->create([
                        'type'=>2,
                        'comments'=>$request->get('content'),
                        'product_id'=>$product->id
                    ]);


                    $this->product->updateModelReviewID($product->id,1008, $review->id);

                    return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }

    public function modelpass(ProductRequest $request, ProductRepository $productRes)
    {

        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->find($productid);
            if($product)
            {
                if($product->status_no==1007)
                {
                    $this->product->updateStatus($product->id,1009);
                    // $this->product->updateCycle($product->id, $request->get('cycle'));

                    return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }


    public function model2nopass(ProductRequest $request, ProductRepository $productRes,HisReviewRepository $productreview)
    {

        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->find($productid);
            if($product)
            {
                if($product->status_no==1010)
                {
                    // $this->product->updateStatus($product->id,1008);
                    $review = $productreview->create([
                        'type'=>3,
                        'comments'=>$request->get('content'),
                        'product_id'=>$product->id
                    ]);


                    $this->product->updateModel2ReviewID($product->id,1011, $review->id);

                    return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }

    // 模型二次审核通过
    public function model2pass(ProductRequest $request, ProductRepository $productRes)
    {

        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->find($productid);
            if($product)
            {
                if($product->status_no==1010)
                {
                    $this->product->updateStatus($product->id,1012);
                    // $this->product->updateCycle($product->id, $request->get('cycle'));

                    return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }
    
}