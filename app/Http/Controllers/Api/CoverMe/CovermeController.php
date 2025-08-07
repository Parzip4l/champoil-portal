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
                        "requirements" => json_decode($record->requirements, true),
                        "maps" => $nama_perusahaan->latitude.','.$nama_perusahaan->longtitude,
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
        }


        // $data = [
        //     [
        //         "nama_perusahaan" => "PT Maju Jaya Abadi",
        //         "id_perusahaan" => "PR001",
        //         "alamat_perusahaan" => "Jl. Merdeka No. 12, Jakarta Pusat",
        //         "description" => "Perusahaan manufaktur komponen otomotif.",
        //         "shift" => "Pagi",
        //         "man_replace" => "Budi Santoso",
        //         "man_nik" => "320101198805010001",
        //         "profile_url" => "https://example.com/profiles/PR001",
        //         "status" => "Aktif",
        //         "sallary" => "Rp 5.000.000 - Rp 7.000.000",
        //         "requirements" => [
        //             "Minimal pendidikan D3",
        //             "Pengalaman kerja minimal 2 tahun",
        //             "Mampu bekerja dalam tim"
        //         ],
        //         "maps" => "-6.200000,106.816666",
        //         "comment" => [
        //             [
        //                 "nik" => "320101199001010002",
        //                 "name" => "Andi Wijaya",
        //                 "profile_url" => "https://example.com/profiles/320101199001010002",
        //                 "comment" => "Pelayanan cepat dan ramah."
        //             ],
        //             [
        //                 "nik" => "320101199202020003",
        //                 "name" => "Rina Putri",
        //                 "profile_url" => "https://example.com/profiles/320101199202020003",
        //                 "comment" => "Tempat kerja nyaman."
        //             ]
        //         ]
        //     ]
        // ];

        $result = [
            "status" => "success",
            "message" => "Data perusahaan berhasil diambil.",
            "data" => $fixed_result
        ];

        return response()->json($result);
    }

    public function details($id)
    {
        $data = [
            "PR001" => [
                "nama_perusahaan" => "PT Maju Jaya Abadi",
                "id_perusahaan" => "PR001",
                "alamat_perusahaan" => "Jl. Merdeka No. 12, Jakarta Pusat",
                "description" => "Perusahaan manufaktur komponen otomotif.",
                "shift" => "Pagi",
                "man_replace" => "Budi Santoso",
                "man_nik" => "320101198805010001",
                "profile_url" => "https://example.com/profiles/PR001",
                "status" => "Aktif",
                "sallary" => "Rp 5.000.000 - Rp 7.000.000",
                "requirements" => [
                    "Minimal pendidikan D3",
                    "Pengalaman kerja minimal 2 tahun",
                    "Mampu bekerja dalam tim"
                ],
                "maps" => "-6.200000,106.816666",
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
            ],
            "PR002" => [
                "nama_perusahaan" => "CV Sukses Selalu",
                "id_perusahaan" => "PR002",
                "alamat_perusahaan" => "Jl. Sudirman No. 45, Bandung",
                "description" => "Perusahaan distribusi produk makanan dan minuman.",
                "shift" => "Malam",
                "man_replace" => "Sutrisno",
                "man_nik" => "320101197912120004",
                "profile_url" => "https://example.com/profiles/PR002",
                "status" => "Nonaktif",
                "sallary" => "Rp 4.000.000 - Rp 6.000.000",
                "requirements" => [
                    "Minimal pendidikan SMA/SMK",
                    "Memiliki SIM A atau C",
                    "Bersedia bekerja shift malam"
                ],
                "maps" => "-6.914744,107.609810",
                "comment" => [
                    [
                        "nik" => "320101198303030005",
                        "name" => "Dewi Lestari",
                        "profile_url" => "https://example.com/profiles/320101198303030005",
                        "comment" => "Lokasi strategis dekat pusat kota."
                    ],
                    [
                        "nik" => "320101198605060006",
                        "name" => "Agus Saputra",
                        "profile_url" => "https://example.com/profiles/320101198605060006",
                        "comment" => "Pengiriman cepat."
                    ]
                ]
            ],
            "PR003" => [
                "nama_perusahaan" => "PT Sejahtera Bersama",
                "id_perusahaan" => "PR003",
                "alamat_perusahaan" => "Jl. Gatot Subroto No. 99, Surabaya",
                "description" => "Perusahaan jasa konstruksi bangunan.",
                "shift" => "Pagi",
                "man_replace" => "Hendra Gunawan",
                "man_nik" => "320101198712070007",
                "profile_url" => "https://example.com/profiles/PR003",
                "status" => "Aktif",
                "sallary" => "Rp 6.000.000 - Rp 8.000.000",
                "requirements" => [
                    "Minimal pendidikan S1 Teknik Sipil",
                    "Pengalaman di bidang konstruksi minimal 3 tahun",
                    "Mampu membaca gambar teknik"
                ],
                "maps" => "-7.250445,112.768845",
                "comment" => [
                    [
                        "nik" => "320101199404040008",
                        "name" => "Eka Pratama",
                        "profile_url" => "https://example.com/profiles/320101199404040008",
                        "comment" => "Proyek selesai tepat waktu."
                    ],
                    [
                        "nik" => "320101199606060009",
                        "name" => "Siti Nurhaliza",
                        "profile_url" => "https://example.com/profiles/320101199606060009",
                        "comment" => "Kualitas kerja memuaskan."
                    ]
                ]
            ]
        ];

        if (isset($data[$id])) {
            return response()->json([
                "status" => "success",
                "message" => "Detail perusahaan berhasil diambil.",
                "data" => $data[$id]
            ]);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Perusahaan tidak ditemukan."
            ], 404);
        }
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
                'id_cover_me' => 'required|integer',
                'nik' => 'required|string',
                'name' => 'required|string',
                'comment' => 'required|string'
            ]);

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
                'id_perusahaan' => 'required|integer',
                'nik_cover' => 'required|integer',
                'tanggal' => 'required|date',
                'shift' => 'required|string',
                'requirements' => 'required|array',
            ]);

            // Encode requirements as JSON
            $validated['requirements'] = json_encode($validated['requirements']);

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
            $claims = CoverClaim::all();

            return response()->json([
                "status" => "success",
                "message" => "Data klaim berhasil diambil.",
                "data" => $claims
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
