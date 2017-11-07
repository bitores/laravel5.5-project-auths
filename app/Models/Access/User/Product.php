<?php

namespace App\Models\Access\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class Product extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_no'];
}
