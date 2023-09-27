<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\Slack;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.survey.form');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Simpan data pembelian
            $survei = new Survey(); // Generate UUID
            $survei->nama = $request->nama;
            $survei->departement = $request->departement;
            $survei->nilai = $request->nilai;
            $survei->kritiksaran = $request->kritiksaran;
            $survei->save();

            $slackChannel = Slack::where('channel', 'Testing Channel')->first();
            $slackWebhookUrl = $slackChannel->url;
            $today = now()->toDateString();
            $data = [
                'text' => "{$request->nama} Telah Mengisi Survei Pada Tanggal {$today}",
                'attachments' => [
                    [
                        'title' => 'Data Survei',
                        'fields' => [
                            [
                                'title' => 'Nama Lengkap',
                                'value' => $request->nama,
                                'short' => true,
                            ],
                            [
                                'title' => 'Depertemen',
                                'value' => $request->departement,
                                'short' => true,
                            ],
                            [
                                'title' => 'Nilai',
                                'value' => $request->nilai,
                                'short' => true,
                            ],
                            [
                                'title' => 'Kritik Dan Saran',
                                'value' => $request->kritiksaran,
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
    
            return redirect()->route('isi-survei.index')->with('success', 'Terimakasih Telah Mengisi Survei.');
        } catch (\Exception $e) {
            // Tangani kesalahan yang mungkin terjadi
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan Data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
