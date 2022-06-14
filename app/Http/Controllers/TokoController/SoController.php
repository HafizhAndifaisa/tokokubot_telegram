<?php

namespace App\Http\Controllers\TokoController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Http\Requests\WebRequest\StoreSoRequest;
use App\Http\Requests\WebRequest\UpdateSoRequest;
use Redirect;

use App\Models\Transaction;
// use App\Tokoku\Periode;
use App\Models\Product;
// use App\Tokoku\Warehouse;

class SoController extends Controller
{
    private $home, $current;

    public function __construct()
    {
        $this->middleware('auth');
        $this->home = route('home');
        $this->current = route('soIndex');
        // $periode = Periode::where('active','Y')->get();
        // if($periode->count() == 1){
        //     $this->periode = $periode->first();
        // } else {
        //     Redirect::to('/periode')->send();;
        // }
    }

    public function index(){
        $data['parse']   = Transaction::where('type','SO')->
        // where('periode_id',$this->periode->id)->
        orderBy('created_at', 'desc')->get();
        // $data['periode'] = $this->periode;
        $no = 1;
        return view('tokoku.stockop.index',compact(
            'data',
            'no'));
    }

    public function create(){
        $data['parse']      = Transaction::where('type','SO')->        get();
        if($data['parse']->count() == 0){
            $data['product'] = Product::get();
        } else {
            foreach($data['parse'] as $id){
                $ids[] = $id->product_id;
            }
            $data['product'] = Product::whereNotIn('id',$ids)->get();
        }
        return view('tokoku.stockop.create',compact('data'));
    }

    public function store(StoreSoRequest $request){
        $item               = new Transaction;
        $item->product_id   = $request->input('product_id');
        $item->date         = Carbon::now()->format('y-m-d');
        $item->price        = 0;
        $item->qty          = $request->input('qty');
        $item->type         = 'SO';
        $item->save();
        
        return redirect(route('soIndex'));
    }

    public function edit($id){
        $data['parse']      = Transaction::find($id);
        if($data == null){
            return redirect($this->current);
        }
        return view('tokoku.stockop.edit',compact('data'));
    }

    public function update(UpdateSoRequest $request, $id){
        $x = Transaction::find($id);
        if($x != null){
            $array_update = [           
                'qty'          => $request->input('qty')
            ];                
            $x->update($array_update);
        }
        return redirect($this->current);
    }

    public function delete(Request $request){
        $id     = $request->input('id');
        $x   = Transaction::find($id);
        if($x != null){
            $x->delete();
        }
        return redirect($this->current)->with('status', 'Berhasil Menghapus ');
    }
}
