<?php

namespace App\Models\MLM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class CategoryA extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories_a';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
