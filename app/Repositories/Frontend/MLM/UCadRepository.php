<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\UCad;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class UCadRepository extends BaseRepository
{
    const MODEL = UCad::class;

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function create(array $data)
    {
        $model = self::MODEL;

        $instance = new $model;

        $instance->path = $data['path'];
        $instance->user_id = $data['user_id'];
        $instance->save();

        return $instance;
    }


    public function findDataById($userid)
    {
        return $this->query()->where('user_id', $userid)->get();
    }
}
