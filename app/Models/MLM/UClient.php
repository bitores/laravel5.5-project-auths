<?php

namespace App\Models\MLM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class UClient extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'u_clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','client_id'];
}
