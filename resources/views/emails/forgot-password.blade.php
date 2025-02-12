<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to the Company</title>
</head>
<body>

    <p><strong>Set Up Your Password:</strong></p>
    <p>To get started, please set up your password by clicking the link below:</p>
    <p><a href="{{ url('/form-forgot-password/' . $resetLink) }}" style="color: #007bff; text-decoration: none; font-weight: bold;">Reset Your Password</a></p>

    <p>If the button above does not work, copy and paste this URL into your browser:</p>
    <p><strong>{{ url('/form-forgot-password/' . $resetLink) }}</strong></p>

    <p>If you have any questions or need further assistance, feel free to reach out to the HR department.</p>

    <p><strong>Please Download Our Mandatory Employee App:</strong></p>
    <ul>
        <li><strong>Android:</strong> <a href="https://play.google.com/store/apps/details?id=co.id.truest.truest">Google Play Store</a></li>
        <li><strong>iOS:</strong> <a href="https://apps.apple.com/idn/app/truest/id6476389232?platform=iphone">App Store</a></li>
    </ul>

    <p>Best Regards,</p>
    <p><strong>{{ $company->unit_bisn
