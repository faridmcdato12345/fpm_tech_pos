<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title inertia>{{ config('app.name', 'Laravel') }}</title>



    <!-- Scripts -->
{{--    @routes--}}
        @vite(["resources/css/app.css"])
{{--    @inertiaHead--}}
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'DejaVu Sans',sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: auto;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            font-weight: bold;
            font-size: 18px;
            color: #007bff;
            margin-bottom: 20px;
        }
        .details-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-box td {
            padding: 10px;
            border: 1px solid #ccc;
            vertical-align: top;
        }
        .details-box h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f9f9f9;
        }
        .invoice-summary {
            margin-top: 20px;
            text-align: right;
        }
        .invoice-summary table {
            width: 50%;
            float: right;
        }
        .invoice-summary td {
            padding: 5px;
        }
        .invoice-summary tr:last-child {
            font-weight: bold;
        }
        th {
            text-transform: uppercase;
        }
    </style>
</head>
<body class="font-sans antialiased">
{{--@inertia--}}
    <div class="container">
        <div class="logo">Your Logo</div>
        <!-- Replacing Flexbox with a Table -->
        <table class="details-box">
            <tr>
                <td>
                    <h3>Business Details:</h3>
                    <p><strong>FROM</strong></p>
                    <p><strong>{{$business[0]->business_name}}</strong></p>
                    <p>{{$business[0]->address}}</p>
                    <p>{{$business[0]->email}}</p>
                    <p>{{$business[0]->contact_number}}</p>
                    <p>TIN: {{$business[0]->tin}}</p>
                </td>
                <td>
                    <h3>Client's details:</h3>
                    <p><strong>TO</strong></p>
                    <p><strong>{{$customer->name}}</strong></p>
                    <p>{{$customer->company}}</p>
                    <p>{{$customer->barangay . ', ' . $customer->municipality . ', ' . $customer->province}}</p>
                    <p>{{$customer->region}}</p>
                    <p>{{$customer->email ?? ''}}</p>
                </td>
            </tr>
        </table>
        <p><strong>Invoice No:</strong> {{$invoiceNumber}}</p>
        <p><strong>Invoice Date:</strong> {{$date}}</p>
        @if($invoices)
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                            Product name
                        </th>
                        <th>
                            Purchased Qty
                        </th>
                        <th>
                            Price
                        </th>
                        <th>
                            Total Price
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $invoice)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800 uppercase">
                                {{$invoice['name']}}
                            </th>
                            <td>
                                {{$invoice['quantity']}}
                            </td>
                            <td>
                                {{$invoice['selling_price']}}
                            </td>
                            <td>
                                {{$invoice['total']}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="invoice-summary">
                    <table>
                        <tr>
                            <td>Subtotal</td>
                            <td>{{$subTotal}}</td>
                        </tr>
                        @forelse ( $taxes as $tax )
                            <tr>
                                <td>{{ $tax['name'] }}</td>
                                <td>{{ currency_formatter((((int)$tax['value'] / 100) * $subT)) }}</td>
                            </tr>
                        @empty
                        @endforelse
                        <tr>
                            <td>Grand Total</td>
                            <td id="grand-total">{{ $grandTotal }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const grandTotalElement = document.getElementById('grand-total');
        const grandTotalValue = parseFloat(grandTotalElement.textContent);
        grandTotalElement.textContent = convertToCurrent(grandTotalValue);
    });
    function convertToCurrent(amount) {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
        }).format(amount);
    }
</script>
</body>
</html>
