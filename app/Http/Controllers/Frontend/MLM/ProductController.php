<?php

namespace App\Http\Controllers\Frontend\MLM;

use App\Http\Controllers\Controller;
use Config;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Frontend\MLM\ProductRequest;
use App\Repositories\Frontend\MLM\ProductRepository;
use App\Repositories\Frontend\MLM\UProductRepository;
use App\Repositories\Frontend\MLM\ProductReviewRepository;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Repositories\Frontend\MLM\UImageRepository;
use App\Repositories\Frontend\MLM\StyleRepository;
use App\Repositories\Frontend\MLM\ProductsViewRepository;

use \Chumper\Zipper\Zipper;

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
                    return '<div style="color:gray">未提交审核</div>';
                } else if($product->status_no === 1001) {
                    return '<div style="color:yellow">审核中</div>';
                } else if($product->status_no === 1002) {
                    return '<div style="color:red">审核未通过</div><div data-proid="'.$product->id.'" class="btn btn-info download">下载修改意见</div>';
                } else if($product->status_no === 1003) {
                    return '<div style="color:green">审核已通过</div>';
                } else if($product->status_no === 1005) {
                    return '<div style="color:yellow">等待接单</div>';
                } else if($product->status_no === 1006) {
                    return '<div style="color:green">制作中</div>';
                } else if($product->status_no === 1007) {
                    return '<div style="color:yellow">模型审核中</div>';
                } else if($product->status_no === 1008) {
                    return '<div style="color:red">模型审核未通过</div><div data-proid="'.$product->id.'" class="btn btn-info">下载修改意见</div>';
                } else if($product->status_no === 1009) {
                    return '<div style="color:green">模型审核已通过</div>';
                } else if($product->status_no === 1010) {
                    return '<div style="color:black">模型已入库</div>';
                }
            })
            ->addColumn('actions', function ($product) {
                if($product->status_no == 1005 )
                {
                    return '<div data-proid="'.$product->id.'" class="btn btn-warning cancelbtn">撤单</div>';
                } else {
                    return " - ";
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
                return '<div data-proid="'.$product->id.'" class="btn btn-info download">下载资料包</div>';
            })
            ->make(true);
    }


    public function producermodels(StyleRepository $styleRes)
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

                return '<div data-proid="'.$product->id.'" class="btn btn-warning nopass">不通过</div> <div data-proid="'.$product->id.'" class="btn btn-success pass">通过</div> ';
            })
            ->addColumn('download', function($product) {
                return '<div data-proid="'.$product->id.'" class="btn btn-info download">资料包</div><div data-proid="'.$product->id.'" class="btn btn-info downloadmodel">模型</div>';
            })
            ->make(true);
    }


    public function tasks()
    {

        return DataTables::of($this->product->getForProducerDataTable())
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
            // ->addColumn('style', function ($product) {

            //     return "$product->style_name";
            // })
            // ->addColumn('actions', function ($product) {

            //     return '<div data-proid="'.$product->id.'" class="btn btn-warning nopass">不通过</div> <div data-proid="'.$product->id.'" class="btn btn-success pass">通过</div> ';
            // })
            // ->addColumn('download', function($product) {
            //     return '<div data-proid="'.$product->id.'" class="btn btn-info">下载资料包</div>';
            // })
            ->addColumn('orders', function($product) {
                return '<div data-proid="'.$product->id.'" class="btn btn-info orderbtn">接单</div>';
            })

            ->make(true);
    }


    public function minetasks()
    {

        return DataTables::of($this->product->getForProducerSelfDataTable())
            ->escapeColumns(['fee'])
            ->addColumn('product_finish', function ($product) {
                if($product->status_no===1009) {
                    return '具体时间';
                }

                return '未完成';
            })
            ->addColumn('product_status', function ($product) {

                if($product->status_no === 1006) {
                    return '<div style="color:green">制作中</div>';
                } else if($product->status_no === 1007) {
                    return '<div style="color:yellow">模型审核中</div>';
                } else if($product->status_no === 1008) {
                    return '<div style="color:red">模型审核未通过</div><div data-proid="'.$product->id.'" class="btn btn-info">下载修改意见</div>';
                } else if($product->status_no === 1009) {
                    return '<div style="color:green">模型审核已通过</div>';
                } else if($product->status_no === 1010) {
                    return '<div style="color:black">模型已入库</div>';
                }

                return '细节问题'.$product->status_no;
            })
            ->addColumn('uploadbtn', function ($product) {


                return '<div class="btn btn-info uploadbtn">上传</div>';
            })
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
            // ->addColumn('style', function ($product) {

            //     return "$product->style_name";
            // })
            // ->addColumn('actions', function ($product) {

            //     return '<div data-proid="'.$product->id.'" class="btn btn-warning nopass">不通过</div> <div data-proid="'.$product->id.'" class="btn btn-success pass">通过</div> ';
            // })
            // ->addColumn('download', function($product) {
            //     return '<div data-proid="'.$product->id.'" class="btn btn-info">下载资料包</div>';
            // })
            ->addColumn('orders', function($product) {
                return '<div data-proid="'.$product->id.'" class="btn btn-info cancelbtn">是</div>';
            })

            ->make(true);
    }



    public function nopass(ProductRequest $request, ProductRepository $productRes,ProductReviewRepository $productreview)
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
                    $productreview->create([
                        'type'=>1,
                        'comments'=>$request->get('content'),
                        'product_id'=>$product->id
                    ]);

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

    public function modelnopass(ProductRequest $request, ProductRepository $productRes,ProductReviewRepository $productreview)
    {

        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->find($productid);
            if($product)
            {
                if($product->status_no==1007)
                {
                    $this->product->updateStatus($product->id,1008);
                    $productreview->create([
                        'type'=>2,
                        'comments'=>$request->get('content'),
                        'product_id'=>$product->id
                    ]);

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

    public function del(ProductRequest $request, ProductRepository $productRes)
    {

        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->find($productid);
            if($product)
            {
                    $bool = $this->product->delProduct($product->id);
                    if($bool) {
                        return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                    } else {
                        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
                    }

                    
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }


    public function postTask(ProductRequest $request, ProductRepository $productRes)
    {
        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->findDataById($productid);
            if($product)
            {
                if($product->status_no==1003)
                {
                    $this->product->updateStatus($product->id,1005);

                    return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }

    public function cancelTask(ProductRequest $request, ProductRepository $productRes)
    {
        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->findDataById($productid);
            if($product)
            {
                if($product->status_no==1005)
                {
                    $this->product->updateStatus($product->id,1003);

                    return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }


    public function reviewComments(ProductRequest $request, ProductReviewRepository $productReviewRes)
    {
        $productid = $request->get('productid');
        if($productid)
        {
            $product = $this->product->findDataById($productid);
            if($product)
            {
                    $productReview = $productReviewRes->findDataById($productid);

                    if($productReview)
                    {
                        return ['code' => 0, 'data'=>[
                            'comments'=>$productReview->comments
                        ], 'msg' => '操作成功'];
                    }

                    
            }
        }

        return ['code' => -1, 'data'=>[
            'comments'=>'暂无内容'
        ], 'msg' => '操作失败'];
    }

    public function order(ProductRequest $request, UProductRepository $uproductRes)
    {
        $productid = $request->get('productid');
        if($productid)
        {
            $product = $this->product->findDataById($productid);
            if($product)
            {
                if($product->status_no==1005)
                {
                    // $this->product->updateStatus($product->id,1006);
                    $ret = $this->product->order($product->id);
                    // add 接单操作
                    // $ret = $uproductRes->create([
                    //     'product_id' => $product->id,
                    //     'user_id' => access()->id()
                    // ]);

                    if($ret) {
                        return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                    } else {
                        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
                    }

                    
                } else {
                    return ['code' => -1, 'data'=>[], 'msg' => '订单不存在'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }

    public function cancelorder(ProductRequest $request, UProductRepository $uproductRes)
    {
        $productid = $request->get('productid');
        if($productid)
        {
            $product = $this->product->findOrderDataById($productid);
            if($product)
            {
                if($product->status_no==1006)
                {
                    // $this->product->updateStatus($product->id,1006);
                    $ret = $this->product->cancelorder($product->id);
                    // add 接单操作
                    // $ret = $uproductRes->create([
                    //     'product_id' => $product->id,
                    //     'user_id' => access()->id()
                    // ]);

                    if($ret) {
                        return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                    } else {
                        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
                    }

                    
                } else {
                    return ['code' => -1, 'data'=>[], 'msg' => '订单不存在'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }


    public function download( ProductRequest $request,UImageRepository $imageRes, ProductsViewRepository $productViewRes, ProductRepository $productRes)
    {
        $productid = $request->get('productid');
        if($productid)
        {

            $product = $productViewRes->findDataById($productid);

            if($product) {

                if(isset($product->zip_path)){
                    return ['code' => 0, 'data'=>'/'.$product->zip_path, 'msg' => '操作成功'];
                }

                $images = $imageRes->findAllDataByProductId($productid);
                $allfiles = array();
                foreach ($images as $key => $image) {
                    # code...
                    $allfiles[] = "uploads/materials/".$image->path;
                }

                if(isset($product->cad_path))
                {
                    $allfiles[] = "uploads/materials/".$product->cad_path;
                }

                if(isset($product->file_path))
                {
                    $allfiles[] = "uploads/materials/".$product->file_path;
                }


                $zipper = new Zipper;

                $filename="uploads/zip/" . date('YmdHis',time()).'.zip';
                $zipper->make($filename)->add($allfiles)->close();

                $productRes->updateZipPath($product->id,$filename);

                return ['code' => 0, 'data'=>'/'.$filename, 'msg' => '操作成功'];
            } else {
                return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
            }

        } else {
            return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
        }
    }
}
