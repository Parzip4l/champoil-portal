@php 
    use App\Models\Employee;
    $companyLogo = '1706616128.png';
    $Playstore = 'playstore.png';
    $Appstore = 'appstore.png';
    $nama = \App\Employee::where('nik', $user->name)->first();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome Admin</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; padding: 20px;">
    <div style="max-width: 600px; margin: auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 30px;">
        
        <h2 style="color: #2c3e50;">Welcome, {{ $nama->nama }}</h2>

        <p>Your admin account for <strong>{{ $company->company_name }}</strong> has been created.</p>

        <p><strong>Here are your login credentials:</strong></p>
        <ul>
            <li><strong>Url:</strong> https://hris.truest.co.id</li>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Password:</strong> {{ $password }}</li>
        </ul>

        <p>You can access the Truest mobile app from the links below:</p>

        <div style="margin-top: 20px; margin-bottom: 20px;">
            <a href="https://play.google.com/store/apps/details?id=co.id.truest.truest" target="_blank" style="margin-right: 10px;">
                <img src="{{ asset('assets/images/company/' . $Playstore) }}" alt="Download on Playstore" style="height: 50px;">
            </a>
            <a href="https://apps.apple.com/idn/app/truest/id6476389232?platform=iphone" target="_blank">
                <img src="{{ asset('assets/images/company/' . $Appstore) }}" alt="Download on AppStore" style="height: 50px;">
            </a>
        </div>

        <p>Silakan login dan lanjutkan pengaturan awal perusahaan Anda melalui dashboard.</p>

        <br>
        <p>Best regards,<br><strong>TRUEST HRIS</strong></p>
    </div>
</body>
</html>
