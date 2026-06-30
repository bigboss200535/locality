        <x-app-layout>
        <!-- Begin page -->
            <!-- Topbar End -->

            <!-- ============================================================== -->
            <!-- Start Main Content -->
            <!-- ============================================================== -->
            <div class="content-page">
                <div class="container-fluid">
                    <div class="page-title-head d-flex align-items-center"></div>
                    <div class="row">
                        <div class="col-xxl-5">
                            <div class="row h-100">
                                <div class="col-lg-3 col-md-6 col-xxl-6">
                                    <div class="card card-h-100 overflow-hidden">
                                        <div class="card-body pb-0">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div class="overflow-hidden flex-shrink-0">
                                                    <h3 class="fw-normal text-reset fs-20 lh-base">
                                                        <span class="text-muted fs-base text-uppercase h5"><img src="{{ $greeting_icon }}" alt="#" class="rounded" style="width: 30px;" /> {{ $greeting_text }},</span> <br />
                                                        <b>{{ $greeting_name }}</b>
                                                    </h3>
                                                </div>
                                                <div class="flex-grow-1 text-end">
                                                    <img class="d-none d-xxl-inline-block" src="{{ asset('images/svg/email-campaign.svg') }}" width="110" alt="Generic placeholder image" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body d-flex align-items-center p-2 bg-light bg-opacity-50">
                                            <p class="d-flex align-items-center justify-content-between w-100 mb-0">
                                                <span class="me-2"
                                                    ><i class="fa fa-calendar fs-15 align-middle"></i>
                                                    <span class="align-middle ms-1 fw-semibold">
                                                        <script>
                                                            document.write(new Date().toLocaleDateString("en-US", { day: "numeric", month: "short", year: "numeric" }))
                                                        </script>
                                                    </span></span
                                                >
                                                <span class="text-nowrap">
                                                    <i class="fa fa-clock fs-15 align-middle"></i>
                                                <span class="align-middle ms-1 fw-semibold" id="clock-widget"></span>
                                            </span>
                                            </p>
                                        </div>
                                        <!-- end card-body -->
                                    </div>
                                </div>
                                <!-- end col-->

                                <div class="col-lg-3 col-md-6 col-xxl-6">
                                    <div class="card card-h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h5 class="text-muted fs-base text-uppercase" title="Number of Orders">Active Products</h5>
                                                    <h3 class="my-3 py-1 fw-semibold"><span data-target="{{ $active_products ?? '0'}}">0</span></h3>
                                                    <p class="mb-0 text-muted">
                                                        <span class="text-danger me-2">
                                                            <!-- <i class="fa fa-arrow-down"></i> 1.89% -->
                                                         </span> 
                                                        <!-- <span class="text-nowrap">Since last month</span> -->
                                                    </p>
                                                </div>
                                                <div class="avatar-md flex-shrink-0">
                                                    <span class="avatar-title bg-primary-subtle rounded-circle fs-22">
                                                        <i class="fa fa-shopping-cart text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card-->
                                </div>
                                <!-- end col-->

                                <div class="col-lg-3 col-md-6 col-xxl-6">
                                    <div class="card card-h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h5 class="text-muted fs-base text-uppercase" title="Current Year Revenue">Revenue</h5>
                                                    <h3 class="my-3 py-1 fw-semibold">GHs <span data-target="{{ number_format($currentYearRevenue ?? 0, 2) }}">0</span></h3>
                                                    <p class="mb-0 text-muted">
                                                        <span class="text-success me-2"><i class="fa fa-arrow-up"></i></span>
                                                        <span class="text-nowrap">Current Year</span>
                                                    </p>
                                                </div>
                                                <div class="avatar-md flex-shrink-0">
                                                    <span class="avatar-title bg-primary-subtle rounded-circle fs-22">
                                                        <i class="fa fa-dollar text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card-->
                                </div>
                                <!-- end col-->

                                <div class="col-lg-3 col-md-6 col-xxl-6">
                                    <div class="card card-h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h5 class="text-muted fs-base text-uppercase" title="Low Stock Items">Low Stock</h5>
                                                    <h3 class="my-3 py-1 fw-semibold"><span data-target="{{ $lowStockProducts->count() }}">0</span></h3>
                                                    <p class="mb-0 text-muted">
                                                        <span class="text-warning me-2"><i class="fa fa-exclamation-triangle"></i></span>
                                                        <span class="text-nowrap">Items need restocking</span>
                                                    </p>
                                                </div>
                                                <div class="avatar-md flex-shrink-0">
                                                    <span class="avatar-title bg-warning-subtle rounded-circle fs-22">
                                                        <i class="fa fa-boxes text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card-->
                                </div>
                                <!-- end col-->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end col -->

                        <div class="col-xxl-7">
                            <div class="row h-100">
                                <div class="col-lg-12">
                                    <div class="card card-h-100">
                                <div class="card-header justify-content-between">
                                            <h4 class="card-title">Sales by Store ({{ date('Y') }})</h4>
                                            <div>
                                                <a href="#" class="btn btn-sm btn-default" data-action="card-refresh">
                                                    <i class="fa fa-refresh me-1"></i> Refresh
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div dir="ltr">
                                                <div id="store-sales-chart" class="apex-charts"></div>
                                            </div>
                                            <div class="text-center mb-1">
                                                <span class="badge badge-outline-light text-dark p-1 px-2 rounded-pill fs-12">
                                                    <i class="fa fa-chart-line text-primary me-1"></i> Monthly Sales
                                                </span>
                                            </div>
                                        </div>
                                        <!-- end card-body-->
                                    </div>
                                    <!-- end card-->
                                </div>
                                <!-- end col-->
                                <!-- <div class="col-lg-6">
                                    <div class="card card-h-100">
                                        <div class="card-header justify-content-between">
                                            <h4 class="card-title">Weekly Performance Insights</h4>
                                            <div class="dropdown ms-auto">
                                                <a href="#" class="btn btn-sm btn-default btn-icon" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-h"></i> 
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="#"> <i class="fa fa-refresh me-2"></i> Refresh Data </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#"> <i class="fa fa-download me-2"></i> Download Report </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#"> <i class="fa fa-share me-2"></i> Share Insights </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider" />
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#"> <i class="fa fa-archive me-2"></i> Archive Widget </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <div dir="ltr">
                                                <div id="weekly-performance-chart" class="apex-charts"></div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                   
                                </div> -->
                            </div>
                            <!-- end row-->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row">
                   
                    <div class="col-xxl-6">
                            <div data-table data-table-rows-per-page="5" class="card card-h-100">
                                <div class="card-header justify-content-between">
                                    <h4 class="card-title">Products Near Expiry <span class="text-muted fs-base fw-normal">({{ $nearExpiryProducts->count() }} items)</span></h4>
                                    <div>
                                        <a href="{{ route('spoilages.index') }}" class="btn btn-sm btn-default"> <i class="fa fa-exclamation-circle me-1"></i> Manage </a>
                                    </div>
                                </div>

                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-custom table-centered table-hover w-100 mb-0">
                                            <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                                                <tr class="text-uppercase table-nowrap fs-xxs">
                                                    <th data-table-sort>#</th>
                                                    <th data-table-sort>Product</th>
                                                    <th data-table-sort>Store</th>
                                                    <th data-table-sort>Qty</th>
                                                    <th data-table-sort>Expiry Date</th>
                                                    <th data-table-sort>Days Left</th>
                                                </tr>
                                            </thead>

                                            <tbody class="text-nowrap">
                                                @forelse($nearExpiryProducts as $index => $stock)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <h5 class="m-0 fs-base">{{ $stock->product ? $stock->product->product_name : 'N/A' }}</h5>
                                                        </td>
                                                        <td>{{ $stock->store ? $stock->store->store_name : 'N/A' }}</td>
                                                        <td>{{ $stock->stock_quantity }}</td>
                                                        <td>{{ $stock->expiry_date ? \Carbon\Carbon::parse($stock->expiry_date)->format('d M Y') : 'N/A' }}</td>
                                                        <td>
                                                            @php
                                                                $daysLeft = $stock->expiry_date ? \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($stock->expiry_date), false) : null;
                                                            @endphp
                                                            @if(is_null($daysLeft))
                                                                <span class="badge bg-secondary-subtle text-secondary">N/A</span>
                                                            @elseif($daysLeft < 0)
                                                                <span class="badge bg-dark-subtle text-dark">Expired</span>
                                                            @elseif($daysLeft <= 7)
                                                                <span class="badge bg-danger-subtle text-danger">{{ $daysLeft }} days</span>
                                                            @elseif($daysLeft <= 30)
                                                                <span class="badge bg-warning-subtle text-warning">{{ $daysLeft }} days</span>
                                                            @else
                                                                <span class="badge bg-success-subtle text-success">{{ $daysLeft }} days</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-4 text-muted">No products near expiry.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card-footer border-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div data-table-pagination-info="orders"></div>
                                        <div data-table-pagination></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col-->


                        <!-- end col-->

                        <div class="col-xxl-6">
                            <div data-table data-table-rows-per-page="6" class="card card-h-100">
                                <div class="card-header justify-content-between">
                                    <h4 class="card-title">Low Stock Products <span class="text-muted fs-base fw-normal">({{ $lowStockProducts->count() }} items)</span></h4>
                                    <div>
                                        <a href="{{ route('inventory.index') }}" class="btn btn-sm btn-default"><i class="fa fa-box me-1"></i> Inventory</a>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-custom table-centered table-hover w-100 mb-0">
                                            <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                                                <tr class="text-uppercase table-nowrap fs-xxs">
                                                    <th>#</th>
                                                    <th>Product / Store</th>
                                                    <th>Qty</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-nowrap">
                                                @forelse($lowStockProducts as $index => $stock)
                                                    <tr>
                                                        <td style="width: 60px">{{ $index + 1 }}</td>
                                                        <td>
                                                            <h5 class="m-0 fs-base">{{ $stock->product ? $stock->product->product_name : 'N/A' }}</h5>
                                                            <span class="text-muted fs-xs">{{ $stock->store ? $stock->store->store_name : 'N/A' }}</span>
                                                        </td>
                                                        <td>
                                                            <h5 class="m-0 fs-base">{{ $stock->stock_quantity }}</h5>
                                                            <span class="text-muted fs-xs">In Stock</span>
                                                        </td>
                                                        <td>
                                                            @if($stock->stock_quantity <= 0)
                                                                <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill fs-12">Out of Stock</span>
                                                            @elseif($stock->stock_quantity <= 10)
                                                                <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill fs-12">Low Stock</span>
                                                            @else
                                                                <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fs-12">In Stock</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-4 text-muted">No low stock products.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- end table-respo.-->
                                </div>
                                <!-- end card-body-->

                                <div class="card-footer border-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div data-table-pagination-info="products"></div>
                                        <div data-table-pagination></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- <div class="row">
                        

                    </div> -->
                    <!-- end row -->
                </div>
                <!-- container -->

                <!-- Footer Start -->
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
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End of Main Content -->
            <!-- ============================================================== -->

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const chartEl = document.querySelector('#store-sales-chart');
                    if (chartEl && typeof ApexCharts !== 'undefined') {
                        const series = @json($storeSalesSeries);
                        const categories = @json($months);

                        const chart = new ApexCharts(chartEl, {
                            series: series,
                            chart: {
                                type: 'line',
                                height: 280,
                                toolbar: { show: false },
                                zoom: { enabled: false }
                            },
                            stroke: {
                                width: 3,
                                curve: 'smooth'
                            },
                            markers: {
                                size: 4,
                                strokeWidth: 0,
                                hover: { size: 7 }
                            },
                            xaxis: {
                                categories: categories,
                                axisBorder: { show: false },
                                labels: { offsetY: 2 }
                            },
                            yaxis: {
                                axisBorder: { show: false },
                                labels: {
                                    formatter: function(value) {
                                        return 'GHs ' + Number(value).toLocaleString();
                                    },
                                    offsetX: -10
                                }
                            },
                            grid: {
                                strokeDashArray: 7,
                                padding: { top: -20, right: 0, bottom: -10, left: 0 }
                            },
                            dataLabels: { enabled: false },
                            tooltip: {
                                y: {
                                    formatter: function(value) {
                                        return 'GHs ' + Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                    }
                                }
                            },
                            legend: {
                                position: 'bottom',
                                offsetY: 5
                            },
                            colors: series.map(s => s.color)
                        });

                        chart.render();
                    }
                });
            </script>
</x-app-layout>
  