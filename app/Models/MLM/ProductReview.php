<?php

namespace App\Models\MLM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class ProductReview extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_review';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type','comments','product_id'];
}
