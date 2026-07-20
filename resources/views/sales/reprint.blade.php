<x-app-layout>
    <div class="content-page">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0"><i class="fa fa-receipt me-2"></i> Receipt Reprint</h5>
                            <div>
                                <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary me-1">Back</a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="window.print()">
                                    <i class="fa fa-print me-1"></i> Print
                                </button>
                            </div>
                        </div>
                        <div class="card-body" id="printable-receipt">
                            <div class="text-center mb-4">
                                <h4 class="mb-1">PACES POS</h4>
                                <!-- <p class="text-muted fs-sm mb-0">--Official Sale Receipt--</p> -->
                                 <p class="text-muted fs-sm mb-0">--Duplicate Receipt--</p>
                            </div>

                            <div class="row mb-3 fs-xs">
                                <div class="col-6">
                                    <strong>Invoice:</strong> {{ $receiptData['invoice_no'] }}<br>
                                    <strong>Date:</strong> {{ $receiptData['date'] }}
                                </div>
                                <div class="col-6 text-end">
                                    <strong>Cashier:</strong> {{ $receiptData['added_by'] }}<br>
                                    <strong>Method:</strong> {{ $receiptData['payment_method'] }}
                                </div>
                            </div>

                            <table class="table table-sm table-borderless fs-sm mb-3">
                                <thead>
                                    <tr class="border-bottom text-uppercase fs-xxs text-muted">
                                        <th>Item</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receiptData['items'] as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ strtoupper($item->product ? $item->product->product_name : 'Unknown Product') }}</strong>
                                                @if(floatval($item->discount) > 0)
                                                    <br><small class="text-success">Discount: -GHs {{ number_format($item->discount, 2) }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">GHs {{ number_format($item->unit_cost, 2) }}</td>
                                            <td class="text-end">GHs {{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="border-top pt-3 fs-sm">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Subtotal</span>
                                    <span>GHs {{ number_format($receiptData['subtotal'], 2) }}</span>
                                </div>

                                @if($receiptData['item_discount'] > 0)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-success">Item Discount</span>
                                        <span class="text-success">-GHs {{ number_format($receiptData['item_discount'], 2) }}</span>
                                    </div>
                                @endif

                                @if($receiptData['cart_discount'] > 0)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-success">Cart Discount</span>
                                        <span class="text-success">-GHs {{ number_format($receiptData['cart_discount'], 2) }}</span>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between fw-bold fs-base border-top pt-2">
                                    <span>Grand Total</span>
                                    <span class="text-primary">GHs {{ number_format($receiptData['grand_total'], 2) }}</span>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <p class="fs-xs mb-0">Thank you for your business!</p>
                                <p class="fs-xxs">Powered by WebEdge Technologies</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .app-topbar,
            .sidenav-menu,
            .footer,
            .card-header,
            .btn {
                display: none !important;
            }

            .content-page {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            body {
                background: #fff !important;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</x-app-layout>
