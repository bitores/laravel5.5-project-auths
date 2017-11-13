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
}
