<x-app-layout>
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center"></div>

            <!-- Report Controls -->
            <div class="row report-controls mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Generate Sales Payment Report(s)</h4>
                        </div>
                        <div class="card-body">
                            <form id="sales-report-form" class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control"
                                           value="{{ $start_date }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control"
                                           value="{{ $end_date }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="user_id" class="form-label">User</label>
                                    <select id="user_id" name="user_id" class="form-select">
                                        <option value="">All Users</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->user_id }}" {{ $userId == $u->user_id ? 'selected' : '' }}>
                                                {{ $u->firstname }} {{ $u->othername }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary me-1">
                                        <i class="fa fa-filter me-1"></i> Generate
                                    </button>
                                    <a id="pdf-download-link" href="{{ route('reports.sales.pdf', ['start_date' => $start_date, 'end_date' => $end_date]) }}" class="btn btn-outline-danger me-1">
                                        <i class="fa fa-file-pdf me-1"></i> PDF
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary btn-print" onclick="window.print()">
                                        <i class="fa fa-print"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row report-controls mb-4">
                <div class="col-md-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-xs">Total Transactions</h6>
                            <h3 class="m-0">{{ $payments->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-xs">Subtotal (GHs)</h6>
                            <h3 class="m-0">{{ number_format($payments->sum('subtotal'), 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-xs">Total Discount (GHs)</h6>
                            <h3 class="m-0">{{ number_format($payments->sum('total_discount'), 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-xs">Total Payment (GHs)</h6>
                            <h3 class="m-0">{{ number_format($payments->sum('total_payment'), 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Output -->
            <div class="row">
                <div class="col-12">
                    <div data-table data-table-rows-per-page="1000" class="card card-h-100">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">{{ $title }} <span class="text-muted fs-base fw-normal">({{ $payments->count() }} records)</span></h4>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-print" onclick="window.print()">
                                <i class="fa fa-print"></i>
                            </button>
                        </div>

                        <div class="card-body p-0">
                            <div class="print-only-header text-center py-3">
                                <h3>{{ config('app.name', 'Paces') }}</h3>
                                <h5 class="text-uppercase">{{ $title }}</h5>
                                <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}</p>
                                <p class="mb-0 text-muted">Generated on {{ now()->format('d M Y, h:i A') }}</p>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover w-100 mb-0">
                                    <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                                        <tr class="text-uppercase table-nowrap fs-xxs">
                                            <th data-table-sort>#ID</th>
                                            <th data-table-sort>Receipt No</th>
                                            <!-- <th data-table-sort>Payment Method</th> -->
                                            <th data-table-sort>Transaction Time</th>
                                            <th data-table-sort>Store</th>
                                            <th data-table-sort class="text-end">Subtotal (GHs)</th>
                                            <th data-table-sort class="text-end">Discount (GHs)</th>
                                            <th data-table-sort class="text-end">Total (GHs)</th>
                                            <th data-table-sort>Status</th>
                                            <th data-table-sort>Added By</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        @forelse ($payments as $payment)
                                            <tr>
                                                <td>#{{ substr($payment->payment_id, 0, 8) }}</td>
                                                <td>{{ $payment->receipt_number }}</td>
                                                <!-- <td>{{ $payment->payment_method ?? 'N/A' }}</td> -->
                                                <td>{{ $payment->transaction_time ? \Carbon\Carbon::parse($payment->transaction_time)->format('d M Y, h:i A') : 'N/A' }}</td>
                                                <td>{{ $payment->store ? $payment->store->store_name : 'N/A' }}</td>
                                                <td class="text-end">{{ number_format($payment->subtotal, 2) }}</td>
                                                <td class="text-end">{{ number_format($payment->total_discount, 2) }}</td>
                                                <td class="text-end">{{ number_format($payment->total_payment, 2) }}</td>
                                                <td>
                                                    @if($payment->status == 'Paid')
                                                        <span class="badge bg-success-subtle text-success">PAID</span>
                                                    @elseif($payment->status == 'Active')
                                                        <span class="badge bg-success-subtle text-success">ACTIVE</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">{{ strtoupper($payment->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $payment->added_by ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('sales.reprint', $payment->receipt_number) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Reprint Receipt">
                                                        <i class="fa fa-pdf"></i>
                                                        Reprint
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center py-4 text-muted">
                                                    No payment records found for the selected date range.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div data-table-pagination-info="payments"></div>
                                <div data-table-pagination></div>
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
                    <div class="col-md-6">
                        <div class="d-none d-md-flex justify-content-end gap-3">
                            <a href="javascript: void(0);" class="link-reset">About</a>
                            <a href="javascript: void(0);" class="link-reset">Support</a>
                            <a href="javascript: void(0);" class="link-reset">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function updatePdfLink() {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            const userId = document.getElementById('user_id').value;
            const pdfLink = document.getElementById('pdf-download-link');
            let url = `/reports/sales/${start}/${end}/pdf`;
            if (userId) {
                url += `?user_id=${userId}`;
            }
            pdfLink.href = url;
        }

        document.getElementById('sales-report-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            const userId = document.getElementById('user_id').value;
            if (start && end) {
                let url = `/reports/sales/${start}/${end}`;
                if (userId) {
                    url += `?user_id=${userId}`;
                }
                window.location.href = url;
            }
        });

        document.getElementById('start_date').addEventListener('change', updatePdfLink);
        document.getElementById('end_date').addEventListener('change', updatePdfLink);
        document.getElementById('user_id').addEventListener('change', updatePdfLink);
    </script>

    <style>
        .print-only-header {
            display: none;
        }

        @media print {
            .app-topbar,
            .sidenav-menu,
            .footer,
            .report-controls,
            .btn-print,
            .card-footer,
            .page-title-head,
            .alert {
                display: none !important;
            }

            .print-only-header {
                display: block !important;
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

            .table {
                font-size: 12px !important;
            }

            .table th,
            .table td {
                padding: 0.35rem !important;
            }
        }
    </style>
</x-app-layout>
