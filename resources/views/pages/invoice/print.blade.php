<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->code }}</title>
    <style>
        /* Reset CSS */
        * {
            border: 0;
            box-sizing: content-box;
            color: inherit;
            font-family: 'Roboto', sans-serif;
            font-size: inherit;
            font-style: inherit;
            font-weight: inherit;
            line-height: inherit;
            list-style: none;
            margin: 0;
            padding: 0;
            text-decoration: none;
            vertical-align: top;
        }
        
        /* Content Editable */
        *[contenteditable] { 
            border-radius: 0.25em; 
            min-width: 1em; 
            outline: 0; 
        }
        
        *[contenteditable] { 
            cursor: pointer; 
        }
        
        *[contenteditable]:hover, 
        *[contenteditable]:focus, 
        td:hover *[contenteditable], 
        td:focus *[contenteditable], 
        img.hover { 
            background: #DEF; 
            box-shadow: 0 0 1em 0.5em #DEF; 
        }

        .text-muted, .dropzone.dz-clickable .dz-message * {
            --bs-text-opacity: 1;
            color: #7987a1 !important;
        }
        
        span[contenteditable] { 
            display: inline-block; 
        }
        
        /* Heading */
        h1 { 
            font: bold 100% sans-serif; 
            letter-spacing: 0.5em; 
            text-align: center; 
            text-transform: uppercase; 
        }

        h4 {
            font-size: 16px;
        }

        p{
            font-size: 12px;
            line-height:1.2rem;
        }
        
        /* Table */
        table { 
            font-size: 75%; 
            table-layout: fixed; 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 2px; 
        }
        
        th, td { 
            border-width: 1px; 
            padding: 0.5em; 
            position: relative; 
            text-align: left; 
            border-radius: 0.25em; 
            border-style: solid; 
        }
        
        th.custom { 
            background: transparent; 
            border-color: transparent; 
            font-size: 12px;
        }

        td.custom{
            background: transparent; 
            border-color: transparent; 
        }

        th { 
            background: #EEE; 
            border-color: #BBB; 
        }
        
        td { 
            border-color: #DDD; 
        }
        
        /* Page */
        html { 
            font: 16px/1 'Roboto', sans-serif; 
            overflow: auto; 
            padding: 0.5in; 
            background: #999; 
            cursor: default; 
        }
        
        body { 
            box-sizing: border-box; 
            height: 11in; 
            margin: 0 auto; 
            overflow: hidden; 
            padding: 0.5in; 
            width: 8.5in; 
            background: #FFF; 
            border-radius: 1px; 
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); 
        }
        
        /* Header */
        header { 
            margin: 0 0 3em; 
        }
        
        header:after { 
            clear: both; 
            content: ""; 
            display: table; 
        }
        
        header h1 { 
            background: #000; 
            border-radius: 0.25em; 
            color: #FFF; 
            margin: 0 0 1em; 
            padding: 0.5em 0; 
        }
        
        
        /* Article */
        article, 
        article address, 
        table.meta, 
        table.inventory { 
            margin: 0 0 3em; 
        }
        
        article:after { 
            clear: both; 
            content: ""; 
            display: table; 
        }
        
        article h1 { 
            clip: rect(0 0 0 0); 
            position: absolute; 
        }
        
        article address { 
            float: left; 
            font-size: 125%; 
            font-weight: bold; 
        }
        
        /* Table Meta & Balance */
        table.meta, 
        table.balance { 
            float: right; 
            width: 36%; 
        }
        
        table.meta:after, 
        table.balance:after { 
            clear: both; 
            content: ""; 
            display: table; 
        }
        
        /* Table Meta */
        table.meta th { 
            width: 40%; 
        }
        
        table.meta td { 
            width: 60%; 
        }
        
        /* Table Items */
        table.inventory { 
            clear: both; 
            width: 100%; 
        }
        
        table.inventory th { 
            font-weight: bold; 
            text-align: center; 
        }
        
        table.inventory td:nth-child(1) { 
            width: 26%; 
        }
        
        table.inventory td:nth-child(2) { 
            width: 38%; 
        }
        
        table.inventory td:nth-child(3) { 
            text-align: right; 
            width: 12%; 
        }
        
        table.inventory td:nth-child(4) { 
            text-align: right; 
            width: 12%; 
        }
        
        table.inventory td:nth-child(5) { 
            text-align: right; 
            width: 12%; 
        }
        
        /* Table Balance */
        table.balance th, 
        table.balance td { 
            width: 50%; 
        }
        
        table.balance td { 
            text-align: right; 
        }
        
        /* Aside */
        aside h1 { 
            border: none; 
            border-width: 0 0 1px; 
            margin: 0 0 1em; 
        }
        
        aside h1 { 
            border-color: #999; 
            border-bottom-style: solid; 
        }

        .header-data {
            display : flex!important;
        }
        
        /* Print Styles */
        @media print {
            * { 
                -webkit-print-color-adjust: exact; 
            }
            
            html { 
                background: none; 
                padding: 0; 
            }
            
            body { 
                box-shadow: none; 
                margin: 0; 
            }
            
            span:empty { 
                display: none; 
            }
        }
        
        @page { 
            margin: 0; 
        }
    </style>
</head>
<body>
@php 
    $dataCustomer = App\KasManagement\CustomerManagement::where('company',$invoice->company)->where('name', $invoice->client)->first();
    $dataKantor = App\Company\CompanyModel::where('company_name',$invoice->company)->first();
@endphp
    <header>
        <h1>Invoice</h1>
        <table class="custom">
            <thead>
                <tr class="custom">
                    <th class="custom"><h4 class="text-muted">Invoice From</h4></th>
                    <th class="custom"><h4 class="text-muted">Invoice To</h4></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="custom">
                        <p class="mt-1 mb-1"><b> Kantor Notaris Iin Titin Rohani, S.H.,M.Kn</b></p>
                        <div class="customer-data">
                        <p>{{$dataKantor->company_address}}</p>
                    </td>
                    <td class="custom">
                        <div class="customer-data">
                            <p>{{ $invoice->client }},<br> {{$dataCustomer->alamat}}</p>
                        </div>
                    </td>
                </tr>
            </tbody>
            
        </table>
    </header>
    <article>
            
            
            <table class="meta">
                <tr>
                    <th>Invoice #</th>
                    <td>{{ $invoice->code }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ $invoice->date }}</td>
                </tr>
                <tr>
                    <th>Amount Due</th>
                    <td>Rp {{ number_format($total, 2) }}</td>
                </tr>
            </table>
        <table class="inventory">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $key => $item)
                    @if(is_array($item))
                    <tr>
                        <td>{{ $item['title'] }}</td>
                        <td>Rp {{ number_format($item['harga']) }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>Rp {{ number_format($item['subtotal']) }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <table class="balance">
            <tr>
                <th>Total</th>
                <td>Rp {{ number_format($total) }}</td>
            </tr>
        </table>
    </article>
</body>
</html>
