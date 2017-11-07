<?php

namespace App\Repositories\Frontend\Access\User;

use App\Models\Access\User\Brand;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class BrandRepository extends BaseRepository
{
    const MODEL = Brand::class;

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function create(array $data)
    {
        $brand = self::MODEL;

        $brand = new $brand;

        $brand->name = $data['name'];
        $brand->user_id = $data['user_id'];
        $brand->save();

        return $brand;
    }


    public function findDataById($userid)
    {
        return $this->query()->where('user_id', $userid)->get();
    }
}
