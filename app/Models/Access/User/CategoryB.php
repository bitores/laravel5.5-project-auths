<?php

namespace App\Models\Access\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class CategoryB extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories_b';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
