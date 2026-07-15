<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Purchase Order - {{ $purchaseOrder->order_no }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
            color: #2563eb;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }
        .details {
            margin-bottom: 25px;
        }
        .details-row {
            display: block;
            width: 100%;
            margin-bottom: 6px;
        }
        .details-row::after {
            content: "";
            display: table;
            clear: both;
        }
        .details-label {
            font-weight: bold;
            color: #555;
        }
        .left, .right {
            width: 48%;
            float: left;
        }
        .right {
            float: right;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #374151;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            width: 100%;
            margin-top: 10px;
        }
        .totals::after {
            content: "";
            display: table;
            clear: both;
        }
        .totals-table {
            width: 45%;
            float: right;
            border-collapse: collapse;
        }
        .totals-table td {
            border: none;
            border-bottom: 1px solid #eee;
            padding: 6px 0;
        }
        .totals-table td:first-child {
            text-align: left;
            font-weight: bold;
            color: #555;
        }
        .totals-table td:last-child {
            text-align: right;
        }
        .grand-total {
            font-size: 15px;
            color: #2563eb;
            border-top: 2px solid #2563eb;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'Paces') }}</h1>
        <p>Purchase Order Report</p>
    </div>

    <div class="details">
        <div class="details-row">
            <div class="left">
                <div><span class="details-label">Order No:</span> {{ $purchaseOrder->order_no }}</div>
                <div><span class="details-label">Supplier:</span> {{ $purchaseOrder->supplier ? $purchaseOrder->supplier->supplier_name : 'N/A' }}</div>
                <div><span class="details-label">Invoice No:</span> {{ $purchaseOrder->invoice_no ?? 'N/A' }}</div>
                <div><span class="details-label">Order Date:</span> {{ $purchaseOrder->order_date ? \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d M Y') : 'N/A' }}</div>
            </div>
            <div class="right">
                <div>
                    <span class="details-label">Status:</span>
                    @if($purchaseOrder->status == 'Active')
                        <span class="status status-active">Active</span>
                    @else
                        <span class="status status-inactive">Inactive</span>
                    @endif
                </div>
                <div><span class="details-label">Added By:</span> {{ $purchaseOrder->added_by ?? 'N/A' }}</div>
                <div><span class="details-label">Added Date:</span> {{ $purchaseOrder->added_date ? \Carbon\Carbon::parse($purchaseOrder->added_date)->format('d M Y, h:i A') : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <h3 style="margin-bottom: 8px; color: #374151;">Order Items</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">#</th>
                <th style="width: 42%;">Product</th>
                <th class="text-right" style="width: 16%;">Qty</th>
                <th class="text-right" style="width: 18%;">Unit Price</th>
                <th class="text-right" style="width: 16%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($purchaseOrder->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->product ? $detail->product->product_name : 'N/A' }}</td>
                    <td class="text-right">{{ number_format($detail->quantity, 2) }}</td>
                    <td class="text-right">GHs {{ number_format($detail->unit_price, 2) }}</td>
                    <td class="text-right">GHs {{ number_format($detail->total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals">
        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td>GHs {{ number_format($purchaseOrder->details->sum('total'), 2) }}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td>GHs {{ number_format($purchaseOrder->discount ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>VAT</td>
                <td>GHs {{ number_format($purchaseOrder->vat ?? 0, 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td>Total Value</td>
                <td>GHs {{ number_format($purchaseOrder->total_value, 2) }}</td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y, h:i A') }} by {{ config('app.name', 'Paces') }}</p>
        <p style="font-size: 10px; color: #aaa;">This is a computer-generated document.</p>
    </div>
</body>
</html>
