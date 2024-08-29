<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 650px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            font-size: 14px;
            margin: 0;
            text-align: left;
            flex-grow: 1;
        }
        .confidential {
            color: red;
            font-size: 12px;
            text-align: right;
        }
        .info-table {
            width: 100%;
            margin-top: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 150px;
            font-weight: bold;
        }
        .earnings-deductions {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        .earnings-deductions th, .earnings-deductions td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .earnings-deductions th {
            background-color: #f2f2f2;
        }
        .earnings-deductions tfoot td {
            font-weight: bold;
        }
        .take-home-pay {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        span.text-title{
            font-size:12px;
            font-weight:normal;
        }
        .data-details {
            display :flex;
            justify-content: space-between;
        }

        .data-details.bold {
            font-weight:bold;
        }

        .text-title.left {
            display: inline-block;
            width: 60%;
        }

        .info p{
            margin-top: 30px;    
            font-size : 10px;
            color : #aaa;
            text-transform: lowercase;
        }

        .info p span{
            margin-top: 30px;    
            font-size : 10px;
            color : red;
            text-transform: lowercase;
        }

        .info h6 {
            color:#000;
            font-size:10px;
        }
        
        .companylogo {
            width : 30%;
        }
    </style>
</head>
<body>
    @php
        $employee = \App\Employee::where('nik', $employee->nik)->first();
        $dataArray = json_decode($payroll->allowances, true);
        $datadeduction = json_decode($payroll->deductions, true);
        $company = \App\Company\CompanyModel::where('company_name', $employee->unit_bisnis)->first();
    @endphp
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/images/company/' . $company->logo) }}" alt="Company Logo" class="companylogo">
            <h1>{{ $company->company_name }}</h1>
            <div class="confidential">*CONFIDENTIAL</div>
        </div>
        
        <table class="info-table">
            <tr>
                <td>Payroll Period</td>
                <td>: {{ $payroll->month }}, {{ $payroll->year }}</td>
                <td>Level / Position</td>
                <td>: {{ $employee->jabatan }} / {{ $employee->golongan }}</td>
            </tr>
            <tr>
                <td>ID / Name</td>
                <td>: {{ $employee->nik }} / {{ $employee->nama }}</td>
                <td>PTKP</td>
                <td>: {{ $employee->status_pernikahan }} / {{ $employee->tanggungan }}</td>
            </tr>
            <tr>
                <td>Organization</td>
                <td>: {{ $employee->organisasi }}</td>
            </tr>
        </table>

        <table class="earnings-deductions">
            <thead>
                <tr>
                    <th>Earnings</th>
                    <th>Deductions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        @foreach($dataArray['data'] as $id => $value)
                            <div class="data-details">
                                <span class="text-title left">{{ \App\Payrol\Component::where('id', $id)->value('name') ?? 'Name not found' }}</span>
                                <span class="text-title">:  Rp {{ number_format($value[0], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </td>
                    <td>
                        @foreach($datadeduction['data'] as $id => $value)
                            <div class="data-details">
                                <span class="text-title left">{{ \App\Payrol\Component::where('id', $id)->value('name') ?? 'Name not found' }}</span>
                                <span class="text-title">:  Rp {{ number_format($value[0], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <div class="data-details bold">
                            <span class="text-title left" style="font-weight:bold;">Total Allowances</span>
                            <span class="text-title" style="font-weight:bold">:  Rp {{ number_format($dataArray['total_allowance'], 0, ',', '.') }} </span>
                        </div>
                    </td>
                    <td>
                        <div class="data-details bold">
                            <span class="text-title left" style="font-weight:bold;">Total Deductions</span>
                            <span class="text-title" style="font-weight:bold">:  Rp {{ number_format($datadeduction['total_deduction'], 0, ',', '.') }} </span>
                        </div>
                    </td>
                    
                </tr>
            </tfoot>
        </table>

        <div class="take-home-pay">
            <p>Take Home Pay: Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</p>
        </div>

        <div class="info">
            <p><span>*These are the benefits you'll get from the company, but not included in your take-home pay (THP).</span><br><br>
                THIS IS COMPUTER GENERATED PRINTOUT AND NO SIGNATURE IS REQUIRED<br><br>
                PLEASE NOTE THAT THE CONTENTS OF THIS STATEMENT SHOULD BE TREATED WITH ABSOLUTE CONFIDENTIALITY EXCEPT TO THE EXTENT YOU ARE REQUIRED TO MAKE
                DISCLOSURE FOR ANY TAX, LEGAL, OR REGULATORY PURPOSES. ANY BREACH OF THIS CONFIDENTIALITY OBLIGATION WILL BE DEALT WITH SERIOUSLY, WHICH MAY
                INVOLVE DISCPLINARY ACTION BEING TAKEN.<br><br>
                HARAP DIPERHATIKAN, ISI PERNYATAAN INI ADALAH RAHASIA KECUALI ANDA DIMINTA UNTUK MENGUNGKAPKANNYA UNTUK KEPERLUAN PAJAK, HUKUM, ATAU
                KEPENTINGAN PEMERINTAH. SETIAP PELANGGARAN ATAS KEWAJIBAN MENJAGA KERAHASIAAN INI AKAN DIKENAKAN SANKSI, YANG MUNGKIN BERUPA TINDAKAN
                KEDISIPLINAN.
            </p>
            <h6>This payslip is generated by TRUEST</h6>
        </div>

    </div>
</body>
</html>
