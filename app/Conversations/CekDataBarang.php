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

class CekDataBarang extends Conversation
{
    public function run()
    {
        $this->searchbarang();
    }

    public function searchbarang()
    {
        $this->ask('Masukkan Kode Barang dari Barang yang Akan Dicari Barangnya', function(Answer $answer){
            $data['parse'] = Product::orderBy('name')->where('code',$answer)->get();                
            $array=$data['parse']->toArray();
            if (empty($array)) {
                $this->say('Data Empty');
                $this->searchbarang();
            } else {
                $this->printdata($array);
            }           
        });
    }
    public function printdata($array)
    {
        $this->say('Nama Barang : '.$array[0]['name']);
        $this->say('Harga Barang : '.$array[0]['price']);
        $this->say('Stok Minimal Barang : '.$array[0]['warn_stock']);
        $this->say('Kode Barang : '.$array[0]['code']);
    }
}
