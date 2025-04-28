<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Slack;

class SendSlackAttendanceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Memulai proses pengecekan kehadiran.');

        $today = now()->toDateString();

        // Ambil daftar karyawan yang tidak hadir
        $dailyreportAbsen = DB::table('karyawan')
            ->leftJoin('absens', function ($join) use ($today) {
                $join->on('karyawan.nik', '=', 'absens.nik')
                    ->whereDate('absens.tanggal', $today);
            })
            ->where('unit_bisnis', 'CHAMPOIL')
            ->where('organisasi', 'Frontline Officer')
            ->where('resign_status', 0)
            ->whereNull('absens.nik')
            ->select('karyawan.nama', 'karyawan.slack_id')
            ->get();

        // Ambil URL Webhook Slack dari database
        $slackChannel = Slack::where('channel', 'Qc Absen')->first();
        if (!$slackChannel) {
            Log::error('Gagal mengambil webhook Slack. Channel tidak ditemukan.');
            return;
        }

        $slackWebhookUrl = $slackChannel->url;

        if ($dailyreportAbsen->isEmpty()) {
            Log::info('Semua karyawan hadir hari ini.');
            $message = "*Semua karyawan hadir hari ini. Tidak ada yang absen.*";
        } else {
            // Membuat pesan daftar karyawan yang tidak hadir
            $message = "*Berikut adalah daftar karyawan yang belum melakukan absensi hari ini:*\n";
            foreach ($dailyreportAbsen as $index => $karyawan) {
                $mention = $karyawan->slack_id ? "<@{$karyawan->slack_id}>" : "(Tidak ada Slack ID)";
                $message .= ($index + 1) . ". {$karyawan->nama} $mention\n";
            }
            Log::info("Pesan Slack yang akan dikirim:\n" . $message);
        }

        // Kirim pesan ke Slack
        $data = ['text' => $message];
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
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($result === false) {
            $error = curl_error($ch);
            Log::error("Gagal mengirim laporan ke Slack. Error: {$error}");
        } elseif ($httpCode !== 200) {
            Log::error("Slack mengembalikan kode status {$httpCode}. Response: {$result}");
        }

        curl_close($ch);

        Log::info('Laporan telah dikirim ke Slack.');
        return 'Laporan telah dikirim ke Slack.';
    }

}
