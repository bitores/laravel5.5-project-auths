<?php

namespace App\Models\MLM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class UModel extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'u_models';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path','user_id'];
}
