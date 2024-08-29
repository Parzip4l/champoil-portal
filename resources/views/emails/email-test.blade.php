<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Employee Email</title>
</head>
<body>
    <h1>Welcome to the Company, {{ $employee->nama }}!</h1>

    <p>Dear {{ $employee->nama }},</p>

    <p>We are excited to have you join our team at {{ $company->unit_bisnis }}. As part of the {{ $company->unit_bisnis }} family, we are confident that you will bring great value and help us achieve our goals.</p>

    <p>Here are your details:</p>
    <ul>
        <li><strong>Name:</strong> {{ $employee->nama }}</li>
        <li><strong>Email:</strong> {{ $employee->email }}</li>
        <li><strong>Start Date:</strong> {{ $employee->joindate }}</li>
    </ul>

    <p>If you have any questions or need further assistance, feel free to reach out to the HR department.</p>

    <p>Welcome aboard and we look forward to working with you!</p>

    <p>Please Download Your Apps </p>
    <ul>
        <li><strong>Android : https://play.google.com/store/apps/details?id=co.id.truest.truest</strong></li>
        <li><strong>Ios : </strong>https://apps.apple.com/idn/app/truest/id6476389232?platform=iphone</li>
    </ul>

    <p>Best Regards,</p>
    <p>{{ $company->unit_bisnis }} Team</p>
</body>
</html>
