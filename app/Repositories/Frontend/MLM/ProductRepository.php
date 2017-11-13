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
        return $this->query()->where('user_id', access()->id())
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


    /**
     * @param User $user
     *
     * @return mixed
     */
    public function create($userid, array $data)
    {
        $product = self::MODEL;

        $product = new $product;

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
        
        $product->user_id = $userid;
        $product->status_no = 1000;
        $product->save();

        return $product;
    }

    public function update($proid, array $data)
    {
        $product = $this->find($proid);

        if($product){
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
            $product->status_no = 1000;
            $product->save();
        }
        
        return $product;    
    }

    public function findAll()
    {
        return $this->query()->where('user_id', access()->id())->get();
    }

    public function findDataById($id)
    {   

        return $this->query()->where('user_id', access()->id())->where('id',$id)->first();
    }

}
