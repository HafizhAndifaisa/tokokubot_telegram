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

class TambahBarangBaru extends Conversation
{
    protected $arraydata=[];

    public function run()
    {
        $this->createbarang();
    }

    public function createbarang()
    {
        $this->ask('Masukkan Kode Barang, Nama Barang, Harga Beli Satuan, Stok Minimum, dan Satuan Barang. 
        Untuk Satuan Masukkan Dengan Kode Sebagai Berikut. Untuk Satuan Buah Masukkan Angka 1 dan Satuan Meter Masukkan Angka 2. 
        Masukkan Data Dengan Format kodebarang,namabarang,hargabelisatuan,stokminimum,satuandenganangka
        Dengan Catatan Masukkan Data Dengan Dipisah Tanda Koma (,).', function(Answer $answer){
            $data=explode(",",$answer);
            $this->arraydata=$data;
            $this->say('Kode Barang : '.$data[0]);
            $this->say('Nama Barang : '.$data[1]);
            $this->say('Harga Beli Satuan Barang : '.$data[2]);
            $this->say('Stok Minimum Barang : '.$data[3]);
            if ($data[4]=='1') {
                $this->say('Satuan : Buah');
            } else {
                $this->say('Satuan : Meter');
            }
            $question=Question::create("Apakah Data Sudah Benar?")
            ->addButtons([
                Button::create('Benar')->value('benar'),
                Button::create('Salah')->value('salah'),
            ]);
            
            $this->ask($question,function(Answer $answer){
                if($answer->isInteractiveMessageReply()){
                    if ($answer->getValue()==='benar') {
                        $this->insertdata();
                    } elseif ($answer->getValue() === 'salah') {
                        $this->say('Ulangi Masukkan Data');
                        $this->createbarang();
                    }
                }
            });            
        });
    }

    public function insertdata()
    {          
        $save = Product::create([
            'name'=>$this->arraydata[1],
            'code'=>$this->arraydata[0],
            'measure_id'=>$this->arraydata[4],            
            'price'=>$this->arraydata[2],            
            'warn_stock'=>$this->arraydata[3]
        ]);
        $save->save();
        $this->say('Telah Disimpan');
    }
}
