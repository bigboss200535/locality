<x-app-layout>
<!-- Begin page -->
    <!-- ============================================================== -->
    <!-- Start Main Content -->
    <!-- ============================================================== -->
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center"></div>

            <!-- Notifications -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
           
            <div class="row">
                <div class="col-xxl-12 col-lg-12">
                    <div data-table data-table-rows-per-page="15" class="card card-h-100">
                        <div class="card-header justify-content-between" align-items-center flex-wrap gap-2>
                             <h4 class="card-title">Inventory <span class="text-muted fs-base fw-normal">({{ $products->count() }} Products)</span></h4>
                             <div class="d-flex align-items-center gap-2">
                                <div class="input-group input-group-sm" style="width: 220px;">
                                    <span class="input-group-text bg-light"><i class="fa fa-search"></i></span>
                                    <form id="searchForm">
                                        <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                                    <!-- <input type="text" data-table-search class="form-control" placeholder="Search products..."> -->
                                    </form>
                                </div>
                                @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                 <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#addStockModal">
                                    <i class="fa fa-plus me-1"></i> Add Stock
                                </button>

                                @endif
                                <!-- <a href="#" class="btn btn-sm btn-default"> <i class="fa fa-cloud-upload me-1"></i> Export </a> -->
                                <!-- <a href="#" class="btn btn-sm btn-light"> <i class="fa fa-download me-1"></i> Import </a> -->
                            </div>
                            <!--<div>
                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#addStockModal">
                                    <i class="fa fa-plus me-1"></i> Add Stock
                                </button>
                            </div> -->
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover w-100 mb-0">
                                    <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                                        <tr class="text-uppercase table-nowrap fs-xxs">
                                            <th data-table-sort>#ID</th>
                                            <th data-table-sort>Product</th>
                                            <!-- <th data-table-sort>Product Category</th> -->
                                            <th data-table-sort>Store Name</th>
                                            <th data-table-sort>Stock Quantity</th>
                                            <th data-table-sort>Cost Price (GHs)</th>
                                            <th data-table-sort>Stocked Value (GHs)</th>
                                            <th data-table-sort>Date Added</th>
                                            <th data-table-sort>Status</th>
                                            <th data-table-sort>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        @forelse ($products as $product)
                                            <tr>
                                                <td>#{{ substr($product->product_id, 0, 8) }}</td>
                                                <td>
                                                    <h5 class="m-0 fs-base">{{ strtoupper($product->product_name) }}</h5>
                                                    @if($product->product_type)
                                                     <!-- <span class="text-muted fs-xs">  -->
                                                        <span class="badge bg-primary-subtle text-primary">{{ $product->category ? $product->category->category_name : 'N/A' }}</span>
                                                    <!-- </span> -->
                                                    @endif
                                                </td>
                                                <!-- <td>
                                                    
                                                 </td> -->
                                                <!-- <td>{{ $product->category ? $product->category->category_name : 'N/A' }}</td> -->
                                                <td>{{ $product->store ? $product->store->store_name : 'N/A'}}</td>
                                                <td>
                                                    @if($product->stock)
                                                        {{ $product->stock->stock_quantity }}
                                                    @else
                                                        <span class="badge bg-soft-danger text-danger">0 (No Stock Record)</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($product->price ? $product->price->unit_cost : 0, 2) }}</td>
                                                <td>{{ number_format(($product->price ? $product->price->unit_cost : 0) * ($product->stock ? $product->stock->stock_quantity : 0), 2) }}</td>
                                                <td>{{ $product->added_date ? \Carbon\Carbon::parse($product->added_date)->format('d M Y, h:i A') : 'N/A' }}</td>
                                                <td>
                                                    @if(($product->stock ? $product->stock->stock_quantity : 0) > 0)
                                                        <span class="badge bg-success-subtle text-success">IN STOCK</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">OUT OF STOCK</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="">
                                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if($product->stock)
                                                                <li>
                                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editStockModal-{{ $product->product_id }}">
                                                                        Edit Stock
                                                                    </a>
                                                                </li>
                                                                 @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this product stock?')) { document.getElementById('delete-stock-form-{{ $product->product_id }}').submit(); }">
                                                                        Delete Stock
                                                                    </a>
                                                                </li>
                                                                @endif
                                                            @else
                                                                <li>
                                                                    <a class="dropdown-item text-success" href="#" data-bs-toggle="modal" data-bs-target="#addStockModal-{{ $product->product_id }}">
                                                                        Add Stock
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>

                                                    @if($product->stock)
                                                        <form id="delete-stock-form-{{ $product->product_id }}" action="{{ route('inventory.destroy', $product->product_id) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">
                                                    No products found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div data-table-pagination-info="products"></div>
                                <div data-table-pagination></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col-->
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

    <!-- Add Stock Modal (Global) -->
    <div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content text-start" style="white-space: normal;">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStockModalLabel">Add New Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Select Product</label>
                            <select class="form-select basic-select-two" id="product_id" name="product_id" required>
                                <option value="" disabled selected>Select a Product...</option>
                                @foreach($products as $product)
                                    @if(!$product->stock)
                                        <option value="{{ $product->product_id }}">{{ strtoupper($product->product_name) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required min="0" placeholder="e.g. 100">
                        </div>
                        <div class="mb-3">
                            <label for="batch_number" class="form-label">Batch Number (if Available)</label>
                            <input type="text" class="form-control" id="batch_number" name="batch_number" min="0" placeholder="e.g. 123BG83">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Specific Modals -->
    @foreach ($products as $product)
        @if($product->stock)
            <!-- Edit Stock Modal -->
            <div class="modal fade" id="editStockModal-{{ $product->product_id }}" tabindex="-1" aria-labelledby="editStockModalLabel-{{ $product->product_id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-start" style="white-space: normal;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editStockModalLabel-{{ $product->product_id }}">Update Stock: {{ strtoupper($product->product_name) }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('inventory.update', $product->product_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" value="{{ strtoupper($product->product_name) }}" readonly disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="stock_quantity-{{ $product->product_id }}" class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control" id="stock_quantity-{{ $product->product_id }}" name="stock_quantity" value="{{ $product->stock->stock_quantity }}" required min="0">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Stock</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Add Stock Modal for Specific Product -->
            <div class="modal fade" id="addStockModal-{{ $product->product_id }}" tabindex="-1" aria-labelledby="addStockModalLabel-{{ $product->product_id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-start" style="white-space: normal;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addStockModalLabel-{{ $product->product_id }}">Add Stock: {{ strtoupper($product->product_name) }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('inventory.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" value="{{ strtoupper($product->product_name) }}" readonly disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="stock_quantity_new-{{ $product->product_id }}" class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control" id="stock_quantity_new-{{ $product->product_id }}" name="stock_quantity" required min="0" placeholder="0">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Stock</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
<script> 
        let timer;

            $('input[name=search]').on('keyup', function(){
                clearTimeout(timer);
                timer=setTimeout(function(){
                    $('#search_form').submit();

                },500);

            });
    </script>
    <!-- ============================================================== -->
    <!-- End of Main Content -->
    <!-- ============================================================== -->
</x-app-layout>
