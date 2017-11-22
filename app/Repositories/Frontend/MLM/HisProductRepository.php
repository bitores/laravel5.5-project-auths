<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\HisProduct;
use App\Repositories\BaseRepository;

/**
 * Class UserSessionRepository.
 */
class HisProductRepository extends BaseRepository
{
    const MODEL = HisProduct::class;

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
