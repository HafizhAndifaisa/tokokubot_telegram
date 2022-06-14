<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;
use App\Conversations\CekDataBarang;
use App\Conversations\TambahBarangBaru;
use App\Conversations\CekLaporanStok;
use App\Conversations\CetakLaporanTelegram;
use App\Conversations\CatatTransaksi;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }

    // botman app toko
    
    public function bacadatabarang(BotMan $bot)
    {
        $bot->startConversation(new CekDataBarang());
    }

    public function tambahbarang(BotMan $bot)
    {
        $bot->startConversation(new TambahBarangBaru());
    }

    public function ceklaporanstok(BotMan $bot)
    {
        $bot->startConversation(new CekLaporanStok());
    }

    public function cetaklaporan(BotMan $bot)
    {
        $bot->startConversation(new CetakLaporanTelegram());
    }

    public function catattransaksi(BotMan $bot)
    {
        $bot->startConversation(new CatatTransaksi());
    }
}
