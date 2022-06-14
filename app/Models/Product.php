<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model 
{

    protected $table = 'product';
    public $timestamps = true;
    protected $fillable = array('id','name', 'code', 'measure_id', 'price', 'warn_stock');
    protected $hidden = array('measure_id','created_at','updated_at');

    public function measure()
    {
        return $this->belongsTo('App\Models\Measure','measure_id');
    } 
}