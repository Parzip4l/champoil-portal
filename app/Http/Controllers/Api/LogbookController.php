<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ModelCG\Logbook\Barang;
use App\ModelCG\Logbook\Tamu;
use App\ModelCG\Logbook\Project;
use App\ModelCG\Logbook\Pos;
use App\ModelCG\Logbook\Pengiriman;
use App\ModelCG\Logbook\Tipebarang;


class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $get_id = Project::where('truest_code', $request->input('project_id'))->first();
        if (!$get_id) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $tamu = Tamu::where('project_id', $get_id->id)
            ->orderBy('id', 'desc') // Order by id descending
            ->paginate($request->get('per_page', 10)); // Default 10 items per page
        return response()->json($tamu);
    }

    public function tamuDetail($id)
    {
        $get_id = Project::where('truest_code', request()->input('project_id'))->first();
        if (!$get_id) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $tamu = Tamu::where('project_id', $get_id->id)->find($id);
        if (!$tamu) {
            return response()->json(['error' => 'Tamu not found'], 404);
        }

        return response()->json($tamu);
    }

    public function barang(Request $request)
    {
        $get_id = Project::where('truest_code', $request->input('project_id'))->first();
        if (!$get_id) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $barang = Barang::where('project_id', $get_id->id)
            ->orderBy('id', 'desc') // Order by id descending
            ->paginate($request->get('per_page', 10)); // Default 10 items per page

        if ($barang->items()) {
            foreach ($barang->items() as $row) {
                $row->tipe = Tipebarang::find($row->tipe_barang)->nama;
                $row->pengiriman = Pengiriman::find($row->jenis_paket)->nama;
                $row->post = Pos::find($row->status)->nama;
                $row->gambar = asset('storage/' . $row->foto_barang);
            }
        }

        return response()->json($barang);
    }

    public function barangDetail($id)
    {
        $get_id = Project::where('truest_code', request()->input('project_id'))->first();
        if (!$get_id) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json(['error' => 'Barang not found'], 404);
        }

        $barang->tipe = Tipebarang::find($barang->tipe_barang)->nama;
        $barang->pengiriman = Pengiriman::find($barang->jenis_paket)->nama;
        $barang->post = Pos::find($barang->status)->nama;
        $barang->gambar = asset('storage/' . $barang->foto_barang);

        return response()->json($barang);
    }
}
