<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\ProductsView;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class ProductsViewRepository extends BaseRepository
{
    const MODEL = ProductsView::class;

    public function findAll()
    {
        return $this->query()->where('user_id', access()->id())->get();
    }

    public function findDataById($id)
    {   

        return $this->query()->where('user_id', access()->id())->where('id',$id)->first();
    }

    // 获取 指定需求方 所有产品
    public function getAllByUserId($userid)
    {
    	return $this->query()->where('user_id', $userid)->get();
    }

    // 获取 指定需求方 指定产品 的信息
    public function findByUserIdAndProductId($userid, $productid)
    {
    	return $this->query()->where('user_id', $userid)->where('id',$productid)->first();
    }
}
