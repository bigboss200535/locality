<x-app-layout>
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center"></div>

            <!-- Report Controls -->
            <div class="row report-controls mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header justify-content-between align-items-center">
                            <h4 class="card-title">Generate Report</h4>
                            <!-- <a href="{{ route('reports.products.pdf') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-cash-register me-1"></i> Product Payments
                            </a> -->
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('reports.products') }}" class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label for="report_type" class="form-label">Report Type</label>
                                    <select name="type" id="report_type" class="form-select">
                                        <option value="active_products" {{ $type === 'active_products' ? 'selected' : '' }}>Products</option>
                                        <option value="stocked_products" {{ $type === 'stocked_products' ? 'selected' : '' }}>Stocks</option>
                                        <option value="product_prices" {{ $type === 'product_prices' ? 'selected' : '' }}>Product Prices</option>
                                    </select>
                                </div>
                                 <div class="col-md-4">
                                    <label for="product_status" class="form-label">Status</label>
                                    <select name="product_status" id="product_status" class="form-select">
                                        <option value="active" {{ ($product_status ?? 'active') === 'active' ? 'selected' : '' }}>Active Products</option>
                                        <option value="inactive" {{ ($product_status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive Products</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                     <a id="pdf-download-link" href="{{ route('reports.products.pdf') }}" class="btn btn-info me-1" target="_blank">
                                        <i class="fa fa-file-pdf me-1"></i> View Report
                                    </a>
                                </div>
                            </form>
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
            const type = document.getElementById('report_type').value;
            const status = document.getElementById('product_status').value;
            const pdfLink = document.getElementById('pdf-download-link');
            pdfLink.href = `/reports/products/pdf?type=${encodeURIComponent(type)}&product_status=${encodeURIComponent(status)}`;
        }

        document.getElementById('report_type').addEventListener('change', updatePdfLink);
        document.getElementById('product_status').addEventListener('change', updatePdfLink);

        // Initialize link on page load
        updatePdfLink();
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
