$(document).ready(function() {
    // Fungsi untuk menghitung total
    let idCounter = 0;
    function calculateTotal1() {
        let total1 = 0;
        let totalKebutuhan1 = 0;
        idCounter++;
        // Menghitung total dari allowances
        $('.allowences1').each(function() {
            const allowanceValue1 = parseFloat($(this).val()) || 0;
            total1 += allowanceValue1;
        });

        // Menghitung total kebutuhan anggota
        $('.kebutuhan-anggota1').each(function() {
            const kebutuhanAnggota1 = parseFloat($(this).val()) || 0;
            totalKebutuhan1 += kebutuhanAnggota1;
        });
        
        const kebutuhanAnggotaVal1 = parseFloat($('.kebutuhan-anggota1').val()) || 0;
        const penawaranGajiPokok1 = parseFloat($('#GajiPokok1').val()) || 0;
        const bpjstk1 = parseFloat($('#p_BPJS_TK1').val()) || 0;
        const bpjsks1 = parseFloat($('#bpjsks1').val()) || 0;
        const thr1 = parseFloat($('#thr1').val()) || 0;
        const kerja1 = parseFloat($('#kerja1').val()) || 0;
        const seragam1 = parseFloat($('#seragam1').val()) || 0;
        const Lain1 = parseFloat($('#lainlain1').val()) || 0;
        const training1 = parseFloat($('#training1').val()) || 0;
        const operasional1 = parseFloat($('#operasional1').val()) || 0;
        const MemberShip1 = parseFloat($('#MemberShipAwal1').val()) || 0;
        const realDeduction1 = parseFloat($('#real_deduction1').val()) || 0;
        const totalGapok1 = kebutuhanAnggotaVal1 * penawaranGajiPokok1;
        const totalBPJSTK1 = bpjstk1 * kebutuhanAnggotaVal1;
        const totalBpjsks1 = bpjsks1 * kebutuhanAnggotaVal1;
        const totalthr1 = thr1 * kebutuhanAnggotaVal1;
        const totalkerja1 = kerja1 * kebutuhanAnggotaVal1;
        const totalseragam1 = seragam1 * kebutuhanAnggotaVal1;
        const totallain1 = Lain1 * kebutuhanAnggotaVal1;
        const totaltraining1 = training1 * kebutuhanAnggotaVal1;
        const totaloperasional1 = operasional1 * kebutuhanAnggotaVal1;
        const totalmembership1 = MemberShip1 * kebutuhanAnggotaVal1;

        // Mengalikan total allowances dengan total kebutuhan anggota
        const TotalSeluruh1 = total1 * totalKebutuhan1;
        const UbahDataPersen1 = realDeduction1 / 100;
        const GajiBulan1 = Math.round(penawaranGajiPokok1 + kerja1 + Lain1) * (1 - UbahDataPersen1);
        const rateHarian1 = (penawaranGajiPokok1 * 12) / 244;

        // Mengembalikan total
        return {
            totalGapok1: totalGapok1,
            totalBPJSTK1: totalBPJSTK1,
            totalBpjsks1: totalBpjsks1,
            totalthr1: totalthr1,
            totalkerja1: totalkerja1,
            totalseragam1: totalseragam1,
            totallain1: totallain1,
            totaltraining1: totaltraining1,
            totaloperasional1: totaloperasional1,
            totalmembership1: totalmembership1,
            TotalSeluruh1: TotalSeluruh1,
            GajiBulan1: GajiBulan1,
            rateHarian1: rateHarian1
        };
    }

    // Fungsi untuk memperbarui total
    function updateTotal1() {
        const total1 = calculateTotal1();
        const totalMembership1 = total1.totalmembership1;
        const TotalSeluruh1 = total1.TotalSeluruh1;
        const GajiBulan1 = Math.round(total1.GajiBulan1);
        const GariHarian1 = Math.round(total1.rateHarian1);

        const ppn1 = Math.round(totalMembership1 * 0.11);
        const pph1 = Math.round(totalMembership1 * 0.02);

        const Cashin1 = (TotalSeluruh1 + totalMembership1 + ppn1) - pph1;

        
        $('#penawaran_total1').val(TotalSeluruh1); 
        $('#TotalGapok1').val(total1.totalGapok1); 
        $('#TotalBPJSTK1').val(total1.totalBPJSTK1);
        $('#TotalBPJSKS1').val(total1.totalBpjsks1);
        $('#totalthr1').val(total1.totalthr1); 
        $('#totalkerja1').val(total1.totalkerja1);
        $('#TotalTSeragam1').val(total1.totalseragam1);
        $('#TotalTLainnya1').val(total1.totallain1); 
        $('#TotalTraining1').val(total1.totaltraining1);
        $('#TotalOperasional1').val(total1.totaloperasional1);
        $('#TotalMembership1').val(total1.totalmembership1);
        $('#TotalPPN1').val(ppn1);
        $('#TotalPPH1').val(pph1);
        $('#TotalCashin1').val(Cashin1);
        $('#rate_bulan1').val(GajiBulan1);
        $('#rate_harian1').val(GariHarian1);
    }
    
    updateTotal1();

    // Panggil updateTotal saat input berubah
    $('.allowences1, .kebutuhan-anggota1, #GajiPokok1, #p_BPJS_TK1, #bpjsks1, #thr1, #kerja1, #seragam1, #lainlain1, #training1, #opersional1, #MemberShipAwal1, #real_deduction1').on('input', function() {
        updateTotal1();
    });
});