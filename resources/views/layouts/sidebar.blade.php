<!-- Brand Logo -->
    <a href="{{ url('/dashboard') }}" class="logo">
        <span class="logo logo-light">
            <span class="logo-lg"><img src="{{ asset('images/logo.png') }}" alt="logo" /></span>
            <span class="logo-sm"><img src="{{ asset('images/logo-sm.png') }}" alt="small logo" /></span>
        </span>

        <span class="logo logo-dark">
            <span class="logo-lg"><img src="{{ asset('images/logo-black.png') }}" alt="dark logo" /></span>
            <span class="logo-sm"><img src="{{ asset('images/logo-sm.png') }}" alt="small logo" /></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-on-hover">
        <span class="btn-on-hover-icon"></span>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-offcanvas">
        <i class="fa fa-menu align-middle"></i>
    </button>

    <div class="scrollbar" data-simplebar="">
        <div id="user-profile-settings" class="sidenav-user" style="background: url(assets/images/user-bg-pattern.svg)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="#" class="link-reset">
                        <img src="{{ asset('images/users/user-1.jpg') }}" alt="user-image" class="rounded-circle mb-2 avatar-md" />
                        <span class="sidenav-user-name fw-bold">{{ Auth::user()->othername }}</span>
                        <span class="fs-12 fw-semibold" data-lang="user-role">Login User</span>
                    </a>
                </div>
                <div>
                    <a class="dropdown-toggle drop-arrow-none link-reset sidenav-user-set-icon" data-bs-toggle="dropdown" data-bs-offset="0,12" href="#!" aria-haspopup="false" aria-expanded="false">
                        <i class="fa fa-settings fs-24 align-middle ms-1"></i>
                    </a>

                    <div class="dropdown-menu">
                        <!-- Header -->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome back!</h6>
                        </div>

                        <!-- My Profile -->
                        <a href="#" class="dropdown-item">
                            <i class="fa fa-user-circle me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Profile</span>
                        </a>

                        <!-- Settings -->
                        <a href="#" class="dropdown-item">
                            <i class="fa fa-settings-2 me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Account Settings</span>
                        </a>

                        <!-- Lock -->
                        <a href="#" class="dropdown-item">
                            <i class="fa fa-lock me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Lock Screen</span>
                        </a>

                         <form method="POST" action="{{ route('logout') }}">
                            @csrf
                        <!-- Logout -->
                            <a href="{{ route('logout') }}" class="dropdown-item text-danger fw-semibold" onclick="event.preventDefault();
                                this.closest('form').submit();">
                                 <i class="fa fa-logout me-1 fs-lg align-middle"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--- Sidenav Menu -->
        <div id="sidenav-menu">
            <ul class="side-nav">
                <!-- <li class="side-nav-title mt-2" data-lang="main">Menu</li> -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="{{ url('/dashboard') }}" aria-expanded="false" aria-controls="dashboards" class="side-nav-link">
                        <span class="menu-icon"><i class="fa fa-dashboard"></i></span>
                        <span class="menu-text" data-lang="dashboards">Dashboard</span>
                        <!-- <span class="menu-arrow"></span> -->
                    </a>
                </li>
                <li class="side-nav-title mt-2" data-lang="apps" style="color:white">Main Menu</li>
                <li class="side-nav-item">
                     @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                    <a data-bs-toggle="collapse" href="#product" aria-expanded="false" aria-controls="product" class="side-nav-link">
                        <span class="menu-icon"><i class="fa fa-list-check"></i></span>
                        <span class="menu-text" data-lang="projects">Inventory</span>
                        <span class="menu-arrow"></span>
                    </a>
                    @endif
                    <div class="collapse" id="product">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="#" class="side-nav-link">
                                    <span class="menu-text" data-lang="apps-product-grid"></span>
                                </a>
                            </li>
                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                            <li class="side-nav-item">
                                <a href="{{ url('/product-categories') }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="apps-product-list"> Product Category</span>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                             <li class="side-nav-item">
                                <a href="{{ url('/products') }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="apps-product-list"> Product </span>
                                </a>
                            </li>
                              @endif
                              @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                             <li class="side-nav-item">
                                <a href="{{ url('/product-prices') }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="apps-product-list"> Price List</span>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                             <li class="side-nav-item">
                                <a href="{{ url('/inventory') }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="apps-product-list"> Stock Adjustment</span>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                             <li class="side-nav-item">
                                <a href="{{ route('purchase-orders.index') }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="apps-product-list"> Purchase Order</span>
                                </a>
                            </li>
                            @endif
                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                             <li class="side-nav-item">
                                <a href="{{ route('requisitions.index') }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="apps-task-list">Requisitions</span>
                                </a>
                            </li>
                           @endif
                        </ul>
                    </div>
                </li>
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sales" aria-expanded="false" aria-controls="sales" class="side-nav-link">
                        <span class="menu-icon"><i class="fa fa-list-check"></i></span>
                        <span class="menu-text" data-lang="sales">Sales</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sales">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ url('/sales') }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="apps-task-list">Current Sales</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                  @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#supplier" aria-expanded="false" aria-controls="supplier" class="side-nav-link">
                        <span class="menu-icon"><i class="fa fa-list-check"></i></span>
                        <span class="menu-text" data-lang="supplier">Supplier</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="supplier">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ url('/suppliers') }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="apps-task-list">Suppliers</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
              @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003' || auth()->user()->role_id === '1004' || auth()->user()->role_id === '1005'
              || auth()->user()->role_id === '1006' || auth()->user()->role_id === '1007' || auth()->user()->role_id === '1008')
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#product-management" aria-expanded="false" aria-controls="product-management" class="side-nav-link">
                        <span class="menu-icon"><i class="fa fa-list-check"></i></span>
                        <span class="menu-text" data-lang="product-management">Product Management</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="product-management">
                        <ul class="sub-menu">
                            <!-- <li class="side-nav-item">
                                <a href="#" class="side-nav-link">
                                    <span class="menu-text" data-lang="apps-task-list">Expired Products</span>
                                </a>
                            </li> -->
                            <li class="side-nav-item">
                                <a href="{{ route('spoilages.index') }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="apps-task-details">Spoilages/Expired</span>
                                </a>
                            </li> 
                            
                        </ul>
                    </div>
                </li>
                @endif
                  @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                <li class="side-nav-title mt-2" data-lang="custom-pages">System Settings</li>
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#pages" aria-expanded="false" aria-controls="pages" class="side-nav-link">
                        <span class="menu-icon"><i class="fa fa-list-check"></i></span>
                        <span class="menu-text" data-lang="pages"> User Management</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="pages">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ url('/users') }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="pages-about-us">Users</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
                @endif
                <!-- <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#plugins" aria-expanded="false" aria-controls="plugins" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-cpu"></i></span>
                        <span class="menu-text" data-lang="plugins">Store Management</span>
                        <span class="menu-arrow"></span>
                    </a>    
                </li> -->
                 @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#error-pages" aria-expanded="false" aria-controls="error-pages" class="side-nav-link">
                        <span class="menu-icon"><i class="fa fa-list-check"></i></span>
                        <span class="menu-text" data-lang="error-pages">Store Management</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="error-pages">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ url('stores')}}" class="side-nav-link">
                                    <span class="menu-text" data-lang="error-400">Stores</span>
                                </a>
                            </li>
                                @if(auth()->user()->role_id === '1001')
                            <li class="side-nav-item">
                                <a href="{{ url('/tenants') }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="error-401">Tenants</span>
                                </a>
                            </li>
                            @endif
                            <!-- <li class="side-nav-item">
                                <a href="error-403.html" class="side-nav-link">
                                    <span class="menu-text" data-lang="error-403">403 Forbidden</span>
                                </a>
                            </li> -->
                            
                        </ul>
                    </div>
                </li>
                @endif
                <li class="side-nav-title mt-2" data-lang="layouts" style="color:white">Reports</li>
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#layout-options" aria-expanded="false" aria-controls="layout-options" class="side-nav-link">
                        <span class="menu-icon"><i class="fa fa-list-check"></i></span>
                        <span class="menu-text" data-lang="layout-options">Inventory</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="layout-options">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('reports.index', ['type' => 'active_products']) }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="layouts-horizontal">Products</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('reports.index', ['type' => 'product_prices']) }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="layouts-boxed">Prices</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('reports.index', ['type' => 'stocked_products']) }}" class="side-nav-link">
                                    <span class="menu-text" data-lang="layouts-scrollable">Stocks</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebars" aria-expanded="false" aria-controls="sidebars" class="side-nav-link">
                        <span class="menu-icon"><i class="fa fa-list-check"></i></span>
                        <span class="menu-text" data-lang="sidebars">Revenue/Expenditure</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebars">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="#" class="side-nav-link">
                                    <span class="menu-text" data-lang="layouts-sidebar-light">Cash Sales</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="layouts-sidebar-gradient.html" class="side-nav-link">
                                    <span class="menu-text" data-lang="layouts-sidebar-gradient">Gradient Menu</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="layouts-sidebar-gray.html" class="side-nav-link">
                                    <span class="menu-text" data-lang="layouts-sidebar-gray">Gray Menu</span>
                                </a>
                            </li>
                           
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#users_report" aria-expanded="false" aria-controls="users_report" class="side-nav-link">
                        <span class="menu-icon"><i class="fa fa-list-check"></i></span>
                        <span class="menu-text" data-lang="sidebars">Users</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="users_report">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="#" class="side-nav-link">
                                    <span class="menu-text" data-lang="layouts-sidebar-light">User Report</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="#" class="side-nav-link">
                                    <span class="menu-text" data-lang="layouts-sidebar-gradient">User Logs</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="#" class="side-nav-link">
                                    <span class="menu-text" data-lang="layouts-sidebar-gray">Gray Menu</span>
                                </a>
                            </li>
                           
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>

