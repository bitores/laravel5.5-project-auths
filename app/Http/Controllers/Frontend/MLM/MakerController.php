<?php

namespace App\Http\Controllers\Frontend\MLM;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Http\Requests\Frontend\MLM\ProductRequest;
use App\Repositories\Frontend\MLM\ProductRepository;
use App\Repositories\Frontend\MLM\UOrderRepository;
use App\Repositories\Frontend\MLM\PReviewRepository;
use App\Repositories\Frontend\MLM\UImageRepository;
use App\Repositories\Frontend\MLM\PStyleRepository;
use App\Repositories\Frontend\MLM\UModelRepository;
use App\Repositories\Frontend\MLM\VProductRepository;

/**
 * Class DashboardController.
 */
class MakerController extends Controller
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
        return view('frontend.mlm.maker.index');
    }

    public function demandlist()
    {
        return view('frontend.mlm.maker.demandlist');
    }

    public function modelingTutorial()
    {
        return view('frontend.mlm.maker.tutorial.modeling');
    }

    public function reviewTutorial()
    {
        return view('frontend.mlm.maker.tutorial.review');
    }

    public function assessment(PReviewRepository $productReview, $productid)
    {
        $review = $productReview->findLastByProductId($productid);
        if($review) {
            return view('frontend.mlm.maker.assessment',[
                'content' => $review->comments
            ]);
        } else {
            return view('frontend.mlm.maker.assessment',[
                'content' => "无修改意见"
            ]);
        }
        
    }




    // -------------------------- API
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
                    return '<div style="color:red">模型审核未通过</div><div data-proid="'.$product->id.'" class="btn btn-info download">下载修改意见</div>';
                } else if($product->status_no === 1009) {
                    return '<div style="color:green">模型审核已通过</div>';
                } else if($product->status_no === 1010) {
                    return '<div style="color:black">模型已入库</div>';
                }

                return '细节问题'.$product->status_no;
            })
            ->addColumn('uploadbtn', function ($product) {

                if($product->status_no == 1006) {
                    return '<div class="btn btn-info uploadbtn" data-proid="'.$product->id.'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#upload-dialog">上传</div>';
                }
                return '';
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
                $pass = strtotime($product->updated_at);
                $now = time();

                $diff = $now - $pass;
                if($product->status_no == 1006 || $diff>1){
                    return '';
                }
                return '<div data-proid="'.$product->id.'" class="btn btn-info cancelbtn">是</div>';
            })

            ->make(true);
    }

    public function order(ProductRequest $request)
    {
        $productid = $request->get('productid');
        if($productid)
        {
            $product = $this->product->find($productid);
            if($product)
            {
                if($product->status_no==1005)
                {
                    // $this->product->updateStatus($product->id,1006);
                    $ret = $this->product->order(access()->id(), $product->id);
                    // add 接单操作


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

    // 提交模型审核 
    public function model(ProductRequest $request, UModelRepository $umodelRes)
    {
        $productid = $request->get('productid');
        $modelid = $request->get('modelid');
        if($productid && $modelid)
        {
            $product = $this->product->findByProducerIdAndProductId(access()->id(), $productid);
            $model = $umodelRes->find($modelid);
            if($product && $model)
            {
                if($product->status_no==1006)
                {
                    // $this->product->updateStatus($product->id,1006);
                    $ret = $this->product->model($product->id, $model->id);
                    // add 接单操作
                    // $ret = $uproductRes->create([
                    //     'product_id' => $product->id,
                    //     'model_id' => $request->get('modelid')
                    // ]);

                    if($ret) {
                        return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                    } else {
                        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
                    }

                    
                } else {
                    return ['code' => -2, 'data'=>[], 'msg' => '操作失败'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }

    

    public function cancelorder(ProductRequest $request)
    {
        $productid = $request->get('productid');
        if($productid)
        {
            $product = $this->product->findByProducerIdAndProductId(access()->id(), $productid);
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





}