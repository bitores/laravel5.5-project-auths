<?php

namespace App\Http\Controllers\Frontend\MLM;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Http\Requests\Frontend\MLM\ProductRequest;
use App\Repositories\Frontend\MLM\UBrandRepository;
use App\Repositories\Frontend\MLM\PCategoryARepository;
use App\Repositories\Frontend\MLM\PCategoryBRepository;
use App\Repositories\Frontend\MLM\ProductRepository;
use App\Repositories\Frontend\MLM\HisReviewRepository;
use App\Repositories\Frontend\MLM\UImageRepository;
use App\Repositories\Frontend\MLM\PStyleRepository;
use App\Repositories\Frontend\MLM\UModelRepository;
use App\Repositories\Frontend\MLM\VProductRepository;

/**
 * Class DashboardController.
 */
class DemandsideController extends Controller
{
    public function __construct(UserRepository $user, UBrandRepository $brand, ProductRepository $product)
    {
        $this->user = $user;
        $this->brand = $brand;
        $this->product = $product;
    }

    public function index(ProductRepository $productRes)
    {   
        $products = $productRes->getAllByUserId(access()->id());

        return view('frontend.mlm.demandside.index',[
            'products' => $products
        ]);
    }

    public function readme()
    {
        return view('frontend.mlm.product.readme');
    }

    public function create(PCategoryARepository $categorya,PCategoryBRepository $categoryb,PStyleRepository $style)
    {
        $brands = $this->brand->getAllByUserId(access()->id());

        return view('frontend.mlm.demandside.create',[
            'brands'=>$brands,
            'categories_a'=>$categorya->getAll(),
            'categories_b'=>$categoryb->getAll(),
            'styles'=>$style->getAll()
        ]);
    }

    public function edit(PCategoryARepository $categorya,PCategoryBRepository $categoryb,PStyleRepository $style, UImageRepository $imageRes, ProductRepository $productRes, $productid)
    {
        $product = $productRes->findByUserIdAndProductId(access()->id(), $productid);

        if($product) {
            $brands = $this->brand->getAllByUserId(access()->id());
            $images = $imageRes->getAllByProductId($productid);

            return view('frontend.mlm.demandside.edit',[
                'product' => $product,
                'brands'=>$brands,
                'categories_a'=>$categorya->getAll(),
                'categories_b'=>$categoryb->getAll(),
                'styles'=>$style->getAll(),
                'images'=> $images
            ]);
        } else {
            return redirect()->route('frontend.mlm.demandside.index')->withFlashSuccess('您无此产品');
        }
        
    }

    public function show( UImageRepository $imageRes, VProductRepository $productRes, $productid)
    {
        $product = $productRes->find( $productid);

        if($product) {

            $images = $imageRes->getAllByProductId($productid);

            return view('frontend.mlm.product.show',[
                'product' => $product,
                'images'=> $images
            ]);
        } else {
            return redirect()->route('frontend.mlm.demandside.index')->withFlashSuccess('您无此产品');
        }
        
    }

    public function assessment(HisReviewRepository $productReview, $productid)
    {
        $review = $productReview->findLastByProductId($productid);
        if($review) {
            return view('frontend.mlm.demandside.assessment',[
                'content' => $review->comments
            ]);
        } else {
            return view('frontend.mlm.demandside.assessment',[
                'content' => "无修改意见"
            ]);
        }
        
    }


    //  --------------------- API

    public function del(ProductRequest $request, ProductRepository $productRes)
    {

        $productid = $request->get('productid');
        if($productid)
        {
            $product = $productRes->findByUserIdAndProductId(access()->id(), $productid);
            if($product)
            { 
                if($product->status_no < 1006)
                {
                    $bool = $this->product->delProduct(access()->id(), $product->id);
                    if($bool) {
                        return ['code' => 0, 'data'=>[], 'msg' => '操作成功'];
                    }
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
            $product = $productRes->findByUserIdAndProductId(access()->id(), $productid);
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
            $product = $productRes->findByUserIdAndProductId(access()->id(), $productid);
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

        if($request->get('face_id'))
        {
            $images = $uimageRes->updateCoverByImageId($request->get('face_id'));
            $image = $uimageRes->find($request->get('face_id'));
            $this->product->updateCoverPath($product->id, $image->path);
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
                    $images = $uimageRes->getAllByProductId($product->id);

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


    public function batsubmit(ProductRepository $productRes)
    {
        $products = $productRes->getAllByUserIdAndStatus(access()->id(), 1000);

        $total = count($products);// 等提交审核的产品数
        $submit = 0;    // 本次批量成功提交的产品数

        foreach ($products as $product) {
            # code...
            if(isset($product->product_no) 
                && isset($product->style_id) 
                && isset($product->a_id) 
                && isset($product->b_id) 
                && isset($product->brand_id) 
                && ($product->image_count>0)
                && isset($product->fee) 
                && isset($product->introduction)){

                $this->product->updateStatus($product->id, 1001);

                $submit++;
            }

        }

        return ['code' => 0, 'data'=>[
            'total' => $total,
            'updated' => $submit
        ], 'msg' => 'success'];
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

    public function table()
    {
        return DataTables::of($this->product->getForDataTable())
            ->escapeColumns(['fee'])
            ->addColumn('fee', function($product){
                if($product->fee) {
                    return '￥'. $product->fee;
                } else {
                    return '-';
                }
                
            })
            ->addColumn('product_no', function ($product) {

                return '<a href="'.route("frontend.mlm.demandside.product.show", $product->id).'" style="display: inline-block;"><img src="'.($product->cover_path?("/uploads/materials/".$product->cover_path):("/img/avatars/product.png")).'" style="width:100px;height:100px;display:inline-block;margin-right:10px"><span>'.(is_null($product->product_no)?"未命名":$product->product_no).'</span></a>';
            })
            ->addColumn('cycle', function ($product) {
                if(is_null($product->cycle)) {
                    return '-';
                }

                return $product->cycle . '天';
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
                // 1010 模型二次审核未审核
                // 1011 模型二次审核已通过
                // 2000 模型已入库（完成制作）

                if($product->status_no === 1000)
                {
                    return '<div>未提交</div>';
                } else if($product->status_no === 1001) {

                    return '<div>需求审核中</div>';
                } else if($product->status_no === 1002) {

                    return '<div>需求审核未通过</div><a href="'.route("frontend.mlm.demandside.product.assessment", $product->id).'" class="font-color">查看结果</a><div data-proid="'.$product->id.'" class="btn download font-color">下载文档</div>';
                } else if($product->status_no === 1003) {

                    return '<div>需求审核已通过</div><div data-proid="'.$product->id.'" class="btn btn-fix postbtn" style="cursor:pointer">发布</div>';
                } else if($product->status_no === 1005) {

                    return '<div>等待接单</div>';
                } else if($product->status_no === 1006) {

                    return '<div>制作中</div>';
                } else if($product->status_no === 1007) {

                    return '<div>模型审核中</div>';
                }  else if($product->status_no === 1008) {

                    return '<div>模型审核未通过</div><a href="'.route("frontend.mlm.demandside.product.assessment", $product->id).'" class="font-color">查看结果</a><div data-proid="'.$product->id.'" class="btn download font-color">下载文档</div>';
                } else if($product->status_no === 1009) {

                    return '<div>模型审核已通过</div><div data-proid="'.$product->id.'" class="btn downloadmodel font-color">下载模型</div>';
                } else if($product->status_no === 1010) {
                    
                    return '<div>模型已入库</div>';
                }
            })
            ->addColumn('actions', function ($product) {
                if($product->status_no == 1005 )
                {
                    return '<div data-proid="'.$product->id.'" class="btn btn-fix cancelbtn">撤单</div>';
                } else {
                    return '<div data-proid="'.$product->id.'" class="btn btn-fix-error">撤单</div>';
                }
                
            })
            ->make(true);
    }



}