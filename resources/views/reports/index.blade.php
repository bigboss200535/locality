<x-app-layout>
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center"></div>

            <!-- Report Controls -->
            <div class="row report-controls mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Generate Report</h4>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label for="report_type" class="form-label">Report Type</label>
                                    <select name="type" id="report_type" class="form-select">
                                        <option value="active_products" {{ $type === 'active_products' ? 'selected' : '' }}>Active Products</option>
                                        <option value="stocked_products" {{ $type === 'stocked_products' ? 'selected' : '' }}>Stocked Products</option>
                                        <option value="product_prices" {{ $type === 'product_prices' ? 'selected' : '' }}>Product Prices</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-primary me-1">
                                        <i class="fa fa-filter me-1"></i> Generate Report
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-print" onclick="window.print()">
                                        <i class="fa fa-print me-1"></i> Print
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Output -->
            <div class="row">
                <div class="col-12">
                    <div data-table data-table-rows-per-page="1000" class="card card-h-100">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">{{ $title }} <span class="text-muted fs-base fw-normal">({{ $reports->count() }} records)</span></h4>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-print" onclick="window.print()">
                                <i class="fa fa-print"></i>
                            </button>
                        </div>

                        <div class="card-body p-0">
                            <div class="print-only-header text-center py-3">
                                <h3>{{ config('app.name', 'Paces') }}</h3>
                                <h5 class="text-uppercase">{{ $title }}</h5>
                                <p class="mb-0 text-muted">Generated on {{ now()->format('d M Y, h:i A') }}</p>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover w-100 mb-0">
                                    <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                                        <tr class="text-uppercase table-nowrap fs-xxs">
                                            <th data-table-sort>#ID</th>
                                            <th data-table-sort>Product</th>
                                            <th data-table-sort>Category</th>
                                            <th data-table-sort>Store Name</th>

                                            @if($type === 'active_products')
                                                <th data-table-sort>Stockable</th>
                                                <th data-table-sort>Expirable</th>
                                                <th data-table-sort>Date Added</th>
                                                <th data-table-sort>Added By</th>
                                                <th data-table-sort>Status</th>
                                            @endif

                                            @if($type === 'stocked_products')
                                                <th data-table-sort>Stock Quantity</th>
                                                <th data-table-sort>Cost Price (GHs)</th>
                                                <th data-table-sort>Stock Value (GHs)</th>
                                                 <th data-table-sort>Added By</th>
                                                <th data-table-sort>Status</th>
                                            @endif

                                            @if($type === 'product_prices')
                                                <th data-table-sort>Cost Price (GHs)</th>
                                                <th data-table-sort>Selling Price (GHs)</th>
                                                <th data-table-sort>Added By</th>
                                                <th data-table-sort>Status</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        @forelse ($reports as $product)
                                            <tr>
                                                <td>#{{ substr($product->product_id, 0, 8) }}</td>
                                                <td>
                                                    <h5 class="m-0 fs-base">{{ strtoupper($product->product_name) }}</h5>
                                                    @if($product->product_type)
                                                        <span class="text-muted fs-xs">{{ strtoupper($product->product_type) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary-subtle text-primary">{{ $product->category ? $product->category->category_name : 'N/A' }}</span>
                                                </td>
                                                <td>{{ $product->store ? $product->store->store_name : 'N/A' }}</td>

                                                @if($type === 'active_products')
                                                    <td>
                                                        @if($product->stockable == 'YES')
                                                            <span class="badge bg-info-subtle text-info">YES</span>
                                                        @else
                                                            <span class="badge bg-primary-subtle text-primary">NO</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($product->expirable == 'YES')
                                                            <span class="badge bg-info-subtle text-info">YES</span>
                                                        @else
                                                            <span class="badge bg-primary-subtle text-primary">NO</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $product->added_date ? \Carbon\Carbon::parse($product->added_date)->format('d M Y, h:i A') : 'N/A' }}</td>
                                                     <td>{{ $product->added_by ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($product->status == 'Active')
                                                            <span class="badge bg-success-subtle text-success">ACTIVE</span>
                                                        @else
                                                            <span class="badge bg-danger-subtle text-danger">INACTIVE</span>
                                                        @endif
                                                    </td>
                                                @endif

                                                @if($type === 'stocked_products')
                                                    @php
                                                        $qty = $product->stock ? $product->stock->stock_quantity : 0;
                                                        $cost = $product->price ? $product->price->unit_cost : 0;
                                                    @endphp
                                                    <td>{{ $qty }}</td>
                                                    <td>{{ number_format($cost, 2) }}</td>
                                                    <td>{{ number_format($cost * $qty, 2) }}</td>
                                                      <td>{{ $product->added_by ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($qty > 0)
                                                            <span class="badge bg-success-subtle text-success">IN STOCK</span>
                                                        @else
                                                            <span class="badge bg-danger-subtle text-danger">OUT OF STOCK</span>
                                                        @endif
                                                    </td>
                                                @endif

                                                @if($type === 'product_prices')
                                                    <td>
                                                        @if($product->price)
                                                            {{ number_format($product->price->unit_cost, 2) }}
                                                        @else
                                                            <span class="badge bg-soft-warning text-warning">No Price</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($product->price)
                                                            {{ number_format($product->price->unit_price, 2) }}
                                                        @else
                                                            <span class="badge bg-soft-warning text-warning">No Price</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $product->added_by ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($product->status == 'Active')
                                                            <span class="badge bg-success-subtle text-success">ACTIVE</span>
                                                        @else
                                                            <span class="badge bg-danger-subtle text-danger">INACTIVE</span>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center py-4 text-muted">
                                                    No records found for the selected report.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div data-table-pagination-info="reports"></div>
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
