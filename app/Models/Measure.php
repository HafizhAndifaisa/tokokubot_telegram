<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measure extends Model 
{

    protected $table = 'measure';
    public $timestamps = true;
    protected $fillable = array('name');

}