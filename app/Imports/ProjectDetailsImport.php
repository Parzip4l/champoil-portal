<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\ModelCG\ProjectDetails;

class ProjectDetailsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return ProjectDetails|null
     */
    public function model(array $row)
    {
        return new ProjectDetails([
            'project_code' => $row['project_code'],
            'jabatan' => $row['jabatan'],
            'kebutuhan' => $row['kebutuhan'],
            'p_gajipokok' => $row['p_gajipokok'],
            'p_bpjstk' => $row['p_bpjstk'],
            'p_bpjs_ks' => $row['p_bpjs_ks'],
            'p_thr' => $row['p_thr'],
            'p_tkerja' => $row['p_tkerja'],
            'p_tseragam' => $row['p_tseragam'],
            'p_tlain' => $row['p_tlain'],
            'p_training' => $row['p_training'],
            'p_operasional' => $row['p_operasional'],
            'p_membership' => $row['p_membership'],
            'r_deduction' => $row['r_deduction'],
            'p_deduction' => $row['p_deduction'],
            'tp_gapok' => $row['tp_gapok'],
            'tp_bpjstk' => $row['tp_bpjstk'],
            'tp_bpjsks' => $row['tp_bpjsks'],
            'tp_thr' => $row['tp_thr'],
            'tp_tunjangankerja' => $row['tp_tunjangankerja'],
            'tp_tunjanganseragam' => $row['tp_tunjanganseragam'],
            'tp_tunjanganlainnya' => $row['tp_tunjanganlainnya'],
            'tp_training' => $row['tp_training'],
            'tp_operasional' => $row['tp_operasional'],
            'tp_ppn' => $row['tp_ppn'],
            'tp_pph' => $row['tp_pph'],
            'tp_cashin' => $row['tp_cashin'],
            'tp_total' => $row['tp_total'],
            'tp_membership' => $row['tp_membership'],
            'tp_bulanan' => $row['tp_bulanan'],
            'rate_harian' => $row['rate_harian'],
        ]);
    }
}
