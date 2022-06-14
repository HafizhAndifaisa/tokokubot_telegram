<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Measure;
use App\Models\Transaction;

class CekLaporanStok extends Conversation
{
    public function run()
    {
        $this->cekstokproduk();
    }

    public function cekstokproduk()
    {
        $this->ask('Masukkan kode barang dari barang yang ingin dicek jumlah stoknya.',function (Answer $answer)
        {
            $data['parse'] = Product::orderBy('name')->where('code',$answer)->get();                
            $array=$data['parse']->toArray();
            // $totalstock = new Transaction();
            // $totalstock->total_stock_id($array[0]['id']);
            $sum= Transaction::where('product_id',$array[0]['id'])->sum('qty');
            $this->say('Stok Barang : '.$sum);
            if ($sum>=$array[0]['warn_stock']) {
                $this->say('Status Barang : Aman');
            } else {
                $this->say('Status Barang : Tidak Aman');
            }
        });
    }
}
