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
        $model = self::MODEL;


        $instance = new $model;

        $instance->name = $data['name'];
        $instance->save();

        return $instance;
    }


    public function findAllData()
    {
        return $this->query()->get();
    }
}
