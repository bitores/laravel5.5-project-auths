<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\PStyle;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class PStyleRepository extends BaseRepository
{
    const MODEL = PStyle::class;

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
        $instance->save();

        return $instance;
    }
}
