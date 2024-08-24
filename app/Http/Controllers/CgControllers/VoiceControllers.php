<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ModelCG\VoiceofGuardians;

class VoiceControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $json='[
    {
      "user_id": "Aditiya Eko Prasetiyo ",
      "project_id": "Courts KHI ",
      "created_at": "1/2/2024 2:30siang",
      "pertanyaan": "Untuk gaji pokok 4.400.000 tidak ada uang BPJS yang 200.000 kiraÂ² potongan apa ya pak ?",
      "attachment": "Screenshot_20240201_143239.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/UwnsNdtc0kEMdDbet1DoUQ/Zwp4jEgPaKa35De9VmSgdMR_TmxBqAAMGY_qdyjI2qRmsHh5EIGbIuypQSsZ-Nx_r62x11lTkH9syDdOhnI1RGfuaLjDxD5VZMpsR8Sy6iyJgb0dvB9UElmJVGQKjmgN9B6sCJGHJIumnruFyQwko-exkxwlwbAyDdXO9I6wywc/NJUJQyYmeOCII1B90IRZIjNRg-JkLopOy7VT3dLKNyM)"
    },
    {
      "user_id": "Rusdianto ",
      "project_id": "Mercure Jakarta Cikini ",
      "created_at": "5/4/2024 11:15siang",
      "pertanyaan": "THR Danru aeharusnya terima Rp 4.800.000 tetapi hanya menerima 4,4jt"
    },
    {
      "user_id": "Zaki Rahmat waldana ",
      "project_id": "Mimaru",
      "created_at": "31/7/2024 7:34malam",
      "pertanyaan": "Selamat malam Ndan, izin bertanya bulan lalu saya terkena potongan 235.000 itu kan test kesehatan, nah di bulan ini saya kena potongan dengan nominal yg sama, izin petunjuk apa ada potongan lain ya Ndan selain test kesehatan",
      "attachment": "IMG_20240731_193709.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/mHFCX18X6JQjJN6C0kumLw/Em-sUL0iO_YZnXhj3-OVtAIwUnubdakttsH4rpV9glL59PyM47BWqNE7mu293ohdAwxw-hkI5Ayr13AcT1N233CDhi8bhm-sYLh_AGjuYvPGtHqcOQr9pOJk4rL31OSXUa2XgNHIENnGwzgtFt4258hXsyBOieZyCTIfvBgVeoE/JEXY8uDz4m_oQ9v-9H3lQg7e_z-KAHMcX4bIO69k-9A)"
    },
    {
      "user_id": "Hendri Setiawan ",
      "project_id": "courts khi",
      "created_at": "18/8/1999 2:47siang",
      "pertanyaan": "saya back up buhory tanpa keterangan kok gak nambah gajinya saya ada bukti back up",
      "attachment": "Screenshot_2024-01-31-14-38-03-08_abb9c8060a0a12c5ac89e934e52a2f4f.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/VsMZ72so36c_QKoFQIXCyw/JuwjCCh0bv5HOXpBcvfRE6y0g6-jK2NCFVfPmZcVlcC-pWPbdjqiqzoMw-fMIdQftL7PlOtZ8_BCz8frkKi4LjXQmBSlCM_Wm1x93z80lfzJyZoxAXI5rIaPOqPDriKJcNrUsgLUPqWlujmZ_cro0s7MoXrPn-x88QLV2u2rzDoHR8SrMstBGOe4Il7EWI4FZ0-YYvXVmIz_iXnu_3y_3E3pey1_8NKAR7ChtpGGms4/hchmGm-7onzoK1mDkrvmUo9IQ_G7Ozqj3OokNTJ7_gE)",
      "status": "Todo"
    },
    {
      "user_id": "Hasbullah",
      "project_id": "MERCURE JAKARTA SIMATUPANG ",
      "created_at": "31/1/2024 2:59siang",
      "pertanyaan": "Di slip gaji ada keterangan lain-lain. Mungkin bisa di rincikan lain-lain ini apa aja sih?\r\n\r\nMengenai potongan GP saya sudah yg keberapa sih ? Terakhir saya nanya di bulan Oktober/November sudah yg ke 7 kali. \r\n\r\nSatu lagi yang menjadi keluhan saya.. saya di tunjuk menjadi jalak di projek Mercure Simatupang dan di janjikan akan ada salery/gaji lebih untuk jalak. Sampe sekrng dari pertama saya menjadi jalak tidak ada tanda2 saya dapat salery/gaji lebih. ",
      "status": "Todo"
    },
    {
      "user_id": "Saiful Anwar",
      "project_id": "Mercure Cikini",
      "created_at": "31/1/2024 3:13sore",
      "pertanyaan": "BERAPA LAGI POTONGAN UNTUK GADA PRATAMA SAYA???\r\nSEHARUSNYA BULAN JANUARI INI TERAKHIR. ",
      "status": "Todo"
    },
    {
      "user_id": "Faisal amir",
      "project_id": "Mandarin Oriental",
      "created_at": "31/1/2024 3:25sore",
      "pertanyaan": "Perihal gaji saya di potong absensi selama 3 hari  sedangkan 3 hari itu saya training dan itu pun 2 hari kerja operasional dan 1 hari off? Apakah training di hari off masih di potong absensi? ",
      "status": "Todo"
    },
    {
      "user_id": "Irwan Maulana",
      "project_id": "Prisma harapan",
      "created_at": "8/11/1990 4:03sore",
      "pertanyaan": "1. Di dalam kolom potongan lain-lain,\r\nIni potongan lain2 265.000 potongan apa yah pak Andre?\r\n\r\n2. Dan kolom backup, waktu saya backup dmn yah pakðŸ™ðŸ»\r\n\r\nSoalnya KL hitungan saya pak,\r\n\r\n3. Novotel Cikini 3 hari : 21-23 desember\r\nPrisma Harapan 15 hari : 29, 30, 31, 3, 4, 5, 6, 9, 10, 11, 12, 15, 16, 17, 18, Desember-Januari\r\n\r\nTgl 24 Desember saya dipulangkan ke HO dri Novotel Cikini,\r\n26-28 Desember Saya Di HO,\r\n29 Desember perdana saya masuk Project prisma harapan",
      "attachment": "Screenshot_2024-01-31-15-44-00-64.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/w4jLzTcN1g_JZmrThReSOA/Qk143sSYl4jc6sA4qIvdUWnt5EIf3dr-DHt0E19z9v9pufBaaB3lYp6M004Wvz9IP7xDrVJoNyuuDtbZvbAQI0Aie_8_WRhYiN_kswOmkkZps_cAWU9yGIns8KnjyEkhfl1ubE1nkKAdrXbR-7Jv0PY67CidRGQF8UWysPJYv7VhrJdh0LIQuf7nmjLlCX2b/m54QFjlGmOT4Cv5-4wQHSc9ijG6Dr61Z4vrkozqMkVQ),Screenshot_2024-01-31-15-41-34-16.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/Ts9yNicGihrmdRVvzQUTzA/GYgRsxkO2NhLpiVvPXtqsfT8H7aCpmCMnIA-yPCHTHpD1uqShNe_JcoDYOP_KSyrqtsZ5UDZq9QpwvqA_RSAsD-hD_1aZLeTVEhbpqPEtlivPzxINuHH5QlySAKWV9dAvyC4xkWNJ7sCuEKY1f1YRWQwSifXtPGIQ3DbMQJn7ZX2NIf--1zL4s6n17yMUw_X/WrMA7cv3nWpEJhqw-sIs_0d_hzTjzkEmH0Ihe4iXu0k)",
      "status": "Todo"
    },
    {
      "user_id": "Fajar Adi Ramadhan",
      "project_id": "Novotel cikini",
      "created_at": "31/1/2024 4:49sore",
      "pertanyaan": "Mengenai slip gaji dan gaji",
      "status": "Todo"
    },
    {
      "user_id": "SYAHRUL RAMADHAN ",
      "project_id": "Hotel Ibis styles Jakarta sunter",
      "created_at": "23/1/1999 5:02sore",
      "pertanyaan": " Selamat sore pak , ini gimana ceritanya ? Bisa di jelaskan tidak? Sebelumnya saya ada lemburan 1 Bukanya gaji di cg naik ya pak?\r\n Gaji di cg di Ibis bukanya 4 JT ya pak\r\nDan pas perpanjangan kontrak kemarin naik gajinya jadi 4.5\r\nTapi ini Nerima cuma 3.4 di potong gp\r\n Udah lemburan 1 nunggunya pas gajian gataunya gajian nya kurang",
      "attachment": "Screenshot_2024-01-31-16-52-56-58_e8205ec1cda7e9657fadf502be9b7b78.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/ZzVgadg8EqrslMbGz9Nh2Q/G87LqtCTCat-fHTp_YAf3_z13hOr9YEh5iCcwo84eOdAPxOAqPxUqyQ0PWLvUqBiCMS2wLE-G81rKNrS7WRMmCMBehyKWWgTY-KlntDFZlZKetJtwsOp6-kFUWRxFxqfwQVDF_6uShwxrtX4Z_3uoSB2hbEp7Wlr6SGB2mtAVhNysCaz_GQal-pQi81pzJMlXSZH9DMB9iBwHLkdz-RsMX9abjpWomwmXtVxz4nnE2k/QK-65mQ8xHE27mO89pm59uZGxy7clLxXiGF42DgPe-g),Screenshot_2024-01-31-17-05-21-37_e2d5b3f32b79de1d45acd1fad96fbb0f.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/ZRBQWEWux7RUXysZGKEZCA/oz62bSNfW9KpuSnueKLqhvGWIuR7lfcULLpoPlwN3-v4tYy2KfZWspc464NkFZQQYGcw2d7BOCT7O-2dxYCB0jZ5O0NIxJKJOq_5eDAtGG4_rJkOCo47W9k2XyCxSZuqaV19ToWFrexlQvj6h9ms-GXjrzWzoCrk6Q0lRII-1uHrMmxTaR_JT4NT4T9QFGmY9M5c7CTBOVJu6rctSBeLaoZyc9Q40FCH4JMlXq0cr5o/DWXDQGS4jG6CkuVWEOOqpQCb517sBSeHyZ6VBMTtIzc)",
      "status": "Todo"
    },
    {
      "user_id": "Aziz",
      "project_id": "BDN KUMBANG",
      "created_at": "31/1/2024 5:34sore",
      "pertanyaan": "Gajih potongan lain-lain 150.000 itu maksudnya apa ya?",
      "status": "Todo"
    },
    {
      "user_id": "Maria Ana Lisa Tamaela ",
      "project_id": "Mandarin Oriental Jakarta ",
      "created_at": "31/1/2024 6:07sore",
      "pertanyaan": "Kenapa yang BPJS belum di berikan di gaji Januari skrng?",
      "attachment": "Screenshot_20240131_180711_Word.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/AkQsjZFSD2lId96wKB5Yhw/_n55aObFL0JWgOaB8VYwzHozHJ4IbSQ8kjq8qLDAHeotDsopSWBY1d1sOEUip_KICV_6QBtrbQp31diCkBmmghe6rJOTS3n2sWDTpxPg7xY-4EDbDPYKPQ52BbaNQBZRJTdcB6BnMQ1TaP31MJaxR6lDkvoRxnliK2Z27OOuSFk/i8P6H2mKUg4zMNL6xM3NQ1nx32jFyrg6cfLPie_sJ5g)",
      "status": "Todo"
    },
    {
      "user_id": "Nana Mardiana ",
      "project_id": "Mandarin Oriental Jakarta ",
      "created_at": "1/2/2024 7:10pagi",
      "pertanyaan": "Lemburannya cuman turun 976.533, seharusnya lebih dari itu karena lemburan\r\nTgl 21 Desember 2023 event 12 jam\r\nTgl 03 Januari Backup Pagi\r\nTgl 09 Januari Backup Pagi\r\nTgl 14 Januari Backup Malam\r\nTgl 20 Januari Backup Malam",
      "attachment": "IMG-20240131-WA0055.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/YVhkfNxJsfufRQOBR3n0Jg/0qRAvvScSTgwOnsYps9jRRKmuT59A6RkbH5aLsXeVHmoJWHWHJplIBaH6HMdFFIE5ehDSXXHLwYCDMmNzmNfW1DCfo2XErQI5W5_rdI_MflHEUp07p9HmZD9caVkkgA7t5_bnazs6LdPOCXWHJCQzP7UnoTwN9ry2wFzC_2VVQY/vK1MG5m6QGONueR5cwv-0PzKsu0xH0twPwHjo6iEYL0)",
      "status": "Todo"
    },
    {
      "user_id": "Muhammad Syahiidul Abdihi ",
      "project_id": "PRISMA HARAPAN ",
      "created_at": "2/2/2024 7:30pagi",
      "pertanyaan": "Selamat Pagii Komandan\r\nIzin Komandan, Tolong Info Tentang Potongan Diskas , Terimakasih Komandan ",
      "attachment": "Screenshot_2024-02-02-07-17-38-44_c37d74246d9c81aa0bb824b57eaf7062.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/GbzNBRly-v4muNpPJjY3Ew/nQaRPVVrPTYk6w9PoyXIvvB5dJxdzKgaDkDgxkuxdVltel_wFKUxyBF0U9mED33j_AgIH-cH7xKX5bIlWebsgMSZfr1CFKWd7IwID4pTOHoy_72OiAuTfA-ssPBfU3DgcZls4jqnwzjn342hbnKarjP1InjvdL5WvZ02va5xnYc46YvE6Vo5l4cd5eyjiMQ-bhqmqFV0SFn7UgYdS6nHLBiOVDW83naITc3Bdwrou2c/PAcBOwSopE82wKez8uf3MUwtHJwL_DcSgq7qSiHyzCA)",
      "status": "Todo"
    },
    {
      "user_id": "Syarif Hidayat",
      "project_id": "Mandarin",
      "created_at": "29/2/2024 11:02siang",
      "pertanyaan": "Saya ingin menanyakan perihal slip gaji yang perincian di lain-lain, itu ada potongan 526rb tolong di perjelas potongan apa aja, terimakasih",
      "attachment": "2401-Februari 2024-SYARIF HIDAYAT.pdf (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/kTVLx4fZb-Gu3IAAM6XjcQ/p2VDp0N-KLVgmLz1o2ZYouvIesEuQs9-3brWu0wdWHa19VFALGcB8fO_j5iBlOoKUYeG0R24yF0zbMtXAIzi8M2n5IZ0ESDHV47LV2rzlmgSAKksjIvTw-Kqly-Wr6aRR__V_oM8AwgBdJcRiXBAEdf4gpjCaBlp4onmBg547Y4/AKonQ2lsRJ5JDRiNz7Dyv_RFy4QimBy75XUP3M0ozVM)",
      "status": "Todo"
    },
    {
      "user_id": "Christian",
      "project_id": "Mercure Cikini",
      "created_at": "29/2/2024 11:21siang",
      "pertanyaan": "Slip gaji tidak bisa di download\r\nMohon izin di kirim ulang kembali ke email saya pak ðŸ™",
      "attachment": "Screenshot_2024-02-29-11-19-52-140_com.google.android.gm.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/ytIyvR2CVh8LQ_qxPDKzKQ/DTW6aLc2lknMqdKwDs8Blbc5N0n7GREczXK6qDyjXD2wrpzhNE3n69JgkgaFymoEESOULNdFUa6BmNbgN51f79zWkDj6khn23etXCXFrB0gaBf_dHTj35JM9eWz0WYQCjem8L604QvM-pxa7nyvlJIT7EdrgUPFnlk3x_OgAay_xNadNHsoccTOY2rFaPkXZNWYj3gD1d8LT-iJvxSjA2g/mn52tGwsBR0i1bnO7Wz7vN-GqhjB7W6IfVspgVnta-0)",
      "status": "Todo"
    },
    {
      "user_id": "PUNGKY HARDIANTO ",
      "project_id": "Mercure Jakarta Simatupang ",
      "created_at": "29/2/2024 12:02siang",
      "pertanyaan": "Selamat siang ndan ,saya mau menanyakan perihal gaji saya dan potongan yang muncul\r\nSaya di Mercure Jakarta Simatupang total masuk ada 12 hari ,mulai melekat tgl 4 februari dan sampai tgl 19 februari...\r\nKalau saya hitung dari salary pokok ,per hari nya Rp 179.606,65\r\nSedangkan saya hanya terima gaji Rp 1.257.247 (gaji Mercure) ditambah Rp 717.230 (uang backup 3 hari)",
      "attachment": "Screenshot_20240229_102539.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/zbM0_ata0K1GOUvTqiWZLg/SBIQm4yr18zrK1sz4z3AoOPqEC9JQ8tHFvefXwxOJUSI0hwV956paxCj5qAUZNgUuQoyJmQESXsI232oyBz-gmO6dcxX2JEM7wGhI-kuKlCEYp0gJhubsvcHLq7Krksa5I3T0sg-2dGdC0VMQCjaV_U2dJlXfUpTlcvqM4GeEpQ/JGkxHyjdXYM3cvW1oUXG7Y6lB9GC0G9s_CCS-Sbc2IE)",
      "status": "Todo"
    },
    {
      "user_id": "Catur ardiansyah",
      "project_id": "PT SURYA SHUENN YUEH INDUSTRY",
      "created_at": "29/2/2024 1:38siang",
      "pertanyaan": "Selamat siang,Mohon maaf ijin bertanya, untuk perihal slip, untuk terakhir di bulan Desember saya masih mendapat income BPJS tk, dan di bulan Januari dan februari tidak dapat, Terima kasih,",
      "status": "Todo"
    },
    {
      "user_id": "Catur Ardiansyah",
      "project_id": "PT SURYA SHUENN YUEH INDUSTRY",
      "created_at": "29/2/2024 1:47siang",
      "pertanyaan": "Selamat siang ijin bertanya, menanggapi slip gaji, untuk perihal BPJS, saya terakhir dapat di bulan Desember, untuk di bulan Januari dan Februari tidak dapat, Terima kasih ",
      "attachment": "Screenshot_20240229_102016.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/1D-qoCZvbE7CCVDloo6FKw/RTy-eBCDvQ1F2O3eq2wOA7lyNG4CRra5_VJFhFlo5wf3NhYO0ELIx6sUkC_jqvU3JbZyTWAt_5DlKVtQb3b8uEXEtXgqGiLlu8oNNikS8aT1ggJSJURl8XmqkXP79rQeysJbGBYfwQsmHBXvUtXYGxK4ez_PxGkRiCyGkQ-V0Xw/uK-7qdl03jneJImksldHvQkAtPGDyyRT6UMaCgVuUzA),Screenshot_20240229_101530.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/kUYJ7SWLkkaz8S9GWwyGSg/Q7gptOp348TI6KnB8KoOjq0B60xb-aIy6wdG5HTFVm2FVcrj82nNr9OltOBn2yXiFDEzNuN6pGgM3wP7qSR8odmXywPpE5gJkSw2ke0VWu2zXYTg8rjITyVkOZBpOMSU4uuTOc8X0cJY4NTgAhPvvyjNX4rrO2VX5Nx4YB9R2sM/ilJT9-bGdcyll_W7N4eEpHRV-dneIT3PevWNVfa_hnQ)",
      "status": "Todo"
    },
    {
      "user_id": "Catur Ardiansyah",
      "project_id": "PT SURYA SHUENN YUEH INDUSTRY",
      "created_at": "29/2/2024 1:48siang",
      "pertanyaan": "Selamat siang , mohon maaf sebelumnya agak lancang ataupun tidak sopan, ijin bertanya mengenai gaji, kemarin sempat ngobrol dengan HRD bersama Danru ketika karyawan Balik kanan, untuk gaji sudah di naikan, intruksi beliau coba cek di bulan ini, terimakasih",
      "status": "Todo"
    },
    {
      "user_id": "Rita N",
      "project_id": "Mandarin",
      "created_at": "29/2/2024 2:10siang",
      "pertanyaan": "Hallo selamat siang tolong dibantu diperbaiki untuk masalah potongan lain lain nya itu biar rinci. Contoh potongan denda gp sekian, potongan ini sekian jangan dijadikan potongan lain lain. Kalo memang komponennya gamuat bisa dihilangkan aja gak kaya potongan diksar potongan gp ( Note: Yang sudah lunas Gp ) Jadi diganti sama yg lebih jelas gituloh yg kena potongannya apa aja biar ga jadi tanda tanya ke saya nya ",
      "status": "Todo"
    },
    {
      "user_id": "OGAN SAYOGI SAIPUR RAHMAT ",
      "project_id": "PT.SURYA SHUENN YUEH INDUSTRY ",
      "created_at": "29/2/2024 8:38malam",
      "pertanyaan": "Selamat malam komandan,mohon ijin lemburan/(BKO) saya di tanggal 15-februari-2024 tidak dibayarkan,untuk lampiran bukti terlampir fhoto ",
      "attachment": "Screenshot_2024-02-29-20-33-26-90.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/u_QuRT-cPmQzfp80gxK12g/wdG3YR0Fst6mBEYbmPLIunufHoX8nDPXiemsDCFIj4keYWYI0YdJOmlzfeq3vnjLU2cSG2JA2a2PVcBGBMx0rej7wjai79m1FGDAmcBSTJRMkubu89ge8OYLnmEgMSmr3Dnuto25i91b454xTsE4zWQXmUJB9q-YTgaA883WUBtZylcEMp5A8OrAcNYiyVDN/oRiyFh_hf8cNeq3lPvqPsWYd3m7AReBypDKeAy_a8cw),Screenshot_2024-02-29-17-04-02-18.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/GQEopxR58VmpIi88RRB06A/S7SI8-TN6d6fkWvVUHb3pk5MRRuw_uSaeqPe0Di0re-asko6x5vWtvAFxyjT5FxGjwu-mBSgow8vWYjSRgAFSXss34csJYwR00OallWaTFNII3tMx77dP3tUj-PkqXpcgpP7gbNWBwKpZkIH5e7jHZH2zUmK1UsIAhOFmfPlOsK0_1I2t570ROYe2iv0AjB3/qe3ieAPfYMd9on4LB-0l3HXgfxIYB0Yy6uFG1o7tvgQ),Screenshot_2024-02-29-20-33-02-54.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/hnaMNn0azlBDt2_7qSMQVA/bAhfO8omrt6rlII5QvHowHzIPA1qryabmQ1bvRIQa1pYfoN5qDOczC9bgX-WsrMCXYtoSZy4zyNWecU9KaqRzbG2b_lXm_hezLRIO94HCTUwqLtTUzXDEOvszXiPymP77qKPDo2CaQRkhEBTCSg53B2se9Uhj2uVisya-XWA3wzHCsS8pI5AzxHW5_0ZFLuo/De_DPzWvj6s0jupAuubqVr0K4EDTng5QUyquLOLBzMw)",
      "status": "Todo"
    },
    {
      "user_id": "OGAN SAYOGI SAIPUR RAHMAT ",
      "project_id": "PT.SURYA SHUENN YUEH INDUSTRY ",
      "created_at": "29/2/2024 8:43malam",
      "pertanyaan": "selamat malam komandan,mohon ijin untuk lemburan/(BKO) saya di tanggal 15-februari-2024 tidak dibayarkan,untuk bukti terlampir fhoto, terimakasih.",
      "attachment": "Screenshot_2024-02-29-20-33-26-90.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/ror44xKiItc7Vsfx_qmZgQ/1G7NbDX701YAy-AaCRfPrjOoWYWmljFkXeESqNvjRjqfmP6PEsgEFiIRZ0Op8AY3kQZQGuoLiDOdVHdglU5ldq246_JRJHObjR7-n0BAip_he1mnXNz_p4F-UP2ynVCofZ7xqg5Yd03NLTyod__dg1z9VarGB2Z416gUJU8EU1Apy3LfmqKqUjkcm_NjMe4E/AurCVjygGqOlqQPUM9tmnDO-lFx2QaIU9xIIIK2Fdmo),Screenshot_2024-02-29-17-04-02-18.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/U6qkj_3LvvE38jENaXs-Tw/nZtye_APFcOG-nmxYNNr6qYNTrEUV_UtVTbQH9Z3WRx604EhtTrOrs7I7DPwTVGZ47NtCU5MJok7xX9eVbKtFLE4k-pt6PgjFwomecV9SoKKBGhbuqB4gAqn1JO0sWQWVCkw-YpWgH3oYae0C3JWQLxbgWkC1VXUoYUFPS0aPvN_KochS1ad6FwoH-7BUrRG/kZue1sEYCGFOIAEb9vl11m9FrYW2k7KeYKnVohF3Xsw),Screenshot_2024-02-29-20-33-02-54.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/c8nrwTQ0nMA61pPOhMc1-Q/tHDXAHsPstEi3urHl3Ls9MvtiTNNy_XG9a3vlh21J37OeQJ7wP9sqzdZGHN7QHHl-kSzoKb4EZICjx5p7W4-qB9jmFhmt6oeiNkvkLW7Ir5Wv9FZUS0KNecRk18yv4h3iBiWudpWBXM6vmnXO5Bh6oAr-l6-BQE0Ks2wqm07ElB4FOXAMa86OdWukH2rgyxB/VPO5JpU70NuDHkukWGA2CcSLCjUq6DU5ya0aai2m88I)",
      "status": "Todo"
    },
    {
      "user_id": "MULYANA",
      "project_id": "PT SSY",
      "created_at": "1/3/2024 7:49pagi",
      "pertanyaan": "Saya ngga masuk 4 hari nerima gajih 2,6 juta sisanya kemana ya ?",
      "status": "Todo"
    },
    {
      "user_id": "Mebi perta",
      "project_id": "Pt surya shueen yueh",
      "created_at": "28/3/2024 5:35sore",
      "pertanyaan": "Selamat sore pak..mau konfirmasi mslah gaji saya pak..saya masuk kan dri tgl 6 maret + beckup 2 hari..tpi gaji saya 2,3 jt \r\nSedangkan punya rekan saya atas nama brian ardani mapeley gajinya 2,4..pdahal duluan saya masuk kerja..tolong di cek ulang pak",
      "status": "Todo"
    },
    {
      "user_id": "Bagus Sanjaya ",
      "project_id": "IPEKA PLUIT ",
      "created_at": "28/3/2024 5:34sore",
      "pertanyaan": "Pak Andre saya bagus sanjaya back upan atas nama riyan Rivano belom masuk di slip gaji",
      "status": "Todo"
    },
    {
      "user_id": "Septian Dwi Yulianto",
      "project_id": "IPEKA PLUIT",
      "created_at": "28/3/2024 5:59sore",
      "pertanyaan": "Data URC tidak masuk 2 kali ",
      "status": "Todo"
    },
    {
      "user_id": "Rachmat Hidayat",
      "project_id": "IPEKA Pluit",
      "created_at": "28/3/2024 6:53sore",
      "status": "Todo"
    },
    {
      "user_id": "OGAN SAYOGI SAIPUR RAHMAT ",
      "project_id": "PT.surya shuenn yueh industry ",
      "created_at": "1/4/2024 6:29sore",
      "pertanyaan": "Hallo bapak2 dan ibu2 ,,mohon maaf ya sebelumnya katanya lemburan saya yg di tanggal 15-february-2024 itu akan di rafel dan dibayar kan untuk gajian bulan ini,, tapi nyatanya tidak terbayarkan juga ,,, backup an ( LEMBUR ) saya dari tanggal 21-february-2024 sampai dengan tanggal 20-maret-2024 itu itu ada 2 , seharusnya 3 dong sama rafelan saya yg ditanggal gajian 29 - February - 2024 tidak dibayarkan.. terimakasih dan tolong di tanggapi ya,,. Mohon maaf kalo kata2 saya kurang berkenan dan kurang di mengerti.",
      "attachment": "Screenshot_2024-04-01-18-27-59-22.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/wQa9CMg4Rw6X9O72u6YRpA/IYCHHr2ZaFKPkjbS1AyY5-zZPJvZFnjT-In37iUHFMgHqYcsQx2lgNO8EqEZUu-ynCwyKwzxTCFKxaKXSUkp9mXW4RmJjYCzPPH7PK7N5Q2bC9ZiS-TKAOzBjFX25k6XNmLIcoW9ITl4KbhCkiQ-dascqieIYoOPqIa3nGH4mMWhuT3VOWfYxd8qQwBMh3tH/fE93Jv0_NGQeY4RXwBRcD6z6-k9FecOq8fTODCp_fJE)",
      "status": "Todo"
    },
    {
      "user_id": "Urip iskandar",
      "project_id": "PT ssa kedaung",
      "created_at": "3/4/2024 5:36sore",
      "pertanyaan": "Selamat sore komandan izin perihal uang THR saya kok dptnya 3,562.435 sedangkan saya pindah projek dr Ipeka Pluit ke ssa Kedaung mohon pencerahannya ",
      "attachment": "Screenshot_20240403_173821.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/TfVAZFtkumYjprQ-uJjsBw/pIx-AUTVE4-iRAhxzqfBgnukVkRZ209cRPkSw7CaJsEGAVJmxJYfz4_VjuhG6m6hUIA7jX058RsazQjYvdmoAiFtZnvwIWNwARs7cCh8nI9t-9Ubq0BlmaWm5aKFkCemrAtgNgBpfeV7bvoCUZrvCSt2t28xc5tm1r8eAvaQLitl5baJ2RZZm3X0QABBk4PS/6Uf3ZJm47W1sEoM3lxOFsgmjB2ZfAzt73FyIzU86RDc)",
      "status": "Todo"
    },
    {
      "user_id": "Abdullah",
      "project_id": "PT ssa Kedaung ",
      "created_at": "3/4/2024 6:01sore",
      "pertanyaan": "Ijin komandan untuk perihan THR taun ini saya hanya dapat 3,3jt sedang THR di tahun lalu saya dapat 3,8 JT tampa ada potongan apapun komandan mohon penjelasannya komandan ",
      "attachment": "Screenshot_20240403_180338.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/8QDKYT4z-6gjJuh9TRyLrg/iaapE-peBkJTxL_ZAVCxox88esKBELA_Hn3zUb0PRKt2efiKu_1_mxHEuY8lLAc6DybpFVNK6y88B_rm9GelM90RbCM3rChwpg0-19odZFIf7JN2Hud_p6Gf6Rvks0DEFV9uaZw2u8alpSnTZz6uZLk3MO2LSdwGTYxanpSB1yPPuACgrpTMcen5EEbVf8e2/rDdKj1i7ds0mubE0aAibegu_ZzXy7TWyn_AOoHMwy10)",
      "status": "Todo"
    },
    {
      "user_id": "Urip Sapikri ",
      "project_id": "PT SYNERGY ",
      "created_at": "3/4/2024 6:05sore",
      "pertanyaan": "Mohon izin komandan,\r\nPerihal THR tahun ini beda dengan tahun sebelumnya, sya terima 3,3 juta tahun sebelumnya 3,9 juta, mohon penjelasannya, terimakasih ",
      "status": "Todo"
    },
    {
      "user_id": "Yonda Yoandana ",
      "project_id": "PT SYNERGI MULTI DAYA PRATAMA ",
      "created_at": "23/6/1996 6:00sore",
      "pertanyaan": "Perihal THR untuk tahun ini berbeda dengan THR tahun tahun sebelumnya, yang biasanya kita terima full 4,1 tanpa ada potongan ini menjadi 3,6. Mohon untuk di tindak lanjuti dan kenapa harus di potong THR padahal kan seharusnya utuh",
      "attachment": "Screenshot_2024-04-03-18-06-29-03_e8205ec1cda7e9657fadf502be9b7b78.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/PhDxDTvjcEfm7GzRAFMaqw/q24RZfJVpGebJ8BtxYU4yI7u02lo8h32YpeInDvSnZd_6Mz8AfhNReCgur6KKK1FSNTFZwV5ME-bMVznSKGyEegrsVRxG3nanMWgB4jxaVSyvfIXj7ya_6nVR8GJFOMfXA7eCo0O6NA-K8UiZLX7T0LO-yO5Oj7HUU41-B8g_NooxE1p9rde_0oHGMkkHcqDpaCl3N2zrbQswB3wYaCzSa1QhM0FLxXSSB4DvU0TMf4/pNrgA62ifdZAJPlWBftbzaXTC2NpiY8oWAx3PgSl9Lk)",
      "status": "Todo"
    },
    {
      "user_id": "Wahyu Hidayat",
      "project_id": "Klinik Gigi BSD Dental Centre",
      "created_at": "3/4/2024 6:19sore",
      "pertanyaan": "Selamat malam Komandan,izin perihal THR sya ko tahun ini berbeda kaga kaya lebaran tahun lalu, lebaran tahun ini THR sya 3,5 sedangkan lebaran tahun lalu sya Nerima full 4,2 Ndan ,izin petunjuk ",
      "attachment": "IMG20240403173630.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/wcntNrSJE57oH7LixKnDyw/J3XC7kpAVHMq2PPCo5_x5Fh0l5FYkguLlJeDdpo8uibylqlKMVUN8R7llewPDMy6Bmp9Plg7SgnfHQ38S2vT8jYKw0VpnBsDkiisUxijsDZGnsmeYwSXsfgD2iSvoNGr0XLYzSmNf-FihuqPSvkdpGKgS0VriOIwbOv2D8UTLck/TJ_5dp_a_hIXAj8_KY9JGPUxRD7_1xvzty3i4TnAIbs)",
      "status": "Todo"
    },
    {
      "user_id": "Bagus sanjaya",
      "project_id": "IPEKA pluit",
      "created_at": "3/4/2024 7:02malam",
      "pertanyaan": "Selamat malam komandan . Saya bagus dari IPEKA Pluit saya kan masuk Dibulan Oktober 2023 tapi ko itunganya cmn 5 bln ya ndan",
      "status": "Todo"
    },
    {
      "user_id": "Rendi Gustiantoro",
      "project_id": "Swissotel Jakarta PIK AVENUE",
      "created_at": "3/4/2024 7:18malam",
      "pertanyaan": "Izin pak andre tahun ini kenapa THR gak kaya tahun kemarin yah hitungan perbulan nya, just info aja ya pak tanggal 20 februari 2024 kemarin saya sudah menjadi Danru, otomatis salary nya jelas berbeda ya pak",
      "status": "Todo"
    },
    {
      "user_id": "Teguh Apriyanto",
      "project_id": "BDN KUMBANG",
      "created_at": "3/4/2024 9:00malam",
      "pertanyaan": "Untuk Bonus tunjangan kehadiran dari BDN kumbang belum diterima Senilai 250.000\r\ndan saya sudah resign ingin mencairkan uang potongan rutin diksar kabarnya tidak bisa dicairkan. mohon bantuannya",
      "status": "Todo"
    },
    {
      "user_id": "Ahyar Maulidin",
      "project_id": "PT SINAR SURYA ALUMINDO",
      "created_at": "18/7/1997 9:12malam",
      "pertanyaan": "Untuk THR saya tidak Full yang biasanya 3,8 dan sekarang hanya 3,3 mohon dibantu untuk penyelesaian masalahnya. Terima kasih ðŸ™",
      "status": "Todo"
    },
    {
      "user_id": "Abdillah",
      "project_id": "Mandarin oriental",
      "created_at": "3/4/2024 12:00pagi",
      "pertanyaan": "Saya menerima THR 5.217.381 ini THR saya yg di project PT. Target prima lestari dan saya masuk Mandarin Oriental terhitung 2 bulan harus nya lebih dari 5.217.381 THR yg saya terima.. Ijin komandan taruna lanjutnya...? ",
      "attachment": "Screenshot_20240403-222108.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/u4pFTbKY0u3QciX_lQto5A/Q0JVk4Lqv8JRSvuJIUNzW-zZlBIrzWtTCNKYXL0NcAZYZ4ZzDtM5Wg5HU8BmOBnb16JQocPT1ouCk9H4YTFE-M5Lc_PQIp7M7AF3_83UNx3ymYos2fLEWTyxY3S9-K1Dyq8oRlg8R_EMu-F9vyjmNH3ZuScJ1Bljf91NiO7JFy3epblm2kYwWZMZMUxNm1Kw/C73syPh7BQnBdcQU1HBd5kRQ3sfa_N4m2zhcwPl3AOc)",
      "status": "Todo"
    },
    {
      "user_id": "Rika Septiana ",
      "project_id": "Mandarin Oriental Jakarta ",
      "created_at": "4/4/2024 3:55pagi",
      "pertanyaan": "Pagi pak Andre ijin bertanya untuk dislip thr yang turun sejumlah Rp. 5.917.381, tatepi yang masuk ke rekening hanya Rp. 5.717.381 pak.\r\nMohon dibantu penjelasannya pak Andre, terimakasih pak andre:)",
      "attachment": "Screenshot_2024-04-04-03-51-52-27_c37d74246d9c81aa0bb824b57eaf7062.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/cWZyDXmzD46aZj1dPiQ3PQ/LSGOvpWPqgMMYyH54UO2xF93A5hQ5bUgGzrkqZMuSXJieYhr0g3p-Bi-TTodt9Okrw_23ZXWgVUCq70sgRqg_q9BAyu7x_c-obbosRTkeahn9R432fwk-XKqBhAEINQaA-xYgZc3kfsvPEzjza67Tfi5KRxDtXipfcnl1rnMIBOoSYzg6k0BNiiWMQv8kmmb2zRQi3SWs0yZ6DQdslnv_IsjDB1Brn4b2cPaDGo5HOg/kriiOrXCXIPS2kQq0xpbiP16BNalHlKL8bMu_woJe9Q),IMG_20240404_035926.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/2TXPrXpKZGx181Iwcv95Vw/aa0mnYlxGpk6MLvbpSZQGETp3fulpJ50bb1J954xtZvwPw50vIkWfvd3PKfdBxICi_KiJ7ZVhSv8UF3_yJnWzRO4x20vMcqgPEQNNd3pGcfb5Wyw_u0KdfRTc8O7VbaXKPSKlRV1iNODhDI5XRYbLs2o17RdPgB5LPLx4v_jk-A/D6jyXTYh_zc4q3YD99KlUpMRxhljwKyEFIESeqdOx1A)",
      "status": "Todo"
    },
    {
      "user_id": "Abdullah ",
      "project_id": "PT.SINAR SURYA ALUMINDO ",
      "created_at": "4/4/2024 8:24pagi",
      "pertanyaan": "UntukTHR saya di tahun ini hanya mendapatkan 3,3jt\r\nSedangkan di tahun kemaren saya mendap 3,8jt tampa ada potongan apa \r\nTHR 1 bulan gajih UMR di tahun 2018  3,8jt Komandan,Mohon penjelasan nya ",
      "attachment": "Screenshot_20240403_173407_Drive.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/P0OoAlgIM8AmI0PeutE15g/wHTaweg5go2pmGWfqHIsliGLYl2n8QYOZpKbjMRiYmirh_4pQrqag4HUHt4c6VK61X1-U_EQ8jnRacn7Umkhb7CQB7lHSAt60n0yvJ4QZm7TT3cAj1TzlxQWbGk_7M4vJ3axE2GV5FvOkg7vM012Afd_ah5Ut-1SJLBvE1G94RTd4ht3ztIjT-JZ7PRJUxbt/kpTMuBNpF4ti3bKsZns44eLo4NoqqxTOgttKWfqJ0jE)",
      "status": "Todo"
    },
    {
      "user_id": "Arif Muntoha",
      "project_id": "PT. Zega Kris Gemilang",
      "created_at": "2/7/1998 5:23sore",
      "pertanyaan": "Mohon izin komandan , untuk THR tahun sekarang berbeda dengan tahun yang lalu saya terima .\r\nTahun ini saya terima 3,3\r\nTahun lalu saya terima 3,9",
      "attachment": "Screenshot_2024-04-04-17-22-07-28_c37d74246d9c81aa0bb824b57eaf7062.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/V0a_CMymlcHtfZPUJXI9GQ/yKV46nIZKObPw2B5cMD0p-Xdelkd6rTDjMRBA0wpq7N23sugLy_gSEgOX-9h9fDsKoyLsYNChlyYsQGMLHzy9BRcFZqkvVrpkReiYjYrJE771II9DAtSwB50oR7xGaLDiXhsnQgCWCgG-71OIpo6iBO0FgfLY5wpcKDRZkaUhGJoQkYRnspjh6ZuJL_brE8qStcTh4sNYUEmUa356BBBxalkd2rES7YvQEQIeVB0MKo/uzmj7Zlq4VrUjDNlL707xMUKOLvtoTGg42NvkZkX3Cc)",
      "status": "Todo"
    },
    {
      "user_id": "Muhammad Ryan Rostiawan ",
      "project_id": "Fave Bandara",
      "created_at": "5/4/2024 9:32pagi",
      "pertanyaan": "Dari 2021 sampai 2023 THR yang di kasih sama ibu Ike 4.2jt , tetapi kenapa 2024 saya terima cuma 3.7 ? , Bisa di jelaskan?",
      "status": "Todo"
    },
    {
      "user_id": "Jaka sentanu abdullah",
      "project_id": "Posto dormire hotel",
      "created_at": "5/4/2024 9:36pagi",
      "pertanyaan": "Kekurangan THR",
      "status": "Todo"
    },
    {
      "user_id": "Christian",
      "project_id": "Mercure cikini",
      "created_at": "5/4/2024 11:13siang",
      "pertanyaan": "Ada perbedaan penerimaan THR tahun kemarin dengan tahun sekarang\r\nSaya hanya ingin penjelasan nya saja kenapa bisa berbeda seperti itu\r\nKarna dengan perbedaan yang menurut saya lumayan itu sangat berharga ",
      "status": "Todo"
    },
    {
      "user_id": "MUHAMAD RENDI GUNAWAN ",
      "project_id": "Mercure hotel Cikini ",
      "created_at": "5/4/2024 11:17siang",
      "pertanyaan": "Selamat siang mohon izin saya a.n Rendi projects Mercure hotel Cikini izin bertanya perihal THR tahun ini 2024 hanya mendapatkan 4.3 padahal tahun lalu saya mendapat THR sebesar 4.6 terimakasih mohon tindak lanjut pimpinan ",
      "status": "Todo"
    },
    {
      "user_id": "Yunan Prasetya",
      "project_id": "Mercure cikini",
      "created_at": "5/4/2024 12:02siang",
      "pertanyaan": "Menerima THR tidak sesuai",
      "status": "Todo"
    },
    {
      "user_id": "Saiful Anwar",
      "project_id": "Mercure Cikini",
      "created_at": "5/4/2024 2:36siang",
      "pertanyaan": "Selamat siang\r\nUntuk THR tahun ini kenapa jadi nya Rp 4.117.000\r\nYang sebelumnya ditahun lalu itu anggota mendapatkan Rp 4.650.000\r\nTerima kasih",
      "status": "Todo"
    },
    {
      "user_id": "KARIS",
      "project_id": "Mercure Cikini",
      "created_at": "4/7/1999 12:06pagi",
      "pertanyaan": "THR tahun ini tidak dibayarken dengan Full Kurang 500 rbu seharusnya mendapatkan 4.800.000 tapi dibayarkan hanya 4.300.000",
      "status": "Todo"
    },
    {
      "user_id": "Ibnu Mubarok",
      "project_id": "PT Bunga Karya Asia",
      "created_at": "30/4/2024 9:42malam",
      "pertanyaan": "Hitungan naik jaga di hari raya Idhul Fitri seperti apa?",
      "status": "Todo"
    },
    {
      "user_id": "Ripan Supartiana",
      "project_id": "PT Sarana Bhakti Timur",
      "created_at": "30/4/2024 10:34malam",
      "pertanyaan": "Izin bertanya tentang potongan abseni saya di bulan April, mohon untuk si jelaskan detail",
      "attachment": "Screenshot_20240430_222958.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/NxEPugdx-rm9pOPcQg4-Tw/gSCSQMRdpsxA18fECvCgxaIkBtTZchuQkXN_3fVsk-jRESxe8Mo6t5xdAUgxRPOZBH7Xxtrt5kxj5eMkZslBoqWQUlCPlRXS945kvDuhwgeuesvR6zO-t8MgeDOR8CoBGexnR8IXPxkIy9-ZeG5Ey93_bfxVCc6IbxPPjajxBc4kfKOuiBWAKNBV3kTP-wct/iduVpkZIKOfShjrfV_gdAPTowTefwCGF0ZLDMHKYS2E)",
      "status": "Todo"
    },
    {
      "user_id": "Fernando yusuf alviri",
      "project_id": "GOFFEE COFFEE STORE",
      "created_at": "30/4/2024 10:57malam",
      "pertanyaan": "Slip gaji saya blm liris pak",
      "status": "Todo"
    },
    {
      "user_id": "Silvia Agustin ",
      "project_id": "Mandarin oriental Jakarta ",
      "created_at": "30/4/2024 11:03malam",
      "pertanyaan": "Selamat Malam Pak Andre mohon izin bertanya pak,Kan saya di tgl 9april 2024 masuk Malam Backup lembur  Irsan dan di Tgl 13 14 April  saya diback up sama oleh Kiki dan Deni,otomatis gaji saya kepotong karna 2 hari gak masuk,nah tapi kan saya ada Backup lembur di tgl 9 itu otomatis itu hanya saya harusnya kepotong cuma 1 kali aja pak karna yang 1nya ketutup sama lemburan saya tapi ini pas saya liat di slip gaji saya potonganya 2 yg dimana nominalnya 500rb,mohon izin pak Andre boleh di bantu croscek lagi atau boleh di jelaskan pak Andre \r\n\r\nTerimakasih pak Andre sebelumnya ðŸ˜Š",
      "attachment": "Screenshot_2024-04-30-23-12-41-173_cn.wps.moffice_eng.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/9BOq3THbWHNUjaESLN9Y2Q/YiW6_fT9uOSa2sKOM95l2dJfeS3wYL9Fx-Yw-n2eR2i8BYz7YcBneg5JmvbdV2RSUTFRoL4NZqI4PR8orJYkKLG1rOxeHAHNz9K1TYDRFnMauWrL0gybraYQXbAanwJJZC1o3e3BiQcOb-sHm3QvIb6yHB91V8YDo60BySkyIW1_HGtwKnlQ-cqbgIRAcKKexef_wXrzNqgsGXuEJelW-w/SfOnotp_pkI6-9klRnb9quicb1HZFtrPQmCyOuMkmRM)",
      "status": "Todo"
    },
    {
      "user_id": "Rajudin",
      "project_id": "CHAMPOIL",
      "created_at": "1/5/2024 5:50pagi",
      "pertanyaan": "Selamat pagi pak. Izin pak mau tnya knapa 4 hari lembur saya di champoil cuman dapat 400.sedangkan gaji sya perhari kan 187.000\r\n Tolong dibantu penjelasannya pak gmna pak? ",
      "attachment": "Screenshot_2024-05-01-05-53-39-84.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/DLs-D54CAwhZ9sPUdylNaQ/K5ZwTQ-FhJpXwdsHOrSbWsKcoSIgXDezBW_kicrVKIAUTQCwH-woFRg6OYVmHSAxLUxgBh0pWfsgFqmU5SVD4aPWc12cLsyXXZSnwxeRMOt0XNKXJdNsT8Mwu5MaiFLyB4F5P5pLaiHK9UKz3_XZjRsOdo5V2QVpR90IuyAgo9-7yPRJlAlkj7qH3guv55FR/blrWLN1v1yUwjZLXzwMid48ToZN7HirOxSk4riN5JCY)",
      "status": "Todo"
    },
    {
      "user_id": "Ibnu Mubarok",
      "project_id": "PT Bunga Karya Asia",
      "created_at": "1/5/2024 7:07pagi",
      "pertanyaan": "Perihal payment naik jaga di hari Raya Idhul Fitri, hitungannya bagaimana?",
      "status": "Todo"
    },
    {
      "user_id": "Amarulloh ",
      "project_id": "Hotel Mandarin ",
      "created_at": "1/5/2024 9:42pagi",
      "pertanyaan": "Selamat pagi bapak izin bertanya untuk potongan GP saya sudah selesai, kenapa bulan April 2024 masih ada potongan GP,  adapun buktinya ",
      "attachment": "complenpotongan gp.pdf (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/mEcciDU68QKFsJr-PKkecw/LZxgr6IQmHBWNX2qzXP1Qsn8pwqafGu5pHkG9FUEakmEqEhF1rDNmxmfD0sXP_aDRNuvoVT6CKMBe3vyOxj2gollawXfwt0rAVGpsI2P8LcnT1zlCf4YMc3SWScHG9e7qSxSJk0cplBnL8XUfahEU6slf2n4PNZl-4pltomvTo4/V6N05Osk9cwOSXnT6SwZ_pSoP27NpMPb7pmqIWkpL6c)",
      "status": "Todo"
    },
    {
      "user_id": "Hengki agung prastyo",
      "project_id": "Hotel mandarin oriental",
      "created_at": "27/6/1998 9:56pagi",
      "pertanyaan": "Gaji kurang ,saya masuk dr tgl 12 april 13&14 off, 15-18 masuk jadi total 5 hari kerja. gaji masuk 1.069.345 kurang 180.655",
      "attachment": "SG-Januari 2023-HENGKI AGUNG PRASTYO.pdf (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/S4HmzUoLUOaqmRcjzHXMhQ/N7p8JIgSzVzJOht-Kwtdh4JMh3VTpS8OQyR3m5FFmCIza_1tFGqwD2w8HAtaUnhpDvNUyAnsqznzWoerGjZe8z-a71AiigA1ZWigvCGAnI3qHYED_rUwlCCnWOoEwHZN56UobfFB_MRBsFYgIu83mSXN800j0tBXGkeOGaUx1vkMrNPFf7L6PR36ZYrslMVi/VqHQ_V9VZGGvCJVRREqF5nhvE_1DwDdEw2uthbcCi5Y)",
      "status": "Todo"
    },
    {
      "user_id": "KARIS",
      "project_id": "MERCURE CIKINI",
      "created_at": "4/7/1999 1:06siang",
      "pertanyaan": "Sisa THR saya belum dibayarkan oleh CG anggota dan danru yg lain sudah dibayarkan tinggal saya sendiri yg belum dibayarkanðŸ˜¥",
      "status": "Todo"
    },
    {
      "user_id": "Mohamad Arief ashar",
      "project_id": "Mercure jakarta cikini ",
      "created_at": "1/5/2024 1:48siang",
      "pertanyaan": "Saya seragam sudah lunas . Tapi masih di potong 2 bulan ini .",
      "status": "Todo"
    },
    {
      "user_id": "Amarulloh ",
      "project_id": "Hotel Mandarin ",
      "created_at": "1/5/2024 2:53siang",
      "pertanyaan": "Selamat sore bapak izin bertanya saya mengenai potongan GP, potongan GP saya udah lunas pada bulan Maret 2024, kenapa masih kena potongan juga ya pak, ini saya lampirkan slip gajih potongan GP dan juga saya pernah bayar langsung 700 selama dua bulan ini saya lampirkan sebagai berikut ",
      "attachment": "Screenshot_20240501-082758.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/IhOEUHO8k9ZeMgolnWZR3w/4fbnNyqybJ4VdknaVIJDOhKGGVKLiX3hJME2yHLainOFIUL0OETXGETlZiT3h4WCOaAxLjHVJICMiusd67KLpQrRDYoxMEOMhva5xTq9LMjiKo-Wp4PhEX5SErYvtOtG3J15kVNq4Grvt3AXEpCDaojjFflfPDgjjq0bOAfEpy8mG0OPa2liaEwcDcMOLF4S/8GkWUTfQYXPELg2g9Gp0WaSz0ztKw75Ospn4q5wxVzM),Screenshot_20240501-083104.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/6BEt6FmU1kYVoUIkVj7GOQ/WDEJA56V7Gu0Bu541zaQMBm5nXXusmVGb-AtlJXQNc0v6rfRpopRksIEsdi7fr-oStqVXcnjn-_HiHTYmUiQcq90gGLZEtqlzV2TTPGADE2hWP-R1fFpuwo_HP5fGb0jk3rL_TqqGA3aPLUviNIVRYN9I0KX3DErarKFDPiZu-5SPnVJrSaeVas6Ri3yyE1f/Q5J8P43ls98nvJuEaD81NE-658U9rcUULMiKn3E_EEQ),Screenshot_20240501-083205.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/nLDW9PYtZsAEECJz209vbQ/xYx6e_26RuM-1-GFUTdH30vJECg-FAnJtnqae05CRmgcAiWV3HFPms4ZfIGjK5TmoHb3Fub_txeTzsSMezUo4N04RIl64w5I8-k5IWDdbBBs1wTUgJRL4cinrPOXkWdz8Eb7ohLgQTk2ANLH8zUvECyKX9gtwFVliOa362Nin28qC-EfeqzavCF8kH33-sC1/HHVPYJxskNq3owAaKru87RM-USuqbowhihkCcO5X_4o),Screenshot_20240501-083136.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/0zqtH3nDQuVZXXX8CBbPZA/o6D1RpZQAjmwb0lKEH7nCiBLM6ZlUSnDNX0egjHkEjBNMaVeeephxzAUsYVE8jermtpCqH40oeZj6N0QucV2wmJc088mF4BBGKg6WQFLYC2xtZIgfNIG_DqR5gNof6pmXWtOLQoRYAk6b-TUTyDPdEXanrAAUsaSIV_y4Ln-sZPktu7Px_3_CWVPHtcKfeEi/Q4pPgdIPMug21Yeq0hDtlHduanM8h6SL1olYiVx9-8E),Screenshot_20240501-083257.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/3SeJ9R4WiTuUGdMd7XdH3g/26HvHbiKk-2iLjfEgReyiqKtTJQjlofGHwo64FhgVfMEY9aesglcnkhgkSVxXzi99krr0mXNENgP0Pi1fv2W2z6SH6lbq8JnzK8MGPpKqKsZplHga-vl0LGJpMvqAp0y_RChUV1LUNIsgkrPDz0ROWyHtPoreedduEODq_Pgn2kemLBRODfmCogILarlVo2m/gL8NtJK8wuoCiogpFdlARV-AV2HXwRJDrlzsgwjwkyY),Screenshot_20240501-083328.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/ic6urBJKk1WFK6CtkIXwYQ/luTA-RYJgQ4VO4OPMNZnfH220JgzofM_iISRNNNgckUb9OVT6AHm2Kht0gpDpRcx7MOFB4qkeATgCsu0W7NOblJ0wYrHCn_DmirhZzbuxRYqmv0N-DUOTvTn9NqFrqxprgqTGQCgqPER4bcCWrjuMAiqSDoY4ZQ20WCAs9SCgf0YcgQDg1AQ9CXycWZ0ibzk/ksfq9Pfe0eMT7_m3oVljuu5OCllYStSHeu5D0PTxJ84),Screenshot_20240501-083437.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/JYB2oH7Goww9MNE7o0ubbQ/AkmtDBLXQYD_tps6PccWOvXZYbzZGsL_NbUidRhb8LSyodJZBwi9c7WQedpQ48yD28r2c92RqS8GB0yu7bpxLp5gXDVLAfHEW8ZfQWmSK1T8bU6G8uhjViC4xz-XCxxYKmatHaVytVxjnqmC8SdxOs4a89MOINZx2Fw90edG-p8h1SOHfhck4KlXwru6LOBJ/Azv6jOGeobt_Iv1G_LmaCQNBZx3JGftzczv0FNvZX_s),Screenshot_20240501-083509.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/0bUaYdYrNT9oSF6T9L9h_A/Vh0_UL8h65KWuMgSgIVbtYD-uUyoH50CRG6g__MWSkjC8JxPmPOL_v3uExHol5Iw864T7xLfAlcPIzh6xwPO1bVKX3rJLXctiscCHT-jttSfxSwKWY2sPW78QSDh92zRitqcJglGkt93QoKnSXEeKUaU9a0wewK92M4jqWhj3f1Kp8S4YKWx6BHPHGQRHEP1/9V9CwogCxY8h89mvK-4vlzfLY_g85aMArFyDksZHuG4),Screenshot_20240501-085423.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/DAFabIV7s39HCzFZ2Ck-dQ/AC0JIOBJtSDxh7zrYGYCM_gGXZLPsqhZXVPn_MDHd9AfsdxBWc6t482A9MhFsi2-o_zJC_oGMhnryKdNjDBD3Nq8-N5a961LZJvtnhwqzpchxlOad5331sPLSUlT_Edn5fvsx_PgXy2jhwJMjSmin-YQdmwhDgFZy85t1ZWrl5UXH-MeZKyGferODrbdUhmg/dEDkiwp_-7Jr6HjvkUNnDSkhOSbhObo4xKZCa3KcbAc)",
      "status": "Todo"
    },
    {
      "user_id": "Abdillah",
      "project_id": "Mandarin oriental",
      "created_at": "8/5/2024 1:00pagi",
      "pertanyaan": "Selamat malam komandan ijin saya bertanya komandan terkait FWD, saya berobat nombok 110.000 itu bisa di klam atau tidak saya sudah mengajukan klamaan di aplikasi FWD nya namun di tolak terus. sampai sekarang blm ada penjelasan nya juga ijin taruna lanjutnya komandan demikian. ",
      "attachment": "Screenshot_20240508-231139.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/BQEwa0wxOUzSv4mNLoPUaw/N1zUZ8759STjtcVRaCKgVTTHNNUh5YAbV3IMC8xd5MZzxAJDHhzh4aw9-8zwysGg8aEPLXIUaFFXyAF8iFm15seSI8xVe-5fqPZVaKbJlMmr9QEUsRHnRcFYuXCy6ksXWUeF0yt8vBMi5ebwjAGngtKIzK-lCdram739QwrquIT_UlsZCvO68skpjZfj1Yxe/bjPf_LZAeyA7CZNjQTlhIUiS8zMMnZBqw5tYFiPVZvw)",
      "status": "Todo"
    },
    {
      "user_id": "KARIS",
      "project_id": "MERCURE CIKINI",
      "created_at": "4/7/1999 9:04pagi",
      "pertanyaan": "Sisa THR saya belum dibayarkan oleh CG",
      "status": "Todo"
    },
    {
      "user_id": "Syahrul Ramadhan ",
      "project_id": "Ibis styles sunter",
      "created_at": "31/5/2024 6:10sore",
      "pertanyaan": "Gamasuk GP di karenakan di hotel gada orang , dan 1 project yang GP 5 orang. Udh info ke chief dan chief sudah info ke CG . Masih kena potongan juga?",
      "status": "Todo"
    },
    {
      "user_id": "Efrisa Intan Agustina",
      "project_id": "Mandarin Oriental Jakartai",
      "created_at": "31/5/2024 6:17sore",
      "pertanyaan": "Lembur Merry sakit tanggal 01 April 2024 (Lembur Malam)",
      "attachment": "Screenshot_20240531_181858.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/9rMEe9vIrudn3Yr994Tdjg/toV_WhBgjwO5jSkFPnihduiW_ZotFhUlJ37iDEvJl9M7PvN9h8F4ea0eGcOM4vrTgXIZT_Gl-LcUWi-HtV_hHxeezY4gWRdddLOJqhnzuX4_FVgJU-19_m_3mbOY0W0QMjZmYwO2Ifrmw13EP9Dp4n-wXgzhCx3NY6wMOtI3Cq3numuG6VuXaNqRoBoxKSqT/NVG3kB8lxQ1DfibWYHVD8_JmOUtLL9CRdXY3BdxwKOM)",
      "status": "Todo"
    },
    {
      "user_id": "Prengki Radiansyah",
      "project_id": "Swissotel Jakarta PiK Avanue",
      "created_at": "31/5/2024 6:22sore",
      "pertanyaan": "Perihal potongan Diksar Kenapa ada potongan ya Kan status saya udah Diksar dan sudah punya sertifikat Gada pratama",
      "attachment": "Screenshot_2024-05-31-18-21-56-40_e2d5b3f32b79de1d45acd1fad96fbb0f.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/JhAkiutufn57aN925PQGlA/47ZyhvCUWHyZVvo5YFfp_VRd4NlkITBFTY0pQAlQukQsueZOHmE2kiRbZC1BHPVM31G-nWoKdEUV1LnhaB81sRx2HK8yf5Chm4dnLVKKyv-5sphWWzCjOBh_slCBPCgbQFH9VaAKWMPV4eIyPB8lCCfbiifhxPPwwmHaM3FEMIOy2xrSeua6K17QB2z5mFOJJJ1kr26GJX6ttR8rklkdEor5RnXXzOasI2JByAeCFsI/BFjThnLpniEcZeBxy_hPKFmBQDdLtdhdNv8xQFV643E)",
      "status": "Todo"
    },
    {
      "user_id": "Eggy Rudistira",
      "project_id": "PT SINAR SURYA ALUMUNINDO KEDAUNG",
      "created_at": "31/5/2024 9:02malam",
      "pertanyaan": "izin komandan bertanya perihal gajih saya kok masih 3.3 jta untuk gajih pokok saya komandan.saya untuk jabatan kan sudah jadi danru bukan anggota lagi komandan.apakah sama gajih danru dengan anggota di SSA komandan? dan satu lagi komandan izin untuk per tanggal 15-20 april saya sudah jadi danru komandan untuk itu ada beberapa hari kekurangan untuk penggajihan.mohon untuk dapat di urus komandan.terima kasih",
      "attachment": "SG-Mei 2024-EGGY RUSDISTIRA.pdf (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/qD_VwHfnodo5lnrx8laBcA/4TdxTsXuYJtZ2dggMzjFWi3RFKWMrM0OlypdtZd7PO47CmYEbPzGAAoxSQ1EgInXvXgHmfOwwYxtz9_ysorvjkHbNJBbevML79c6WWCKoJX0KDFeMRj3z3VjELGUstFNq0YUxnlzOx9iavCJtRKPGTTQKnCogY_BDCfpRS3otAebWRWGFWbYItvuIuv0322R/tIwB2J5M_UDvT6JL0S4ySK9Ptt959vbkHaLzVc-lV3M),absen cg SSA (1).xlsx (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/vAXWZFFzkJ6ap1cp9hEojQ/qGZPouYjDGbfjrh9yKSExVefqPaETvxCV2cHv4JKqXyfGj96HslcXyqczbo4odCnTyfBwzLLssqjuZS1JBDMvAlDX-sKajYI6ZvHbFA3zP95ujZWMHG8m-Y2liAVUZQHsRJ1snYnYr_st9P17eN27w7S72kRLI-JiFP2ICZnsA8/Kl7civQtom8wqrjF902nbEmT1M9gp40WTsimmCU31_4)",
      "status": "Todo"
    },
    {
      "user_id": "Prengki Radiansyah",
      "project_id": "Swissotel Jakarta PiK Avanue ",
      "created_at": "1/6/2024 3:27sore",
      "pertanyaan": "Selamat Siang Pak Perihal keterangan Slip Gaji Kenapa saya ada Potongan Diksar Ya Sebelumnya saya sudah Pernah diksar dan Sudah Mengikuti GP Dari Area lain dan Untuk sertifikat GP nya saya juga sudah punyaSelamat Siang Pak Perihal keterangan Slip Gaji Kenapa saya ada Potongan Diksar Ya Sebelumnya saya sudah Pernah diksar dan Sudah Mengikuti GP Dari Area lain dan Untuk sertifikat GP nya saya juga sudah punya",
      "attachment": "Screenshot_2024-05-31-18-21-56-40_e2d5b3f32b79de1d45acd1fad96fbb0f.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/vRQYpCT-Q7w6VI7O6KUMVw/GKxY0o7RHiFze8yXTvoLDin79wzg6I1tISeWOQGo02bZJbxeEXyroeDBvF_ZWZkyc3Lbv4Sah3CGhpMGPmp7lWKWLGROMXUOx3NfLcMEKqW9nG2IhQT8LcDQdMsEqb04T2uzM-OkMNYfzZQVmhNsSb65DC0yWCNqzjmub8u8ni6JdVFrVkaSlfe2ehK8dBLBUCiS4VQle1Tj6eSWBhDYLGFywtNbse5sWWHmAWxOa3o/7eAVUI1IJXIQBQ6VYdWT17OfQ6BDp_EFAItvBE2vG9s)",
      "status": "Todo"
    },
    {
      "user_id": "Mohamad sahroni",
      "project_id": "Mercure pik",
      "created_at": "28/6/2024 10:00pagi",
      "pertanyaan": "Saya belum masuk gaji nya",
      "attachment": "Screenshot_20240628_215457.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/683a9LKnuANf7F9aoYASbA/M1iSlLMKoLsMO3a9QCj6rSEl3vcWvKG9zIaui5nfjM1eN9G4CV6gf7njEEQwbplzEzgSeI2AcXcVOk6G1NlkUhXNK1omST1XxJ6T-XX2NL6pUmyXO_W9trj53SfGoV_CAr59xLp3ouEPfqiSeHHh7gAO0ftf7smlx6RzHxk8huiwQJj1SaZ5WO6gZmW1N7BR/pgpxMtOhcDrZnE6doe2xJX1JI6WVEf-bpTO4w-OxAPs)",
      "status": "Todo"
    },
    {
      "user_id": "MIFTAHURROBI",
      "project_id": "STELLA MARIS BSD",
      "created_at": "3/7/2024 5:40sore",
      "pertanyaan": "Mohon ijin, mau nanya gaji saya koq turun bukan nya naik komandan... ",
      "attachment": "Screenshot_2024-07-03-17-38-26-592_src.com.bni.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/gxEQzwuwvzpCxX8pw3xQ_g/0hA_nZralUzkKGNqpUh8AFd42FMbo65TZwZ2mewAQ0QHTQGTVCqheANHUVYwW8RlvlMR5TtxYr5lrFzJ4N4dSxd_vd6cqAv1CrtXa8pej0_Cxx_L5qC5JuHqi5F2y-T9Rv_mGeS1BpGitwqrfxRbYAoZ6kN8c8qqxwZHJuln3cOFm_T9u_eeaC4YIzpdPs1BekH_pp8os9trOkCrFmnPsQ/j-uKNe31uJ0HYbEAkkU6REMABrRLQwFOnG9EMZNiDxk)",
      "status": "Todo"
    },
    {
      "user_id": "Miftahurrobi",
      "project_id": "Stella maris BSD",
      "created_at": "31/7/2024 7:57malam",
      "pertanyaan": "Mas Andre kenapa sudah 3 bulan benefit yang diberikan kepada leader tidak sesuai atau tidak direalisasikan bebarengan dengan Gaji",
      "status": "Todo"
    },
    {
      "user_id": "DEDE MUAMAR ",
      "project_id": "Mandarin Oriental",
      "created_at": "31/7/2024 7:48malam",
      "pertanyaan": "Saya sudah Diksar GP tapi kenapa gajian kepotong untuk diksar GP ?",
      "attachment": "Screenshot_20240731-194729_1.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/vFLE8QCMo53RLKHM6NXnxw/Xu2gcLHf4nrHYKcCveYcaPKyorj9nzTcLZu8CJMoMYkhaVyWGs_j2Y279CIXsc3Ovr8nBZE4E_7Fi7Yt4SqwGjLYuuGsPadB5ooaILg-QB_5_U4PQeuzrJPnOURBTsUblM5mXD9sJpVciorF-D-iSxVJECKArj6mWdo_S7IRgjWc2G6JKXLRHGjp5zhQyKDo/K9LogGYyFMKYx4e7bcd0Pg47DdLdNJnztcMuelS0t7M)",
      "status": "Todo"
    },
    {
      "user_id": "IRFAN ZIDNI",
      "project_id": "Mercure Pik",
      "created_at": "31/7/2024 8:06malam",
      "pertanyaan": "Selamat malam pak, mohon ijin bertanya perihal gaji saya ada potongan 241.000 keterangan absensi, seingat saya, saya tidak pernah absensi, selalu masuk kerja sesuai jadwal, mohon penjelasannya, terima kasih.",
      "status": "Todo"
    },
    {
      "user_id": "Alpi Pebriansyah",
      "project_id": "Mandarin Oriental Jakarta",
      "created_at": "1/8/2024 10:39pagi",
      "pertanyaan": "Gaji saya masih kurang . ",
      "attachment": "Screenshot_2024-08-01-10-41-39-732_com.mi.globalbrowser.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/Ay1Psq50QSXWLkPo4ajv3w/FD324YHeeNMevftVTlIherwQAgur8XiLb5TaDS21lEynZEngwuYcLp0X6io-fhgZ8ZZvrt2hthiTjh6eIdPSoNBBU7bf4lsp9Kgm_tmQGgvw4VorNtnPlRXhNAP1pQ-K5IV5NTLId2rq_2HpWxEI-GdfGEpG65eWUdEvoH4gbGeb0fxI287GkmMDGnPNpcjZY5eKxHBnyVZDnOaKOHTB4IIQcILZSKhyVIuUvmEHB68/vJcwrF1OC_7_qrrVd9SelY8yRjpP9jLNmi9RfEPLDGE)",
      "status": "Todo"
    },
    {
      "user_id": "Juanda ",
      "project_id": "Swissotel jakarta PIK AVENUE ",
      "created_at": "2/11/2001 3:52sore",
      "pertanyaan": "Selamat sore pak, izin bertanya untuk potongan Garda pertama udah yang ke berapa kali terimakasih ",
      "status": "In progress"
    },
    {
      "user_id": "Faizal Hidayat",
      "project_id": "Ipeka Pluit",
      "created_at": "28/3/2024 5:39sore",
      "pertanyaan": "Backup lemburan 24 jam tidak masuk ke gaji",
      "attachment": "Screenshot_20240328-181632.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/P1p5kDDEElu-kURLjWNP9w/x-lBztl-d01G5Xgin1Uh6kiyypzIjgs94AMIsuU21QxA4g7f0u17ozbzgExohW0z-OKD_La0-zszMC-yQox4zbCsQMDynM_04M4a2UR367V8yYba_JHxGjpVw5ZnLbRw6z9kRzG1FBE9bYt6dNXHqwQvXs39VP2XyUXtz4g1sEY/FsRaGGW1HDY94rS1A3KOmGy_5XCDEkVyAGKCchMkREU)",
      "status": "In progress"
    },
    {
      "user_id": "Risky Afandi",
      "project_id": "Posto dormire",
      "created_at": "5/4/2024 9:36pagi",
      "pertanyaan": "THR Saya kurang 600.000 ribu",
      "status": "In progress"
    },
    {
      "user_id": "EGGY RUDISTIRA",
      "project_id": "PT SINAR SURYA ALUMUNINDO KEDAUNG",
      "created_at": "1/5/2024 8:40pagi",
      "pertanyaan": "Mohon izin komandan untuk gajih saya kok kurang ya harusnya lebih dari seperti biasa dikarnakan saya masuk serta ada backup shiefnya komandan urip waktu Dari tanggal 15 sampai 20 sudah menjadi danru komandan.untuk datanya sudah ada d rekapan absen saya harusnya masuk 23 hari komandan dan pak urip hanya masuk 15 hari",
      "attachment": "SG-April 2024-EGGY RUSDISTIRA.pdf (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/kjowcf2KT-cHNo1NJT6b7A/i6A50DHJeo21pbYwpwIrt5zAltyqgg7Ar2ExgmylFLsymcjYQ-wnmJNCjUh45m1ecWE20CosS4os0IhB4Y4cSkJXfI1prKPJk-erMtF2vE8WGFQZ9BySna6BEgNyJloQtLAmFSm48yo4N7zhuOr4NXKxhBOXyHSL9RVKEbK9eVHV9MQMznCKJq4iPpDPEILr/_pa0hOGuZQTtqsFy1OsRmNxNT4PeWYnJ-ka1A6C7_Tw),absen cg SSA.xlsx (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/k4KBIGTbtrd49PPSF-4u0Q/3cy4papeigtOKunQF8gmVl1qx4nDvl8JWIs1-rXnz0CBCKQhZnhq6Td-OwFT4I941JJ8b7UMg8BmUkLyJPjKLBmiUE7I5xt-2bJN4kjV8ZfpIRhlPK5A7DFQ3pn_1JXXwmzZy-lD3nM2e4DZTNf8EU4JAoxeJqhJthLoB-pDe_I/hsZgwOak7I4avceLrTsd3isjF3EEsjss-U6h-2d23OU)",
      "status": "In progress"
    },
    {
      "user_id": "Prengki Radiansyah",
      "project_id": "Swissotel Jakarta PiK Avanue",
      "created_at": "1/6/2024 9:33pagi",
      "pertanyaan": "Selamat Siang Pak Perihal keterangan Slip Gaji Kenapa saya ada Potongan Diksar Ya Sebelumnya saya sudah Pernah diksar dan Sudah Mengikuti GP Dari Area lain dan Untuk sertifikat GP nya saya juga sudah punya",
      "attachment": "SG-Mei 2024-PRENGKI RADIANSYAH (1).pdf (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/lVuMTvOj9AAcF9nOHVcW3A/3FKBt_7fL_c0iWFbz7fyUAmFPJyHgiJivvr9vf5g_sh9zB_r4LNSKJFoUYk15tMSdjIcwfX7jVTqH2yf4ReD4Yt_QIVykBh6TgW7IENc7NYLUCxg2ttSboTO33m3-PL7p_wMaVcOeYbz-BXZTH8oMymg00ggwmUv94LAYx9CI2GfzyKqa7Nz8UHn6pz0KILw/ojsFdm5cDyGw8_MW98PkdoRNVZmTuGv6lXPccAiJaZ4)",
      "status": "In progress"
    },
    {
      "user_id": "Muhammad helmi alvanza",
      "project_id": "Mandarin orental",
      "created_at": "1/8/2024 7:39pagi",
      "pertanyaan": "Izin bertanya tentang potongan 235 itu potongan apa ",
      "attachment": "Screenshot_20240731-194750_Samsung Notes.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/MYDhVFU1vbgmy8MU1Iv-zw/tMjdFR9oosKPzdFzbAxBOBZ4ILNWBTNZDVvA9nw-MP8wolRVxsx6dk8SRqBPEL_4NtQ9CEOlnp11hYIj4HNF8SW8Z28Nbfd5t6p07gdK1UenPcYxSftG7-FX6rdErRNvOFO1rqvBJzZsQ-ia5rkFcKe9MpQin2SManj2GTsbBC0r0FjsdY65VVrM-axJxVUimA3MSsrJ2hm9aCLxH45Gtw/5oEvlhpMUAnVkDWbDRSlAFIFmvWOjXn2yStAATlhD8M)",
      "status": "In progress"
    },
    {
      "user_id": "Yogi Ramdani",
      "project_id": "Mercure",
      "created_at": "5/6/2023 11:34siang",
      "pertanyaan": "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
      "attachment": "1.PNG (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/XdwOrIR6Fd2XtsPdorVCXA/RMkxyToCgj80JGhjK2WN-URJ_3Kzbok9dGhT-sZ48kCSuXbTTXiR1Y90vIpg3oZ1iSlAZexPgKTcFnf_Ay76sCz-3njYnyiTvIcbhTEGG8x9qNxU8bnyxPU76ABhyCvsQjwdpl0dqo8k7kDXzI0dZA/tJ8c7SIbED49MKyQ6VhSDXRWff4kpTEHgf3Os-HejbE)",
      "status": "Done"
    },
    {
      "user_id": "Efrisa Intan Agustina",
      "project_id": "Mandarin Oriental Jakarta",
      "created_at": "12/6/2023 2:07siang",
      "pertanyaan": "Gaji tidak kurang tapi dalam slip gaji tidak ada potongan gp dari bulan pertama dan lemburan kurang dua serta tidak tercantum uang hari raya",
      "attachment": "Screenshot_20230609_115921.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/D48z5uMeMgEeGKTpBolJ-w/6h3fFk3eie_cLlewYFNzmWQt3QqI9f59zSM1f-TbFLADLe2qATvUb3op-CCT0zCbfF1RStRmIsILTSPPC8xB7f-mS9DqpaaFEUSKFj68vGsCOJxO2_zFSoKA8c0Lha14G8GLQcR_bmRRnV4H0LZaVtnifTTpqp5vcakZUgeU5oI/xjShqeWKPqLi9hgFH-G-07V8bk8a6Nae2pV7BLvityY)",
      "status": "Done"
    },
    {
      "user_id": "Rika Septiana ",
      "project_id": "Mandarin Oriental Jakarta ",
      "created_at": "12/6/2023 2:18siang",
      "pertanyaan": "Insentif lebaran tanggal 22 belum turun",
      "attachment": "Screenshot_2023-06-09-10-30-09-02_c37d74246d9c81aa0bb824b57eaf7062.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/7vzcoseoo0xeo4IKJSd-3w/gIrwooSm3BR5ZUkvV2I-aJ3B3hjuZ5vgoFwhcKhQnP_G-MltaDkJm-XNEAJn_wHb4ZBWF3IC9aA-SFDAhi9oNG0CUba9iZc7zEwj6EXP5tmk4TtoHJ_lcfwR4xTHg-NiEOegbg-ZeBdIH02MrSZLgsiO2g1pPJsG2fW1OYO-xdMWAZPAgSqOGYLVViJRe3dw6mCGqfmCHe-I9LWZeSVlX41vwZeMIsdsb3AyHCjfbTg/UKRzdrM7pzecD8xFXmRfxsu1HwnzxcNV1531H19SxnE)",
      "status": "Done"
    },
    {
      "user_id": "Benny H Hutasoit ",
      "project_id": "Mandarin oriental ",
      "created_at": "23/8/1997 3:51sore",
      "pertanyaan": "1.Lembur event wedding 2kali (5jam) cuma dibayar \r\n    RP.117000\r\n2. Insentif hari raya belum dibayarkan ",
      "status": "Done"
    },
    {
      "user_id": "TEGUH ADRIANTO ",
      "project_id": "HOTEL MANDARIN ORIENTAL JAKARTA ",
      "created_at": "12/6/2023 4:10sore",
      "pertanyaan": "Saya 20 hari kerja di tambah 1 hari lembur jadi totalnya 21 hari tapi di slip gaji saya di hitung cuman 20 hari jadi kurang 1 hari, dan tunjangan hari raya belum di bayar.",
      "attachment": "Screenshot_2023-06-09-15-21-36-56_c37d74246d9c81aa0bb824b57eaf7062.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/-pa5wBVabczt5kijEknuqA/Fw_tp891ZIz8u38MeXspISy_BVUV-HNtirB1mgLN3m9unumkTgVAVWWXl4VZif2OMpG1gBdcZPni0O2cfYoBQxP-z0oGuGboqJusemtbzyNTwQha1hsx3UxBoyN_kbGahd4YANlq1ZScDNBhcSMGKIEIyc1aRp_kTInCO3UvHZHT0TW0rN_u8qjCMdPmou2_XGLbJRvYkb-GP8JRroVrkfUw0j00xqjZxTXHqf9BA98/fPpUqZDVjFzZc73EC8y4KpFrQT6cntbTjShUIcZ6R_s)",
      "status": "Done"
    },
    {
      "user_id": "Novi beliani",
      "project_id": "hotel mandarin",
      "created_at": "12/6/2023 4:09sore",
      "pertanyaan": "insentif lebaran 2hari tanggal 22-23april belum turun",
      "attachment": "IMG_8163.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/8s_pSVZ3AcCcI3KmSho57A/f2ZI2o1YwBhsrIqDx4_pt404EA2SlaFfiQgYR2jthNxer8_7U0Y8NZUMOwFqIGk7lQyX09RH41xxzbbhU3pmGmy5wyxynxqcxapf4WbSrlOesITiZiHXCsD0baqGGuElJU6Ksc1cY9EFLNg3hy80SA/fa45G-ACLlIcrS0V1B8Wok-LSc9Ui7MyHbz5k7PsLNc)",
      "status": "Done"
    },
    {
      "user_id": "Rita Novita",
      "project_id": "Mandarin Oriental Jakarta",
      "created_at": "12/6/2023 4:30sore",
      "pertanyaan": "Tolong kedepannya bagian finance lebih teliti lagi agar tidak terjadi kekurangan gaji terus. Sekian terimakasih",
      "status": "Done"
    },
    {
      "user_id": "Endah Suci",
      "project_id": "Mandarin",
      "created_at": "12/6/2023 4:43sore",
      "pertanyaan": "Lembur hari raya belum masuk",
      "attachment": "Screenshot_2023-06-12-16-42-16-492_com.mi.globalbrowser.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/fbjZeAqozSfhBpDdaXnwYQ/JjZ_0jUuUqljv7DaL-u8kVes4tDv62Aqr57n53yngQYaWy4k36hR6Rtfnu-k0DutXTUReHWo87gWiLCVlC9gVWUGPYK-BX0cjRoTmp4UvrKEa7BPijBnJ9QYj_RraEkBtk8GGmnjnqE_XAhlXRibuvE3YaMPl47usJJZiZKftGIU-BbNEfPifyt1cMrD3lcRA9KA8OQnPKg8Uttgb3uPZw/M4_oMmLb71owIF_C5TEGOY9SRf0xcKM5f0NVwV45_VA)",
      "status": "Done"
    },
    {
      "user_id": "Diyan teger ",
      "project_id": "Mandarin oriental Jakarta ",
      "created_at": "12/6/2023 4:49sore",
      "pertanyaan": "Gaji kurang ",
      "status": "Done"
    },
    {
      "user_id": "FAISAL AMIR",
      "project_id": "MANDARIN ORIENTAL",
      "created_at": "3/6/1995 5:06sore",
      "pertanyaan": "Gaji kurang, INSENTIF HARI RAYA TIDAK ADA",
      "status": "Done"
    },
    {
      "user_id": "IDRIS",
      "project_id": "Mandarin",
      "created_at": "12/6/2023 5:06sore",
      "pertanyaan": "Uang insentiv hari raya belum di bayar trimakssih\r\n\r\n\r\n\r\n\r\n",
      "status": "Done"
    },
    {
      "user_id": "Anton Saputra",
      "project_id": "Hotel Mandarin",
      "created_at": "12/6/2023 7:10pagi",
      "pertanyaan": "Gajih kurang baru terima gajih Rp.5.673.262 \r\nSedangkan di selip gajih Rp.5.768.596 \r\nkarna ada lemburan 2 full",
      "status": "Done"
    },
    {
      "user_id": "Yeyen permata sari",
      "project_id": "Mandarin oriental jakarta",
      "created_at": "12/6/2023 8:02malam",
      "pertanyaan": "Mohon izin komandan,saya di skedjul terhitung 20 hari kerja tp di slip gaji cuma terhitung 19 hari kerja doang,sama saya punya lemburan 1 jdi harus nya 21 hari tp d slip gaji cuma 20 hari",
      "status": "Done"
    },
    {
      "user_id": "Yeyen permata sari",
      "project_id": "Mandarin oriental jakarta",
      "created_at": "12/6/2023 8:08malam",
      "pertanyaan": "Izin komandan,saya d skejul hitungan kerja 20 hari dan itu masuk terus,sama punya lemburan 1..\r\nTp di slip gaji saya cuma terhitung 19 hari doang ",
      "attachment": "00648ED5-E19B-44C5-ABC0-9BA4FE2527EC.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/CGzSG1wlAb37fbeOHEH5ww/E7cqiB8HnYp2rgu5bNFdBF62whn-hAxGcudkrCaxnGtSaYChzhAdwRq9KMwBxsAAN3M-b5pl1kjxQBYvNswv7e0HClFC6wKSe7Aq75f8V2h5zr49KtNc8PyRf8bu5cQFiZM9gQJ0gKim7B8-AAi79-Gq2FL94TvIY_Z_ijMdTouT5X13DY70xiSNZH5MICWW/m1wA8BnYYOSZwOpS5uCRsJ7d63RlhL-AR7mBJ87IOTc)",
      "status": "Done"
    },
    {
      "user_id": "Ana Maria sofiana",
      "project_id": "Mandarin Oriental ",
      "created_at": "12/6/2023 9:56malam",
      "pertanyaan": "Saya gaji kurang Rp 350.000 lagi di karena kan saya sudah punya Gada Pratama tapi masih di potong ",
      "status": "Done"
    },
    {
      "user_id": "Amarulloh ",
      "project_id": "Hotel Mandarin Oriental Jakarta",
      "created_at": "13/6/2023 8:05pagi",
      "pertanyaan": "Uang tunjangan Hari raya Iedul Fitri ",
      "status": "Done"
    },
    {
      "user_id": "nana mardiana",
      "project_id": "hotel mandarin prental jakarta",
      "created_at": "13/6/2023 4:00pagi",
      "pertanyaan": "tujangan hari raya idulfitri",
      "status": "Done"
    },
    {
      "user_id": "Ana Maria sofiana",
      "project_id": "Mandarin Oriental ",
      "created_at": "13/6/2023 7:51malam",
      "pertanyaan": "Gaji masih kurang dan saya sudah punya Gada Pratama tapi di potong Gada Pratama lgi RP 350.000",
      "attachment": "Screenshot_2023-06-09-18-48-16-79_439a3fec0400f8974d35eed09a31f914.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/PsMHV0fhd6iig0XZNRRDZQ/QEje2x5JP0wxfs70kUKZVnxaoafi6CjdhaVPqqgXDJzZ5Ayb7wJutSbuyLeVWRXu0wONZXXJbXM9gjqbvOFnf2s4cVxJchaqaY6AoVpd7Y9Asc3KoDo1AJ1CZyexp_kg45O1siisLbqkvUElrILoiujHPBtu9tGYZ_e9dp0y_9kfk_U_wIu_3DhBHd463zf3pF-qDqQ_FK20Ch7mgLAbmRrx2K7w0Thm0zhmi1WycdM/Irk7ZsbRmbhcAFVcdPS1XfS2a2ZBjzWK8Y_BFDq-eNE)",
      "status": "Done"
    },
    {
      "user_id": "Cica wilantika ",
      "project_id": "City guard ",
      "created_at": "13/6/2023 8:30pagi",
      "pertanyaan": "Lembur  gentiin Veni belom di TF terus SMA hari raya ",
      "status": "Done"
    },
    {
      "user_id": "Ani nur widyasari",
      "project_id": "Mandarin oriental",
      "created_at": "13/6/2023 10:39malam",
      "pertanyaan": "Gaji kurang,,masuk saat hari raya idul fitri belum dibayar",
      "status": "Done"
    },
    {
      "user_id": "Luinda putri ",
      "project_id": "Mandarin ",
      "created_at": "15/6/2023 7:41malam",
      "pertanyaan": "Gaji yang di terima sama di selip gaji berbeda \r\n",
      "status": "Done"
    },
    {
      "user_id": "MUHAMMAD AL IRSAN",
      "project_id": "Hotel Mandarin oriental",
      "created_at": "16/6/2023 7:08pagi",
      "pertanyaan": "Lemburan kurang 1 hari",
      "status": "Done"
    },
    {
      "user_id": "Eko febriyanto",
      "project_id": "Mandarin Oriental jakarta",
      "created_at": "16/6/2023 6:38sore",
      "pertanyaan": "Uang insentive Hari raya  ke 1 & ke 2 belum masuk ",
      "status": "Done"
    },
    {
      "user_id": "Amarulloh",
      "project_id": "Mandarin oriental Jakarta ",
      "created_at": "18/6/2023 2:30siang",
      "pertanyaan": "uang insentip hari raya idul Fitri ",
      "status": "Done"
    },
    {
      "user_id": "Dadan Supriatna ",
      "project_id": "Mandarin ",
      "created_at": "30/6/2023 10:21pagi",
      "pertanyaan": "3 hari lembur berturut-turut",
      "status": "Done"
    },
    {
      "user_id": "nana mardiana",
      "project_id": "hotel mandarin prental jakarta",
      "created_at": "18/7/1993 2:00pagi",
      "pertanyaan": "lemburan nomal kurang 1/dan insentif lebaran juga belom",
      "status": "Done"
    },
    {
      "user_id": "Rika Septiana ",
      "project_id": "Mandarin oriental Jakarta ",
      "created_at": "30/6/2023 3:02sore",
      "pertanyaan": "Buike bantu cek gaji saya bu, dari perhitungan saya 21hari kerja+tunjangan scanner+lembur gantiin veni+tunjangan hari raya yg belum dibayar bulan mei itu gaji saya kurang lebih diangka 5.327.472 sudah termasuk potongan gp yg ke 3 tapi saya nerima cuma 5.188.000. terimakasih buike",
      "attachment": "Screenshot_2023-06-30-08-50-02-85_e8205ec1cda7e9657fadf502be9b7b78.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/dhKSCJuM6LJH1DIns-zO1g/M4WXoCs7QEUlVgThn4NNNV64Sg8kZONARXrXaqBMD5O1qQMFl8BGcgD669w27lYYAeHeOuHafVayVM23DRoQ2ujINjKIu73mRtiZXFkD-T5G5dtfVai6kMYUmeIJLUCmV2mJmNTrapI69MbDIdj1-xnnmtMgFMYMVEZu7hb0ze96_6IoTamqKS9ad7Ugh4kCqftiJsDYws3iNK4_1QWj3QRARbpsC4fiwrB3sr63SDg/e5rcuEnuDLxAlJPx7SsBROgyIDL8T28dOapAFheEkRg)",
      "status": "Done"
    },
    {
      "user_id": "Faisal amir",
      "project_id": "Mandarin Oriental Jakarta",
      "created_at": "3/6/1995 3:55sore",
      "pertanyaan": "Gaji kurang tapi belum tau rinciannya karena slip gaji belum ada, insentif hari raya belum masuk",
      "status": "Done"
    },
    {
      "user_id": "Dendi triyandi",
      "project_id": "Mandarin oriental jakarta",
      "created_at": "1/7/2023 4:38sore",
      "pertanyaan": "Jumat 02_06_2023 lembur malam",
      "status": "Done"
    },
    {
      "user_id": "Yeyen permata sari",
      "project_id": "Mandarin oriental jakarta",
      "created_at": "2/7/2023 7:16pagi",
      "pertanyaan": "Izin ibu/bapak,mohon izin untuk komplain prihal masalah gaji,bln2 sblm nya oke gaji kurang karna masalah kurang rapi nya absen ditalenta,tp knpa ya gaji saya bln juni masih kurang absen talenta udah rapi,lembur pun udah ke data di talenta tp masih aja hitungan gaji nya kurang,lembur saya ga d bayar..\r\nMohon untuk perihal gaji nya bapak/ibu di benerin d koreksi kmbali trutama gaji saya ataupun talenta saya pas bulan juni,\r\nSaya disuruh lembur mau lmbur tp klo pas gajian lmburan ga dibayar bukan nya apa2 jdi lmbur baceup juga males mohon maafðŸ™,\r\nSmoga di mengerti ibu/bapak trima kasih\r\nMohon maaf klo ada salah kta dri saya",
      "attachment": "21AA0A10-4488-4ABB-A542-EE3083A56835.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/E89D1edQipHXLMum2pSIWw/8ez69S-z_j7QaNJ7gK31wxlb5bYJvR-tOLf7dKVlkh_l2xiKOXgr_MnlJCHVxWAdwRtRYJ0fih1pg91cVr7RseXdy_rjETPca8lKsDXsfqpIDZl4X13xNcVsv7GA0M_X6FzBQXkz5QSyAS7UjEOnw1k-DRZz0VxDKD93u8NgamX5o8Zz9eD-A0Yl2ZslsGxj/AzT_eyoLf23kpPrVOs8YebpvSBkOlNN0GC87b2ZPy7k),99F6443B-D696-4D70-B3A9-25A14A3AAC72.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/2KqJuNcKqhrBOJqNxEdETw/rBQPOcEEQ2Btme1DiCKTv4WaregN1x8PH8h84ps0RlyW25Fm186VNhV-0LYcfvo1--2mwoXpY66dtf2Esib3C6tO3BHr012thynxoCTjcUuTBdhkzL3080uX4uJqwq293sOfFUuoI_bnhKEIXp0iyIBdYoJNGLp4mVfaZ8tmmTENruAT3L71AeyGqHSAXc-1/kOBaa7EGGGemMAFmheFWmLQxrb952n1Vj-ecAOvfKIo),FC521775-2031-40FA-A767-53841D722D61.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/Q4C4M1b0Io98Sp6MBsFRYw/CBqg5uDGA8tP5983DJKeEns7ZdjPDC38Jf9SotqWuzEv6CSZ62YDzn5C2Ci1TTw1T3lPDBg5pNCUyT4rVeVvOJl60qJRblF1hW9-Xlxk99ggqOsJaImEPpiyJzYFr0GU6unBLLkEvBYXXyXHgAfqeP4SWfiHwwtBVcBn9DWcJbwhPoYQKMV7Qt3OQokxrA94/aKlbvqqIoJLbL_FeBPLQ8HJZv_KJviUNhmZRQwMipcs)",
      "status": "Done"
    },
    {
      "user_id": "Diyan teger ",
      "project_id": "Hotel Mandarin oriental Jakarta ",
      "created_at": "2/7/2023 7:38malam",
      "pertanyaan": "Izin ibu/bapak,mohon izin untuk komplain prihal masalah gaji,bln2 sblm nya oke gaji kurang karna masalah kurang rapi nya absen ditalenta,tp knpa ya gaji saya bln juni masih kurang absen talenta udah rapi,lembur pun udah ke data di talenta tp masih aja hitungan gaji nya kurang,lembur saya ga d bayar..\r\nMohon untuk perihal gaji nya bapak/ibu di benerin d koreksi kmbali trutama gaji saya ataupun talenta saya pas bulan juni,\r\nSaya disuruh lembur mau lmbur tp klo pas gajian lmburan ga dibayar bukan nya apa2 jdi lmbur baceup juga males mohon maafðŸ™,\r\nSmoga di mengerti ibu/bapak trima kasih\r\nMohon maaf klo ada salah kta dri saya",
      "status": "Done"
    },
    {
      "user_id": "RIZKI ANDIKA",
      "project_id": "Prisma harapan",
      "created_at": "3/7/2023 11:33siang",
      "pertanyaan": "Izin ibu/bapak,mohon izin untuk komplain prihal masalah gaji,bln2 sblm nya oke gaji kurang karna masalah kurang rapi nya absen ditalenta,tp knpa ya gaji saya bln juni masih kurang absen talenta udah rapi,lembur pun udah ke data di talenta tp masih aja hitungan gaji nya kurang,lembur saya ga d bayar..\r\nMohon untuk perihal gaji nya bapak/ibu di benerin d koreksi kmbali trutama gaji saya ataupun talenta saya pas bulan juni,\r\nSaya disuruh lembur mau lmbur tp klo pas gajian lmburan ga dibayar bukan nya apa2 jdi lmbur baceup juga males mohon maafðŸ™,\r\nSmoga di mengerti ibu/bapak trima kasih\r\nMohon maaf klo ada salah kta dri saya",
      "status": "Done"
    },
    {
      "user_id": "Maria Ana Lisa Tamaela ",
      "project_id": "Security female scanner ",
      "created_at": "3/7/2023 10:30pagi",
      "pertanyaan": "Izin ibu/bapak,mohon izin untuk komplain prihal masalah gaji,bln2 sblm nya oke gaji kurang karna masalah kurang rapi nya absen ditalenta,tp knpa ya gaji saya bln juni masih kurang absen talenta udah rapi,lembur pun udah ke data di talenta tp masih aja hitungan gaji nya kurang,lembur saya ga d bayar..\r\nMohon untuk perihal gaji nya bapak/ibu di benerin d koreksi kmbali trutama gaji saya ataupun talenta saya pas bulan juni,\r\nSaya disuruh lembur mau lmbur tp klo pas gajian lmburan ga dibayar bukan nya apa2 jdi lmbur baceup juga males mohon maafðŸ™,\r\nSmoga di mengerti ibu/bapak trima kasih\r\nMohon maaf klo ada salah kta dri saya",
      "status": "Done"
    },
    {
      "user_id": "Achmad syaifoellah ",
      "project_id": "Mandarin oriental Jakarta ",
      "created_at": "6/7/2023 8:04pagi",
      "pertanyaan": "Bu/pak lemburan saya blom di bayarkan per tanggal 25 Mai 2023 dan 19 Juni 2023... insentif+tunjangan juga blom di bayar Bu ... Tks..",
      "attachment": "Screenshot_2023-07-06-08-10-13-38_e8205ec1cda7e9657fadf502be9b7b78.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/f1s-hKly6ZPUgHDxaHwQWg/LYkpq_JB7NZC3bg_LvtMrEMDAdn_6ZVctJhaDrPquB2v9mj9OlrTBR7uFjtc-0wEv_bmhpb2ZIaqvzqhFlSg6DiaqM4hOaW6roYzmU-T-ekVIHcnfM5lKfLN4HjgU1Sz9FzuVjPTzwIdaa8vc6obwtO7NGRCkKmuPnFGEKfSx6dHMHgDE_jaPrvnzOt-vX-8kdoi0Fj4eDo-V1DF9GrUBMkTz8zUKbFEeURXTYEFjr4/RsRQGIgPvB92KKnNRbntxV3xoOydbh59_pfx6Zz79kg)",
      "status": "Done"
    },
    {
      "user_id": "Adi Maulana Putra",
      "project_id": "Mandarin Oriental Jakarta",
      "created_at": "6/7/2023 8:56pagi",
      "pertanyaan": "Izin ibu/bapak,mohon izin untuk komplain prihal masalah gaji,bln2 sblm nya oke gaji kurang karna masalah kurang rapi nya absen ditalenta,tp knpa ya gaji saya bln juni masih kurang absen talenta udah rapi,lembur pun udah ke data di talenta tp masih aja hitungan gaji nya kurang,lembur saya ga d bayar..\r\nMohon untuk perihal gaji nya bapak/ibu di benerin d koreksi kmbali trutama gaji saya ataupun talenta saya pas bulan juni,\r\nSaya disuruh lembur mau lmbur tp klo pas gajian lmburan ga dibayar bukan nya apa2 jdi lmbur baceup juga males mohon maafðŸ™,\r\nSmoga di mengerti ibu/bapak trima kasih\r\nMohon maaf klo ada salah kta dri saya",
      "attachment": "Screenshot_20230706-085740_BNIMobilenew.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/A3kH247V7F0pRjicgxcw2A/sLZtWcn4vZsM9E1sshXpzwMFdeLcIPEICfBG-pf02bHO5VT5PGcvocb7P74Z2tzATvwJMAOA2yEkRip_zeZyO6xo6AD3cErkIOeEMoPIE7QOuqGW_94PPei1-935VvLS07jPKR3wk4HMi7JHJ20ibx_lAiPQfakeHTTarVZWG8mzaOeSynyGHMLsG9psAweq/ZWCcVnIaz-t42fALraZ_vY4znJ50LDraODnzgCy6_vY)",
      "status": "Done"
    },
    {
      "user_id": "Maria Ana Lisa Tamaela ",
      "project_id": "Security wanita hotel Mandarin oriental Jakarta ",
      "created_at": "6/7/2023 8:30malam",
      "pertanyaan": "Kurang gajih, seharusnya dapat 1.440.000,00 tapi hanya dapat 1.200.000,00. Saya mulai di hitung kerja dari tgl 12 Juni - 21 Juni tutup buku dapat 6 hari kerja. Dan itu tidak di hitung orientasi dan tidak lembur. Jadi masih kurang 240 rb lagi. Semoga city guard ada kebijakan tentang ini! Terimakasih ",
      "status": "Done"
    },
    {
      "user_id": "Anton Saputra",
      "project_id": "Hotel Mandarin Oriental Jakarta",
      "created_at": "7/7/2023 9:47pagi",
      "pertanyaan": "Gajih yang kurang bulan juni belum di bayar,dan sama bunus lembur dalam 1 bulan ada lemburan 3 full dapet bonus,tapi belum nerima juga.",
      "status": "Done"
    },
    {
      "user_id": "Alpi Pebriansyah",
      "project_id": "Mandarin Oriental Jakarta",
      "created_at": "7/7/2023 5:55sore",
      "pertanyaan": "Lembur masuk malam tgl 05-06-2023 belum dibayar. ",
      "status": "Done"
    },
    {
      "user_id": "Teguh Adrianto ",
      "project_id": "Hotel Mandarin oriental ",
      "created_at": "8/7/2023 8:53pagi",
      "pertanyaan": "Gaji periode Mei-Juni  harusnya 22 hari (1 hari bulan lalu yang belum di distribusikan) kerja namun baru di terbayar 21 hari kerja.",
      "attachment": "Screenshot_2023-07-07-15-36-49-70_c37d74246d9c81aa0bb824b57eaf7062.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/W4f16l5vJ6otdaYbCVMUiQ/ZXWOHVuLsGDQlcsaFambyGYFCIqTQpCoCNY9yg_gqVa4DavBhQcmLptQ91LpByeOWCJflF12PENTdhQD9_KY4YeDf2PqMJCwRjLSjjUK6STQX06smG1Cx2w4JLth5IpM6YNWEObcK0CEeF40bmieGD6Zaw0A1s0-NQTLRftMJ_dI-esRQqP8eiw0tNN0gk6Ofp_22n1SFZv29qSjMJQwmQQpVrrwp0J56-PuAOJnjDE/LklDi7-tvKjKaIm6JpS1lbdh4DmnmIFHYh3wvD6Ir1c),Screenshot_2023-06-13-16-45-29-61_abb9c8060a0a12c5ac89e934e52a2f4f.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/4zj38LkTdBtvLE7ftSysVQ/uUE9oNJ94UpLak-LnhKRJEF1iNPTztZqrh4ZATHYvGvYOM-d9jYMYFUqoXPYgGQmI_wqe4soo4HATL0g70nP9h6aIE2YNGFw8420Ke-J6V1--CzW79Gu9kTwwo-QCZgb3fLp1uFpFmuvq6D2srFY31Z5jbyrpv7iYR-yMrD2Hv-yGk16La9wYGk7B8_B3fcUPGTcQ2hulOEdVVsaQwFQEAJJivJSFOQs5swN_8o6H0A/b4u_0f-rwmkPH4MlqQbejU9LqZDEn4ES_qxBHtl1Rh8)",
      "status": "Done"
    },
    {
      "user_id": "Maria Ana Lisa Tamaela ",
      "project_id": "Mandarin oriental Jakarta ",
      "created_at": "14/7/2023 8:01malam",
      "pertanyaan": "Gajih kurang harus nya 2.440.000 JT tapi cuman dapet 1.200 kurang 240rb lgi. Saya masuk dari tgl 12-20 Juni tutup buku dapat 6 hari kerja 6Ã—240= 1.440. semoga ada kebijakan dari CG ",
      "status": "Done"
    },
    {
      "user_id": "Wiwin",
      "project_id": "Mandarin oriental hotel",
      "created_at": "31/7/2023 3:41sore",
      "pertanyaan": "Double check gaji ( Kurang ) gaji lembur tidak masuk",
      "status": "Done"
    },
    {
      "user_id": "Urip Sapikri",
      "project_id": "PT SYNERGY ",
      "created_at": "31/7/2023 7:04malam",
      "pertanyaan": "Pembayaran uang backup di PT Champoil pda tanggal 16 Juni 2023 blum di bayar,",
      "status": "Done"
    },
    {
      "user_id": "AGAM SETYA HERMANSYAH",
      "project_id": "MANDARIN ORIENTAL",
      "created_at": "31/7/2023 8:09malam",
      "pertanyaan": "SAYA MENERIMA GAJIH KURANG TERUS SAYA MEMPUNYAI 1 LEMBUR DAN HANYA GAJIH 4,8",
      "status": "Done"
    },
    {
      "user_id": "Yonda Yoandana ",
      "project_id": "PT SYNERGI ",
      "created_at": "1/8/2023 8:58pagi",
      "pertanyaan": "Uang backup belum masuk, \r\n\r\nBackup di champoil tanggal 24 Juni 2023",
      "attachment": "16908554182414563435744261584324.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/qemZd1VYgiq1P5Goozsdcw/uRk_2rgst4evZJFYNrpon31ih7HZ_9brHBee_yEc3Jboj7ZFemAnH6OyeaFbH-pS-x3rEktEdi81NhKrm8nkB5pIoEGfkGyuJ-jPFcPOsG5Kbt9NDxljF7zEYo8iIiK3QIHS3IW7C_hka9o7FbipI0QYOcPJiNU5FMZqXKbZjKQ/GsAH-bwCj0jVMAw-UWEvo5G9z65Eu4PvD1eZkRwpMpQ)",
      "status": "Done"
    },
    {
      "user_id": "Ricel klery",
      "project_id": "Mandarin oriental Jakarta ",
      "created_at": "1/8/2023 9:30pagi",
      "pertanyaan": "Lembur event gak masuk ke rekening ",
      "status": "Done"
    },
    {
      "user_id": "AGAM SETYA HERMANSYAH",
      "project_id": "MANDARIN ORIENTAL",
      "created_at": "1/8/2023 11:23siang",
      "pertanyaan": "GAJI SAYA KURANG - KURANG GAJI LEMBURAN ADA 1 MALAH PALING KECILL",
      "attachment": "IMG_20230801_113012.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/KSpkELdUYpfhXuMGf7rv0g/pvFPOGqy-1hSkH1Q6CCDvLt8vAQDt6ZNlluSJS9762qwbTuCiRXHqpg5_Red6ycYx4RvO2pjRFxHbwPndyX_b1fvJ5Ss6YHQI3F28M8Aqx4Ti7D2mPNCCfaJ2HtryVrn1NjQLrtR13TN41-KTHZkJPKjKLOKe43jItRIQtG29yg/N1RgKu386DgfLGy4AiiN4pHZUGiC4x3oOLUq-_HO7w0)",
      "status": "Done"
    },
    {
      "user_id": "SAMSUL",
      "project_id": "Ssa kedaung",
      "created_at": "1/8/2023 4:59sore",
      "pertanyaan": "Gaji belum turun",
      "status": "Done"
    },
    {
      "user_id": "PAHRUROJI ",
      "project_id": "MANDARIN ORIENTAL JAKARTA ",
      "created_at": "1/8/2023 8:27malam",
      "pertanyaan": "Lemburan audit tgl 24 Juli 2023 , belum masuk ",
      "status": "Done"
    },
    {
      "user_id": "Heriston Sinaga ",
      "project_id": "Swissotel Jakarta PIK",
      "created_at": "2/8/2023 10:19pagi",
      "pertanyaan": "Selamat Pagi Bapak/Ibu.. kenapa saya nerima gaji hanya Rp2196.000",
      "status": "Done"
    },
    {
      "user_id": "EGGY RUDISTIRA",
      "project_id": "Mercure Jakarta Simatupang",
      "created_at": "3/8/2023 1:02siang",
      "pertanyaan": "Mohon izin saya masuk kerja di periode 21 juni-20 Juli adalah 20 hari tetapi mengapa di slip gajih saya hanya 16 hari didukung oleh absensi talenta saya juga 20 hari kerja.19 dan 20 Juli saya memang sudah tidak di projek Mercure Jakarta Simatupang tapi saya masuk projek IPEKA Puri kembangan.tapi terhitung tetap saya masuk full tanpa ada izin/sakit 20 hari kerja dimohon untuk penyelesaian nya.trima kasih",
      "attachment": "Screenshot_2023-08-03-13-07-05-66.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/AovlWxuRQM6_o0Hx-84EHg/0iZi04SpUjJCq5dpZiJ208OE4qFEsgbsZzHNjRgmHpNf4H-Ps-uNjbfWUuO3ZEQBLDus3Tvf7PQ0-qxKw6WFTY1AAh_kpc3FffFhY6VTrjZ4YSiobYAcDg56-fo_IHvE0cn3RlUN_9asKqoAIjWHL42dvSesd9WBRnGEWrKml8lFe_e8ZweinL0dMTMbmeAW/tDegbULeZY3TOvXdKKOyx6UYytnAKLJ-KbeqV3kcqq8),Screenshot_2023-08-03-13-07-33-71.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/nXDsyjIrTT8Re8nfwExpGg/6vOkDctlbFnACq1rcu2c5NlSVxLTir5_7QP7c6o3u62US-3S-8ZfhN95alpoEfW3WLiOR8QzyU_y7DigL_42MQ4y33yvC15OOkyrH1UnQpXk_hV_CUwBZggNjwqdzKf7W-J2PDPrURG9QvlO76ciyNNK7FJz-nR4zEqTZ6CbraqP63kqkbX0F4NOVSz-DB4b/bxLwEfrwzbfVjMBh-M4Pcy8fv9McEUACaqWtUWDU7CI),Screenshot_2023-08-03-13-07-40-63.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/b-F4ryqjqwVcSgFb2-P2Ow/6h0ubizerE-5Zxau9bVHBrcY8380g5mf3TMUuU7-v0nc9-ycf-_b-bdbBf88nigslwxS3NoPHtK-7VXawJrmOeY2-wwn6-zOCfIZ_xh0Yjb-RySgJaCX5qoEe3ROeA-sxOtvE4aOhIfWVXwa2ISzH9zY7qoM4_QlvUyN2qg1XPIQoPFQrs39lgBPAd1n-EGb/iU0DbFe9lXPyFbW-7c96v3oRsWcVXQtklRJhIIIWaSs),Screenshot_2023-08-03-13-07-47-63.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/aT_CzlrDrnqzCSKOVmTmAA/6-Z5GuZjnR5iPNxYS01wYwCAro50DMlbdSUySKZcSeWisrMFxVdBWA2Zs3pyMCtm5AXiG53uUeGOwDg2HB3qLQFgd2hEDWs5w17UQK3nD_v87HecQZ63qsqu_amGwoai4tcwZrYboTDnORLGqd2bWaRk9R2Unvjjvm--t_sUjViS4Yp6nOz6NkIpwPRmz7DI/byX_QO7Ov_PzRpORssrSgM9sK-pJ_1aAu4DPnxfNZRY),Screenshot_2023-08-03-13-07-54-43.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/XZNKHzZe5r9YYC-9gr1YSg/dnWSTHUkSK3wy0qfv3mhnMme-zIs27rzvjHzdVxx6QVfCk2YJ2XhnJJfsW41DIC1fPOpgPyljic9dwRVwcnS3P0RQtMhbmbV-CmgXkHC06VKnPdzXmZOpboLMnovIDsiM7_jOdypzfeG7NzLvMShdv6g2rkXEwXNl47fNvM3E56iG4Oph007G8WlcvOPtmIJ/42bb2ivCWD175ADv_4qst4K7Q_vswV4lkgJ7gBnV6xI),Screenshot_2023-08-03-13-08-00-18.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/u8d9HLG-Pgy6K1jaDhxDqQ/WHSXFhrS4AF2OKX642U7uUtFQ51ivgsefZMBSR0dYht7eDdZjAcsor-IX3RukqeocGddPVmmLqAMKjlqvayfjlPSSWaC-zLiKFmaHOjEj-1yrLDY2K3hh_WM9oCZbvLX7SXUrnvEvmfzFyYR-tLIik6GjXhESbR45EhiLWNmNfcYCsbLdUuc-Up_jhaOF2j0/AvMWLG_J4nrb0QP_jH7Y_JiAvjZAFSmZRJN0GHKOA0k),Screenshot_2023-08-03-13-11-54-09.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/Efh2Fb7hdTdDIwuvq1jVEw/ZUsjbnBrUmuBZh2Y5h4F5qerjjA5cAP-GN68EfkFaeiuqIGV3LbODliBTzKgyu2AKGmkUgFPfABhwwX-9h5L2bU6lHx2zask3S948PfcXu-S2lGUzGSLyG1G74qo_Tx3UPUQK80wRW5WiIIN2RBAlQD2wh900hZ4T705LByQ9Hhn0KVvOYsl33hn50lpKejo/_QuP86Od6gSWbd9LEJZZ8ZiS7alCEv2vfX2C-_n8h_w)",
      "status": "Done"
    },
    {
      "user_id": "Endah suci",
      "project_id": "Mandarin Oriental",
      "created_at": "3/8/2023 3:50sore",
      "pertanyaan": "Lembur event tanggal 24 Juni belum masuk",
      "status": "Done"
    },
    {
      "user_id": "Amarullah ",
      "project_id": "Mandarin oriental ",
      "created_at": "4/8/2023 10:00pagi",
      "pertanyaan": "ada potongan absensi padahal saya masuk terus ",
      "attachment": "Screenshot_2023-08-04-09-53-50-19_e2d5b3f32b79de1d45acd1fad96fbb0f.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/xRCX3GgZPqlb2NBcPjcmEg/dRdmQUx92G_ceIiaKno7kJGci_vU4rKnAg3qpMCRRrZEVfm-LugW8faJ-nusYX50tBoNQbgi3-F9ICS9kvcdhkl5BFTEdSnvtQ6nYBUiTFPNAcIaEaQNZkKcKxMyj2QUyp2a3MplfjRNrsERBtK28o86Ww7dnecpkCoCW_rSIDntlWr_db18SInMrbknvq-Kuz6_F2Ag3Hq4coPeT88RS-H-6ErrSzJpcSvwdtnx-O4/lGLKdABhVUnRZ6sTn-Xvu8Y9E4PkVdYDGGAhanUtWKg)",
      "status": "Done"
    },
    {
      "user_id": "Urip iskandar ",
      "project_id": "PT ssa kedaung",
      "created_at": "5/2/1988 12:27siang",
      "pertanyaan": "Uang lemburan say belum terbayar di ipeka Pluit backup 3x urc 2x terima kasih ",
      "attachment": "Screenshot_20230804_122530.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/nPFIAb98uSUjGLq45vx4gQ/Auj3pIWW5w9UPFH0ncbZUxtN1eBNA5PeLUsd7f13Rgon2t4Bj0jW-VX0CElujKj-n43je9R3Y1UF7PZ4yeIFzPX6nqSJFy-jb_qwcPzpeX0vbIpK_iVRi1XixacubGxGkLkNCYEUbqi6imvx12YUr0TOmSDPKEDfNAafvqOq9Uc/n7a-P-JBKHbkS5dPhBy4TfGW4L46uYbCo0oZM_sL5CY)",
      "status": "Done"
    },
    {
      "user_id": "Yogi Ramdani",
      "project_id": "Test",
      "created_at": "4/8/2023 12:54siang",
      "pertanyaan": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus dolor mauris, sollicitudin ac dolor eu, hendrerit convallis magna. Nunc euismod massa sed vestibulum sodales. Integer viverra sem id turpis tincidunt venenatis. Nullam molestie iaculis tristique. Mauris quis ullamcorper eros, at iaculis magna. Nullam at sapien id odio tincidunt tincidunt. Donec pharetra pellentesque arcu. Quisque mattis sapien tellus, sed porttitor est accumsan maximus. Nulla ut nibh a arcu tincidunt dictum in sit amet sapien. Aliquam non velit bibendum, lacinia sem eget, fermentum ipsum. Mauris sed fermentum dolor. Nam at nisi malesuada, mollis neque sed, fermentum mauris. Fusce at dui est. Morbi elementum erat eu pellentesque sollicitudin.",
      "status": "Done"
    },
    {
      "user_id": "Novi Beliani",
      "project_id": "Hotel Mandarin Oriental Jakarta",
      "created_at": "4/8/2023 8:53malam",
      "pertanyaan": "Lembur event tanggal 08 july 2023 belum dibayar ðŸ™ðŸ»",
      "attachment": "IMG_8777.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/ZdX4iyBax-Mhc5aP-59KGA/lG1tzxIzzwNx_sSZI85Usv6SAtOCG4_6GuSbBnbBwy6xYIOrl-uhzZscSHDuD-49ljB4AYdl4JSP2epGmxBw7tOU3Ho3y1x0OxuVbHK8DWmtlQeYrScD344HlwPChKKvzMAYHpO_avAOrCKW_zqpZg/LBhhO4oGfqeiVo_iHSuah_hiA2Q7lbmKXjZSCgUz_Vg)",
      "status": "Done"
    },
    {
      "user_id": "Rika Septiana ",
      "project_id": "Mandarin Oriental Jakarta ",
      "created_at": "5/8/2023 7:49malam",
      "pertanyaan": "Selamat malam pa andre yanto, lemburan tanggal 25 juli tidak turun dislip gajinya",
      "attachment": "Screenshot_2023-08-05-19-48-29-32_c37d74246d9c81aa0bb824b57eaf7062.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/b-KnZ66340NEwS_GSBiZzw/3K9y_6iMkKGtPRKDNc9_4ume_0FnKonTa_95GcAy-JThTU8y_fdRBQLa-SMiSgSq7WcGLNx2peWVidxQhWsScqDJWF_E58hzl5qlV1Xu6py5NKmA94ImpBZTJOFSrfO3pbTjudgNP2S6nko4758Clcanjg8zvp78zIb-h1k33rYTU3rbpi1GXCkhzj08Eef-xiRU1FXvPjEIdRr6XexkHVmUqgiM8IuTQgoUc7KzG6k/xARBy00FwgHPtlT4DTiKUXEfZaraLXYpMiKV40uXZdA)",
      "status": "Done"
    },
    {
      "user_id": "Rizki andika",
      "project_id": "Prisma harapan",
      "created_at": "8/8/2023 10:49pagi",
      "pertanyaan": "Selamat siang bapak/ibu pimpinan city guard izin memberi informasi perihal untuk gaji saya masih kekurangn , untuk di gaji bulan mei-juni itu kekurangan lemburan 1 dan bpjs keshatan mandiri tidak di release jadi total ke kurang skitar 400rb tp yang baru di bayarkan 220rb jadi bulan ini masih kekurangan gaji sebesar 180rb demikian laporan saya mohon maaf apabila ada kesalahan kata atau perbuatan demikian terimakasih ðŸ™",
      "attachment": "Rizki Andika.pdf (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/EaFamnCKGu8JgizqckBWgw/iJQiCNYzJSGFDeo3jgPZqZ4_cbsWJuzp6v_pbV1O4LpQ-1TicXFzcOn1C-MJLqo9elFxLC8Jrhn6zk2rlvAlemBDozhbhOU5WhZaiNMhi4hnPHxPoPAcT3DK3C7G4vVont8Ho7ij5f21__EQO7GFfg/l3-6z1SEEqNmPsAxP_MZWf7JhQ9iUM1DGoXAqBEnsOM)",
      "status": "Done"
    },
    {
      "user_id": "Andri suhendri",
      "project_id": "Pt.prisma harapan",
      "created_at": "8/8/2023 7:00malam",
      "pertanyaan": "Selamat malam mas/mba mau info Gaji saya kmren 4.780.000 sepertinya ada kekurangan , sebab info terakhir Gaji di prisma naik tp kok masih kurang ya mau tanya untuk di revisi kembali untuk gaji saya benar atau tidak nya demikian terimakasih ðŸ™",
      "status": "Done"
    },
    {
      "user_id": "FAISAL AMIR",
      "project_id": "Mandarin Oriental",
      "created_at": "9/8/2023 6:23sore",
      "pertanyaan": "Gaji lembur event belum terbayar",
      "status": "Done"
    },
    {
      "user_id": "Septiani ",
      "project_id": "City Guard ",
      "created_at": "10/8/2023 10:07malam",
      "pertanyaan": "Lembur event tanggal 1 Juli 2023 belum masuk di gajian bulan 7",
      "status": "Done"
    },
    {
      "user_id": "Cica wilantika",
      "project_id": "Mandarin oriental Jakarta ",
      "created_at": "4/5/2023 7:00pagi",
      "pertanyaan": "Nge lemburin veni dari bulan 5 malah gajian masuk ke veni udah lama udah  komplen  setiap gajian komplen terus tapi  belom  turun jga uang lemburin veni ",
      "status": "Done"
    },
    {
      "user_id": "Yogi Ramdani",
      "project_id": "Lorem Ipsum",
      "created_at": "14/8/2023 1:18siang",
      "pertanyaan": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vehicula ex aliquam diam ultricies vehicula. Suspendisse potenti. Cras purus dolor, ullamcorper sed risus ac, efficitur vehicula urna. Integer rutrum turpis lacinia libero pretium facilisis. Aenean finibus, tellus ac hendrerit aliquam, lacus ipsum ultrices ipsum, quis commodo nulla sem rutrum dui. Phasellus eleifend, turpis euismod pretium egestas, quam tellus euismod metus, id varius leo magna at purus. Aliquam iaculis luctus enim vulputate varius. Vivamus commodo sit amet augue eget condimentum. Duis vitae ultrices eros. Donec suscipit hendrerit nisi feugiat vestibulum. Sed at orci lectus. Ut sit amet ligula lorem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur vitae tempor justo. Sed scelerisque dictum urna a placerat.",
      "status": "Done"
    },
    {
      "user_id": "Yonda Yoandana ",
      "project_id": "PT SYNERGI MULTI DAYA PRATAMA ",
      "created_at": "31/8/2023 2:08siang",
      "pertanyaan": "Untuk gaji periode Agustus saya mengalami kekurangan. Mohon bantuannya. Karena tidak sesuai dengan gaji di bulan kemarin. Bulan kemarin saya terima 2,8 bulan ini saya terima 2,6. Mohon info lebih lanjut pak/ibu. Terima kasih ",
      "attachment": "Screenshot_2023-08-31-14-09-23-15_e8205ec1cda7e9657fadf502be9b7b78.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/NjXJUric9kjaxt2EypZn_w/YF9hzmyr4-T1pcTbqkB7KkJJzjbX6ud4oJRJDGbJUWO4cHuMcGJqefwN5BUwKzs-CGVi1-a9fmfx0YKPwVDgg57wLt-yNdd7hFXqJNnYdQt5iQqUVRbxfNo_4oucGaXDWjEUArTQdAESNt5pbGB_4Dkcf6HHJUIJ-oprL2JsNF7M-gq8p2Eb_2f0kX6DJBZ0R17Yq17XmkGptiBXGL2oQDTOr1lQUhdaKarVl2tJKGI/qTZWfTQ34ffl1jh3oHCqbtLWeORS0mWBbi6myAuhecc)",
      "status": "Done"
    },
    {
      "user_id": "Muhammad Al Irsan",
      "project_id": "Hotel MANDARIN ORIENTAL JAKARTA ",
      "created_at": "31/8/2023 3:03sore",
      "pertanyaan": "Tolang untuk sytem peng gajian diperbaiki kok bisa kurang",
      "status": "Done"
    },
    {
      "user_id": "Ripan Supartiana",
      "project_id": "PT Niaga Logistik ",
      "created_at": "31/8/2023 4:16sore",
      "pertanyaan": "Mohon izin bertanya perihal upah yg saya terima tidak sesuai dengan upah dijanjikan di awal masuk projects yaitu 3.800.000",
      "attachment": "Screenshot_20230831_160742.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/t3SrZjaivHToNddJu7zrBg/oGHZWzv1daekZLJu5PHdiYpZHk1sPguMrGWu5A1DNBbZUND-OoXjws9vfVNCo6HhRzv_T8XRvot1Q2HJWi4Z70ADPHYnNlAksyB4sJw8qHaakBNkqJFMrTK9AbpjZRdUz4cBa0jRTCkH0kwTkCmJzswir8jVkKOTrD_jSeEfRcE/pGCHn0zgRTXqpHkxCFpboibtMuVlpX-L-bRdCeQk6qY)",
      "status": "Done"
    },
    {
      "user_id": "Wiwin",
      "project_id": "Mandarin oriental hotel",
      "created_at": "2/5/1992 1:40siang",
      "pertanyaan": "Minta tolong di check kembali mengenai kekurangan gaji saya,\r\n1. Lembur event 5jam.\r\n2. Lembur event 12jam.\r\n3. Lembur urgency. \r\n\r\n\r\n",
      "status": "Done"
    },
    {
      "user_id": "Wahyu mahesa",
      "project_id": "PT SURYA SUENN YUEH INDUSTRI",
      "created_at": "2/1/2003 12:14siang",
      "pertanyaan": "Untuk masalah penggajian saya masih kurang bulan kmren saya menerima gaji sebesar 3.684.000",
      "attachment": "IMG_1774.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/nwURuik0XWXobvS_-D0yLw/C8LKQYKHMCYNKP3Ibv4pPYTP8LIU12zHZI31wCGvnTvVH0lh1ZO2IxWMpbwyAVXzWYMEZxaleVLWc4i4ug52hBfKzaPhAuAvXcgQVtyoe_kZXtvFNOYENQe4VPSHglOzmHfXjWwAtabfdqHTpr5Flg/25crK8NoAY0NPsJlverLkJXAwlMuLbhzy8NKYlFIVEk)",
      "status": "Done"
    },
    {
      "user_id": "Adi Wahyu Setiawan ",
      "project_id": "PT Surya shuenn yueh industri ",
      "created_at": "5/9/2023 8:34pagi",
      "pertanyaan": "Selamat Pagi komandan dari PT. SSY ijin Kordinasi terkait Taruna Kekurangan Hasil Pendapatan dari anggota sbb\r\n\r\nNama : Adi Wahyu Setiawan\r\nProject : PT. SSY\r\nHasil pendapatan Periode 21 Juli - 20 Agustus 2023 sebesar Rp 3.688.000 sementara periode 21 Juni - 20 Juli 2023 sebesar Rp  4.034.000",
      "attachment": "IMG_20230905_083154_672.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/QZejoVXWFw8Q7Mpc6N8KsQ/s3bUX6KS3Rda2CZD2zIIjh751tGlfT80ngEO4-F6OzXFQrrQy-2X9pSXklDwLwW5uufUo70EDo8cg5lb039qaCuEJGAWwXvxdSUj-EUekGYLefBqv-sD3zW-0lvIIPXkDeGllo2K46FlFEGQeH1-IkAf_WgTi238Wkm2-lIBJqs/GTx9W2s-Yk8E_QelM5dpiL6mLWxleP9jB9b9nsDxFZU)",
      "status": "Done"
    },
    {
      "user_id": "Suganda ",
      "project_id": "Mercure hotel PIK ",
      "created_at": "5/9/2023 12:54siang",
      "pertanyaan": "Selamat siang komandan ijin.saya mau bertanya masalah gaji saya. saya sudah sudah bekerja satu tahun lebih.terakhir menerima gaji kemarin 3.4 terimakasih tolong dibantu komandan terimakasih,ðŸ™ dan untuk GP pun saya belum menerima nya.",
      "status": "Done"
    },
    {
      "user_id": "Hasbullah",
      "project_id": "Mercure jakarta simatupang",
      "created_at": "5/9/2023 1:36siang",
      "pertanyaan": "Selamat siang pak.. ijin saya mau komplain gajih saya di bulan agustus.. kekurangan gajih saya sebesar 300.000,00.. ",
      "attachment": "IMG_20230905_134050.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/YvY6xBV_btDtIf82oqP1_g/dQ7JqLmB-E7drntEsXU38UQiUrODrjswNK-c8m8bzz52cVVrWL8Re618LlIU4j9oCOM6vGpI_LUS5AA5HwnXom00wXvvfPuMLVCR9MwVMTMAQZhCPhrVZv71DflCe8t4H2Kk7wnd5vkcTIgiJBBQpHABgEGLKFvzYfi4-e88s6A/MTL7OVut41JISFA1hD8GoubELDGoWd-AuCHtIJNrpCU)",
      "status": "Done"
    },
    {
      "user_id": "Hasbullah",
      "project_id": "Mercure jakarta simatupang",
      "created_at": "5/9/2023 1:44siang",
      "pertanyaan": "Bulan juli gajih saya kurang dan bulan agustus gajih saya kurang lagi. Mohon komandan agar kekurangan gajih saya bisa segera di transfer. Kekurangan gajih bulan agustus sebesar 350.000,00.\r\n3.190.000 yg masuk bulan agustus dan seharusnya 3.550.000. ",
      "attachment": "IMG_20230905_134050.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/6B5gYMk0_VEgDnhKABK4oA/nnsLbNgg0EyI9uB0cri2E9t-k3cHOIdesbdADqJWl-tPCf3Ehn7b0II23mpT5NJHA3o-SljqIhmFKq8xa71DbJLoGVJ5N-gZyn3Cspje8hA7-OuIAzPwujtjIzGjHaoIogeJ28oMRugRrs1jujE2A7xeq2yv8Jf8_D1mufofNy0/eiFoxcm_Lli3zp8VRk828d9yZXRiTO92WMIgjoHKWwM)",
      "status": "Done"
    },
    {
      "user_id": "Pra dirta jaya",
      "project_id": "Mercure Jakarta Simatupang",
      "created_at": "5/9/2023 1:58siang",
      "pertanyaan": "Ijin bapak/ ibu, mohon segera di proses untuk gaji kami anggota security hotel Mercure Simatupang yg kurang gaji. Terimakasih",
      "status": "Done"
    },
    {
      "user_id": "Nurman",
      "project_id": "Ipeka pluit ",
      "created_at": "5/9/2023 2:21siang",
      "pertanyaan": "Selamat siang mas/mba saya ingin mempertanyakan kekurangan gaji saya,sepertinya untuk urc yang tanggal 5 agustus acara open house belom di bayarkan, dengan ini saya berharap untuk langsung di proses adapun penjelasan dari pihak terkait. Demikian atas waktunya saya ucapkan terimakasih ",
      "status": "Done"
    },
    {
      "user_id": "Rachmat Hidayat",
      "project_id": "IPEKA PLUIT",
      "created_at": "5/9/2023 2:48siang",
      "pertanyaan": "Gaji Lemburan kurang.\r\n\r\nseharusnya nerima Rp.1.070.000 tapi di slip gaji Rp.762.108.\r\n\r\nkurang Rp.307.892.",
      "attachment": "Screenshot_20230905-113336.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/I93q8uMjQadDT2T5M06a7Q/bBT5vPiL7ysjpnGtjFWlEI6RLYbEaUyInIDfhgtYiCxp7A50i_WSo68ouSgSIXX9Pd6B_57UqbAmBEYBTGSelfIHrmam1eYG2riqgps788kiVoaGVuCnTmGxvzeeo2IxmXJFLAr1I--pQ3DhF3FKM2EvYzRXdcCkNc165unPSsg/TphLH6Flw091FYpn450rWdDWofPIwbQjJHMeu67Xn0U),Screenshot_20230905-144805.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/CfC5yLEjVpqE-VTB3oR0Dw/ws9aBw77EnCxYYAKzBdiQrvjatAMak-IR4Sr02FpKCNo7jZxxaubRpd1567MGh3Cm6K8KTIsuZ1wG8rcWfK5mxjEcjHKJ9Zwx-rjs3m6sHZz8_ocEprhgIdApNfFAnBiOBH8xPhZyQeiQUL70itXxyW6MIgRkCUBcpAsP8tgERw/oHTY1ZRdEx55Rwl_n_rw3sbbraDA-ce3RwyZ76fah6U)",
      "status": "Done"
    },
    {
      "user_id": "Riyan Rivano ",
      "project_id": "IPEKA PLUIT ",
      "created_at": "5/9/2023 3:07sore",
      "pertanyaan": "Atas nama RIYAN RIVANO project IPEKA PLUIT, penggajian gaji pokok kurang 1 hari belum dibayar. terima kasih ",
      "status": "Done"
    },
    {
      "user_id": "Andika saputra",
      "project_id": "Ipeka pluit",
      "created_at": "5/9/2023 4:59sore",
      "pertanyaan": "Belum menerima slip gaji lewat email",
      "status": "Done"
    },
    {
      "user_id": "Mulyana",
      "project_id": "Ipeka pluit",
      "created_at": "5/9/2023 4:56sore",
      "pertanyaan": "Total lembur 7 yang di bayar baru 6 ",
      "status": "Done"
    },
    {
      "user_id": "Mulyana",
      "project_id": "Ipeka pluit",
      "created_at": "5/9/2023 5:34sore",
      "pertanyaan": "Lemburan 5 yang baru di bayar 4",
      "status": "Done"
    },
    {
      "user_id": "Vijay Priyanto",
      "project_id": "Ipeka pluit",
      "created_at": "5/9/2023 5:33sore",
      "pertanyaan": " ada kekurangan pembayaran urc 1x di tanggal 5 ya",
      "status": "Done"
    },
    {
      "user_id": "Vijay Priyanto",
      "project_id": "Ipeka pluit",
      "created_at": "5/9/2023 5:39sore",
      "pertanyaan": "Ada  kekurangan pembayaran 1x urc di tanggal 5 ",
      "status": "Done"
    },
    {
      "user_id": "Saipulloh Amin",
      "project_id": "IPEKA PLUIT",
      "created_at": "5/9/2023 7:06malam",
      "pertanyaan": "lembaran urc 2 kok yang masuk 1",
      "status": "Done"
    },
    {
      "user_id": "Sri Suprihatiningsih ",
      "project_id": "Tentang kekurangan gaji",
      "created_at": "6/9/2023 7:23pagi",
      "pertanyaan": "Pagi.. sya Sri Suprihatiningsih mau tanya tentang gaji sya lmburan dua tnggal :\r\n1. 9-8-2023 lemburan risaign si Ruri\r\n2. 15-8-2023 lembur Yeyen sakit\r\nDan uang jabatan 200rb knp di potong absensi sebesar itu sedangkan sya absen trus di talenta pindah ke mofis Bru tanggal setelah acara 17agustus blm ada sebulan dr talenta ke mofis. Dan... Sya absen di.mofis awalnya BS tp sudah brp hri ini tidak bs2 check in dan check out nya. Blm ada sebulan mofis Bru 2/3minggu knp di potong sebulan smpai 2rtus RB lbih???",
      "status": "Done"
    },
    {
      "user_id": "Ana Maria Sofiana ",
      "project_id": "Mandarin oriental ",
      "created_at": "6/9/2023 9:11pagi",
      "pertanyaan": "Gaji kurang 230.000 ",
      "attachment": "Screenshot_2023-09-06-09-05-12-60_e8205ec1cda7e9657fadf502be9b7b78.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/iGp7-nS_CM5rQq0vKako3g/3TVdbMuL2Ozhxq_vKuhNa0rcXevfFZ-vaxSfQZ3ZV6pYbUbucU6DKuEnFH6tEr2IrC13YMvLZq3bjAhaJ0EjeVKbN0ZDu0ufF3_1-qet6l6fJE2wZGaO54hdt-cXQl9Xpsyrq3Xd8ACDJu11KIHc_DRJH8oFY5ENMsIyE5etWkZTUiWAApSx_rVw8MmZacYT2m1nfartTMLYs02FA_8MDk-s6Zi8zvIg0MrrVtWRmtk/KBjOXevBrDhidy9xf4o4bOC-N-oJ05FkuhZhYFIttPI),Screenshot_2023-09-06-09-03-53-82_439a3fec0400f8974d35eed09a31f914.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/_35FEMR38HpldM4Pcd6PWQ/kROz2l3ODQKUw7aZPt7OyS6ZWZ57lwJuxtQjpxc3I2X4AujAkNmKo-EhQTkRbeCwmztvr4N0mWuzPL5NDuMaNH1LoaSXsq4rUabhmH1OIC6BxUWzXwz9z2XZ-SE3ETn5Qz4v0FdlMR7Q8s4-9zui9RVcHx9qNBsD7-OR3yqeo_dagk8STyX2L-JtrNvrGQeolcoPLk4uidKFDvrEOEohID4W-yRJnr803VSHglfhY7g/d1mv1LWTSavkIms2X33sLkT6qZLZ9SmvUMWNglX4_DM)",
      "status": "Done"
    },
    {
      "user_id": "Andika Nurrohman Wahid ",
      "project_id": "Hotel Mandarin oriental Jakarta ",
      "created_at": "6/9/2023 9:25pagi",
      "pertanyaan": "Mohon maaf pak Andre kenapa keterangan di slip gaji dari bulan-bulan kemarin masuk kerja 21 & 20 hari itu nominalnya sama, harusnya beda ya pak jika di kalkulasikan jumlahnya tidak mungkin sama. Mohon di cek kembali kurangnya ðŸ™",
      "status": "Done"
    },
    {
      "user_id": "Agri wijaya hamidin ",
      "project_id": "Novotel cikini ",
      "created_at": "6/9/2023 10:26pagi",
      "attachment": "Screenshot_20230906_103224_WPS Office.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/ISlHWFf_h9LAluoxJYgD1Q/rw4HP2KpC1ZZbgHMVpET-59_kgh7ziuHzOzF7s29DKs_LxxbkEHV05opgDybgQrZ8w6_lrf3ETe0Xmg8D76cdppAriKCoOcf3L0zQbA0x7yVKHe2MfoEJRNbzKPo7tOOSvrbEFFekG55I-FRp_pt2qkLWAc2RJMng8F7XKMBM3RvCaROynu7zYwlNY5VZ7gf/rTGmorPASt9KdO308D-_XMiyUsYlCZMQ3TavoXOPLLg)",
      "status": "Done"
    },
    {
      "user_id": "Muhammad Iqbal Irianto ",
      "project_id": "Mandarin oriental Jakarta ",
      "created_at": "6/9/2023 10:41pagi",
      "status": "Done"
    },
    {
      "user_id": "Jaka umbara ",
      "project_id": "Swisshotel ",
      "created_at": "6/9/2023 5:11sore",
      "pertanyaan": "Slmt sore pak. \r\nPerihal masalah hitungan gaji saya penepatan sebelumnya di IPEKA blm dapat gaji terus perihal di Swisshotel pik gaji masih kurang seharunya itungan absen 16 hari saya masuk kerja tapi di selip gaji cuman 13 hari. Total kerja saya 21 hari bukan 13hari tolong pak kurang gaji saya di proses karena itu pun hak anggota juga membutuhkan gaji itu.",
      "status": "Done"
    },
    {
      "user_id": "Nuralam nugraha",
      "project_id": "Mandarin oriental",
      "created_at": "6/9/2023 12:00pagi",
      "pertanyaan": "gaji yang diterima tgl 31/08/2023 kurang nerima 4.1 juta. lemburan saya dua tgl 31/07/2023 dan 05/08/2023",
      "status": "Done"
    },
    {
      "user_id": "Muhammad Iqbal Irianto ",
      "project_id": "Mandarin oriental Jakarta ",
      "created_at": "6/9/2023 7:09malam",
      "status": "Done"
    },
    {
      "user_id": "Kiki Eriana",
      "project_id": "Mandarin Oriental Jakarta ",
      "created_at": "7/9/2023 1:30siang",
      "pertanyaan": "Please lebih teliti again ",
      "status": "Done"
    },
    {
      "user_id": "Rachmat Hidayat",
      "project_id": "IPEKA Pluit",
      "created_at": "7/9/2023 2:59siang",
      "pertanyaan": "kekurangan di gaji Lemburan.\r\n\r\ntotaL Lemburan 7x,, dengan rincian sebagai berikut :\r\nLembur back up di ipeka 3x.\r\nLembur di projek Lain 1x ( mercure pik ).\r\nLembur URC 3x.\r\n\r\ndi slip gaji hanya menerima 762.108.",
      "attachment": "Screenshot_20230905-113336.png (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/SV0HjWrFkGOiPLDVjCuMdg/aGXqoFNQ-KjoiftzFwNPpyM3IIGTE2V_a28vG8bbBvbOo8XRWKbgsS4uTbcqPUpKxC21EBpCfZm6TNB1-tDJANlw-3bMvdWAayc58tCXtmgwuZ9lr-_R-0zlIHXQ42VmKWBjFUNzM3zVshkhCIluuSJZajH3n16bHOhXiHOK_V4/w30NclMxE8fsHpGrz1ehD8NorOIR_ONtoD5kLIxHInE)",
      "status": "Done"
    },
    {
      "user_id": "Pra dirta jaya",
      "project_id": "Mercure Jakarta Simatupang",
      "created_at": "9/9/2023 3:16sore",
      "pertanyaan": "Ijin komandan. Kapan gaji kekurangan kami keluar? Beri info agar kami tidak berharap dan menunggu, terimakasih",
      "status": "Done"
    },
    {
      "user_id": "Aditiya Eko Prasetiyo",
      "project_id": "Courts KHI",
      "created_at": "12/9/2023 12:25siang",
      "pertanyaan": "Assalamualaikum wr wb pak izin bertanya?\r\n\r\nSaya di tanyakan surat tugas  oleh chief saya dan setiap pulang ,saya di tanyakan member parkir oleh petugas parkir .Dan HRD Courts Menanyakan . Karena belum ada surat tugas jadi belum bisa buat member , mohon info selanjutnya pak ðŸ™ðŸ»\r\n\r\nTerimakasih ðŸ™ðŸ»",
      "attachment": "Screenshot_20230912-122801_WhatsAppBusiness.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/HGSo_PRqTV4SHk-uFyqnLg/i0xAFYwFLsHgbB2FfGPuHi3nYyfa74XvcR4_I8831NrWyK9T4PFe35nQdjgZvztvCD918VorI5vG0sJnl7MfkCC17c7tSNi8OQx3txPmqu_IE-QyTfL7LT2PIQ18udLuGRJKJjVGiGIir-1g5XLZ49J3BGctWIxnZtLBoO6jN7dcdOLFYmC3CcB12_6DsaTK/V5ryCFDGNSgR6FwpcGHtQlyCqeEowDYVMl35Wc6txXQ)",
      "status": "Done"
    },
    {
      "user_id": "Ahmad Maulana ",
      "project_id": "Novotel Jakarta cikini",
      "created_at": "16/9/2023 11:22malam",
      "pertanyaan": "Kenapa gajih sekarang bulan Agustus  Nerima 4,1 seharus nya Nerima 4,3",
      "attachment": "Screenshot_2023-09-16-20-28-47-89_e2d5b3f32b79de1d45acd1fad96fbb0f.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/tqHSnH7hYsaQYACZyTi8wQ/RYN0zO5sbpnmxFbWimcEADm_qWEOqt5PBcYGJJ58KA3g7nGuyJ8auf0_Er6tH_lwQsodtfJaztndLuIJFeylWAE7WMg3bsiovZII6XFcpD3Ui38r2eMonJy7WXFGNjnaQ1J0B1tAg-8ebPlIf_JechZgEVyo-fsmQQiOyeln6wNO-mo24nSQVYdY4pP2NDgIZkporkypG2Vev3kwJl0WrF1tQdCNFdMS2X4gpokd1IU/7OXrNxr33Jk7rlzv0TOniQB-ps-qs3N2MBuEHIOpItE)",
      "status": "Done"
    },
    {
      "user_id": "YOGI",
      "project_id": "ESCA",
      "created_at": "25/10/2023 10:55pagi",
      "pertanyaan": "TEST",
      "status": "Done"
    },
    {
      "user_id": "Alpi pebriansyah",
      "project_id": "Mandarin oriental jakarta",
      "created_at": "31/10/2023 12:31siang",
      "pertanyaan": "mohon ijin gaji saya kurang sudah 2 bulan terkait tunjangan jabatan",
      "status": "Done"
    },
    {
      "user_id": "Rita Novita",
      "project_id": "Mandarin oriental",
      "created_at": "30/12/2023 8:00pagi",
      "pertanyaan": "Saya tidak setuju atas pemotongan GP sebesar 1.050.000, jadwal saya yang dikasih admin mandarin untuk mengikuti GP yaitu selama 2 hari, tetapi kena potongan 4 hari pelatihan GP.\r\nSaya lebih memprioritaskan masuk dimandarin karna tidak ada backup dimandarin (Kekurangan orang) yang harusnya saya tanggal 25 naik jaga kemalam untuk mengikuti GP tetapi saya tidak bisa hadir karna tanggal 24 yang tadinya masuk pagi ditarik malem. Saya tidak bisa hadir karna masih 3 malam dan malamnya saya habis dari HO untuk tes urin dan bela belain hujanan dari kerjaan untuk datang ke HO. Saya udah konfirmasi ke ndan wahyu tetapi tidak ada respon..\r\n",
      "attachment": "57e7ca94-afad-442e-865e-8366e3e207c9.jpeg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/SJINmuzOJc2Fpw2kXNChJA/MkdkSYjFZW7LDKHAyjulhCFKeU-kwMAVCFz8rZUtVkPD0-V6mxejBFqhG5PTtMlSBYYdl99vujKHkpiFXHiXxevWJjV8wGLQ7EboKFrc_bXkBdmUPA1r1p9An8V1KVUVDv_c-x1J1XhvcS9U7oOKAb3I2gTsEyCZeQN6sGkUSzALM47V3-AYcpQLx4ZZaSb9/q5TjfIrVl6pRWYbbVicnNMrgcTzTbp-RVS57jomxwwo),c3658b74-15d0-406a-be32-d11d39c391ff.jpeg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/xUag4K5Re4h5rk2prLO5og/YKQhmHZr5Fuypq6KYP8576yB76rtXeQEo5XyKv2sXbQ-GUz9b-sMRl4sBjPEUbvSyeTMjktqYdki3r16tz0prjwE5DDzobH2ZsLimgwLjsc1Bw32x-2-9ynBkLiH2TRgYLWlEV6_WTKr7t2Movi9fOfj0UpKQoENSd6fuBpMAabIwuiNmIn6Ho9GcbZTx9bw/rsLOyNcB-kuNTxX3XJTjj3pqq7I3hrq5r5C4YIXV2eU)",
      "status": "Done"
    },
    {
      "user_id": "Hasbullah",
      "project_id": "Mercure Jakarta Simatupang ",
      "created_at": "3/1/2024 7:26malam",
      "pertanyaan": "Selamat malam komandan.. ijin bertanya perihal potongan diksar saya . Apakah sudah selesai ya Ndan? Soalnya di projek Mercure Simatupang saya sndiri yg Nerima gaji 3.200.000 yg lain 3.550.000. ",
      "status": "Done"
    },
    {
      "user_id": "Rian saputra",
      "project_id": "Mandarin oriental",
      "created_at": "4/1/2024 9:00pagi",
      "pertanyaan": "Lembur saya cuma keinput satu harunya saya 2 kali lembur back up. Tgl 26 & 29 mohon izin dibantu",
      "attachment": "Screenshot_2024-01-04-01-09-18-151_com (1).jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/8QI3PuiYRnxFvO6M-Z5I3w/xa-DhDnffMkGqmjUoEEaavtcWjr1KEQ7pnkMJqWMPshfJgCES4WDS8UJO757dHfYsryVpdE-G1aWzyQ3uLv7_J7LmFLKQycdkfxC2agajQYJp4wxMv9IIGFWOUuSUZ3da7RxeZkFJfXEwLIfQha2pQapTVfqRaq3Utpd0DZtF-bEVddqIMmBBT7Y1na2O6V9/xzv0FMpLmMEsCejgZOow8U2KYUcXGKU8kIBW7fqdxdk)",
      "status": "Done"
    },
    {
      "user_id": "Diyan teger ",
      "project_id": "Mandarin ",
      "created_at": "4/1/2024 5:13sore",
      "pertanyaan": "Di bantu pak lembur tgl 2 Desember sya ngk ke input atau ngk masuk ðŸ™",
      "status": "Done"
    },
    {
      "user_id": "Diyan teger ",
      "project_id": "Hotel Mandarin oriental Jakarta ",
      "created_at": "4/1/2024 5:46sore",
      "pertanyaan": "Di bantu Tgl 2 Desember sya lembur pgi tapi ngk masuk ke gaji atau ngk ke input ðŸ™",
      "status": "Done"
    },
    {
      "user_id": "Rizky Abiola ",
      "project_id": "IBIS hotel raden saleh ",
      "created_at": "31/1/2024 7:40malam",
      "pertanyaan": "Tolong jelaskan sistem penggajian dan potongan yang tertera di slip gaji saya bulan ini, karena saya kurang paham dengan isis slip gaji saya",
      "status": "Done"
    },
    {
      "user_id": "Mebi perta",
      "project_id": "Pt surya shuen yueh",
      "created_at": "28/3/2024 6:16sore",
      "pertanyaan": "Selamat sore pak andre..maaf pak saya mau konfirmasi mslah gaji..saya kan incash dri tgl 6 maret ke 20 maret aktif 9 hari di tambah Beckup 2..sementara brian ardani aktif 7 hari tmbah bekup 3 hari.gajinya lebih besar dia dripda saya..mohon arahan nya pak",
      "status": "Done"
    },
    {
      "user_id": "Mebi perta",
      "project_id": "Pt surya shuen yueh",
      "created_at": "28/3/2024 6:26sore",
      "pertanyaan": "Selamat malam pak andre.saya mau konfirmasi msalah gaji.saya mulai incas dri tgl 6.di tambah beckup 2 hari. Saya nerima gaji 2,3.\r\nSdangkan punya rian di aktif dri tgl 9+beckup 3 hari..dia nerima gaji 3,4.mohon arahan nya pak\r\n",
      "status": "Done"
    },
    {
      "user_id": "SYAHRUDIN ",
      "project_id": "PT SINAR SURYA ALUMINDO ",
      "created_at": "5/4/2024 9:35pagi",
      "pertanyaan": "Selamat pagi komandan izin komentar tentang perihal THR dari dulu setiap tahun dapat THR 3800000",
      "attachment": "IMG_20240405_093321.jpg (https://v5.airtableusercontent.com/v3/u/32/32/1724328000000/auTC0hLPip4PyUFdGiXlTw/ygEUjmCHSLgok_bfNhYR-zQgvW0-HXeXB23vufYqwSGVca6Djc3OXysZnUQV5aZXzbafN6c9mWeX3vfOTyVbGQ2YL6oDF5DwCzHQUruiJ9e0Bizby2LoUGluOspZjCB6sjFE5cVVk7Msh-Xpxe9nCUVYpktvooWLCRow-jpvu04/qwiXOKehXJ5aduH8jI1mGdc3iyMwW5hLWUzSiY_f0P0)",
      "status": "Done"
    },
    {
      "user_id": "NUR HIDAYAT",
      "project_id": "swissotel",
      "created_at": "31/7/2024 7:22malam",
      "pertanyaan": "kalau saya boleh tau kenapa tadi gaji saya turun 3,1 apakah dupotong dengan gp dan kesehatan? \r\nterimakasih ",
      "status": "Done"
    }
  ]';
    $query="";
    foreach(json_decode($json) as $row) {
        // Split the date and time
        if(!empty($row->created_at)){
            $dateParts = explode(' ', $row->created_at);
    echo "insert into `voiceof_guardians` (`user_id`, `project_id`, `created_at`, `pertanyaan`, `status`) values ('".$row->user_id."','".$row->project_id."','".date('Y-m-d', strtotime($dateParts[0]))."','".@$row->pertayaan."','".@$row->status."');<br/>";
        }
        
        
        
    }

    dd();

        return view('pages.voice.index');
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
        //
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
