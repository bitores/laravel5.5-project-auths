<?php

namespace App\Models\MLM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class HisReview extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'his_reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type','comments','product_id'];
}
