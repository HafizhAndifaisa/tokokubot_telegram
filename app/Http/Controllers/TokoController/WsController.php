<?php

namespace App\Http\Controllers\TokoController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Redirect;
use Excel;

use App\Models\Transaction;
// use App\Tokoku\Periode;
use App\Models\Product;
// use App\Tokoku\Warehouse;

class WsController extends Controller
{
    private $home, $current;

    public function __construct()
    {
        $this->middleware('auth');
        $this->home = route('home');
        $this->current = route('wsIndex');
        // $periode = Periode::where('active','Y')->get();
        // if($periode->count() == 1){
        //     $this->periode = $periode->first();
        // } else {
        //     Redirect::to('/periode')->send();;
        // }
    }

    public function index(){
        $x = Transaction::
        where('type','SO')->get();
        $data['parse'] = $x->groupBy('product_id');      
        $no = 1;
        return view('tokoku.warnstock.index',compact('data','no'));
    }

}
