<?php

namespace App\Models\MLM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class PCategoryA extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'p_category_a';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
