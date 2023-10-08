$(document).ready(function() {
    // Fungsi untuk menghitung total
    let idCounter = 0;
    function calculateTotal() {
        let total = 0;
        let totalKebutuhan = 0;
        idCounter++;
        // Menghitung total dari allowances
        $('.allowences').each(function() {
            const allowanceValue = parseFloat($(this).val()) || 0;
            total += allowanceValue;
        });

        // Menghitung total kebutuhan anggota
        $('.kebutuhan-anggota').each(function() {
            const kebutuhanAnggota = parseFloat($(this).val()) || 0;
            totalKebutuhan += kebutuhanAnggota;
        });
        
        const kebutuhanAnggotaVal = parseFloat($('.kebutuhan-anggota').val()) || 0;
        const penawaranGajiPokok = parseFloat($('#GajiPokok').val()) || 0;
        const bpjstk = parseFloat($('#p_BPJS_TK').val()) || 0;
        const bpjsks = parseFloat($('#bpjsks').val()) || 0;
        const thr = parseFloat($('#thr').val()) || 0;
        const kerja = parseFloat($('#kerja').val()) || 0;
        const seragam = parseFloat($('#seragam').val()) || 0;
        const Lain = parseFloat($('#lainlain').val()) || 0;
        const training = parseFloat($('#training').val()) || 0;
        const operasional = parseFloat($('#operasional').val()) || 0;
        const MemberShip = parseFloat($('#MemberShipAwal').val()) || 0;
        const realDeduction = parseFloat($('#real_deduction').val()) || 0;
        const totalGapok = kebutuhanAnggotaVal * penawaranGajiPokok;
        const totalBPJSTK = bpjstk * kebutuhanAnggotaVal;
        const totalBpjsks = bpjsks * kebutuhanAnggotaVal;
        const totalthr = thr * kebutuhanAnggotaVal;
        const totalkerja = kerja * kebutuhanAnggotaVal;
        const totalseragam = seragam * kebutuhanAnggotaVal;
        const totallain = Lain * kebutuhanAnggotaVal;
        const totaltraining = training * kebutuhanAnggotaVal;
        const totaloperasional = operasional * kebutuhanAnggotaVal;
        const totalmembership = MemberShip * kebutuhanAnggotaVal;

        // Mengalikan total allowances dengan total kebutuhan anggota
        const TotalSeluruh = total * totalKebutuhan;
        const UbahDataPersen = realDeduction / 100;
        const GajiBulan = Math.round(penawaranGajiPokok + kerja + Lain) * (1 - UbahDataPersen);
        const rateHarian = (penawaranGajiPokok * 12) / 244;

        // Mengembalikan total
        return {
            totalGapok: totalGapok,
            totalBPJSTK: totalBPJSTK,
            totalBpjsks: totalBpjsks,
            totalthr: totalthr,
            totalkerja: totalkerja,
            totalseragam: totalseragam,
            totallain: totallain,
            totaltraining: totaltraining,
            totaloperasional: totaloperasional,
            totalmembership: totalmembership,
            TotalSeluruh: TotalSeluruh,
            GajiBulan: GajiBulan,
            rateHarian: rateHarian
        };
    }

    // Fungsi untuk memperbarui total
    function updateTotal() {
        const total = calculateTotal();
        const totalMembership = total.totalmembership;
        const TotalSeluruh = total.TotalSeluruh;
        const GajiBulan = Math.round(total.GajiBulan);
        const GariHarian = Math.round(total.rateHarian);

        const ppn = Math.round(totalMembership * 0.11);
        const pph = Math.round(totalMembership * 0.02);

        const Cashin = (TotalSeluruh + totalMembership + ppn) - pph;

        
        $('#penawaran_total').val(TotalSeluruh); 
        $('#TotalGapok').val(total.totalGapok); 
        $('#TotalBPJSTK').val(total.totalBPJSTK);
        $('#TotalBPJSKS').val(total.totalBpjsks);
        $('#totalthr').val(total.totalthr); 
        $('#totalkerja').val(total.totalkerja);
        $('#TotalTSeragam').val(total.totalseragam);
        $('#TotalTLainnya').val(total.totallain); 
        $('#TotalTraining').val(total.totaltraining);
        $('#TotalOperasional').val(total.totaloperasional);
        $('#TotalMembership').val(total.totalmembership);
        $('#TotalPPN').val(ppn);
        $('#TotalPPH').val(pph);
        $('#TotalCashin').val(Cashin);
        $('#rate_bulan').val(GajiBulan);
        $('#rate_harian').val(GariHarian);
    }
    
    updateTotal();

    // Panggil updateTotal saat input berubah
    $('.allowences, .kebutuhan-anggota, #GajiPokok, #p_BPJS_TK, #bpjsks, #thr, #kerja, #seragam, #lainlain, #training, #opersional, #MemberShipAwal, #real_deduction').on('input', function() {
        updateTotal();
    });
});

