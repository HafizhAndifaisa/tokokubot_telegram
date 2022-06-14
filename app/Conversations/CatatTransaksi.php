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

class CatatTransaksi extends Conversation
{
    protected $arraydata=[];
    protected $datenow;
    protected $typetransaction;
    protected $idproduct;

    public function run()
    {
        $this->datatransaksi();
    }

    public function datatransaksi()
    {
        $questiontransaction=Question::create('Pilih Jenis Transaksi : (Beli/Jual)')
        ->addButtons([
            Button::create('Jual')->value('jual'),
            Button::create('Beli')->value('beli')
        ]);
        $this->ask($questiontransaction,function(Answer $answer){
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue()==='jual') {
                    $this->transaksijual();
                } elseif ($answer->getValue()==='beli'){
                    $this->transaksibeli();
                }
            }
        }); 
    }

    public function transaksijual()
    {
        $tglsekarang=date('Y-m-d');  
        $this->datenow=$tglsekarang;
        $this->ask('Masukkan data transaksi dengan format
        kodebarang,harga,jumlahbarang (dipisahkan dengan koma).',function(Answer $answer){
            $dataarray=explode(",",$answer);
            $data['parse'] = Product::orderBy('name')->where('code',$dataarray[0])->get();                
            $dataproduk=$data['parse']->toArray();
            $this->idproduct=$dataproduk[0]['id'];
            $this->typetransaction='S';
            $this->arraydata=$dataarray;

            $this->say('Transaksi tanggal : '.$this->datenow);
            $this->say('Kode Barang : '.$dataarray[0]);
            $this->say('Nama Barang : '.$dataproduk[0]['name']);
            $this->say('Harga Barang : '.$dataarray[1]);
            $this->say('Jumlah Barang : '.$dataarray[2]);
            
            $question=Question::create("Apakah Data Sudah Benar?")
            ->addButtons([
                Button::create('Benar')->value('benar'),
                Button::create('Salah')->value('salah'),
            ]);
            $this->ask($question,function(Answer $answer){
                if($answer->isInteractiveMessageReply()){
                    if ($answer->getValue()==='benar') {
                        $this->savedatajual();
                    } elseif ($answer->getValue() === 'salah') {
                        $this->say('Ulangi Masukkan Data');
                        $this->datatransaksi();
                    }
                }
            });            
        }); 
        
    }
    public function savedatajual()
    {
        $save = Transaction::create([
            'product_id'=>$this->idproduct,
            'date'=>$this->datenow,
            'price'=>$this->arraydata[1],
            'qty'=>($this->arraydata[2])*-1,            
            'type'=>$this->typetransaction
        ]);
        $save->save();
        $this->say('Telah Disimpan');
    } 


    public function transaksibeli()
    {
        $tglsekarang=date('Y-m-d');  
        $this->datenow=$tglsekarang;
        $this->ask('Masukkan data transaksi dengan format
        kodebarang,harga,jumlahbarang (dipisahkan dengan koma).',function(Answer $answer){
            $dataarray=explode(",",$answer);
            $data['parse'] = Product::orderBy('name')->where('code',$dataarray[0])->get();                
            $dataproduk=$data['parse']->toArray();
            $this->idproduct=$dataproduk[0]['id'];
            $this->typetransaction='B';
            $this->arraydata=$dataarray;

            $this->say('Transaksi tanggal : '.$this->datenow);
            $this->say('Kode Barang : '.$dataarray[0]);
            $this->say('Nama Barang : '.$dataproduk[0]['name']);
            $this->say('Harga Barang : '.$dataarray[1]);
            $this->say('Jumlah Barang : '.$dataarray[2]);
            
            $question=Question::create("Apakah Data Sudah Benar?")
            ->addButtons([
                Button::create('Benar')->value('benar'),
                Button::create('Salah')->value('salah'),
            ]);
            $this->ask($question,function(Answer $answer){
                if($answer->isInteractiveMessageReply()){
                    if ($answer->getValue()==='benar') {
                        $this->savedatabeli();
                    } elseif ($answer->getValue() === 'salah') {
                        $this->say('Ulangi Masukkan Data');
                        $this->datatransaksi();
                    }
                }
            });            
        });         
    }
    public function savedatabeli()
    {
        $save = Transaction::create([
            'product_id'=>$this->idproduct,
            'date'=>$this->datenow,
            'price'=>$this->arraydata[1],
            'qty'=>$this->arraydata[2],            
            'type'=>$this->typetransaction
        ]);
        $save->save();
        $this->say('Telah Disimpan');
    } 
}
