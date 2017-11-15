<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\CategoryB;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class CategoryBRepository extends BaseRepository
{
    const MODEL = CategoryB::class;

    public function create(array $data)
    {
        $model = self::MODEL;


        $instance = new $model;

        $instance->name = $data['name'];
        $instance->category_a_id = $data['category_a_id'];
        $instance->save();

        return $instance;
    }

    public function findAllData()
    {
        return $this->query()->get();
    }
}
