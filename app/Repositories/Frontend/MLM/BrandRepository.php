<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\Brand;
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
        $model = self::MODEL;

        $instance = new $model;

        $instance->name = $data['name'];
        $instance->user_id = $data['user_id'];
        $instance->save();

        return $instance;
    }


    public function findDataById($userid)
    {
        return $this->query()->where('user_id', $userid)->get();
    }
}
