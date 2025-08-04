<?php

namespace App\Http\Controllers\Api\CoverMe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CovermeController extends Controller
{
    //

    public function index()
    {
        $data = [
            [
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
            [
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
            [
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

        $result = [
            "status" => "success",
            "message" => "Data perusahaan berhasil diambil.",
            "data" => $data
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
    }

    public function postComment(Request $request)
    {
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
    }
}
