<?php

namespace App\Repositories\Frontend\Access\User;

use App\Models\Access\User\CategoryB;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class CategoryBRepository extends BaseRepository
{
    const MODEL = CategoryB::class;

    public function create(array $data)
    {
        $brand = self::MODEL;


        $brand = new $brand;

        $brand->name = $data['name'];
        $brand->category_a_id = $data['category_a_id'];
        $brand->save();

        return $brand;
    }

    public function findAllData()
    {
        return $this->query()->get();
    }
}
