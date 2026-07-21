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
        <h1>{{ config('app.name', 'PACES POS') }}</h1>
        <h2>{{ strtoupper($title) }}</h2>
    </div>

    <div class="meta">
        <p><strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}</p>
        <p><strong>Records:</strong> {{ $reports->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#ID</th>
                <th>Product</th>
                <th>Category</th>
                <th>Store Name</th>

                @if($type === 'active_products')
                    <th>Stockable</th>
                    <th>Expirable</th>
                    <th>Date Added</th>
                    <th>Added By</th>
                    <th>Status</th>
                @endif

                @if($type === 'stocked_products')
                    <th class="text-end">Stock Quantity</th>
                    <th class="text-end">Cost Price (GHs)</th>
                    <th class="text-end">Stock Value (GHs)</th>
                    <th>Added By</th>
                    <th>Status</th>
                @endif

                @if($type === 'product_prices')
                    <th class="text-end">Cost Price (GHs)</th>
                    <th class="text-end">Selling Price (GHs)</th>
                    <th>Added By</th>
                    <th>Status</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $product)
                <tr>
                    <td>#{{ substr($product->product_id, 0, 8) }}</td>
                    <td>
                        <strong>{{ strtoupper($product->product_name) }}</strong>
                        @if($product->product_type)
                            <br><small>{{ strtoupper($product->product_type) }}</small>
                        @endif
                    </td>
                    <td>{{ $product->category ? $product->category->category_name : 'N/A' }}</td>
                    <td>{{ $product->store ? $product->store->store_name : 'N/A' }}</td>

                    @if($type === 'active_products')
                        <td>{{ $product->stockable == 'YES' ? 'YES' : 'NO' }}</td>
                        <td>{{ $product->expirable == 'YES' ? 'YES' : 'NO' }}</td>
                        <td>{{ $product->added_date ? \Carbon\Carbon::parse($product->added_date)->format('d M Y, h:i A') : 'N/A' }}</td>
                        <td>{{ $product->added_by ?? 'N/A' }}</td>
                        <td>{{ strtoupper($product->status) }}</td>
                    @endif

                    @if($type === 'stocked_products')
                        @php
                            $qty = $product->stock ? $product->stock->stock_quantity : 0;
                            $cost = $product->price ? $product->price->unit_cost : 0;
                        @endphp
                        <td class="text-end">{{ number_format($qty, 2) }}</td>
                        <td class="text-end">{{ number_format($cost, 2) }}</td>
                        <td class="text-end">{{ number_format($cost * $qty, 2) }}</td>
                        <td>{{ $product->added_by ?? 'N/A' }}</td>
                        <td>{{ $qty > 0 ? 'IN STOCK' : 'OUT OF STOCK' }}</td>
                    @endif

                    @if($type === 'product_prices')
                        <td class="text-end">{{ $product->price ? number_format($product->price->unit_cost, 2) : 'N/A' }}</td>
                        <td class="text-end">{{ $product->price ? number_format($product->price->unit_price, 2) : 'N/A' }}</td>
                        <td>{{ $product->added_by ?? 'N/A' }}</td>
                        <td>{{ strtoupper($product->status) }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">No records found for the selected report.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; {{ date('Y') }} PACES - By WebEdge Technologies</p>
    </div>
</body>
</html>
