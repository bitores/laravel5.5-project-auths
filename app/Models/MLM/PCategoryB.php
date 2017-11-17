<?php

namespace App\Models\MLM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class PCategoryB extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'p_category_b';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
