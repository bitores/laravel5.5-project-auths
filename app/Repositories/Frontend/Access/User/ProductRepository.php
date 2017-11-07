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
    public function create(array $data)
    {
        $product = self::MODEL;

        $product = new $product;

        // $product->name = $data['name'];
        $product->user_id = $data['user_id'];
        $product->save();

        return $product;
    }
}
