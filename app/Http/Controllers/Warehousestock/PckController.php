<?php

namespace App\Http\Controllers\Warehousestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FngM;
use App\RmaM;
use App\PckM;
use App\Slack;

class PckController extends Controller
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
            $purchase = new PckM(); // Generate UUID
            $purchase->supreme_15 = $request->supreme_15;
            $purchase->supreme_4 = $request->supreme_4;
            $purchase->optima_15 = $request->optima_15;
            $purchase->super = $request->super;
            $purchase->f300 = $request->f300;
            $purchase->heavy_loader = $request->heavy_loader;
            $purchase->xtreme = $request->xtreme;
            $purchase->power_15 = $request->power_15;
            $purchase->power_10 = $request->power_10;
            $purchase->power_4 = $request->power_4;
            $purchase->wh300 = $request->wh300;
            $purchase->active_10 = $request->active_10;
            $purchase->save();

            $slackChannel = Slack::where('channel', 'Warehouse')->first();
            $slackWebhookUrl = $slackChannel->url;
            $today = now()->toDateString();
            $data = [
                'text' => "Update Packaging Stock {$today}",
                'attachments' => [
                    [
                        'title' => 'Data Packaging',
                        'fields' => [
                            [
                                'title' => 'Supreme 15Kg',
                                'value' => $request->supreme_15 . ' Pail',
                                'short' => true,
                            ],
                            [
                                'title' => 'Supreme 4Kg',
                                'value' => $request->supreme_4 . ' Pail',
                                'short' => true,
                            ],
                            [
                                'title' => 'Optima 15Kg',
                                'value' => $request->optima_15 . ' Pail',
                                'short' => true,
                            ],
                            [
                                'title' => 'Super',
                                'value' => $request->super . ' Pail',
                                'short' => true,
                            ],
                            [
                                'title' => 'F 300',
                                'value' => $request->f300 . ' Pail',
                                'short' => true,
                            ],
                            [
                                'title' => 'Heavy Loader',
                                'value' => $request->heavy_loader . ' Pail',
                                'short' => true,
                            ],
                            [
                                'title' => 'Xtreme',
                                'value' => $request->xtreme . ' Pail',
                                'short' => true,
                            ],
                            [
                                'title' => 'Power 15Kg',
                                'value' => $request->power_15 . ' Pail',
                                'short' => true,
                            ],
                            [
                                'title' => 'Power 10Kg',
                                'value' => $request->power_10 . ' Pail',
                                'short' => true,
                            ],
                            [
                                'title' => 'Power 4Kg',
                                'value' => $request->power_4 . ' Pail',
                                'short' => true,
                            ],
                            [
                                'title' => 'WH 300',
                                'value' => $request->wh300 . ' Pail',
                                'short' => true,
                            ],
                            [
                                'title' => 'Active 10Kg',
                                'value' => $request->active_10 . ' Pail',
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
