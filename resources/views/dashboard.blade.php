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
                                                        <span class="text-muted fs-base text-uppercase h5">Good Day,</span> <br />
                                                        <b>Mohammed</b>
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
                                                    <h5 class="text-muted fs-base text-uppercase" title="Number of Orders">Active Employees</h5>
                                                    <h3 class="my-3 py-1 fw-semibold"><span data-target="9,754">0</span></h3>
                                                    <p class="mb-0 text-muted">
                                                        <span class="text-danger me-2">
                                                            <!-- <i class="fa fa-arrow-down"></i> 1.89% -->
                                                         </span> 
                                                        <span class="text-nowrap">Since last month</span>
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
                                                    <h5 class="text-muted fs-base text-uppercase" title="Average Revenue">Revenue</h5>
                                                    <h3 class="my-3 py-1 fw-semibold">$<span data-target="75.21">0</span>k</h3>
                                                    <p class="mb-0 text-muted">
                                                        <span class="text-danger me-2"><i class="fa fa-arrow-down"></i> 5.23%</span>
                                                        <span class="text-nowrap">Since last month</span>
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
                                                    <h5 class="text-muted fs-base text-uppercase" title="Growth">Growth</h5>
                                                    <h3 class="my-3 py-1 fw-semibold">+ <span data-target="25.08">0</span>%</h3>
                                                    <p class="mb-0 text-muted">
                                                        <span class="text-success me-2"><i class="ti ti-arrow-up"></i> 4.87%</span>
                                                        <span class="text-nowrap">Since last month</span>
                                                    </p>
                                                </div>
                                                <div class="avatar-md flex-shrink-0">
                                                    <span class="avatar-title bg-primary-subtle rounded-circle fs-22">
                                                        <i class="fa fa-trend text-primary"></i>
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
                                <div class="col-lg-6">
                                    <div class="card card-h-100">
                                        <div class="card-header justify-content-between">
                                            <h4 class="card-title">Store Performance Analytics</h4>
                                            <div>
                                                <a href="#" class="btn btn-sm btn-default" data-action="card-refresh">
                                                    <i class="fa fa-refresh me-1"></i> Refresh
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div dir="ltr">
                                                <div id="total-sales-chart" class="apex-charts"></div>
                                            </div>
                                            <div class="text-center mb-1">
                                                <span class="badge badge-outline-light text-dark p-1 px-2 rounded-pill fs-12">
                                                    <i class="fa fa-star text-danger me-1"></i> POOR SALES
                                                </span>
                                            </div>
                                        </div>
                                        <!-- end card-body-->
                                    </div>
                                    <!-- end card-->
                                </div>
                                <!-- end col-->
                                <div class="col-lg-6">
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
                                        <!-- end card-body-->
                                    </div>
                                    <!-- end card-->
                                </div>
                            </div>
                            <!-- end row-->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-xxl-6">
                            <div class="card card-h-100">
                                <div class="card-header border-dashed card-tabs">
                                    <div class="flex-grow-1">
                                        <h4 class="card-title">Sales Report <span class="text-muted fs-base fw-normal">(25822 Orders)</span></h4>
                                    </div>
                                    <ul class="nav nav-tabs nav-justified card-header-tabs nav-bordered">
                                        <li class="nav-item">
                                            <a href="#!" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                                <span class="d-md-none d-block">1D</span>
                                                <span class="d-none d-md-block">Today</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#!" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                                <span class="d-md-none d-block">1M</span>
                                                <span class="d-none d-md-block">Monthly</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#!" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                                <span class="d-md-none d-block">1Y</span>
                                                <span class="d-none d-md-block">Annual</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body p-0">
                                    <div class="bg-light bg-opacity-25 border-bottom border-dashed">
                                        <div class="row text-center">
                                            <div class="col-sm-4">
                                                <p class="text-muted mt-3 mb-1">Revenue</p>
                                                <h4 class="mb-3">
                                                    <i class="fa fa-arrow-up text-success me-1"></i>
                                                    <span>$<span data-target="78,224.68"></span></span>
                                                </h4>
                                            </div>
                                            <div class="col-sm-4">
                                                <p class="text-muted mt-3 mb-1">Orders</p>
                                                <h4 class="mb-3">
                                                    <i class="fa fa-arrow-up text-success me-1"></i>
                                                    <span><span data-target="8541"></span></span>
                                                </h4>
                                            </div>
                                            <div class="col-sm-4">
                                                <p class="text-muted mt-3 mb-1">Growth Rate</p>
                                                <h4 class="mb-3">
                                                    <i class="fa fa-arrow-up text-success me-1"></i>
                                                    <span><span data-target="25.3"></span>%</span>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-3 pt-1">
                                        <div class="dash-item-overlay d-none d-md-block" dir="ltr">
                                            <h5>Today's Earning: $8,975.30</h5>
                                            <p class="text-muted mb-0 mt-2">Property PS007 is not receiving hits. Either your site is not receiving any sessions.</p>
                                        </div>
                                        <div dir="ltr">
                                            <div id="sales-report-chart" class="apex-charts"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                        <!-- end col-->

                        <div class="col-xxl-6">
                            <div data-table data-table-rows-per-page="6" class="card card-h-100">
                                <div class="card-header justify-content-between">
                                    <h4 class="card-title">Top Selling Products</h4>
                                    <div>
                                        <a href="#" class="btn btn-sm btn-default"><i class="ti ti-cloud-upload me-1"></i> Export</a>
                                        <a href="#" class="btn btn-sm btn-light"><i class="ti ti-download me-1"></i> Import</a>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-custom table-centered table-hover w-100 mb-0">
                                            <tbody class="text-nowrap">
                                                <!-- Record 1 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                       1
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Modern Fabric Sofa Set</h5>
                                                        <span class="text-muted fs-xs">By: Homeluxe</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$499.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">34</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$16,966.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill fs-12"> Low Stock </span>
                                                    </td>
                                                </tr>

                                                <!-- Record 2 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                      2
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">L-Shaped Sectional Sofa</h5>
                                                        <span class="text-muted fs-xs">By: ComfortHub</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$899.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">21</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$18,879.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fs-12"> In Stock </span>
                                                    </td>
                                                </tr>

                                                <!-- Record 3 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                        <!-- <img src="assets/images/products/3.png" alt="product-pic" height="36" /> -->
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Velvet Recliner Chair</h5>
                                                        <span class="text-muted fs-xs">By: SoftEase</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$379.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">47</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$17,813.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fs-12"> In Stock </span>
                                                    </td>
                                                </tr>

                                                <!-- Record 4 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                        <!-- <img src="assets/images/products/4.png" alt="product-pic" height="36" /> -->
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Classic Wooden Coffee Table</h5>
                                                        <span class="text-muted fs-xs">By: OakCraft</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$259.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">58</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$15,022.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill fs-12"> Out of Stock </span>
                                                    </td>
                                                </tr>

                                                <!-- Record 5 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                        <!-- <img src="assets/images/products/5.png" alt="product-pic" height="36" /> -->
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Minimalist TV Stand</h5>
                                                        <span class="text-muted fs-xs">By: FurniPro</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$315.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">64</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$20,160.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fs-12"> In Stock </span>
                                                    </td>
                                                </tr>

                                                <!-- Record 6 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                        <!-- <img src="assets/images/products/6.png" alt="product-pic" height="36" /> -->
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Leather Lounge Chair</h5>
                                                        <span class="text-muted fs-xs">By: UrbanStyle</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$425.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">39</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$16,575.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill fs-12"> Low Stock </span>
                                                    </td>
                                                </tr>

                                                <!-- Record 7 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                        <!-- <img src="assets/images/products/7.png" alt="product-pic" height="36" /> -->
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Glass Center Table</h5>
                                                        <span class="text-muted fs-xs">By: CrystalCasa</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$289.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">52</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$15,028.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fs-12"> In Stock </span>
                                                    </td>
                                                </tr>

                                                <!-- Record 8 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                        <!-- <img src="assets/images/products/8.png" alt="product-pic" height="36" /> -->
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Wooden Bookshelf Unit</h5>
                                                        <span class="text-muted fs-xs">By: TimberWorks</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$349.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">28</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$9,772.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill fs-12"> Low Stock </span>
                                                    </td>
                                                </tr>

                                                <!-- Record 9 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                        <!-- <img src="assets/images/products/9.png" alt="product-pic" height="36" /> -->
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Luxury King Bed Frame</h5>
                                                        <span class="text-muted fs-xs">By: DreamRest</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$1,099.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">15</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$16,485.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill fs-12"> Out of Stock </span>
                                                    </td>
                                                </tr>

                                                <!-- Record 10 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                        <!-- <img src="assets/images/products/10.png" alt="product-pic" height="36" /> -->
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Round Dining Table Set</h5>
                                                        <span class="text-muted fs-xs">By: CasaDine</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$725.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">25</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$18,125.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fs-12"> In Stock </span>
                                                    </td>
                                                </tr>

                                                <!-- Record 11 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                        <!-- <img src="assets/images/products/2.png" alt="product-pic" height="36" /> -->
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Ergonomic Office Chair</h5>
                                                        <span class="text-muted fs-xs">By: WorkEase</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$269.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">44</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$11,836.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fs-12"> In Stock </span>
                                                    </td>
                                                </tr>

                                                <!-- Record 12 -->
                                                <tr>
                                                    <td style="width: 60px">
                                                        <!-- <img src="assets/images/products/5.png" alt="product-pic" height="36" /> -->
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Nightstand with Drawers</h5>
                                                        <span class="text-muted fs-xs">By: CozyHome</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$189.00</h5>
                                                        <span class="text-muted fs-xs">Price</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">53</h5>
                                                        <span class="text-muted fs-xs">Quantity</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">$10,017.00</h5>
                                                        <span class="text-muted fs-xs">Amount</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill fs-12"> Low Stock </span>
                                                    </td>
                                                </tr>
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

                    <div class="row">
                        <div class="col-xxl-7">
                            <div data-table data-table-rows-per-page="5" class="card card-h-100">
                                <div class="card-header justify-content-between">
                                    <h4 class="card-title">Recent Orders <span class="text-muted fs-base fw-normal">(186.25k Transactions)</span></h4>
                                    <div>
                                        <a href="#" class="btn btn-sm btn-default"> <i class="ti ti-cloud-upload me-1"></i> Export </a>
                                        <a href="#" class="btn btn-sm btn-light"> <i class="ti ti-download me-1"></i> Import </a>
                                    </div>
                                </div>

                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-custom table-centered table-hover w-100 mb-0">
                                            <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                                                <tr class="text-uppercase table-nowrap fs-xxs">
                                                    <th data-table-sort>#ID</th>
                                                    <th data-table-sort>Customer</th>
                                                    <th data-table-sort>Date</th>
                                                    <th data-table-sort>Amount</th>
                                                    <th data-table-sort>Payment</th>
                                                    <th data-table-sort>Status</th>
                                                </tr>
                                            </thead>

                                            <tbody class="text-nowrap">
                                                <!-- Order 1 -->
                                                <tr>
                                                    <td>#ORD-1023</td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">John Carter</h5>
                                                        <span class="text-muted fs-xs">john&#64;example.com</span>
                                                    </td>
                                                    <td>12 Nov 2025</td>
                                                    <td>$249.00</td>
                                                    <td>Credit Card</td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success"> Completed </span>
                                                    </td>
                                                </tr>

                                                <!-- Order 2 -->
                                                <tr>
                                                    <td>#ORD-1022</td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Emma Wilson</h5>
                                                        <span class="text-muted fs-xs">emma&#64;example.com</span>
                                                    </td>
                                                    <td>12 Nov 2025</td>
                                                    <td>$179.00</td>
                                                    <td>UPI</td>
                                                    <td>
                                                        <span class="badge bg-warning-subtle text-warning"> Pending </span>
                                                    </td>
                                                </tr>

                                                <!-- Order 3 -->
                                                <tr>
                                                    <td>#ORD-1021</td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Michael Harris</h5>
                                                        <span class="text-muted fs-xs">michael&#64;example.com</span>
                                                    </td>
                                                    <td>11 Nov 2025</td>
                                                    <td>$329.00</td>
                                                    <td>PayPal</td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success"> Completed </span>
                                                    </td>
                                                </tr>


                                                <!-- Order 9 -->
                                                <tr>
                                                    <td>#ORD-1015</td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Oliver Brown</h5>
                                                        <span class="text-muted fs-xs">oliver&#64;example.com</span>
                                                    </td>
                                                    <td>08 Nov 2025</td>
                                                    <td>$720.00</td>
                                                    <td>UPI</td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success"> Completed </span>
                                                    </td>
                                                </tr>

                                                <!-- Order 10 -->
                                                <tr>
                                                    <td>#ORD-1014</td>
                                                    <td>
                                                        <h5 class="m-0 fs-base">Charlotte Green</h5>
                                                        <span class="text-muted fs-xs">charlotte&#64;example.com</span>
                                                    </td>
                                                    <td>08 Nov 2025</td>
                                                    <td>$138.00</td>
                                                    <td>PayPal</td>
                                                    <td>
                                                        <span class="badge bg-warning-subtle text-warning"> Pending </span>
                                                    </td>
                                                </tr>
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

                        <div class="col-xxl-5">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-h-100">
                                        <div class="card-header justify-content-between">
                                            <h4 class="card-title">Recent Activity</h4>
                                            <div class="dropdown ms-auto">
                                                <a href="#" class="btn btn-sm btn-default btn-icon" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical fs-lg"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="#"> <i class="ti ti-activity me-2"></i> View Activity Log </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#"> <i class="ti ti-filter-2 me-2"></i> Filter Activities </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#"> <i class="ti ti-download me-2"></i> Export Logs </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider" /></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#"> <i class="ti ti-trash me-2"></i> Clear Activity </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="card-body" data-simplebar style="max-height: 426px">
                                            <div class="timeline timeline-users">
                                                <!-- Event 1 -->
                                                <div class="timeline-item d-flex align-items-stretch">
                                                    <div class="timeline-dot text-bg-primary">
                                                        <i class="ti ti-shopping-cart fs-md"></i>
                                                    </div>
                                                    <div class="timeline-content ps-3 pb-4">
                                                        <h5 class="mb-1">New Orders Synced from Storefront</h5>
                                                        <p class="mb-1 text-muted">1,250 new customer orders were successfully imported from the online store.</p>
                                                        <span class="text-primary fw-semibold">By Olivia Green</span>
                                                    </div>
                                                </div>

                                                <!-- Event 2 -->
                                                <div class="timeline-item d-flex align-items-stretch">
                                                    <div class="timeline-dot text-bg-success">
                                                        <i class="ti ti-credit-card fs-md"></i>
                                                    </div>
                                                    <div class="timeline-content ps-3 pb-4">
                                                        <h5 class="mb-1">Payment Gateway Integration Updated</h5>
                                                        <p class="mb-1 text-muted">Stripe API upgraded to support faster settlements and improved security tokens.</p>
                                                        <span class="text-primary fw-semibold">By James Parker</span>
                                                    </div>
                                                </div>

                                                <!-- Event 3 -->
                                                <div class="timeline-item d-flex align-items-stretch">
                                                    <div class="timeline-dot text-bg-warning">
                                                        <i class="ti ti-package fs-md"></i>
                                                    </div>
                                                    <div class="timeline-content ps-3 pb-4">
                                                        <h5 class="mb-1">Inventory Levels Auto-Synced</h5>
                                                        <p class="mb-1 text-muted">All product quantities were updated based on the latest warehouse data.</p>
                                                        <span class="text-primary fw-semibold">By Sophia Lee</span>
                                                    </div>
                                                </div>

                                                <!-- Event 4 -->
                                                <div class="timeline-item d-flex align-items-stretch">
                                                    <div class="timeline-dot text-bg-info">
                                                        <i class="ti ti-user fs-md"></i>
                                                    </div>
                                                    <div class="timeline-content ps-3 pb-4">
                                                        <h5 class="mb-1">New Vendor Accounts Approved</h5>
                                                        <p class="mb-1 text-muted">Five new seller accounts were verified and added to the marketplace.</p>
                                                        <span class="text-primary fw-semibold">By Liam Johnson</span>
                                                    </div>
                                                </div>

                                                <!-- Event 5 -->
                                                <div class="timeline-item d-flex align-items-stretch">
                                                    <div class="timeline-dot text-bg-danger">
                                                        <i class="ti ti-alert-circle fs-md"></i>
                                                    </div>
                                                    <div class="timeline-content ps-3 pb-4">
                                                        <h5 class="mb-1">Refund Requests Reviewed</h5>
                                                        <p class="mb-1 text-muted">27 refund claims were processed successfully with zero pending disputes.</p>
                                                        <span class="text-primary fw-semibold">By Ethan Miller</span>
                                                    </div>
                                                </div>

                                                <!-- Event 6 -->
                                                <div class="timeline-item d-flex align-items-stretch">
                                                    <div class="timeline-dot text-bg-secondary">
                                                        <i class="ti ti-speakerphone fs-md"></i>
                                                    </div>
                                                    <div class="timeline-content ps-3">
                                                        <h5 class="mb-1">Summer Campaign Launched</h5>
                                                        <p class="mb-1 text-muted">The “Summer Deals 2025” campaign is now live across all marketing channels.</p>
                                                        <span class="text-primary fw-semibold">By Ava Mitchell</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end timeline -->
                                        </div>
                                        <!-- end slimscroll -->
                                    </div>
                                    <!-- end card-->
                                </div>
                                <!-- end col -->
                            </div>
                        </div>
                    </div>
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
</x-app-layout>
  