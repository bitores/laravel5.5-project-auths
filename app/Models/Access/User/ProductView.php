<?php

namespace App\Models\Access\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class ProductView extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_view';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_no','brand_name','style_name','a_name','b_name','brand_name','cad_path','file_path','status_no','model_id','fee','introduction'];
}
