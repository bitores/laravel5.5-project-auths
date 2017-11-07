<?php

namespace App\Models\Access\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class Brand extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'u_brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
