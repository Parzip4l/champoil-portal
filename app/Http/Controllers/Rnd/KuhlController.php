<?php

namespace App\Http\Controllers\Rnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kuhl;
use App\Slack;

class KuhlController extends Controller
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
            $purchase = new Kuhl(); // Generate UUID
            $purchase->batch = $request->batch;
            $purchase->ph = $request->ph;
            $purchase->keterangan = $request->keterangan;
            $purchase->checker = $request->checker;
            $purchase->save();

            $slackChannel = Slack::where('channel', 'QC')->first();
            $slackWebhookUrl = $slackChannel->url;
            $today = now()->toDateString();
            $data = [
                'text' => "Update Penetrasi Produksi {$today}",
                'attachments' => [
                    [
                        'title' => 'Data Check Penetrasi',
                        'fields' => [
                            [
                                'title' => 'Batch Number',
                                'value' => $request->batch,
                                'short' => true,
                            ],
                            [
                                'title' => 'Ph',
                                'value' => $request->ph,
                                'short' => true,
                            ],
                            [
                                'title' => 'Keterangan',
                                'value' => $request->keterangan,
                                'short' => true,
                            ],
                            [
                                'title' => 'Checker',
                                'value' => $request->checker,
                                'short' => true,
                            ],
                            [
                                'title' => 'Lihat Detail Data Di Champoil Portal',
                                'value' => '(https://portal.champoil.co.id/rnd-check)',
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
    
            return redirect()->route('rnd-check.index')->with('success', 'Data berhasil disimpan.');
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
        $request->validate([
            'batch' => 'required|string|max:255',
        ]);

        // Find the kuhl by ID
        $kuhldata = Kuhl::findOrFail($id);

        // Update the kuhl data
        $kuhldata->batch = $request->input('batch');
        $kuhldata->ph = $request->input('ph');
        $kuhldata->keterangan = $request->input('keterangan');
        $kuhldata->checker = $request->input('checker');

        // Save the updated kuhl
        $kuhldata->save();

        // Redirect to a view or return a response as needed
        return redirect()->route('rnd-check.index')->with('success', 'Kuhl Penetration data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kuhl = Kuhl::find($id);
        $kuhl->delete();
        return redirect()->route('rnd-check.index')->with('success', 'Data Successfully Deleted');
    }
}
