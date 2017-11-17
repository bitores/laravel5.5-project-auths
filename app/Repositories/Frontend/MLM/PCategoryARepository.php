<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\PCategoryA;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class PCategoryARepository extends BaseRepository
{
    const MODEL = PCategoryA::class;

    public function create(array $data)
    {
        $model = self::MODEL;


        $instance = new $model;

        $instance->name = $data['name'];
        $instance->save();

        return $instance;
    }


    // getAll
    // public function findAllData()
    // {
    //     return $this->query()->get();
    // }
}
