<?php

namespace App\Models\Access\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class UImage extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'u_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path','user_id','product_id','is_cover'];
}
