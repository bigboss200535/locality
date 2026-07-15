<!-- View Purchase Order Modal -->
<div class="modal fade view-purchase-order-modal" id="viewPurchaseOrderModal-{{ $order->purchase_order_id }}" tabindex="-1" aria-labelledby="viewPurchaseOrderModalLabel-{{ $order->purchase_order_id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPurchaseOrderModalLabel-{{ $order->purchase_order_id }}">Purchase Order: {{ $order->order_no }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-3" id="poTab-{{ $order->purchase_order_id }}" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="summary-tab-{{ $order->purchase_order_id }}" data-bs-toggle="tab" data-bs-target="#summary-{{ $order->purchase_order_id }}" type="button" role="tab" aria-controls="summary-{{ $order->purchase_order_id }}" aria-selected="true">
                            <i class="fa fa-file-lines me-1"></i> Summary Report
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pdf-tab-{{ $order->purchase_order_id }}" data-bs-toggle="tab" data-bs-target="#pdf-{{ $order->purchase_order_id }}" type="button" role="tab" aria-controls="pdf-{{ $order->purchase_order_id }}" aria-selected="false" data-pdf-loaded="false" data-order-id="{{ $order->purchase_order_id }}">
                            <i class="fa fa-file-pdf me-1"></i> PDF Viewer
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="poTabContent-{{ $order->purchase_order_id }}">
                    <!-- Summary Report Tab -->
                    <div class="tab-pane fade show active" id="summary-{{ $order->purchase_order_id }}" role="tabpanel" aria-labelledby="summary-tab-{{ $order->purchase_order_id }}">
                        <div id="printable-summary-{{ $order->purchase_order_id }}" class="p-3 border rounded bg-white">
                            <div class="text-center mb-4 print-only-header">
                                <h3 class="mb-1">{{ config('app.name', 'Paces') }}</h3>
                                <h5 class="text-uppercase text-muted">Purchase Order Summary</h5>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Order No:</strong> {{ $order->order_no }}</p>
                                    <p class="mb-1"><strong>Supplier:</strong> {{ $order->supplier ? $order->supplier->supplier_name : 'N/A' }}</p>
                                    <p class="mb-1"><strong>Invoice No:</strong> {{ $order->invoice_no ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Order Date:</strong> {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p class="mb-1"><strong>Status:</strong>
                                        @if($order->status == 'Active')
                                            <span class="badge bg-success-subtle text-success">ACTIVE</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">INACTIVE</span>
                                        @endif
                                    </p>
                                    <p class="mb-1"><strong>Added By:</strong> {{ $order->added_by ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Added Date:</strong> {{ $order->added_date ? \Carbon\Carbon::parse($order->added_date)->format('d M Y, h:i A') : 'N/A' }}</p>
                                </div>
                            </div>

                            <h6 class="fw-bold text-uppercase fs-xs text-muted mb-2">Order Items</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($order->details as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $detail->product ? $detail->product->product_name : 'N/A' }}</td>
                                                <td class="text-end">{{ number_format($detail->quantity, 2) }}</td>
                                                <td class="text-end">GHs {{ number_format($detail->unit_price, 2) }}</td>
                                                <td class="text-end">GHs {{ number_format($detail->total, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No items found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-light fw-bold">
                                        <tr>
                                            <td colspan="4" class="text-end">Subtotal</td>
                                            <td class="text-end">GHs {{ number_format($order->details->sum('total'), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">Discount</td>
                                            <td class="text-end">GHs {{ number_format($order->discount ?? 0, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">VAT</td>
                                            <td class="text-end">GHs {{ number_format($order->vat ?? 0, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">Total Value</td>
                                            <td class="text-end text-primary">GHs {{ number_format($order->total_value, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mt-4 text-muted fs-xs print-footer">
                                <p class="mb-0">Generated on {{ now()->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- PDF Viewer Tab -->
                    <div class="tab-pane fade" id="pdf-{{ $order->purchase_order_id }}" role="tabpanel" aria-labelledby="pdf-tab-{{ $order->purchase_order_id }}">
                        <div class="ratio ratio-4x3" style="min-height: 500px;">
                            <iframe id="pdf-iframe-{{ $order->purchase_order_id }}" data-src="{{ route('purchase-orders.pdf', $order->purchase_order_id) }}" title="Purchase Order PDF" style="width: 100%; height: 100%; border: 1px solid #dee2e6; border-radius: 0.375rem;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('purchase-orders.pdf', $order->purchase_order_id) }}" class="btn btn-outline-danger" target="_blank" download>
                    <i class="fa fa-file-pdf me-1"></i> Download PDF
                </a>
                <button type="button" class="btn btn-outline-secondary" onclick="printPurchaseOrderSummary('{{ $order->purchase_order_id }}')">
                    <i class="fa fa-print me-1"></i> Print Summary
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            function printPurchaseOrderSummary(orderId) {
                const content = document.getElementById('printable-summary-' + orderId).innerHTML;
                const printWindow = window.open('', '_blank');
                printWindow.document.write('<!DOCTYPE html><html><head><title>Purchase Order Summary</title>');
                printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">');
                printWindow.document.write('<style>');
                printWindow.document.write('@media print { .no-print { display: none !important; } body { padding: 20px; } }');
                printWindow.document.write('.print-only-header { display: block !important; margin-bottom: 20px; }');
                printWindow.document.write('</style>');
                printWindow.document.write('</head><body>');
                printWindow.document.write(content);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.focus();
                setTimeout(function() {
                    printWindow.print();
                }, 300);
            }

            document.addEventListener('DOMContentLoaded', function() {
                const pdfTabs = document.querySelectorAll('[id^="pdf-tab-"]');
                pdfTabs.forEach(function(tab) {
                    tab.addEventListener('shown.bs.tab', function() {
                        const orderId = this.getAttribute('data-order-id');
                        const iframe = document.getElementById('pdf-iframe-' + orderId);
                        const loaded = this.getAttribute('data-pdf-loaded');
                        if (iframe && loaded === 'false') {
                            iframe.setAttribute('src', iframe.getAttribute('data-src'));
                            this.setAttribute('data-pdf-loaded', 'true');
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce
