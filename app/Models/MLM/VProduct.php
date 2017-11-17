<?php

namespace App\Models\MLM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role.
 */
class VProduct extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products_view';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_no','brand_name','style_name','ca_name','cb_name','cad_path','file_path','status_no','model_id','fee','introduction'];
}
