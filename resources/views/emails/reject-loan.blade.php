<!DOCTYPE html>
<html>
<head>
    <title>Membership Approved</title>
    <style>
        img.companylogo {
            max-width:12%;
        }

        img.LogoPlaystore {
            max-width:8%;
        }
        ul {
            list-style-type: none;
        }
        li {
            text-align: center;
            display: inline-flex;
        }

        @media(max-width:675px){
            img.companylogo {
            max-width:25%;
            }

            img.LogoPlaystore {
                max-width:25%;
            }
        }
    </style>
</head>
@php 
        $companyLogo = '1706616128.png';
        $Playstore = 'playstore.png';
        $Appstore = 'appstore.png';
@endphp
<body>
    <table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" style="background-color: #f9f9f9; padding: 20px;">
            <img src="{{ asset('assets/images/company/' . $companyLogo) }}" alt="Company Logo" class="companylogo">
                <h2>Loan Rejected</h2>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px;">
                <p>Dear {{ $employeeName }},</p>
                <p>We would like to inform you that your loan application at TRUEST cannot be approved at this time. We apologize for the inconvenience.</p>
                <p>If you have further questions or would like to know the reason for the rejection, please contact our support team. We will be happy to help you.</p>
                
                <p>Download the latest version of the TRUEST application!</p>
                <div class="donwload-button">
                <a href="https://play.google.com/store/apps/details?id=co.id.truest.truest"><img src="{{ asset('assets/images/company/' . $Playstore) }}" alt="Playstore" class="LogoPlaystore"></a>
                <a href="https://apps.apple.com/idn/app/truest/id6476389232?platform=iphone"><img src="{{ asset('assets/images/company/' . $Appstore) }}" alt="AppStore" class="LogoPlaystore"></a>
                </div>
                <p>Best regards,</p>
    <p>The TRUEST Team</p>
            </td>
        </tr>
    </table>

    
</body>
</html>
