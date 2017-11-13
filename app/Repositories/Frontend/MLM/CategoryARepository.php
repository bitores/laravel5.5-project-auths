<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\CategoryA;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class CategoryARepository extends BaseRepository
{
    const MODEL = CategoryA::class;

    public function create(array $data)
    {
        $brand = self::MODEL;


        $brand = new $brand;

        $brand->name = $data['name'];
        $brand->save();

        return $brand;
    }


    public function findAllData()
    {
        return $this->query()->get();
    }
}
