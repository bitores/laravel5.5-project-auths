<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\HisOrder;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class HisOrderRepository extends BaseRepository
{
    const MODEL = HisOrder::class;

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
        $instance->action = $data['action'];
        $instance->save();

        return $instance;
    }


    public function accept($userid, $productid)
    {
        // 1、接单
        // 2、取消订单

        return $this->create([ 
            'user_id' => $userid,
            'product_id' => $productid,
            'action' => 1
        ]);
    }

    public function cancel($userid, $productid)
    {
        // 1、接单
        // 2、取消订单

        return $this->create([ 
            'user_id' => $userid,
            'product_id' => $productid,
            'action' => 2
        ]);
    }


    // 获取 指定用户 所有产品
    public function getAllByUserId($userid)
    {
        return $this->query()->where('user_id', $userid)->get();
    }
}
