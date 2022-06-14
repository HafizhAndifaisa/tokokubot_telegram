<?php

namespace App\Http\Controllers\TokoController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Redirect;

use App\Http\Requests\WebRequest\StoreTrRequest;
// use App\Http\Requests\Tokoku\UpdateProductRequest;

use App\Models\Transaction;
// use App\Tokoku\Periode;
use App\Models\Product;
// use App\Tokoku\Warehouse;

class TrController extends Controller
{
    private $home, $current;

    public function __construct()
    {
        $this->middleware('auth');
        $this->home = route('home');
        $this->current = route('trIndex');
        // $periode = Periode::where('active','Y')->get();
        // if($periode->count() == 1){
        //     $this->periode = $periode->first();
        // } else {
        //     Redirect::to('/periode')->send();
        // }
    }

    public function index(){
        $data['parse'] = Transaction::
        where('type','!=','SO')->orderBy('date','desc')->get();
        $no = 1;
        // dd($data);
        return view('tokoku.transaction.index',compact('data','no'));
    }

    public function create(){
        $data['product'] = Transaction::where('type','SO')->get();
        $data['now']     = Carbon::now()->format('Y-m-d');
        return view('tokoku.transaction.create',compact('data'));
    }

    public function store(StoreTrRequest $request){
        $key1 = 0;
        $key2 = 0;
        $key3 = 0;
        $key4 = 0;

        if($request->input('type') == 'S'){
            foreach ($request->input('product_id') as $row){
                
                // $data['parse'] = Product::orderBy('name')->where('code',$answer)->get();                
                // $array=$data['parse']->toArray();
                // $sum= Transaction::where('product_id',$array[0]['id'])->sum('qty');
                
               

                $wh = Transaction::where('product_id',$request->input('product_id.'.$key1++))->
                where('type','SO')->first();
                // dd($wh);
                // if($wh == NULL){
                //     return redirect(route($this->current))->with('error','Ada Kesalahan !, Barang mungkin belum di Stok Opname untuk periode ini');
                // }
                $charges[] = [
                    'date'         => $request->input('date'),
                    'type'         => $request->input('type'),
                    'product_id'   => $request->input('product_id.'.$key2++),
                    'qty'          => $request->input('qty.'.$key3++)*-1,
                    'price'        => $request->input('price.'.$key4++),
                ];
            }
        } elseif($request->input('type') == 'B'){
            foreach ($request->input('product_id') as $row){
                $wh = Transaction::where('product_id',$request->input('product_id.'.$key1++))->
                where('type','SO')->first();
                // if($wh == NULL){
                //     return redirect(route($this->current))->with('error','Ada Kesalahan !, Barang mungkin belum di Stok Opname untuk periode ini');
                // }
                $charges[] = [
                    'date'         => $request->input('date'),
                    'type'         => $request->input('type'),
                    'product_id'   => $request->input('product_id.'.$key2++),
                    'qty'          => $request->input('qty.'.$key3++),
                    'price'        => $request->input('price.'.$key4++),
                ];
            }
        } else {
            return redirect($this->current);
        }
        Transaction::insert($charges);
        return redirect($this->current);
    }

    public function delete(Request $request){
        $id     = $request->input('id');
        $x   = Transaction::find($id);
        if($x != null){
            $x->delete();
        }
        return redirect($this->current)->with('status', 'Berhasil Menghapus Transaksi');
    }
}
