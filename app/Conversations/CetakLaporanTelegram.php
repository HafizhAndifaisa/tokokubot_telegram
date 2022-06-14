<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

use BotMan\BotMan\Messages\Attachments\File;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Measure;
use App\Models\Transaction;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
// use Illuminate\Support\Facades\DB;
use App\Http\Requests;
// use App\Models\Transaction;
// use App\Models\Product;

class CetakLaporanTelegram extends Conversation
{

    public $bulaninput;
    public $tahuninput;
    public $bulan=[
        'Januari'=>'01',
        'Februari'=>'02',
        'Maret'=>'03',
        'April'=>'04',
        'Mei'=>'05',
        'Juni'=>'06',
        'Juli' => '07',
        'Agustus' => '08',
        'September' => '09',
        'Oktober' => '10',
        'November' => '11',
        'Desember' => '12'
    ];
    public $laporannama;

    public function run()
    {
        $this->inputbulantahun();
    }

    public function inputbulantahun()
    {
        $this->ask('Masukkan Bulan',function(Answer $answer)
        {
            $this->bulaninput=$answer;
            // $this->say('Bulan : '.$answer);
            $this->ask('Masukkan Tahun',function(Answer $answer){
                $this->tahuninput=$answer;
                // $this->say('Tahun : '.$answer);
                $this->konfirmasi();
            });
        });
    }

    public function konfirmasi()
    {
        $this->say('Bulan : '.$this->bulaninput);
        $this->say('Tahun : '.$this->tahuninput);
        $question=Question::create("Apakah Data Sudah Benar?")
        ->addButtons([
            Button::create('Benar')->value('benar'),
            Button::create('Salah')->value('salah'),
        ]);
        $this->ask($question,function(Answer $answer){
            if($answer->isInteractiveMessageReply()){
                if ($answer->getValue()==='benar') {
                    $this->exportdata();
                } elseif ($answer->getValue() === 'salah') {
                    $this->say('Ulangi Masukkan Bulan dan Tahun');
                    $this->inputbulantahun();
                }
            }
        });
    }

    public function exportdata()
    {
        $bln=$this->bulaninput;
        $month=$this->bulan["$bln"];
        $year=$this->tahuninput;
        // $this->say('Bulan : '.$month);
        // $this->say('Tahun : '.$year);

        $datapenjualan=DB::table('transaction')
        ->join('product','transaction.product_id','=','product.id')
        ->selectRaw('product.code,product.name,transaction.price,transaction.type,SUM(transaction.qty) as sum')
        ->groupBy('transaction.price','product.code','transaction.type','product.name')
        ->where('type','S')
        ->whereMonth('date','=',$month)
        ->whereYear('date','=',$year)
        ->get()->toArray();

        $datapembelian=DB::table('transaction')
        ->join('product','transaction.product_id','=','product.id')
        ->selectRaw('product.code,product.name,transaction.price,transaction.type,SUM(transaction.qty) as sum')
        ->groupBy('transaction.price','product.code','transaction.type','product.name')
        ->where('type','B')
        ->whereMonth('date','=',$month)
        ->whereYear('date','=',$year)
        ->get()->toArray();

        $datastoklaporan=DB::table('transaction')
        ->join('product','transaction.product_id','=','product.id')
        ->selectRaw('product.code,product.name,SUM(transaction.qty) as sum, product.warn_stock')
        ->groupBy('product.code','product.name','product.warn_stock')
        ->where('transaction.type','!=','SO')
        ->whereMonth('transaction.date','<=',$month)
        ->whereYear('transaction.date','<=',$year)
        ->get()->toArray();

        $datastokopname=DB::table('transaction')
        ->join('product','transaction.product_id','=','product.id')
        ->selectRaw('product.code,product.name,SUM(transaction.qty) as sum, product.warn_stock')
        ->groupBy('product.code','product.name','product.warn_stock')
        ->where('type','SO')
        ->whereMonth('transaction.date','<=',$month)
        ->whereYear('transaction.date','<=',$year)
        ->get()->toArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        foreach(range('A','V') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->getStyle("A1")->getFont()->setSize(16);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('A1', 'Laporan Keuangan Bulanan Toko Noor Electric')
              ->setCellValue('A3', 'Bulan : ')
              ->setCellValue('B3', $bln)
              ->setCellValue('A4', 'Tahun : ')
              ->setCellValue('B4', $year);

        $sheet->mergeCells('A7:E7');
        $sheet->getStyle('A7')->getFont()->setSize(14);
        $sheet->getStyle('A7')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A7', 'Data Penjualan Barang')
              ->setCellValue('A8', 'Kode Barang')
              ->setCellValue('B8', 'Nama Barang')
              ->setCellValue('C8', 'Harga Penjualan')
              ->setCellValue('D8', 'Jumlah Terjual')
              ->setCellValue('E8', 'Total Pemasukan');
        $baris=9;
        $jumlahpenjualan=0;
        foreach ($datapenjualan as $key => $value) {    
            $sheet
            ->setCellValue('A'.strval($baris),$value->code)
            ->setCellValue('B'.strval($baris),$value->name)
            ->setCellValue('C'.strval($baris),$value->price)
            ->setCellValue('D'.strval($baris),($value->sum)*-1)
            ->setCellValue('E'.strval($baris),($value->price*$value->sum)*-1);
            $baris++;
            $jumlahpenjualan+=(($value->price*$value->sum)*-1);
        }
        $sheet
        ->setCellValue('D'.strval($baris),'Total Penjualan')
        ->setCellValue('E'.strval($baris),$jumlahpenjualan);
        $baris+=3;
        
        $sheet->mergeCells('A'.strval($baris).':E'.strval($baris));
        $sheet->getStyle('A'.strval($baris))->getFont()->setSize(14);
        $sheet->getStyle('A'.strval($baris))->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A'.strval($baris), 'Data Pembelian Barang')
              ->setCellValue('A'.strval($baris), 'Kode Barang')
              ->setCellValue('B'.strval($baris), 'Nama Barang')
              ->setCellValue('C'.strval($baris), 'Harga Pembelian')
              ->setCellValue('D'.strval($baris), 'Jumlah Terbeli')
              ->setCellValue('E'.strval($baris), 'Total Pengeluaran');
        $baris++;
        $jumlahpembelian=0;
        foreach ($datapembelian as $key => $value) {
            $sheet
            ->setCellValue('A'.strval($baris),$value->code)
            ->setCellValue('B'.strval($baris),$value->name)
            ->setCellValue('C'.strval($baris),$value->price)
            ->setCellValue('D'.strval($baris),$value->sum)
            ->setCellValue('E'.strval($baris),$value->price*$value->sum);
            $baris++;
            $jumlahpembelian+=($value->price*$value->sum);
        }
        $sheet
        ->setCellValue('D'.strval($baris),'Total Pembelian')
        ->setCellValue('E'.strval($baris),$jumlahpembelian);
        $baris+=3;

        $sheet->mergeCells('A'.strval($baris).':E'.strval($baris));
        $sheet->getStyle('A'.strval($baris))->getFont()->setSize(14);
        $sheet->getStyle('A'.strval($baris))->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A'.strval($baris), 'Jumlah Stok Tersedia Bulan Ini')
              ->setCellValue('A'.strval($baris), 'Kode Barang')
              ->setCellValue('B'.strval($baris), 'Nama Barang')
              ->setCellValue('C'.strval($baris), 'Jumlah Stok Bulan Ini')
              ->setCellValue('D'.strval($baris), 'Minimum Stok')
              ->setCellValue('E'.strval($baris), 'Status');
        $baris++;
        foreach ($datastokopname as $key => $value) {
            $stokjualan=0;
            $sheet
            ->setCellValue('A'.strval($baris),$value->code)
            ->setCellValue('B'.strval($baris),$value->name);
            foreach ($datastoklaporan as $arrayso) {
                if ($arrayso->code==$value->code) {
                    $stokjualan=$arrayso->sum;
                }
            }
            $sheet
            ->setCellValue('C'.strval($baris),($value->sum)+$stokjualan)
            ->setCellValue('D'.strval($baris),$value->warn_stock);
            if ((($value->sum)+$stokjualan)>$value->warn_stock) {
                $sheet->setCellValue('E'.strval($baris),'Stok Aman');
            } else {
                $sheet->setCellValue('E'.strval($baris),'Stok Tidak Aman');
            }
            $baris++;
        }
        $baris+=3;

        $sheet->mergeCells('A'.strval($baris).':B'.strval($baris));
        $sheet->getStyle('A'.strval($baris))->getFont()->setSize(14);
        $sheet->getStyle('A'.strval($baris))->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A'.strval($baris), 'Rangkuman Total')
              ->setCellValue('A'.strval($baris+1), 'Jumlah Penjualan')
              ->setCellValue('A'.strval($baris+2), 'Jumlah Pembelian')
              ->setCellValue('A'.strval($baris+3), 'Selisih');
        $sheet
        ->setCellValue('B'.strval($baris+1),$jumlahpenjualan)
        ->setCellValue('B'.strval($baris+2),$jumlahpembelian)
        ->setCellValue('B'.strval($baris+3),($jumlahpenjualan-$jumlahpembelian));

        $filenamelaporan="Laporan Toko Bulan $bln Tahun $year.pdf";
        // $writer = new Xlsx($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filenamelaporan");
        $writer->save('./'.$filenamelaporan);
        $this->laporannama=$filenamelaporan;

        // $this->say('Silahkan masukkan perintah /ambillaporan untuk mengunduh laporan.');

    }
}
