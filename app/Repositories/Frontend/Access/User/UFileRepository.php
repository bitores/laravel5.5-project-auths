<?php

namespace App\Repositories\Frontend\Access\User;

use App\Models\Access\User\UFile;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class UFileRepository extends BaseRepository
{
    const MODEL = UFile::class;

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
