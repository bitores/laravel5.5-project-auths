<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\UFile;
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

    // 获取 指定用户 所有文件
    public function getAllByUserId($userid)
    {
        return $this->query()->where('user_id', $userid)->get();
    }
}
