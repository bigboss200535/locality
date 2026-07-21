<x-app-layout>
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center justify-content-between mb-4">
                <h4 class="card-title">Purchase Order Details</h4>
                <div>
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-sm btn-secondary me-1">
                        <i class="fa fa-arrow-left me-1"></i> Back
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" onclick="window.print()">
                        <i class="fa fa-print me-1"></i> Print
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-h-100">
                        <div class="card-header">
                            <h5 class="card-title">Order Items</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover w-100 mb-0">
                                    <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                                        <tr class="text-uppercase table-nowrap fs-xxs">
                                            <th>#</th>
                                            <th>Product</th>
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Unit Price (GHs)</th>
                                            <th class="text-end">Total (GHs)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-nowrap">
                                        @forelse ($purchaseOrder->details as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ strtoupper($detail->product ? $detail->product->product_name : 'Unknown Product') }}</strong>
                                                </td>
                                                <td class="text-end">{{ number_format($detail->quantity, 2) }}</td>
                                                <td class="text-end">{{ number_format($detail->unit_price, 2) }}</td>
                                                <td class="text-end">{{ number_format($detail->total, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    No items found for this purchase order.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-h-100">
                        <div class="card-header">
                            <h5 class="card-title">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="text-muted fs-xs text-uppercase">Order No</span>
                                <h6 class="m-0">{{ $purchaseOrder->order_no }}</h6>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted fs-xs text-uppercase">Supplier</span>
                                <h6 class="m-0">{{ $purchaseOrder->supplier ? $purchaseOrder->supplier->supplier_name : 'N/A' }}</h6>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted fs-xs text-uppercase">Invoice No</span>
                                <h6 class="m-0">{{ $purchaseOrder->invoice_no ?? 'N/A' }}</h6>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted fs-xs text-uppercase">Order Date</span>
                                <h6 class="m-0">{{ $purchaseOrder->order_date ? \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d M Y') : 'N/A' }}</h6>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted fs-xs text-uppercase">Status</span>
                                <h6 class="m-0">
                                    @if($purchaseOrder->status == 'Active')
                                        <span class="badge bg-success-subtle text-success">ACTIVE</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">INACTIVE</span>
                                    @endif
                                </h6>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted fs-xs text-uppercase">Added By</span>
                                <h6 class="m-0">{{ $purchaseOrder->added_by ?? 'N/A' }}</h6>
                            </div>

                            <hr class="border-light-subtle">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span>GHs {{ number_format($summary['subtotal'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Discount</span>
                                <span class="text-success">-GHs {{ number_format($summary['discount'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">VAT</span>
                                <span>GHs {{ number_format($summary['vat'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-base border-top pt-2">
                                <span>Total Value</span>
                                <span class="text-primary">GHs {{ number_format($summary['total'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        © Paces - By <span class="fw-bold text-decoration-underline text-uppercase text-reset fs-12">WebEdge Technologies</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <style>
        @media print {
            .app-topbar,
            .sidenav-menu,
            .footer,
            .page-title-head .btn,
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
