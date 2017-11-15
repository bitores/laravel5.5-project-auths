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
        return $this->query()->where('user_id', access()->id())->where('status_no', 1001)->leftjoin('product_styles','products.style_id','=','product_styles.id')
            ->select([
                'products.id',
                'products.product_no',
                'products.style_id',
                // 'products.brand_id',
                // 'products.a_id',
                // 'products.b_id',
                'products.images',
                'products.cad_id',
                // 'products.user_id',
                'products.file_id',
                // 'products.model_id',
                // 'products.status_no',
                'products.cycle',
                'products.fee',
                'product_styles.name as style_name'
                // 'products.introduction',
            ])
            ->orderBy('products.updated_at','desc');
    }

    public function getForProducerDataTable()
    {
        return $this->query()->where('user_id', access()->id())->where('status_no', 1005)
        // ->leftjoin('product_styles','products.style_id','=','product_styles.id')
            ->select([
                'products.id',
                'products.product_no',
                'products.style_id',
                // 'products.brand_id',
                // 'products.a_id',
                // 'products.b_id',
                'products.images',
                'products.cad_id',
                // 'products.user_id',
                'products.file_id',
                // 'products.model_id',
                // 'products.status_no',
                'products.cycle',
                'products.fee',
                // 'product_styles.name as style_name'
                // 'products.introduction',
            ])
            ->orderBy('products.updated_at','desc');
    }

    public function getForProducerSelfDataTable()
    {
        return $this->query()->where('user_id', access()->id())->where('status_no', 1006)->where('producer_id', access()->id())
        // ->leftjoin('product_styles','products.style_id','=','product_styles.id')
            ->select([
                'products.id',
                'products.product_no',
                'products.style_id',
                // 'products.brand_id',
                // 'products.a_id',
                // 'products.b_id',
                'products.images',
                'products.cad_id',
                // 'products.user_id',
                'products.file_id',
                // 'products.model_id',
                'products.status_no',
                'products.cycle',
                'products.fee',
                // 'product_styles.name as style_name'
                // 'products.introduction',
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
        $product = self::MODEL;

        $product = new $product;
        $product->user_id = $userid;
        
        $this->save($product, $data, $status);
        return $product;
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

    public function delProduct($proid)
    {
        $product = $this->findDataById($proid);
        if($product) {
            $product->status_no = 1004;
            // $product->deleted_at = time();
            $product->save();
        }
        

        return $product;
    }

    public function order($proid)
    {
        $product = $this->findDataById($proid);
        if($product) {
            $product->status_no = 1006;
            $product->producer_id = access()->id();
            $product->save();
        }
        

        return $product;
    }

    public function cancelorder($proid)
    {
        $product = $this->findOrderDataById($proid);
        if($product) {
            $product->status_no = 1005;
            $product->producer_id = null;
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

    public function findOrderDataById($id)
    {   

        return $this->query()->where('producer_id', access()->id())->where('id',$id)->first();
    }
}
