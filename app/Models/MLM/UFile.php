<?php

namespace App\Models\MLM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class UFile extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'u_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path','user_id'];
}
