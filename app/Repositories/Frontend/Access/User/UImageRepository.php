<?php

namespace App\Repositories\Frontend\Access\User;

use App\Models\Access\User\UImage;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class UImageRepository extends BaseRepository
{
    const MODEL = UImage::class;

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function create(array $data)
    {
        $model = self::MODEL;

        $instance = new $model;

        $instance->path = $data['path'];
        $instance->user_id = $data['user_id'];
        $instance->save();

        return $instance;
    }

    public function resetProductId($productid)
    {
        return $this->query()->where('product_id', $productid)->update(['product_id'=>NULL]);
    }

    public function updateProductId(array $images,$productid)
    {
        return $this->query()->whereIn('id', $images)->update(['product_id'=>$productid]);
    }


    public function findDataById($userid)
    {
        return $this->query()->where('user_id', $userid)->get();
    }


    public function findAllDataByProductId($productid)
    {
        return $this->query()->where('product_id',$productid)->get();
    }
}
