<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Artikel; 
use App\Slack; 
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SendArtikel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:artikel';
    protected $description = 'Kirim artikel ke Slack berdasarkan tanggal kirim';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::now()->toDateString();

        // Ambil artikel yang memiliki tanggal kirim hari ini
        $artikelHariIni = Artikel::where('tanggal_kirim', $today)->get();

        // Cek apakah ada artikel
        if ($artikelHariIni->isEmpty()) {
            $this->info('Tidak ada artikel untuk dikirim hari ini.');
            return;
        }

        // Loop melalui setiap artikel dan kirim ke Slack
        foreach ($artikelHariIni as $artikel) {
            $slackChannel = Slack::where('channel', 'Management')->first();
            $slackWebhookUrl = $slackChannel->url;

            $data = [
                // Data artikel yang ingin Anda kirim ke Slack
                'text' => "Halo Champions Crew",
                'attachments' => [
                    [
                        'title' => 'Artikel Baru Hari Ini',
                        'fields' => [
                            [
                                'title' => 'Title',
                                'value' => $artikel->title,
                                'short' => true,
                            ],
                            [
                                'title' => 'Original Link',
                                'value' => $artikel->url,
                                'short' => true,
                            ]
                        ],
                    ],
                ],
            ];

            $data_string = json_encode($data);

            $ch = curl_init($slackWebhookUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
            ]);

            $result = curl_exec($ch);

            if ($result === false) {
                // Penanganan kesalahan jika Curl gagal
                $error = curl_error($ch);
                // Handle the error here
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim data ke Slack: ' . $error);
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode !== 200) {
                // Penanganan kesalahan jika Slack merespons selain status 200 OK
                // Handle the error here
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim data ke Slack. Kode status: ' . $httpCode);
            }

            curl_close($ch);
            $response = Http::post($slackWebhookUrl, ['payload' => json_encode($data)]);
        }
    }

}
