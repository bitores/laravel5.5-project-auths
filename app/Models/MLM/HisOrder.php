<?php

namespace App\Models\MLM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class HisOrder extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'his_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id','user_id'];
}
