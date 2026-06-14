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
                    <div data-table data-table-rows-per-page="5" class="card card-h-100">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Product Prices <span class="text-muted fs-base fw-normal">({{ $products->count() }} Products)</span></h4>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#addProductPriceModal">
                                    <i class="fa fa-plus me-1"></i> Add Product Price
                                </button>
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
                                            <th data-table-sort>Store Name</th>
                                            <th data-table-sort>Cost Price</th>
                                            <th data-table-sort>Selling Price</th>
                                            <th data-table-sort>Date Added</th>
                                            <th data-table-sort>Added by</th>
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
                                                     <span class="text-muted fs-xs">{{ $product->category ? $product->category->category_name : 'N/A' }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $product->category ? $product->category->category_name : 'N/A' }}</td>
                                                <td>{{ $product->store ? $product->store->store_name : 'N/A'}}</td>
                                                <td>
                                                    @if($product->price)
                                                        GHs {{ number_format($product->price->unit_cost, 2) }}
                                                    @else
                                                        <span class="badge bg-soft-warning text-warning">No Price</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($product->price)
                                                        GHs {{ number_format($product->price->unit_price, 2) }}
                                                    @else
                                                        <span class="badge bg-soft-warning text-warning">No Price</span>
                                                    @endif
                                                </td>
                                                <td>{{ $product->added_date ? \Carbon\Carbon::parse($product->added_date)->format('d M Y, h:i A') : 'N/A' }}</td>
                                                <td>{{ $product->price ? strtoupper($product->price->added_by) : 'N/A' }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if($product->price)
                                                                <li>
                                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProductPriceModal-{{ $product->product_id }}">
                                                                        Edit Price
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this product price?')) { document.getElementById('delete-price-form-{{ $product->product_id }}').submit(); }">
                                                                        Delete Price
                                                                    </a>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <a class="dropdown-item text-success" href="#" data-bs-toggle="modal" data-bs-target="#addProductPriceModal-{{ $product->product_id }}">
                                                                        Add Price
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>

                                                    @if($product->price)
                                                        <form id="delete-price-form-{{ $product->product_id }}" action="{{ route('product-prices.destroy', $product->product_id) }}" method="POST" style="display: none;">
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

    <!-- Add Product Price Modal (Global) -->
    <div class="modal fade" id="addProductPriceModal" tabindex="-1" aria-labelledby="addProductPriceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content text-start" style="white-space: normal;">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductPriceModalLabel">Add New Product Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('product-prices.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Select Product</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                <option value="" disabled selected>Select a Product...</option>
                                @foreach($products as $product)
                                    @if(!$product->price)
                                        <option value="{{ $product->product_id }}">{{ strtoupper($product->product_name) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="unit_cost" class="form-label">Cost Price (GHs)</label>
                                <input type="number" step="0.01" class="form-control" id="unit_cost" name="unit_cost" required min="0" placeholder="0.00">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="unit_price" class="form-label">Selling Price (GHs)</label>
                                <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price" required min="0" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Price</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Specific Modals -->
    @foreach ($products as $product)
        @if($product->price)
            <!-- Edit Product Price Modal -->
            <div class="modal fade" id="editProductPriceModal-{{ $product->product_id }}" tabindex="-1" aria-labelledby="editProductPriceModalLabel-{{ $product->product_id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-start" style="white-space: normal;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductPriceModalLabel-{{ $product->product_id }}">Edit Price: {{ strtoupper($product->product_name) }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('product-prices.update', $product->product_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" value="{{ strtoupper($product->product_name) }}" readonly disabled>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="unit_cost-{{ $product->product_id }}" class="form-label">Cost Price (GHs)</label>
                                        <input type="number" step="0.01" class="form-control" id="unit_cost-{{ $product->product_id }}" name="unit_cost" value="{{ $product->price->unit_cost }}" required min="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="unit_price-{{ $product->product_id }}" class="form-label">Selling Price (GHs)</label>
                                        <input type="number" step="0.01" class="form-control" id="unit_price-{{ $product->product_id }}" name="unit_price" value="{{ $product->price->unit_price }}" required min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Price</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Add Product Price Modal for Specific Product -->
            <div class="modal fade" id="addProductPriceModal-{{ $product->product_id }}" tabindex="-1" aria-labelledby="addProductPriceModalLabel-{{ $product->product_id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-start" style="white-space: normal;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductPriceModalLabel-{{ $product->product_id }}">Add Price: {{ strtoupper($product->product_name) }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('product-prices.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" value="{{ strtoupper($product->product_name) }}" readonly disabled>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="unit_cost_new-{{ $product->product_id }}" class="form-label">Cost Price (GHs)</label>
                                        <input type="number" step="0.01" class="form-control" id="unit_cost_new-{{ $product->product_id }}" name="unit_cost" required min="0" placeholder="0.00">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="unit_price_new-{{ $product->product_id }}" class="form-label">Selling Price (GHs)</label>
                                        <input type="number" step="0.01" class="form-control" id="unit_price_new-{{ $product->product_id }}" name="unit_price" required min="0" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Price</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- ============================================================== -->
    <!-- End of Main Content -->
    <!-- ============================================================== -->
</x-app-layout>
