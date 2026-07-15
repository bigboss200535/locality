<x-app-layout>
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center"></div>

            <!-- Report Controls -->
            <div class="row report-controls mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Generate Stock Adjustments Report</h4>
                        </div>
                        <div class="card-body">
                            <form id="stock-adjustment-form" class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" id="start_date" name="start_date" class="form-control"
                                           value="{{ $start_date }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" id="end_date" name="end_date" class="form-control"
                                           value="{{ $end_date }}" required>
                                </div>
                                <div class="col-md-2">
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
                                <div class="col-md-2">
                                    <label for="report_type" class="form-label">Report Type</label>
                                    <select id="report_type" name="report_type" class="form-select">
                                        <option value="">All Types</option>
                                        @foreach($reportTypes as $type)
                                            <option value="{{ $type }}" {{ $reportType == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary me-1">
                                        <i class="fa fa-filter me-1"></i> Generate
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-print" onclick="window.print()">
                                        <i class="fa fa-print"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if($start_date && $end_date)
            <!-- Summary Cards -->
            <div class="row report-controls mb-4">
                <div class="col-md-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-xs">Total Movements</h6>
                            <h3 class="m-0">{{ $adjustments->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-xs">Total Quantity</h6>
                            <h3 class="m-0">{{ number_format($adjustments->sum('stock_quantity'), 0) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-xs">Stock In</h6>
                            <h3 class="m-0">{{ number_format($adjustments->where('stock_quantity', '>', 0)->sum('stock_quantity'), 0) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase fs-xs">Stock Out</h6>
                            <h3 class="m-0">{{ number_format($adjustments->where('stock_quantity', '<', 0)->sum('stock_quantity'), 0) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Output -->
            <div class="row">
                <div class="col-12">
                    <div data-table data-table-rows-per-page="1000" class="card card-h-100">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">{{ $title }} <span class="text-muted fs-base fw-normal">({{ $adjustments->count() }} records)</span></h4>
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
                                            <th data-table-sort>Product</th>
                                            <th data-table-sort>Batch No</th>
                                            <th data-table-sort>Type</th>
                                            <th data-table-sort>Stock Date</th>
                                            <th data-table-sort>Expiry Date</th>
                                            <th data-table-sort>Store</th>
                                            <th data-table-sort class="text-end">Quantity</th>
                                            <th data-table-sort>Status</th>
                                            <th data-table-sort>Added By</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        @forelse ($adjustments as $adjustment)
                                            <tr>
                                                <td>#{{ substr($adjustment->stock_movement_id, 0, 8) }}</td>
                                                <td>{{ $adjustment->product ? $adjustment->product->product_name : 'N/A' }}</td>
                                                <td>{{ $adjustment->batch_number ?? 'N/A' }}</td>
                                                <td>{{ $adjustment->stocked_type ?? 'N/A' }}</td>
                                                <td>{{ $adjustment->stock_date ? \Carbon\Carbon::parse($adjustment->stock_date)->format('d M Y') : 'N/A' }}</td>
                                                <td>{{ $adjustment->expiry_date ? \Carbon\Carbon::parse($adjustment->expiry_date)->format('d M Y') : 'N/A' }}</td>
                                                <td>{{ $adjustment->store ? $adjustment->store->store_name : 'N/A' }}</td>
                                                <td class="text-end {{ $adjustment->stock_quantity < 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($adjustment->stock_quantity, 0) }}
                                                </td>
                                                <td>
                                                    @if($adjustment->status == 'Active')
                                                        <span class="badge bg-success-subtle text-success">ACTIVE</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">{{ strtoupper($adjustment->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $adjustment->added_by ?? 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4 text-muted">
                                                    No stock movement records found for the selected date range.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div data-table-pagination-info="adjustments"></div>
                                <div data-table-pagination></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
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
        document.getElementById('stock-adjustment-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            const userId = document.getElementById('user_id').value;
            const reportType = document.getElementById('report_type').value;

            if (!start || !end) {
                alert('Please select both start and end dates.');
                return;
            }

            const params = new URLSearchParams();
            if (userId) params.append('user_id', userId);
            if (reportType) params.append('report_type', reportType);

            let url = `/reports/stock-adjustments/${start}/${end}`;
            const query = params.toString();
            if (query) {
                url += `?${query}`;
            }
            window.location.href = url;
        });
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
