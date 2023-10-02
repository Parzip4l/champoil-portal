<?php

namespace App\Http\Controllers\Warehousestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RmaM;
use App\Slack;

class RmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $purchase = new RmaM(); // Generate UUID
            $purchase->oli_bahan = $request->oli_bahan;
            $purchase->oli_service = $request->oli_service;
            $purchase->oli_trafo = $request->oli_trafo;
            $purchase->lemak = $request->lemak1;
            $purchase->wandes = $request->wandes1;
            $purchase->pfad = $request->pfad1;
            $purchase->kapur = $request->kapur;
            $purchase->latex = $request->latex;
            $purchase->minarex = $request->minarex;
            $purchase->s_merah = $request->s_merah;
            $purchase->s_biru = $request->s_biru;
            $purchase->s_kuning = $request->s_kuning;
            $purchase->s_hijau = $request->s_hijau;
            $purchase->s_kuhl = $request->s_kuhl;
            $purchase->tackifier_22 = $request->tackifier_22;
            $purchase->tackifier_champ = $request->tackifier_champ;
            $purchase->natrium_bicarbonat = $request->natrium_bicarbonat;
            $purchase->soda_ash = $request->soda_ash;
            $purchase->save();

            $slackChannel = Slack::where('channel', 'Warehouse')->first();
            $slackWebhookUrl = $slackChannel->url;

            $today = now()->toDateString();
            $data = [
                'text' => "Update Raw Material Stock {$today}",
                'attachments' => [
                    [
                        'title' => 'Data Raw Materials',
                        'fields' => [
                            [
                                'title' => 'Oli Bahan',
                                'value' => $request->oli_bahan . ' Drum',
                                'short' => true,
                            ],
                            [
                                'title' => 'Oli Service',
                                'value' => $request->oli_service  . ' Drum',
                                'short' => true,
                            ],
                            [
                                'title' => 'Oli Trafo',
                                'value' => $request->oli_trafo . ' Drum',
                                'short' => true,
                            ],
                            [
                                'title' => 'Lemak',
                                'value' => $request->lemak1 . ' Drum',
                                'short' => true,
                            ],
                            [
                                'title' => 'Wandes',
                                'value' => $request->wandes1 . ' Drum',
                                'short' => true,
                            ],
                            [
                                'title' => 'PFAD',
                                'value' => $request->pfad1 . ' Drum',
                                'short' => true,
                            ],
                            [
                                'title' => 'Kapur',
                                'value' => $request->kapur . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Latex',
                                'value' => $request->latex . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Minarex',
                                'value' => $request->minarex . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Sepuhan Merah',
                                'value' => $request->s_merah . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Sepuhan Biru',
                                'value' => $request->s_biru . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Sepuhan Kuning',
                                'value' => $request->s_kuning . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Sepuhan Hijau',
                                'value' => $request->s_hijau . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Sepuhan KUHL',
                                'value' => $request->s_kuhl . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Tackifier 2022',
                                'value' => $request->tackifier_22 . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Tackifier Champ',
                                'value' => $request->tackifier_champ . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Soda Ash',
                                'value' => $request->soda_ash . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Natrium Bicarbonat',
                                'value' => $request->natrium_bicarbonat . ' Kg',
                                'short' => true,
                            ],
                            [
                                'title' => 'Lihat Detail Data Di Champoil Portal',
                                'value' => '(https://portal.champoil.co.id/warehouse-stock)',
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
    
            return redirect()->route('warehouse-stock.index')->with('success', 'Data berhasil disimpan.');
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
