<x-app-layout>
<!-- Begin page -->
    <!-- Topbar End -->
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
                        <div class="card-header justify-content-between align-items-center flex-wrap gap-2">
                            <h4 class="card-title">Products <span class="text-muted fs-base fw-normal">({{ $products->count() }} Products)</span></h4>
                            <div class="d-flex align-items-center gap-2">
                                <div class="input-group input-group-sm" style="width: 220px;">
                                    <span class="input-group-text bg-light"><i class="fa fa-search"></i></span>
                                    <input type="text" data-table-search class="form-control" placeholder="Search products...">
                                </div>
                                @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    <i class="fa fa-plus me-1"></i> Add Product
                                </button>
                                @endif
                                <!-- <a href="#" class="btn btn-sm btn-default"> <i class="fa fa-cloud-upload me-1"></i> Export </a> -->
                                <!-- <a href="#" class="btn btn-sm btn-light"> <i class="fa fa-download me-1"></i> Import </a> -->
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover w-100 mb-0">
                                    <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                                        <tr class="text-uppercase table-nowrap fs-xxs">
                                            <th data-table-sort>#ID</th>
                                            <th data-table-sort>Product</th>
                                            <th data-table-sort>Product Category</th>
                                            @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                            <th data-table-sort>Store Name</th>
                                            @endif
                                            @if(auth()->user()->role_id === '1001')
                                            <th data-table-sort>Tenant Name</th>
                                            @endif
                                            <!-- <th data-table-sort>Stock Quantity</th> -->
                                            <th data-table-sort>Stockable</th>
                                            <th data-table-sort>Expirable</th>
                                            <th data-table-sort>Date Added</th>
                                            <th data-table-sort>Status</th>
                                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                            <th data-table-sort>Action</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        @forelse ($products as $product)
                                            <tr>
                                                <td>#{{ substr($product->product_id, 0, 8) }}</td>
                                                <td>
                                                    <h5 class="m-0 fs-base">{{ strtoupper($product->product_name) }}</h5>
                                                    @if($product->product_type)
                                                     <span class="text-muted fs-xs">{{ strtoupper($product->product_type )}}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                     <span class="badge bg-primary-subtle text-primary">{{ $product->category ? $product->category->category_name : 'N/A' }}</span>
                                                 </td>
                                            @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                <td>{{ $product->store ? $product->store->store_name : 'N/A'}}</td>
                                            @endif
                                            @if(auth()->user()->role_id === '1001')
                                                <td>{{ $product->tenant ? $product->tenant->tenant_name : 'N/A'}}</td>
                                            @endif
                                            
                                                <td>
                                                    @if($product->stockable == 'NO')
                                                        <span class="badge bg-primary-subtle text-primary">NO</span>
                                                    @else
                                                        <span class="badge bg-info-subtle text-info">YES</span>
                                                    @endif
                                                </td>
                                                 <!-- <td>{{ $product->expirable }}</td> -->
                                                 <td>
                                                    @if($product->expirable == 'YES')
                                                        <span class="badge bg-info-subtle text-info">YES</span>
                                                    @else
                                                        <span class="badge bg-primary-subtle text-primary">NO</span>
                                                    @endif
                                                </td>
                                                <!-- <td>GHs {{ number_format($product->price ? $product->price->unit_cost : 0, 2) }}</td> -->
                                                <!-- <td> {{  strtoupper($product->added_by)  }}</td> -->
                                                <td>{{ $product->added_date ? \Carbon\Carbon::parse($product->added_date)->format('d M Y, h:i A') : 'N/A' }}</td>
                                                <td>
                                                    @if($product->status == 'Active' )
                                                        <span class="badge bg-success-subtle text-success">ACTIVE</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">INACTIVE</span>
                                                    @endif
                                                </td>
                                                 @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                <td>
                                                    <div class="">
                                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                             <li>
                                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->product_id }}">
                                                                        Edit
                                                                    </a>
                                                                </li>
                                                            <!-- <li><a class="dropdown-item" href="{{ route('products.edit', $product->product_id) }}">Edit</a></li> -->
                                                            <!-- <li><a class="dropdown-item" href="{{ route('products.show', $product->product_id) }}">View</a></li>
                                                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                                                             <li> -->
                                                               <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this product price?')) { document.getElementById('delete-product-form-{{ $product->product_id }}').submit(); }">
                                                                        Delete
                                                                    </a>
                                                            </li>
                                                            @endif
                                                            <!-- <li><a class="dropdown-item" href="{{ route('products.destroy', $product->product_id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $product->product_id }}').submit();">Delete</a></li> -->
                                                        </ul>
                                                    </div>

                                                     <!-- Edit Product Modal -->
                                                    <div class="modal fade" id="editProductModal{{ $product->product_id }}" tabindex="-1" aria-labelledby="editProductModal{{ $product->product_id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content text-start text-wrap">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editProductModal{{ $product->product_id }}">Edit Product: {{ strtoupper($product->product_name) }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('products.update', $product->product_id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label for="product_name-{{ $product->product_id }}" class="form-label">Product Name</label>
                                                                            <input type="text" class="form-control text-start" id="product_name-{{ $product->product_id }}" name="product_name" value="{{ $product->product_name }}" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="product_type-{{ $product->product_type }}" class="form-label">Product Type / Variant</label>
                                                                            <input type="text" class="form-control text-start" id="product_type-{{ $product->product_type }}" name="product_type" value="{{ $product->product_type }}" required>
                                                                        </div>
                                                                         <div class="mb-3">
                                                                            <label for="category_id-{{ $product->category_id }}" class="form-label">Product Category </label>
                                                                             <select class="form-select" name="category_id" id="category_id-{{ $product->category_id }}" required>
                                                                                <option value="" disabled {{ is_null($product->category_id) ? 'selected' : '' }}>-Select-</option>
                                                                                @foreach($categories as $category)
                                                                                    <!-- <option value="{{ $category->category_id }}">{{ $category->category_name }}</option> -->
                                                                                    <option value="{{ $category->category_id }}" {{ $product->category_id == $category->category_id ? 'selected' : '' }}>
                                                                                    {{ $category->category_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                         <div class="row">
                                                                            <div class="col-md-6 mb-3">
                                                                                <!-- <label for="expirable" class="form-label">Expirable</label> -->
                                                                                  <label for="expirable-{{ $product->expirable }}" class="form-label">Expirable</label>
                                                                                <select name="expirable" id="expirable-{{ $product->expirable }}" class="form-control" required>
                                                                                    <option value="YES" {{ $product->expirable === 'YES' ? 'selected' : '' }}>YES</option>
                                                                                    <option value="NO" {{ $product->expirable === 'NO' ? 'selected' : '' }}>NO</option>
                                                                                </select>
                                                                                <!-- <input type="text" step="0.01" class="form-control" id="expirable" name="expirable" required min="0" placeholder="0.00"> -->
                                                                            </div>
                                                                            <div class="col-md-6 mb-3">
                                                                                <label for="stocked-{{ $product->stockable }}" class="form-label">Stocked Product</label>
                                                                                <select name="stockable" id="stocked-{{ $product->stockable }}" class="form-control" required>
                                                                                    <option value="YES" {{ $product->stockable === 'YES' ? 'selected' : '' }}>YES</option>
                                                                                    <option value="NO" {{ $product->stockable === 'NO' ? 'selected' : '' }}>NO</option>
                            
                                                                                </select>
                                                                                <!-- <input type="number" step="0.01" class="form-control" id="stockable" name="stockable" required min="0" placeholder="0.00"> -->
                                                                            </div>
                                                                        </div>
                                                                         <div class="mb-3">
                                                                        <label for="status-{{ $product->status }}" class="form-label">Status</label>
                                                                            <select class="form-control" id="status-{{ $product->status }}" name="status">
                                                                                <option value="Active" {{ $product->status === 'Active' ? 'selected' : '' }}>ACTIVE</option>
                                                                                <option value="Inactive" {{ $product->status === 'Inactive' ? 'selected' : '' }}>INACTIVE</option>
                                                                            </select>
                                                                    </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                                          @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                        @endif
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <form id="delete-product-form-{{ $product->product_id }}" action="{{ route('products.destroy', $product->product_id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    No products found. Click "Add Product" to create one.
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

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required placeholder="e.g. iPhone 15 Pro" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="product_type" class="form-label">Product Type / Variant</label>
                            <input type="text" class="form-control" id="product_type" name="product_type" placeholder="e.g. 256GB, Titanium Blue">
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="" disabled selected>-Select-</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cost_price" class="form-label">Cost Price (GHs)</label>
                                <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price" required min="0" placeholder="0.00">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selling_price" class="form-label">Selling Price (GHs)</label>
                                <input type="number" step="0.01" class="form-control" id="selling_price" name="selling_price" required min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expirable" class="form-label">Expirable</label>
                                <select name="expirable" id="expirable" class="form-control" required>
                                    <option value="NO">NO</option>
                                    <option value="YES">YES</option>
                                </select>
                                <!-- <input type="text" step="0.01" class="form-control" id="expirable" name="expirable" required min="0" placeholder="0.00"> -->
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stockable" class="form-label">Stocked Product</label>
                                <select name="stockable" id="stockable" class="form-control" required>
                                    <option value="NO">NO</option>
                                    <option value="YES">YES</option>
                                </select>
                                <!-- <input type="number" step="0.01" class="form-control" id="stockable" name="stockable" required min="0" placeholder="0.00"> -->
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Initial Stock Quantity</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required min="0" placeholder="0" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                         @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' )
                        <button type="submit" class="btn btn-primary">Save Product</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- End of Main Content -->
    <!-- ============================================================== -->
</x-app-layout>
