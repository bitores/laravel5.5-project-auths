<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\ProductReview;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class ProductReviewRepository extends BaseRepository
{
    const MODEL = ProductReview::class;

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function create(array $data)
    {
        $brand = self::MODEL;

        $instance = new $brand;

        $instance->type = $data['type'];
        $instance->comments = $data['comments'];
        $instance->product_id = $data['product_id'];
        $instance->save();

        return $instance;
    }


    public function findDataById($product_id)
    {
        return $this->query()->where('product_id', $product_id)->orderBy('updated_at','desc')->first();
    }
}
