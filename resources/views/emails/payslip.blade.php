<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <style>
        img.companylogo {
            max-width : 10%;
        }
    </style>
</head>
<body>
    @php
        $company = \App\Company\CompanyModel::where('company_name', $employee->unit_bisnis)->first();
    @endphp
    <table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" style="background-color: #f9f9f9; padding: 20px;">
            <img src="{{ asset('assets/images/company/' . $company->logo) }}" alt="Company Logo" class="companylogo">
                <h2>Payslip</h2>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px;">
                <p>Dear {{ $employee->nama }},</p>
                <p>Your Payslip is ready. Find the attachment below to download your Payslip.</p>
                <p>Happy {{ \Carbon\Carbon::now()->format('l') }}!</p>
            </td>
        </tr>
    </table>
</body>
</html>
