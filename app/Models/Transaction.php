<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use App\Tokoku\Periode;

class Transaction extends Model 
{

    protected $table = 'transaction';
    public $timestamps = true;
    protected $fillable = array('product_id', 'date', 'qty', 'type','price');
    
    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }
    // public function warehouse()
    // {
    //     return $this->belongsTo('App\Tokoku\Warehouse','warehouse_id');
    // }
    public function total_stock()
    {
        // $p = Periode::where('active','Y')->get();
        // if($p->count() == 1){
        //     $pr = $p->first()->id;
        // } else {
        //     $pr = 0;
        // }
        $sql = DB::table('transaction')->select(DB::raw('SUM(qty) as total_stock'))->
        // where('periode_id',$pr)->
        where('product_id',$this->product_id)->first();
        return $sql->total_stock;
    }
    public function total_stock_id($id)
    {
        $sql = DB::table('transaction')->select(DB::raw('SUM(qty) as total_stock'))->
        where('product_id',$id)->first();
        return $sql->total_stock;
        
    }
    public function type_stock($type)
    {
        // $p = Periode::where('active','Y')->get();
        // if($p->count() == 1){
        //     $pr = $p->first()->id;
        // } else {
        //     $pr = 0;
        // }
        $sql = DB::table('transaction')->select(DB::raw('SUM(qty) as total_stock'))->
        // where('periode_id',$pr)->
        where('product_id',$this->product_id)->where('type',$type)->first();
        return $sql->total_stock;
    } 
}