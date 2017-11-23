<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\Product;
use App\Models\MLM\HisProduct;
use App\Repositories\BaseRepository;

/**
 * Class UserSessionRepository.
 */
class ProductRepository extends BaseRepository
{
    const MODEL = Product::class;
    const HISMODEL = HisProduct::class;

    /**
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->query()->where('user_id', access()->id())->where('status_no','<>',1004)
            ->select([
                'products.id',
                'products.product_no',
                'products.style_id',
                'products.brand_id',
                'products.a_id',
                'products.b_id',
                'products.cad_id',
                'products.user_id',
                'products.file_id',
                'products.model_id',
                'products.status_no',
                'products.cycle',
                'products.fee',
                'products.introduction',
            ])
            ->orderBy('updated_at','desc');
    }

    public function getForAuditorDataTable()
    {
        return $this->query()->where('status_no', 1001)->leftjoin('p_styles','products.style_id','=','p_styles.id')
            ->select([
                'products.id',
                'products.product_no',
                'products.style_id',
                'products.image_count',
                'products.cad_id',
                'products.file_id',
                'products.cycle',
                'products.fee',
                'p_styles.name as style_name'
            ])
            ->orderBy('products.updated_at','desc');
    }

    public function getProducerModelsDataTable()
    {
        return $this->query()->where('status_no', 1007)->leftjoin('p_styles','products.style_id','=','p_styles.id')
            ->select([
                'products.id',
                'products.product_no',
                'products.style_id',
                'products.image_count',
                'products.cad_id',
                'products.file_id',
                'products.cycle',
                'products.fee',
                'p_styles.name as style_name'
            ])
            ->orderBy('products.updated_at','desc');
    }


    public function getForProducerDataTable()
    {
        return $this->query()->where('status_no', 1005)
            ->select([
                'products.id',
                'products.product_no',
                'products.style_id',
                'products.image_count',
                'products.cad_id',
                'products.file_id',
                'products.cycle',
                'products.fee',
            ])
            ->orderBy('products.updated_at','desc');
    }

    public function getForProducerSelfDataTable()
    {
        return $this->query()->where('status_no','>=',1006)->where('producer_id', access()->id())
            ->select([
                'products.id',
                'products.product_no',
                'products.style_id',
                'products.image_count',
                'products.cad_id',
                'products.file_id',
                'products.status_no',
                'products.cycle',
                'products.fee',
            ])
            ->orderBy('products.updated_at','desc');
    }


    public function save($product, array $data, $status)
    {
        // $product->product_no = isset($data['product_no']) ?: null;
        // $product->style_id = isset($data['style_id']) ?: null;
        // $product->a_id = isset($data['a_id']) ?: null;
        // $product->b_id = isset($data['b_id']) ?: null;
        // $product->brand_id = isset($data['brand_id']) ?: null;
        // $product->cad_id = isset($data['cad_id']) ?: null;
        // $product->file_id = isset($data['file_id']) ?: null;
        // $product->fee = isset($data['fee']) ?: null;
        // $product->introduction = isset($data['introduction']) ?: null;


        if(isset($data['product_no']))
        {
            $product->product_no = $data['product_no'];
        }
        
        if(isset($data['style_id']))
        {
            $product->style_id = $data['style_id'];
        }
        
        if(isset($data['a_id']))
        {
            $product->a_id = $data['a_id'];
        }
        
        if(isset($data['b_id']))
        {
            $product->b_id = $data['b_id'];
        }
        
        if(isset($data['brand_id']))
        {
            $product->brand_id = $data['brand_id'];
        }
        
        if(isset($data['cad_id']))
        {
            $product->cad_id = $data['cad_id'];
        }
        
        if(isset($data['file_id']))
        {
            $product->file_id = $data['file_id'];
        }
        
        if(isset($data['fee']))
        {
            $product->fee = $data['fee'];
        }
        
        if(isset($data['introduction']))
        {
            $product->introduction = $data['introduction'];
        }
        
        if(isset($data['images']))
        {
            $images = explode(',',$data['images']);
            $product->image_count = count($images);
        }

        $product->status_no = $status;
        $product->zip_path = null;
        $product->save();

        return $product;
    }

    public function saveHistory($product)
    {
        $mode = self::HISMODEL;

        $instance = new $mode;
        $instance->product_id = $product->id;
        $instance->user_id = $product->user_id;
        $instance->product_no = $product->product_no;
        $instance->brand_id = $product->brand_id;
        $instance->a_id = $product->a_id;
        $instance->b_id = $product->b_id;
        $instance->style_id = $product->style_id;
        $instance->fee = $product->fee;
        $instance->introduction = $product->introduction;
        $instance->cad_id = $product->cad_id;
        $instance->file_id = $product->file_id;
        $instance->model_id = $product->model_id;
        $instance->image_count = $product->image_count;

        $instance->review_demand_id = $product->review_demand_id;
        $instance->review_model_id = $product->review_model_id;
        $instance->review_demand_count = $product->review_demand_count;
        $instance->review_model_count = $product->review_model_count;

        $instance->save();

        return $instance;
    }


    /**
     * @param User $user
     *
     * @return mixed
     */
    public function create($userid, array $data, $status)
    {
        $model = self::MODEL;

        $instance = new $model;
        $instance->user_id = $userid;
        
        $product = $this->save($instance, $data, $status);

        $this->saveHistory($product);
       
        return $instance;
    }

    public function update($proid, array $data, $status)
    {
        $product = $this->find($proid);

        if($product){
            $this->save($product, $data, $status);
        }
        
        $this->saveHistory($product);
        return $product;    
    }

    public function updateStatus($proid, $status)
    {
        $product = $this->find($proid);
        $product->status_no = $status;
        $product->save();

        $this->saveHistory($product);
        return $product;
    }

    public function updateDemandReviewID($proid, $status, $demandid)
    {
        $product = $this->find($proid);
        $product->status_no = $status;
        $product->review_demand_id = $demandid;
        $product->review_demand_count++; // 记录打回次数
        $product->save();

        $this->saveHistory($product);
        return $product;
    }


    public function updateModelReviewID($proid, $status, $modelid)
    {
        $product = $this->find($proid);
        $product->status_no = $status;
        $product->review_model_id = $modelid;
        $product->review_model_count++; // 记录打回次数
        $product->save();

        $this->saveHistory($product);
        return $product;
    }

    public function updateModel2ReviewID($proid, $status, $modelid)
    {
        $product = $this->find($proid);
        $product->status_no = $status;
        $product->review_model2_id = $modelid;
        $product->review_model2_count++; // 记录打回次数
        $product->save();

        $this->saveHistory($product);
        return $product;
    }

    

    public function updateCycle($proid, $cycle)
    {
        $product = $this->find($proid);
        $product->cycle = $cycle;
        $product->save();

        $this->saveHistory($product);
        return $product;
    }

    public function updateZipPath($proid, $path)
    {
        $product = $this->find($proid);
        $product->zip_path = $path;
        $product->save();

        $this->saveHistory($product);
        return $product;
    }

    public function delProduct($userid, $proid)
    {
        $product = $this->findByUserIdAndProductId($userid, $proid);
        if($product) {
            $product->status_no = 1004;
            // $product->deleted_at = time();
            $product->save();
        }
        
        $this->saveHistory($product);
        return $product;
    }

    // 下订单
    public function order($userid ,$proid)
    {
        $product = $this->find($proid);
        if($product) {
            $product->status_no = 1006;
            $product->producer_id = $userid;
            $product->save();
        }
        
        $this->saveHistory($product);
        return $product;
    }

    // 制作方 绑定 模型id
    public function model($proid,  $modelid)
    {
        $product = $this->find($proid);
        if($product) {
            $product->status_no = 1007;
            $product->model_id = $modelid;
            $product->save();
        }
        
        $this->saveHistory($product);
        return $product;
    }

    // 制作方 取消订单
    public function cancelorder($userid, $proid)
    {
        $product = $this->findByProducerIdAndProductId($userid, $proid);
        if($product) {
            $product->status_no = 1005;
            $product->producer_id = null;
            $product->save();
        }
        
        $this->saveHistory($product);
        return $product;
    }

    //  获取 指定需求方 的 所有产品
    public function getAllByUserId($userid)
    {
        return $this->query()->where('user_id',$userid)->get();
    }

    //  获取 指定需求方 指定状态 的 所有产品
    public function getAllByUserIdAndStatus($userid, $status)
    {
        return $this->query()->where('user_id',$userid)->where('status_no', $status)->get();
    }

    // 获取 指定需求方 指定产品 的信息
    public function findByUserIdAndProductId($userid, $id)
    {
        return $this->query()->where('user_id', $userid)->where('id',$id)->first();
    }

    // 获取 指定制作方 指定产品 的信息
    public function findByProducerIdAndProductId($producerid, $id)
    {
        return $this->query()->where('producer_id', $producerid)->where('id',$id)->first();
    }
}
