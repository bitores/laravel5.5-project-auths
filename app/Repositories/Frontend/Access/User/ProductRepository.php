<?php

namespace App\Repositories\Frontend\Access\User;

use App\Models\Access\User\Product;
use App\Repositories\BaseRepository;

/**
 * Class UserSessionRepository.
 */
class ProductRepository extends BaseRepository
{
    const MODEL = Product::class;

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function create($userid, array $data)
    {
        $product = self::MODEL;

        $product = new $product;

        
        $product->product_no = $data['product_no'];
        $product->style_id = $data['style_id'];
        $product->a_id = $data['a_id'];
        $product->b_id = $data['b_id'];
        $product->brand_id = $data['brand_id'];
        // // $product->cad_id = $data['cad_id'];
        // // $product->file_id = $data['file_id'];
        $product->fee = $data['fee'];
        $product->introduction = $data['introduction'];

        $product->user_id = $userid;
        $product->save();

        return $product;
    }

    public function update($proid, array $data)
    {
        $product = $this->find($proid);

        if($product){
            $product->product_no = $data['product_no'];
            $product->style_id = $data['style_id'];
            $product->a_id = $data['a_id'];
            $product->b_id = $data['b_id'];
            $product->brand_id = $data['brand_id'];
            // $product->cad_id = $data['cad_id'];
            // $product->file_id = $data['file_id'];
            $product->fee = $data['fee'];
            $product->introduction = $data['introduction'];

            $product->save();


        }
        
        return $product;
        
    }
}
