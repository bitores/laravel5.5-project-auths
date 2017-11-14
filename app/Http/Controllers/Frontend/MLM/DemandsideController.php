<?php

namespace App\Http\Controllers\Frontend\MLM;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\MLM\BrandRepository;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Repositories\Frontend\MLM\CategoryARepository;
use App\Repositories\Frontend\MLM\CategoryBRepository;
use App\Repositories\Frontend\MLM\StyleRepository;
use App\Repositories\Frontend\MLM\UImageRepository;
use App\Repositories\Frontend\MLM\ProductRepository;
use App\Repositories\Frontend\MLM\ProductsViewRepository;
use App\Repositories\Frontend\MLM\ProductReviewRepository;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

/**
 * Class DashboardController.
 */
class DemandsideController extends Controller
{
    public function __construct(BrandRepository $brand)
    {
        $this->brand = $brand;
    }

    public function index(ProductRepository $productRes)
    {   
        $products = $productRes->findAll();

        return view('frontend.mlm.demandside.index',[
            'products' => $products
        ]);
    }

    public function readme()
    {
        return view('frontend.mlm.demandside.readme');
    }

    public function create(CategoryARepository $categorya,CategoryBRepository $categoryb,StyleRepository $style)
    {
        $brands = $this->brand->findDataById(access()->id());
        $categories_a = $categorya->findAllData();
        $categories_b = $categoryb->findAllData();


        return view('frontend.mlm.demandside.product.create',[
            'brands'=>$brands,
            'categories_a'=>$categories_a,
            'categories_b'=>$categories_b,
            'styles'=>$style->getAll()
        ]);
    }

    public function edit(CategoryARepository $categorya,CategoryBRepository $categoryb,StyleRepository $style, UImageRepository $imageRes, ProductRepository $productRes, $productid)
    {
        $product = $productRes->findDataById($productid);

        

        if($product) {
            $brands = $this->brand->findDataById(access()->id());
            $categories_a = $categorya->findAllData();
            $categories_b = $categoryb->findAllData();

            $images = $imageRes->findAllDataByProductId($productid);

            return view('frontend.mlm.demandside.product.edit',[
                'product' => $product,
                'brands'=>$brands,
                'categories_a'=>$categories_a,
                'categories_b'=>$categories_b,
                'styles'=>$style->getAll(),
                'images'=> $images
            ]);
        } else {
            return redirect()->route('frontend.mlm.demandside.index')->withFlashSuccess('您无此产品');
        }
        
    }
// CategoryARepository $categorya,CategoryBRepository $categoryb,StyleRepository $style,
    public function show( UImageRepository $imageRes, ProductsViewRepository $productRes, $productid)
    {
        $product = $productRes->findDataById($productid);

        

        if($product) {
            // $brand = $this->brand->find($product->brand_id);

            // $categories_a = $categorya->findAllData();
            // $categories_b = $categoryb->findAllData();

            $images = $imageRes->findAllDataByProductId($productid);

            return view('frontend.mlm.demandside.product.show',[
                'product' => $product,
                // 'brand'=>$brand,
                // 'categories_a'=>$categories_a,
                // 'categories_b'=>$categories_b,
                // 'styles'=>$style->getAll(),
                'images'=> $images,
                // 'status' => $product->status_no
            ]);
        } else {
            return redirect()->route('frontend.mlm.demandside.index')->withFlashSuccess('您无此产品');
        }
        
    }

    public function assessment(ProductReviewRepository $productReview, $productid)
    {
        $review = $productReview->findDataById($productid);
        if($review) {
            return view('frontend.mlm.demandside.product.assessment',[
                'content' => $review->comments
            ]);
        } else {
            return view('frontend.mlm.demandside.product.assessment',[
                'content' => "无修改意见"
            ]);
        }
        
    }


    public function html2pdf(ProductReviewRepository $productReview, $productid)
    {
        $review = $productReview->findDataById($productid);
        if($review) {
            $content = $review->comments;
        } else {
            $content = '暂无修改意见';
        }



        $html2pdf = new Html2Pdf('P', 'A4', 'tr', true, 'UTF-8', 3);
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        $html2pdf->output("修改意见文档.pdf");
    }
}