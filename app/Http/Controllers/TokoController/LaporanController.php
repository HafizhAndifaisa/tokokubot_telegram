<?php

namespace App\Http\Controllers\TokoController;
// require 'vendor/autoload.php';
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Models\Transaction;
use App\Models\Product;

class LaporanController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->home = route('home');
        $this->current = route('lapIndex');
    }
    public function index()
    {
        $tahun=range(2000,2030);
        return view('tokoku.laporan.index',[
            'bulan'=> $this->bulan,
            'tahun'=> $tahun,
        ]);
    }
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


    public function exportLaporan(Request $request)
    {   
        
        $data=$request->post();
        // print_r($data);
        $year=($data['tahun'])+2000;
        $month=$this->bulan[$data['bulan']];
        // $data['parse'] = 
        // Transaction::orderBy('product_id')
        // ->whereMonth('date','=',$month)
        // ->whereYear('date','=',$year)
        // ->get();                
        // $array=$data['parse']->toArray();

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

        // $bulanaaaa="A";
        // dd($this->bulan[$bulanaaaa]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        foreach(range('A','V') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->getStyle("A1")->getFont()->setSize(16);
        $sheet->mergeCells('A1:V1');
        $sheet->mergeCells('A7:E7');
        $sheet->mergeCells('H7:L7');
        $sheet->mergeCells('O7:S7');
        $sheet->mergeCells('V7:W7');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A7')->getFont()->setSize(14);
        $sheet->getStyle('H7')->getFont()->setSize(14);
        $sheet->getStyle('O7')->getFont()->setSize(14);
        $sheet->getStyle('V7')->getFont()->setSize(14);
        $sheet->getStyle('A7')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('H7')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('O7')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('V7')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A1', 'Laporan Keuangan Bulanan Toko Noor Electric')
              ->setCellValue('A3', 'Bulan : ')
              ->setCellValue('B3', $data['bulan'])
              ->setCellValue('A4', 'Tahun : ')
              ->setCellValue('B4', $year)
              ->setCellValue('A7', 'Data Penjualan Barang')
              ->setCellValue('A8', 'Kode Barang')
              ->setCellValue('B8', 'Nama Barang')
              ->setCellValue('C8', 'Harga Penjualan')
              ->setCellValue('D8', 'Jumlah Terjual')
              ->setCellValue('E8', 'Total Pemasukan')
              ->setCellValue('H7', 'Data Pembelian Barang')
              ->setCellValue('H8', 'Kode Barang')
              ->setCellValue('I8', 'Nama Barang')
              ->setCellValue('J8', 'Harga Pembelian')
              ->setCellValue('K8', 'Jumlah Terbeli')
              ->setCellValue('L8', 'Total Pengeluaran')
              ->setCellValue('O7', 'Jumlah Stok Tersedia Bulan Ini')
              ->setCellValue('O8', 'Kode Barang')
              ->setCellValue('P8', 'Nama Barang')
              ->setCellValue('Q8', 'Jumlah Stok Bulan Ini')
              ->setCellValue('R8', 'Minimum Stok')
              ->setCellValue('S8', 'Status')
              ->setCellValue('V7', 'Rangkuman Total')
              ->setCellValue('V8', 'Jumlah Penjualan')
              ->setCellValue('V9', 'Jumlah Pembelian')
              ->setCellValue('V10', 'Selisih');


        

        //input penjualan 
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

        //input pembelian
        $baris=9;
        $jumlahpembelian=0;
        foreach ($datapembelian as $key => $value) {
            $sheet
            ->setCellValue('H'.strval($baris),$value->code)
            ->setCellValue('I'.strval($baris),$value->name)
            ->setCellValue('J'.strval($baris),$value->price)
            ->setCellValue('K'.strval($baris),$value->sum)
            ->setCellValue('L'.strval($baris),$value->price*$value->sum);
            $baris++;
            $jumlahpembelian+=($value->price*$value->sum);
        }
        $sheet
        ->setCellValue('K'.strval($baris),'Total Pembelian')
        ->setCellValue('L'.strval($baris),$jumlahpembelian);

        //masukkan data stok toko
        $baris=9;
        foreach ($datastokopname as $key => $value) {
            $stokjualan=0;
            $sheet
            ->setCellValue('O'.strval($baris),$value->code)
            ->setCellValue('P'.strval($baris),$value->name);
            foreach ($datastoklaporan as $arrayso) {
                if ($arrayso->code==$value->code) {
                    $stokjualan=$arrayso->sum;
                }
            }
            $sheet
            ->setCellValue('Q'.strval($baris),($value->sum)+$stokjualan)
            ->setCellValue('R'.strval($baris),$value->warn_stock);
            if ((($value->sum)+$stokjualan)>$value->warn_stock) {
                $sheet->setCellValue('S'.strval($baris),'Stok Aman');
            } else {
                $sheet->setCellValue('S'.strval($baris),'Stok Tidak Aman');
            }
            $baris++;
        }

        $sheet
        ->setCellValue('W8',$jumlahpenjualan)
        ->setCellValue('W9',$jumlahpembelian)
        ->setCellValue('W10',($jumlahpenjualan-$jumlahpembelian));

        $bulan=$data['bulan'];
        $filenamelaporan="Laporan Toko Bulan $bulan Tahun $year.xlsx";
        $writer = new Xlsx($spreadsheet);
        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filenamelaporan");
        $writer->save('php://output');

        
    }
}
