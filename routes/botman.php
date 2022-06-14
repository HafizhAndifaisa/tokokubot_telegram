<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Attachments\File;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use App\Conversations\CetakLaporanTelegram;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$botman->hears('Start conversation', BotManController::class.'@startConversation');

$botman->hears('Halo, namaku {name}', function($bot,$name){
    $bot->reply('Hi '.$name.', aku bot ini');
});

$botman->hears('/start', function ($bot) {
    $bot->reply('Hi, Ini adalah bot Manajemen Toko Noor Electric
Panduan
/bacadatabarang = Mencari data barang dengan kode barang
/tambahbarang = Menambahkan barang baru
/ceklaporanstok = Cek stok barang pada toko
/catattransaksi = Mencatat transaksi Toko');
});

$botman->hears('/bacadatabarang', BotManController::class.'@bacadatabarang');
$botman->hears('/tambahbarang', BotManController::class.'@tambahbarang');
$botman->hears('/ceklaporanstok', BotManController::class.'@ceklaporanstok');
$botman->hears('/catattransaksi', BotManController::class.'@catattransaksi');

$botman->hears('/ambillaporan', function($bot){
    // $filenameget = new CetakLaporanTelegram();
    // $name=$filenameget->laporannama;
    // $attachment = new File('./public/'.$name, [
    //     'custom_payload' => true,
    // ]);
    $attachment = new File('./Laporan Toko Bulan Juni Tahun 2021.pdf', [
        'custom_payload' => true,
    ]);
    $message = OutgoingMessage::create('Ini merupakan file nya')
                ->withAttachment($attachment);
    // $bot->reply('Name : '.$name);
    $bot->reply($message);
    // $bot->reply('Terambil');
});

$botman->fallback(function($bot){
    $bot->reply('Maaf, perintah tidak ada, coba masukkan perintah lain');
});