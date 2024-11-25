@extends('layout.master')
    @php 
        $user = Auth::user();
        $dataLogin = json_decode(Auth::user()->permission); 
        $employee = \App\Employee::where('nik', Auth::user()->name)->first(); 
        $tasks='[
            {
                "id": 114,
                "judul": "ROOF TOP WING A",
                "point": [
                    {
                        "id": 230,
                        "id_master": "114",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 231,
                        "id_master": "114",
                        "task": "PENGECEKAN PANEL",
                        "list_data": [
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 232,
                        "id_master": "114",
                        "task": "PENGECEKAN AREA WING A",
                        "list_data": [
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 115,
                "judul": "ROOF TOP MESIN LIFT",
                "point": [
                    {
                        "id": 233,
                        "id_master": "115",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 234,
                        "id_master": "115",
                        "task": "PENGECEKAN AC DAN PANEL APABILAN TIDAK DIGUNAKAN (OFF)",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "situasi aman dan kondusif",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 235,
                        "id_master": "115",
                        "task": "PENGECEKAN PINTU LIFT & MESIN",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "situasi aman dan kondusif",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 236,
                        "id_master": "115",
                        "task": "PENGECEKAN SALKAR APBILA TIDAK DIGUNAKAN DI OFF",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "aman dan kondusif",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 116,
                "judul": "ROOF TOP WING B",
                "point": [
                    {
                        "id": 237,
                        "id_master": "116",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 238,
                        "id_master": "116",
                        "task": "PENGECEKAN AC DAN PANEL APABILAN TIDAK DIGUNAKAN (OFF)",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 239,
                        "id_master": "116",
                        "task": "PENGECEKAN KEBERSIHAN AREA",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "area bersih aman dan kondusif",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 240,
                        "id_master": "116",
                        "task": "PENGECEKAN AKSES APABILA TIDAK DIGUNAKAN KONDISI TERTUTUP/TERKUNCI",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "semua terkunci",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 117,
                "judul": "LT 15 WING B",
                "point": [
                    {
                        "id": 241,
                        "id_master": "117",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "kurang satu",
                                "image": ""
                            },
                            {
                                "employee_code": "3172030307880010",
                                "status": "Baik",
                                "description": "baik",
                                "image": "/images/company_logo/1720301089_photo241.png"
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 243,
                        "id_master": "117",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 244,
                        "id_master": "117",
                        "task": "PENGECEKAN KOORIDOR KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 245,
                        "id_master": "117",
                        "task": "PENGECEKAN PANEL",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 246,
                        "id_master": "117",
                        "task": "PENGECEKAN EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 118,
                "judul": "LT 15 WING A",
                "point": [
                    {
                        "id": 247,
                        "id_master": "118",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "oke baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "oke baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 248,
                        "id_master": "118",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 249,
                        "id_master": "118",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 250,
                        "id_master": "118",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 251,
                        "id_master": "118",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 119,
                "judul": "LT 12 WING A",
                "point": [
                    {
                        "id": 252,
                        "id_master": "119",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 253,
                        "id_master": "119",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 254,
                        "id_master": "119",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 255,
                        "id_master": "119",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 256,
                        "id_master": "119",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik tidak ada kendala apapun",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 120,
                "judul": "LT 12 WING B",
                "point": [
                    {
                        "id": 257,
                        "id_master": "120",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 258,
                        "id_master": "120",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 259,
                        "id_master": "120",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 260,
                        "id_master": "120",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 261,
                        "id_master": "120",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 121,
                "judul": "LT 11 WING B",
                "point": [
                    {
                        "id": 262,
                        "id_master": "121",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "kk",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 263,
                        "id_master": "121",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 264,
                        "id_master": "121",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 265,
                        "id_master": "121",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 266,
                        "id_master": "121",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 122,
                "judul": "LT 11 WING A",
                "point": [
                    {
                        "id": 267,
                        "id_master": "122",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "cuma satu",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "cuma satu",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "cuma satu",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "cuma satu",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 268,
                        "id_master": "122",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "okeok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "okeok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "okeok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "okeok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 269,
                        "id_master": "122",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 270,
                        "id_master": "122",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 271,
                        "id_master": "122",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 123,
                "judul": "LT 10 WING A",
                "point": [
                    {
                        "id": 272,
                        "id_master": "123",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "cuma satu",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 273,
                        "id_master": "123",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 274,
                        "id_master": "123",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 275,
                        "id_master": "123",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 276,
                        "id_master": "123",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 124,
                "judul": "LT 10 WING B",
                "point": [
                    {
                        "id": 277,
                        "id_master": "124",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "cuma satu",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 278,
                        "id_master": "124",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 279,
                        "id_master": "124",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 280,
                        "id_master": "124",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 281,
                        "id_master": "124",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 125,
                "judul": "LT 09 WING B",
                "point": [
                    {
                        "id": 282,
                        "id_master": "125",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "cuma satu",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 283,
                        "id_master": "125",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 284,
                        "id_master": "125",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 285,
                        "id_master": "125",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 286,
                        "id_master": "125",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 126,
                "judul": "LT 09 WING A",
                "point": [
                    {
                        "id": 287,
                        "id_master": "126",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "cuma satu",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik aman",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 288,
                        "id_master": "126",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik aman",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 289,
                        "id_master": "126",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik aman",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 290,
                        "id_master": "126",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik aman",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 291,
                        "id_master": "126",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik aman",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 127,
                "judul": "LT 08 WING A",
                "point": [
                    {
                        "id": 292,
                        "id_master": "127",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "cuma satu",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 293,
                        "id_master": "127",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 294,
                        "id_master": "127",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 295,
                        "id_master": "127",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 296,
                        "id_master": "127",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "ada penambalan tembok belum rapih",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 128,
                "judul": "LT 08 WING B",
                "point": [
                    {
                        "id": 297,
                        "id_master": "128",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "cuma satuok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Kurang Baik",
                                "description": "cuma satuok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 298,
                        "id_master": "128",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 299,
                        "id_master": "128",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 300,
                        "id_master": "128",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 301,
                        "id_master": "128",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 129,
                "judul": "LT 07 WING B",
                "point": [
                    {
                        "id": 302,
                        "id_master": "129",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 303,
                        "id_master": "129",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 304,
                        "id_master": "129",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "okom",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 305,
                        "id_master": "129",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 306,
                        "id_master": "129",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 130,
                "judul": "LT 07 WING A",
                "point": [
                    {
                        "id": 307,
                        "id_master": "130",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "olk",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 308,
                        "id_master": "130",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 309,
                        "id_master": "130",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 310,
                        "id_master": "130",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 311,
                        "id_master": "130",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 131,
                "judul": "LT 06 WING A",
                "point": [
                    {
                        "id": 312,
                        "id_master": "131",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 313,
                        "id_master": "131",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 314,
                        "id_master": "131",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 315,
                        "id_master": "131",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 316,
                        "id_master": "131",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 132,
                "judul": "LT 06 WING B",
                "point": [
                    {
                        "id": 317,
                        "id_master": "132",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 318,
                        "id_master": "132",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 319,
                        "id_master": "132",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 320,
                        "id_master": "132",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 321,
                        "id_master": "132",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 133,
                "judul": "LT 05 WING B",
                "point": [
                    {
                        "id": 322,
                        "id_master": "133",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 323,
                        "id_master": "133",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 324,
                        "id_master": "133",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 326,
                        "id_master": "133",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 327,
                        "id_master": "133",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 134,
                "judul": "LT 05 WING A",
                "point": [
                    {
                        "id": 328,
                        "id_master": "134",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 329,
                        "id_master": "134",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 331,
                        "id_master": "134",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 333,
                        "id_master": "134",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 334,
                        "id_master": "134",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 135,
                "judul": "LT 03 WING A",
                "point": [
                    {
                        "id": 335,
                        "id_master": "135",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 336,
                        "id_master": "135",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 337,
                        "id_master": "135",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 338,
                        "id_master": "135",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 339,
                        "id_master": "135",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 136,
                "judul": "LT 03 POOL 1",
                "point": [
                    {
                        "id": 340,
                        "id_master": "136",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 341,
                        "id_master": "136",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 345,
                        "id_master": "136",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 350,
                        "id_master": "136",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 137,
                "judul": "LT 03 POOL 2",
                "point": [
                    {
                        "id": 346,
                        "id_master": "137",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 347,
                        "id_master": "137",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 348,
                        "id_master": "137",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 349,
                        "id_master": "137",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 138,
                "judul": "LT 03 GYM",
                "point": [
                    {
                        "id": 351,
                        "id_master": "138",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 352,
                        "id_master": "138",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 139,
                "judul": "LT 03 WING B",
                "point": [
                    {
                        "id": 353,
                        "id_master": "139",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 354,
                        "id_master": "139",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 355,
                        "id_master": "139",
                        "task": "PENGECEKAN PINTU KAMAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 356,
                        "id_master": "139",
                        "task": "PENGECEKAN KORIDOR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 357,
                        "id_master": "139",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 140,
                "judul": "LT 03 ROOF TOP",
                "point": [
                    {
                        "id": 358,
                        "id_master": "140",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 359,
                        "id_master": "140",
                        "task": "PENGECEKAN PINTU LIFT & MESIN",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 360,
                        "id_master": "140",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 141,
                "judul": "LT 03 AHU ROOM",
                "point": [
                    {
                        "id": 361,
                        "id_master": "141",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 362,
                        "id_master": "141",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 363,
                        "id_master": "141",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 142,
                "judul": "LT 02 AV ROOM",
                "point": [
                    {
                        "id": 364,
                        "id_master": "142",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 365,
                        "id_master": "142",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 366,
                        "id_master": "142",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 143,
                "judul": "LT 02 BACK ANGSANA 3",
                "point": [
                    {
                        "id": 367,
                        "id_master": "143",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 368,
                        "id_master": "143",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 369,
                        "id_master": "143",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 144,
                "judul": "LT 02 STORE BANQUET",
                "point": [
                    {
                        "id": 370,
                        "id_master": "144",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "lk",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 371,
                        "id_master": "144",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 372,
                        "id_master": "144",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 145,
                "judul": "LT 02 SMOKING AREA",
                "point": [
                    {
                        "id": 373,
                        "id_master": "145",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 374,
                        "id_master": "145",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 146,
                "judul": "LT 02 CENDANA 1",
                "point": [
                    {
                        "id": 375,
                        "id_master": "146",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 376,
                        "id_master": "146",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 147,
                "judul": "LT 02 PANTRY",
                "point": [
                    {
                        "id": 377,
                        "id_master": "147",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Bauj",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 378,
                        "id_master": "147",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 379,
                        "id_master": "147",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 148,
                "judul": "LT 02 KITCHEN BANQUET",
                "point": [
                    {
                        "id": 380,
                        "id_master": "148",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 381,
                        "id_master": "148",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 382,
                        "id_master": "148",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 149,
                "judul": "LT 02 STAIRCASE WING B",
                "point": [
                    {
                        "id": 383,
                        "id_master": "149",
                        "task": "PENGECEKAN PINTU STAIRCASE",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 150,
                "judul": "LT 01 BACK OFFICE",
                "point": [
                    {
                        "id": 384,
                        "id_master": "150",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 385,
                        "id_master": "150",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ol",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 386,
                        "id_master": "150",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 398,
                        "id_master": "150",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 151,
                "judul": "LT 01 KITCHEN BOTANY 1",
                "point": [
                    {
                        "id": 388,
                        "id_master": "151",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "situasi aman dan kondusif",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Bauj",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 389,
                        "id_master": "151",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "situasi aman dan kondusif",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 390,
                        "id_master": "151",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3276022001880005",
                                "status": "Baik",
                                "description": "baik aman dan kondusif",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 152,
                "judul": "LT 01 KITCHEN BOTANY 2",
                "point": [
                    {
                        "id": 391,
                        "id_master": "152",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baij",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 392,
                        "id_master": "152",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 393,
                        "id_master": "152",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 153,
                "judul": "LT 01 SERVER ROOM",
                "point": [
                    {
                        "id": 394,
                        "id_master": "153",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 395,
                        "id_master": "153",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 396,
                        "id_master": "153",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 397,
                        "id_master": "153",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 154,
                "judul": "LT 01 GAS ROOM",
                "point": [
                    {
                        "id": 399,
                        "id_master": "154",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 400,
                        "id_master": "154",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 401,
                        "id_master": "154",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 155,
                "judul": "LT 01 LOADING DOCK",
                "point": [
                    {
                        "id": 402,
                        "id_master": "155",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 403,
                        "id_master": "155",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 404,
                        "id_master": "155",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 405,
                        "id_master": "155",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 156,
                "judul": "LT 01 GARDEN LOADING DOCK",
                "point": [
                    {
                        "id": 406,
                        "id_master": "156",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 407,
                        "id_master": "156",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 408,
                        "id_master": "156",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 409,
                        "id_master": "156",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 157,
                "judul": "LT 01 GARDEN BOTANY",
                "point": [
                    {
                        "id": 410,
                        "id_master": "157",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 411,
                        "id_master": "157",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 158,
                "judul": "LT 01 CAR CHECK",
                "point": [
                    {
                        "id": 412,
                        "id_master": "158",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 413,
                        "id_master": "158",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 414,
                        "id_master": "158",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 415,
                        "id_master": "158",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 159,
                "judul": "LT 01 VALET PARKING ( NEAR BG )",
                "point": [
                    {
                        "id": 416,
                        "id_master": "159",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ol",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 417,
                        "id_master": "159",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 418,
                        "id_master": "159",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 160,
                "judul": "LT 01 NEAR FB OFFICE",
                "point": [
                    {
                        "id": 419,
                        "id_master": "160",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 420,
                        "id_master": "160",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 421,
                        "id_master": "160",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 161,
                "judul": "LT 01 TRAVO ROOM",
                "point": [
                    {
                        "id": 422,
                        "id_master": "161",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 423,
                        "id_master": "161",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "kk",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 425,
                        "id_master": "161",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 162,
                "judul": "LT 01 RAMP IN OUT",
                "point": [
                    {
                        "id": 432,
                        "id_master": "162",
                        "task": "PENGECEKAN KERUSAKAN",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "aman",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 163,
                "judul": "B.1 RAMP IN OUT",
                "point": [
                    {
                        "id": 431,
                        "id_master": "163",
                        "task": "PENGECEKAN KERUSAKAN",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3175022106900005",
                                "status": "Kurang Baik",
                                "description": "drill berkarat",
                                "image": "/images/company_logo/1720134989_photo431.png"
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 164,
                "judul": "B.1 PARKIR MOTOR 1",
                "point": [
                    {
                        "id": 433,
                        "id_master": "164",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 434,
                        "id_master": "164",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 435,
                        "id_master": "164",
                        "task": "PENGECEKAN CCTV",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 436,
                        "id_master": "164",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 447,
                        "id_master": "164",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 165,
                "judul": "B.1 PARKIR MOTOR 2",
                "point": [
                    {
                        "id": 437,
                        "id_master": "165",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 438,
                        "id_master": "165",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 439,
                        "id_master": "165",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 440,
                        "id_master": "165",
                        "task": "PENGECEKAN CCTV",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Biak",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 446,
                        "id_master": "165",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 166,
                "judul": "B.1 PARKIR MOTOR 3",
                "point": [
                    {
                        "id": 441,
                        "id_master": "166",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 442,
                        "id_master": "166",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 443,
                        "id_master": "166",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 444,
                        "id_master": "166",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 445,
                        "id_master": "166",
                        "task": "PENGECEKAN CCTV",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 167,
                "judul": "B.1 STAIRCASE TO LOADING DOCK",
                "point": [
                    {
                        "id": 449,
                        "id_master": "167",
                        "task": "PENGECEKAN PINTU STAIRCASE",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 168,
                "judul": "B.1 NEAR TRANSIT PUMP",
                "point": [
                    {
                        "id": 450,
                        "id_master": "168",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 451,
                        "id_master": "168",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 452,
                        "id_master": "168",
                        "task": "PENGECEKAN KERUSAKAN",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 169,
                "judul": "B.1 STORE FB",
                "point": [
                    {
                        "id": 453,
                        "id_master": "169",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 454,
                        "id_master": "169",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 170,
                "judul": "B.1 DRIVER ROOM",
                "point": [
                    {
                        "id": 455,
                        "id_master": "170",
                        "task": "PENGECEKAN KEAMANAN",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 466,
                        "id_master": "170",
                        "task": "PENGECEKAN KERUSAKAN",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 171,
                "judul": "B.1 LAUNDRY",
                "point": [
                    {
                        "id": 456,
                        "id_master": "171",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 457,
                        "id_master": "171",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 458,
                        "id_master": "171",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 459,
                        "id_master": "171",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 172,
                "judul": "B.1 MALE LOCKER",
                "point": [
                    {
                        "id": 460,
                        "id_master": "172",
                        "task": "PENGECEKAN KERUSAKAN",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3215251607770008",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 465,
                        "id_master": "172",
                        "task": "PENGECEKAN KEAMANAN",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "Baik",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3215251607770008",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 472,
                        "id_master": "172",
                        "task": "PENGECEKAN PNITU LOKER",
                        "list_data": [
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3215251607770008",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1806091806950001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1806091806950001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 473,
                        "id_master": "172",
                        "task": "PENGECEKAN TOILET",
                        "list_data": [
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3215251607770008",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1806091806950001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1806091806950001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 474,
                        "id_master": "172",
                        "task": "PENGECEKAN KEBERSIHAN AREA",
                        "list_data": [
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3215251607770008",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1806091806950001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1806091806950001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 173,
                "judul": "B.1 KITCHEN KONGKOW",
                "point": [
                    {
                        "id": 461,
                        "id_master": "173",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 462,
                        "id_master": "173",
                        "task": "PENGECEKAN HYDRANT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 463,
                        "id_master": "173",
                        "task": "PENGECEKAN EMERGENCY EXIT",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "okeoke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "okok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 464,
                        "id_master": "173",
                        "task": "PENGECEKAN PANEL LISTRIK",
                        "list_data": [
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "oke",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202281706780006",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 471,
                        "id_master": "173",
                        "task": "PENGECEKAN AREA KOMPOR",
                        "list_data": [
                            {
                                "employee_code": "1207020204940003",
                                "status": "Baik",
                                "description": "Ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3328111312020008",
                                "status": "Baik",
                                "description": "baik",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3212241909920001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3215251607770008",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 174,
                "judul": "LT 1 PANTRY",
                "point": [
                    {
                        "id": 467,
                        "id_master": "174",
                        "task": "PENGECEKAN APAR",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3215251607770008",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1806091806950001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 468,
                        "id_master": "174",
                        "task": "PENGECEKAN AC DAN PANEL APABILAN TIDAK DIGUNAKAN (OFF)",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3215251607770008",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1806091806950001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "oj",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 469,
                        "id_master": "174",
                        "task": "PENGECEKAN KEBERSIHAN AREA",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3215251607770008",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1806091806950001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    },
                    {
                        "id": 470,
                        "id_master": "174",
                        "task": "PENGECEKAN PINTU",
                        "list_data": [
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3215251607770008",
                                "status": "Baik",
                                "description": "good",
                                "image": ""
                            },
                            {
                                "employee_code": "3201190901990002",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1806091806950001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3275112006870003",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "3202260810880001",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            },
                            {
                                "employee_code": "1808071010940004",
                                "status": "Baik",
                                "description": "ok",
                                "image": ""
                            }
                        ]
                    }
                ]
            }
        ]';
    @endphp
<style>
  /* styles.css */
    .loading-backdrop {
        display: none; /* Initially hidden */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
        z-index: 9999; /* High z-index to ensure it covers other elements */
        align-items: center;
        justify-content: center;
        display: flex;
    }

    .loading-spinner {
        text-align: center;
        padding: 20px;
        font-size: 18px;
        color: #fff; /* White text color */
        border: 4px solid rgba(255, 255, 255, 0.3); /* Light border */
        border-radius: 50%;
        border-top: 4px solid #fff; /* White top border for spinner effect */
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite; /* Spin animation */
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .table{
        font-size:9px !important;   
    }
</style>
@section('content')
<!-- <div id="loadingBackdrop" class="loading-backdrop">
  <div class="loading-spinner"></div>
</div> -->
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                Filter
                
            </div>
            <div class="card-body">
                <div id="treeView"></div>
            </div>
        </div>
    </div>
    <div  class="col-md-9">
        <div class="row">
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size:30px" id="total_titik">0</span>
                    </div>
                    <div class="card-footer">
                        <h6>Jumlah Titik Patroli</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size:30px"  id="total_point">0</span>
                    </div>
                    <div class="card-footer">
                        <h6>Jumlah Point</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size:30px" id="jumlah_shift">0</span>
                    </div>
                    <div class="card-footer">
                        <h6>Jumlah SHIFT</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size:30px" id="patroli_pershift">0</span>
                        
                    </div>
                    <div class="card-footer">
                        <h6>Jumlah Patroli</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div id="chart"></div>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div id="pie"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <table class="datatable table table-bordered">
                            <thead>
                                <tr>
                                    <td width="5px">No</td>
                                    <td>Tanggal</td>
                                    <td>Titik</td>
                                    <td colspan="10">Point</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $no=1;
                                @endphp
                                @foreach(json_decode($tasks) as $task)
                                    <tr>
                                        <td rowspan="2">{{ $no }}</td>
                                        <td rowspan="2"></td>
                                        <td rowspan="2" colspan="10">{{ $task->judul }}</td>
                                    </tr>
                                    <tr>
                                        
                                        @foreach($task->point  as $row)
                                            @if($loop->last)
                                                <td colspan="10">
                                                    {{ $row->task }}<hr/>
                                                    @if($row->list_data)
                                                        @foreach($row->list_data  as $data)
                                                            {{ $data->employee_code }}<br/>
                                                            {{ $data->status }}<br/>
                                                            {{ $data->description }}<hr/>
                                                        @endforeach
                                                    @endif
                                                            
                                                </td>
                                            @else
                                                <td>
                                                    {{ $row->task }}<hr/>
                                                    @if($row->list_data)
                                                        @foreach($row->list_data  as $data)
                                                            {{ $data->employee_code }}<br/>
                                                            {{ $data->status }}<br/>
                                                            {{ $data->description }}<hr/>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            @endif
                                            
                                        @endforeach
                                    </tr>
                                    @php 
                                        $no++;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</div>


@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/fullcalendar/index.global.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
  <!-- <script src="{{ asset('assets/js/fullcalendar.js') }}"></script> -->
@endpush


@push('custom-scripts')

<script>
    // Function to fetch and update dashboard data based on selected filters
    const dashboardData = async (selectedIds = []) => {
        try {
            const groupedFilters = groupByParentId(selectedIds); // Group selected IDs by parent categories
            
            // Make an API request
            const response = await axios.post('/api/v1/dashboard-patroli', { filters: groupedFilters });
            const responseData = response.data.record;

            // Update the UI elements with the response data
            $("#total_titik").text(responseData.total_titik || 0);
            $("#total_point").text(responseData.total_point || 0);
            $("#jumlah_shift").text(responseData.jumlah_shift || 0);
            $("#patroli_pershift").text(responseData.patroli_pershift || 0);

            // Update the charts
            updateGrafikData(responseData);
            updatePieChart(responseData);
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch dashboard data. Please try again later.',
            });
        }
    };

    // Function to group selected IDs by their parent_id
    const groupByParentId = (selectedIds) => {
        const grouped = {};
        selectedIds.forEach(id => {
            const item = rawData.find(data => data.id === id);
            if (item) {
                const parentId = item.parent_id || 'root'; // Use 'root' for items with no parent
                grouped[parentId] = grouped[parentId] || [];
                grouped[parentId].push(id);
            }
        });
        return grouped;
    };

    // Add data for the tree view
    const rawData = {!! json_encode(project_filter($user->project_id)) !!};
    rawData.push(
        { id: 'Yearly', name: "Yearly", parent_id: null },
        { id: 'Monthly', name: "Monthly", parent_id: null },
        { id: 'January', name: "January", parent_id: 'Monthly' },
        { id: 'February', name: "February", parent_id: 'Monthly' },
        { id: 'March', name: "March", parent_id: 'Monthly' },
        { id: 'April', name: "April", parent_id: 'Monthly' },
        { id: 'May', name: "May", parent_id: 'Monthly' },
        { id: 'June', name: "June", parent_id: 'Monthly' },
        { id: 'July', name: "July", parent_id: 'Monthly' },
        { id: 'August', name: "August", parent_id: 'Monthly' },
        { id: 'September', name: "September", parent_id: 'Monthly' },
        { id: 'October', name: "October", parent_id: 'Monthly' },
        { id: 'November', name: "November", parent_id: 'Monthly' },
        { id: 'December', name: "December", parent_id: 'Monthly' },
        // { id: 'Shift', name: "Shift", parent_id: null },
        // { id: 'SHIFT PAGI', name: "SHIFT PAGI", parent_id: 'Shift' },
        // { id: 'SHIFT MIDLE', name: "SHIFT MIDLE", parent_id: 'Shift' },
        // { id: 'SHIFT MALAM', name: "SHIFT MALAM", parent_id: 'Shift' },
    );

    // Initialize the tree view with jstree
    $('#treeView').jstree({
        core: {
            data: rawData.map(item => ({
                id: item.id,
                parent: item.parent_id || '#',
                text: item.name
            })),
            themes: { responsive: true }
        },
        plugins: ["checkbox"],
        checkbox: { keep_selected_style: false }
    });

    // Handle tree view selection changes
    $('#treeView').on('changed.jstree', (e, data) => {
        const selectedIds = data.selected;
        dashboardData(selectedIds);
    });

    // Initial dashboard data fetch
    dashboardData();

    // Declare chart instances
    let lineChart = null;
    let pieChart = null;

    // Update line chart with data
    const updateGrafikData = (responseData) => {
        const percentData = responseData.percent.map(item => {
            const match = item.match(/\d+/);
            return match ? parseFloat(match[0]) : 0;
        });

        const options = {
            chart: { height: 350, type: 'line', stacked: false },
            series: [
                { name: 'Activity', type: 'line', data: responseData.value_shift1 },
                { name: 'Target Activity', type: 'bar', data: responseData.grafik_value },
                { name: 'Percentage', type: 'line', data: percentData }
            ],
            stroke: { curve: 'smooth', width: [2, 2, 2] },
            xaxis: { categories: responseData.grafik_key },
            
            title: { text: 'Patrol Activity', align: 'center' },
            markers: { size: [4, 4, 4] },
            colors: ['#FEB019', '#00E396', '#008FFB'],
            legend: { position: 'top', horizontalAlign: 'right' },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: (value, { seriesIndex }) =>
                        seriesIndex === 2 ? value + '%' : value
                }
            }
        };

        if (lineChart) {
            lineChart.updateOptions(options);
        } else {
            lineChart = new ApexCharts(document.querySelector("#chart"), options);
            lineChart.render();
        }
    };

    // Update pie chart with data
    const updatePieChart = (responseData) => {
        const options = {
            chart: { type: 'pie', height: 350 },
            series: responseData.pie_chart,
            labels: ['Patrol', 'No Patrol'],
            colors: ['#008FFB', '#FF4560'],
            title: { text: 'Chart Statistic', align: 'center' },
            legend: { position: 'bottom' }
        };

        if (pieChart) {
            pieChart.updateOptions(options);
        } else {
            pieChart = new ApexCharts(document.querySelector("#pie"), options);
            pieChart.render();
        }
    };
</script>


@endpush