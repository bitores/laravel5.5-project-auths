<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\UProduct;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class UProductRepository extends BaseRepository
{
    const MODEL = UProduct::class;

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function create(array $data)
    {
        $model = self::MODEL;

        $instance = new $model;

        $instance->product_id = $data['product_id'];
        $instance->user_id = $data['user_id'];
        $instance->save();

        return $instance;
    }


    public function findDataById($userid)
    {
        return $this->query()->where('user_id', $userid)->get();
    }

    // 获取 指定用户 所有产品
    public function getAllByUserId($userid)
    {
        return $this->query()->where('user_id', $userid)->get();
    }
}
