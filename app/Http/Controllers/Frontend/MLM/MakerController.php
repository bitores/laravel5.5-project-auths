<?php

namespace App\Http\Controllers\Frontend\MLM;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Http\Requests\Frontend\MLM\ProductRequest;
use App\Repositories\Frontend\MLM\ProductRepository;
use App\Repositories\Frontend\MLM\HisOrderRepository;
use App\Repositories\Frontend\MLM\HisReviewRepository;
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
        return view('frontend.mlm.tutorial.modeling');
    }

    public function reviewTutorial()
    {
        return view('frontend.mlm.tutorial.review');
    }

    public function assessment(HisReviewRepository $productReview, $productid)
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

    //-------------------------- API ----------------
    public function tasks()
    {

        return DataTables::of($this->product->getForProducerDataTable())
            ->escapeColumns(['fee'])
            ->addColumn('fee', function($product){
                if($product->fee) {
                    return '￥'. $product->fee;
                } else {
                    return '-';
                }
                
            })
            ->addColumn('product_no', function ($product) {
                // if(is_null($product->product_no)) {
                //     return '<a class="col-md-12" href="'.route("frontend.mlm.demandside.product.show", $product->id).'">未命名</a>';
                // }

                // return '<a class="col-md-12" href="'.route("frontend.mlm.demandside.product.show", $product->id).'">'.$product->product_no.'</a>';


                return '<a href="'.route("frontend.mlm.demandside.product.show", $product->id).'" style="display: inline-block;"><img src="'.($product->cover_path?("/uploads/materials/".$product->cover_path):("/img/avatars/product.png")).'" style="width:100px;height:100px;display:inline-block;margin-right:10px"><span>'.(is_null($product->product_no)?"未命名":$product->product_no).'</span></a>';
            })
            ->addColumn('cycle', function ($product) {
                if(is_null($product->cycle)) {
                    return '未确定';
                }

                return $product->cycle  . '天';
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
                return '<div data-proid="'.$product->id.'" class="btn btn-fix orderbtn" class="font-color">接单</div>';
            })

            ->make(true);
    }


    public function minetasks()
    {

        return DataTables::of($this->product->getForProducerSelfDataTable())
            ->escapeColumns(['fee'])
            ->addColumn('fee', function($product){
                if($product->fee) {
                    return '￥'. $product->fee;
                } else {
                    return '-';
                }
                
            })
            ->addColumn('product_finish', function ($product) {
                if($product->status_no===1009) {
                    return '具体时间';
                }

                return '未完成';
            })
            ->addColumn('product_status', function ($product) {

                if($product->status_no === 1006) {
                    return '<div>制作中</div>';
                } else if($product->status_no === 1007) {
                    return '<div>模型审核中</div>';
                } else if($product->status_no === 1008) {
                    return '<div>模型审核未通过</div><a href="'.route("frontend.mlm.producer.product.assessment", $product->id).'" class="font-color">查看结果</a><div data-proid="'.$product->id.'" class="btn download font-color">下载文档</div>';
                } else if($product->status_no === 1009) {
                    return '<div>模型审核已通过</div>';
                } else if($product->status_no === 1010) {
                    return '<div>模型已入库</div>';
                }

                return '细节问题'.$product->status_no;
            })
            ->addColumn('uploadbtn', function ($product) {

                if($product->status_no == 1006 || $product->status_no == 1008) {
                    return '<div class="btn btn-fix uploadbtn" data-proid="'.$product->id.'" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#upload-dialog">上传</div>';
                }
                return '已上传';
            })
            ->addColumn('product_no', function ($product) {
                // if(is_null($product->product_no)) {
                //     return '<a class="col-md-12" href="'.route("frontend.mlm.demandside.product.show", $product->id).'">未命名</a>';
                // }

                // return '<a class="col-md-12" href="'.route("frontend.mlm.demandside.product.show", $product->id).'">'.$product->product_no.'</a>';

                return '<a href="'.route("frontend.mlm.demandside.product.show", $product->id).'" style="display: inline-block;"><img src="'.($product->cover_path?("/uploads/materials/".$product->cover_path):("/img/avatars/product.png")).'" style="width:100px;height:100px;display:inline-block;margin-right:10px"><span>'.(is_null($product->product_no)?"未命名":$product->product_no).'</span></a>';
            })
            ->addColumn('cycle', function ($product) {
                if(is_null($product->cycle)) {
                    return '未确定';
                }

                return $product->cycle  . '天';
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

                if($product->status_no == 1006){
                    return '<div data-proid="'.$product->id.'" class="btn btn-fix cancelbtn">取消</div>';
                }
                return '-';// '<div data-proid="'.$product->id.'" class="btn btn-info cancelbtn">是</div>';
            })

            ->make(true);
    }

    public function order(ProductRequest $request, HisOrderRepository $order)
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
                    $order->accept(access()->id(), $product->id);

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
                    $ret = $this->product->model($product->id, $model->id);

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

    

    public function cancelorder(ProductRequest $request, HisOrderRepository $order)
    {
        $productid = $request->get('productid');
        if($productid)
        {
            $product = $this->product->findByProducerIdAndProductId(access()->id(), $productid);
            if($product)
            {
                if($product->status_no==1006)
                {
                    $ret = $this->product->cancelorder(access()->id(), $product->id);
                    // cancel 取消订单操作
                    $order->cancel(access()->id(), $product->id);

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