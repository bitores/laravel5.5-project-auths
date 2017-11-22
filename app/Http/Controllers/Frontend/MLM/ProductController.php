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


    public function reviewComments(ProductRequest $request, HisReviewRepository $productReviewRes)
    {
        $productid = $request->get('productid');
        if($productid)
        {
            $product = $this->product->find($productid);
            if($product)
            {
                    $productReview = $productReviewRes->findLastByProductId($productid);

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

    public function modelreviewComments(ProductRequest $request, HisReviewRepository $productReviewRes)
    {
        $productid = $request->get('productid');
        if($productid)
        {
            $product = $this->product->find($productid);
            if($product)
            {
                    $productReview = $productReviewRes->findLastByProductId($productid);

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

    


    public function download( ProductRequest $request,UImageRepository $imageRes, VProductRepository $productViewRes, ProductRepository $productRes)
    {
        $productid = $request->get('productid');
        if($productid)
        {

            $product = $productViewRes->find($productid);

            if($product) {

                if(isset($product->zip_path)){
                    return ['code' => 0, 'data'=>'/'.$product->zip_path, 'msg' => '操作成功'];
                }

                $images = $imageRes->getAllByProductId($productid);
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
                return ['code' => -2, 'data'=>[], 'msg' => '操作失败'];
            }

        } else {
            return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
        }
    }

    public function downloadmodel( ProductRequest $request, VProductRepository $productViewRes)
    {
        $productid = $request->get('productid');
        if($productid)
        {

            $product = $productViewRes->find($productid);

            if($product) {

                if(isset($product->model_path)){

                    return ['code' => 0, 'data'=>'/uploads/materials/'.$product->model_path, 'msg' => '操作成功'];
                }
            }
        }

        return ['code' => -1, 'data'=>[], 'msg' => '操作失败'];
    }
}
