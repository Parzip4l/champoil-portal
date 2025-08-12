<?php

namespace App\Http\Controllers\Api\CoverMe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CoverMe; // Assuming you have a CoverMe model
use App\Models\CoverComment; // Assuming you have a CoverMeComment model
use App\Models\CoverClaim; // Assuming you have a CoverMeApply model
use App\Models\CategoryRequirements; // Assuming you have a CoverMeResource for API responses

class CovermeController extends Controller
{
    //

    public function index()
    {

        $records = CoverMe::all();
        $fixed_result=[];

        if ($records->isEmpty()) {
            return response()->json([
                "status" => "error",
                "message" => "Tidak ada data perusahaan."
            ], 404);
        }

        foreach ($records as $record) {
            $nama_perusahaan = project_byID($record->id_perusahaan);
            $karyawan = karyawan_bynik($record->nik_cover);
            $requirements = ["GP","BISA MENGEMUDI"];
            $comments=[];
            $cover_comments = CoverComment::where('cover_id', $record->id)->get();
            if($cover_comments->isEmpty()) {
                $comments=[];
            }

            foreach ($cover_comments as $comment) {
                $karyawan_comment = karyawan_bynik($comment->nik);
                $comments[] = [
                    "nik" => $comment->nik,
                    "name" => $karyawan_comment->nama ?? 'Unknown',
                    "profile_url" => "https://example.com/profiles/" . $comment->nik,
                    "comment" => $comment->comment
                ];
            }
            $fixed_result[] = [
                        "id" => $record->id,
                        "nama_perusahaan" => $nama_perusahaan->name,
                        "id_perusahaan" => $record->id_perusahaan,
                        "alamat_perusahaan" => $nama_perusahaan->badan,
                        "description" => '',
                        "shift" => $record->shift,
                        "man_replace" => $karyawan->nama,
                        "man_nik" => $karyawan->nik,
                        "profile_url" => "https://example.com/profiles/PR001",
                        "status" => "Aktif",
                        "sallary" => null,
                        "requirements" => $requirements,
                        "maps" => $nama_perusahaan->latitude.','.$nama_perusahaan->longtitude,
                        "comment" => $comments
            ];
        }

        $result = [
            "status" => "success",
            "message" => "Data perusahaan berhasil diambil.",
            "data" => $fixed_result
        ];

        return response()->json($result);
    }

    public function details($id){
        $record = CoverMe::find($id);

        if (!$record) {
            return response()->json([
                "status" => "error",
                "message" => "Perusahaan tidak ditemukan."
            ], 404);
        }

        $nama_perusahaan = project_byID($record->id_perusahaan);
        $karyawan = karyawan_bynik($record->nik_cover);

        $detail = [
            "id" => $record->id,
            "nama_perusahaan" => $nama_perusahaan->name,
            "id_perusahaan" => $record->id_perusahaan,
            "alamat_perusahaan" => $nama_perusahaan->badan,
            "description" => $record->description ?? '', // Jika field ada
            "shift" => $record->shift,
            "man_replace" => $karyawan->nama ?? null,
            "man_nik" => $karyawan->nik ?? null,
            "profile_url" => "https://example.com/profiles/" . $record->id_perusahaan,
            "status" => "Aktif", // Atau ambil dari field jika tersedia
            "sallary" => $record->sallary ?? null, // Jika field ada
            "requirements" => json_decode($record->requirements, true),
            "maps" => $nama_perusahaan->latitude . ',' . $nama_perusahaan->longtitude,
            "comment" => [
                [
                    "nik" => "320101199001010002",
                    "name" => "Andi Wijaya",
                    "profile_url" => "https://example.com/profiles/320101199001010002",
                    "comment" => "Pelayanan cepat dan ramah."
                ],
                [
                    "nik" => "320101199202020003",
                    "name" => "Rina Putri",
                    "profile_url" => "https://example.com/profiles/320101199202020003",
                    "comment" => "Tempat kerja nyaman."
                ]
            ]
        ];

        return response()->json([
            "status" => "success",
            "message" => "Detail perusahaan berhasil diambil.",
            "data" => $detail
        ]);
    }


    public function apply(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_perusahaan' => 'required|string',
                'nik' => 'required|string',
                'name' => 'required|string'
            ]);

            return response()->json([
                "status" => "success",
                "message" => "Lamaran berhasil diajukan.",
                "data" => $validated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function postComment(Request $request)
    {
        try {
            $validated = $request->validate([
                'nik' => 'required|string',
                'comment' => 'required|string'
            ]);
            $validated['cover_id'] = $request->input('id_cover_me', 0); // Default to 0 if not provided

            
            CoverComment::create($validated);
            return response()->json([
                "status" => "success",
                "message" => "Komentar berhasil ditambahkan.",
                "data" => $validated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nik_cover' => 'required',
                'tanggal' => 'required|date',
                'shift' => 'required|string',
                'alasan' => 'required',
                'requirement' => 'required|array',
            ]);

            $validated['id_perusahaan'] = $request->input('project', 0); // Default to 0 if not provided
            // Encode requirements as JSON
            $validated['requirements'] = json_encode($validated['requirement']);

            // Assuming you have a CoverMe model to handle the data
            $coverMe = CoverMe::create($validated);

            return response()->json([
                "status" => "success",
                "message" => "Data berhasil disimpan.",
                "data" => $coverMe
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function claim(Request $request)
    {
        try {
            $validated = $request->validate([
                'cover_id' => 'required|integer',
                'nik' => 'required|string'
            ]);
            $validated['status'] = 0; // Default status for new claims
            // Assuming you have a CoverClaim model to handle the data
            $coverClaim = CoverClaim::create($validated);

            return response()->json([
                "status" => "success",
                "message" => "Klaim berhasil diajukan.",
                "data" => $coverClaim
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function actionClaim(Request $request)
    {
        try {
            $claim_id = $request->input('claim_id');
            $claim = CoverClaim::findOrFail($claim_id);
            $claim->status = $request->input('status', $claim->status);
            $claim->action_by = $request->input('action_by', 00000); // Default to 00000 if not provided
            $claim->save();

            return response()->json([
                "status" => "success",
                "message" => "Status klaim berhasil diperbarui.",
                "data" => $claim
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function getClaim(Request $request)
    {
        try {
            $claims = CoverClaim::join('cover_mes', 'cover_claims.cover_id', '=', 'cover_mes.id')
                ->select('cover_claims.*', 'cover_mes.id_perusahaan', 'cover_mes.nik_cover', 'cover_mes.shift')
                ->get();

            if ($claims->isEmpty()) {
                return response()->json([
                    "status" => "error",
                    "message" => "Tidak ada data klaim."
                ], 404);
            }

            $fixed_result = [];

            foreach ($claims as $claim) {
                $nama_perusahaan = project_byID($claim->id_perusahaan);
                $karyawan = karyawan_bynik($claim->nik_cover);
                $man_backup = karyawan_bynik($claim->nik);
                $requirements = ["GP", "BISA MENGEMUDI"]; // Bisa dibuat dinamis kalau ada field-nya

                if($claim->status == 0) {
                    $claim->status = "Menunggu Konfirmasi";
                }else if($claim->status == 1) {
                    $claim->status = "Diterima";
                } else if($claim->status == 2) {
                    $claim->status = "Ditolak";
                }
                $fixed_result[] = [
                    "id" => $claim->id,
                    "nama_perusahaan" => $nama_perusahaan->name ?? '-',
                    "shift" => $claim->shift,
                    "man_replace" => $karyawan->nama ?? '-',
                    "man_backup" => $man_backup->nama ?? '-',
                    "status" => $claim->status,
                    
                ];
            }

            return response()->json([
                "status" => "success",
                "message" => "Data klaim berhasil diformat.",
                "data" => $fixed_result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }


    public function getRequirements()
    {
        try {
            $requirements = CategoryRequirements::all();

            return response()->json([
                "status" => "success",
                "message" => "Data persyaratan berhasil diambil.",
                "data" => $requirements
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
