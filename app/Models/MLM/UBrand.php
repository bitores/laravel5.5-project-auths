<?php

namespace App\Models\MLM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class UBrand extends Model
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
