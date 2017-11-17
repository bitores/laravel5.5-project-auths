<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\UModel;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class UModelRepository extends BaseRepository
{
    const MODEL = UModel::class;

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


    // public function findDataById($userid)
    // {
    //     return $this->query()->where('user_id', $userid)->get();
    // }

    // 获取 指定用户 上传的 所有模型
    public function getAllByUserId($userid)
    {
        return $this->query()->where('user_id', $userid)->get();
    }
}
