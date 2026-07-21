<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'PACES POS') }} | {{ $title }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0 0 5px;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 0 0 10px;
            font-size: 14px;
            color: #555;
        }
        .meta {
            margin-bottom: 20px;
        }
        .meta p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        td {
            font-size: 11px;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            margin-top: 15px;
            border-top: 1px solid #999;
            padding-top: 10px;
            width: 100%;
        }
        .summary table {
            width: 300px;
            margin-left: auto;
            border: none;
        }
        .summary td {
            border: none;
            padding: 4px 8px;
        }
        .summary-label {
            font-weight: bold;
        }
        .summary-value {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 align="left" style="color:black">{{ config('app.name', 'PACES POS') }}</h1>
        <h2 align="left" style="color:black">{{ strtoupper($title) }}</h2>
        <p class="text-center" style="color:black">
            PERIOD: {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}
        </p>
    </div>

    <div class="meta">
        <p><strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}</p>
        <!-- @if($selectedUser)
            <p><strong>User:</strong> {{ $selectedUser->firstname }} {{ $selectedUser->othername }}</p>
        @else
            <p><strong>User:</strong> All Users</p>
        @endif -->
        <!-- <p><strong>Records:</strong> {{ $payments->count() }}</p> -->
    </div>

    <table border='2' style="border-color: black; border-collapse: collapse;">
        <thead>
            <tr>
                <!-- <th>#ID</th>  -->
                <th>Transaction Time</th>
                <th>Receipt No</th>
                <!-- <th>Payment Method</th> -->
                <th>Store</th>
                <th class="text-end">Subtotal (GHs)</th>
                <th class="text-end">Discount (GHs)</th>
                <th class="text-end">Total (GHs)</th>
                <!-- <th>Status</th> -->
                <th>Cashier</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr>
                    <!-- <td>#{{ substr($payment->payment_id, 0, 8) }}</td> -->
                    <!-- <td>{{ $payment->payment_method ?? 'N/A' }}</td> -->
                    <td>{{ $payment->transaction_time ? \Carbon\Carbon::parse($payment->transaction_time)->format('d M Y, h:i A') : 'N/A' }}</td>
                    <td>{{ $payment->receipt_number }}</td>
                    <td>{{ $payment->store ? $payment->store->store_name : 'N/A' }}</td>
                    <td class="text-end">{{ number_format($payment->subtotal, 2) }}</td>
                    <td class="text-end">{{ number_format($payment->total_discount, 2) }}</td>
                    <td class="text-end">{{ number_format($payment->total_payment, 2) }}</td>
                    <!-- <td>{{ strtoupper($payment->status) }}</td> -->
                    <td>{{ strtoupper($payment->added_by) ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No payment records found for the selected date range.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary" style="border-top: 2px solid black; padding-top: 10px; border-color:white">
        <table>
            <tbody>
                <tr>
                    <td class="summary-label">Total Transactions</td>
                    <td class="summary-value">{{ $payments->count() }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Subtotal (GHs)</td>
                    <td class="summary-value">{{ number_format($payments->sum('subtotal'), 2) }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Total Discount (GHs)</td>
                    <td class="summary-value">{{ number_format($payments->sum('total_discount'), 2) }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Total Payment (GHs)</td>
                    <td class="summary-value">{{ number_format($payments->sum('total_payment'), 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} PACES - By WebEdge Technologies</p>
    </div>
</body>
</html>
