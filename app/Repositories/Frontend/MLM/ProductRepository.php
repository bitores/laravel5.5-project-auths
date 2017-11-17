<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\Product;
use App\Repositories\BaseRepository;

/**
 * Class UserSessionRepository.
 */
class ProductRepository extends BaseRepository
{
    const MODEL = Product::class;

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
                'products.images',
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
                'products.images',
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
                'products.images',
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
                'products.images',
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
            $product->images = count($images);
        }

        $product->status_no = $status;
        $product->zip_path = null;
        $product->save();
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
        
        $this->save($instance, $data, $status);
        return $instance;
    }

    public function update($proid, array $data, $status)
    {
        $product = $this->find($proid);

        if($product){
            $this->save($product, $data, $status);
        }
        
        return $product;    
    }

    public function updateStatus($proid, $status)
    {
        $product = $this->find($proid);
        $product->status_no = $status;
        $product->save();

        return $product;
    }

    public function updateCycle($proid, $cycle)
    {
        $product = $this->find($proid);
        $product->cycle = $cycle;
        $product->save();

        return $product;
    }

    public function updateZipPath($proid, $path)
    {
        $product = $this->find($proid);
        $product->zip_path = $path;
        $product->save();

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
        

        return $product;
    }

    //  获取 指定需求方 的 所有产品
    public function getAllByUserId($userid)
    {
        return $this->query()->where('user_id',$userid)->get();
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
