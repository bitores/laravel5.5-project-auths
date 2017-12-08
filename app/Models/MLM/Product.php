<?php

namespace App\Models\MLM;

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
    protected $fillable = ['product_no','brand_id','style_id','a_id','b_id','cad_id','file_id','status_no','model_id','fee','introduction','cover_path'];
}
