<x-app-layout>
<!-- Begin page -->
    <!-- Topbar End -->
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
             @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('error') }}
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
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Product Categories <span class="text-muted fs-base fw-normal">({{ $categories->count() }} Categories)</span></h4>
                            <div>
                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                    <i class="fa fa-plus me-1"></i> Add Category
                                </button>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover w-100 mb-0">
                                    <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                                        <tr class="text-uppercase table-nowrap fs-xxs">
                                            <th data-table-sort>#ID</th>
                                            <th data-table-sort>Category Name</th>
                                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                <th data-table-sort>Store Name</th>
                                             @endif
                                             @if(auth()->user()->role_id === '1001')
                                                <th data-table-sort>Tenant Name</th>
                                            @endif
                                           
                                            <!-- <th data-table-sort>Added By</th> -->
                                            <th data-table-sort>Added Date</th>
                                            <th data-table-sort>Status</th>
                                            @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                            <th data-table-sort>Action</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        @forelse ($categories as $category)
                                            <tr>
                                                <td>#{{ substr($category->category_id, 0, 8) }}</td>
                                                <td>
                                                    <h5 class="m-0 fs-base">{{ strtoupper($category->category_name) }}</h5>
                                                </td>
                                                 @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                <td>
                                                    {{ strtoupper($category->store->store_name) ?? 'N/A'}}
                                                </td>
                                                 @endif
                                                 @if(auth()->user()->role_id === '1001')
                                                 <td>
                                                    {{ strtoupper($category->tenant->tenant_name) ?? 'N/A'}}
                                                </td>
                                                @endif
                                               
                                                <!-- <td>{{ strtoupper($category->added_by) ?? 'N/A' }}</td> -->
                                                <td>{{ $category->added_date ? \Carbon\Carbon::parse($category->added_date)->format('d M Y, h:i A') : 'N/A' }}</td>
                                                 <td>
                                                    @if($category->status === 'Active')
                                                        <span class="badge bg-success-subtle text-success">ACTIVE</span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-danger">INACTIVE</span>
                                                    @endif
                                                </td>
                                                  @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                    <td>
                                                        <div class="">
                                                            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                  @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                                <li>
                                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal-{{ $category->category_id }}">
                                                                        Edit
                                                                    </a>
                                                                </li>
                                                                @endif
                                                                  @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                                <li>
                                                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('toggle-form-{{ $category->category_id }}').submit();">
                                                                        {{ $category->status === 'Active' ? 'Disable' : 'Enable' }}
                                                                    </a>
                                                                </li>
                                                                @endif
                                                                  @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this category?')) { document.getElementById('delete-form-{{ $category->category_id }}').submit(); }">
                                                                        Delete
                                                                    </a>
                                                                </li>
                                                                @endif
                                                            </ul>
                                                        </div>

                                                    <!-- Edit Category Modal -->
                                                    <div class="modal fade" id="editCategoryModal-{{ $category->category_id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel-{{ $category->category_id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content text-start text-wrap">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editCategoryModalLabel-{{ $category->category_id }}">Edit Category</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('product-categories.update', $category->category_id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label for="category_name-{{ $category->category_id }}" class="form-label">Category Name</label>
                                                                            <input type="text" class="form-control text-start" id="category_name-{{ $category->category_id }}" name="category_name" value="{{ $category->category_name }}" required>
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

                                                    <!-- Invisible form for Toggle Status -->
                                                    <form id="toggle-form-{{ $category->category_id }}" action="{{ route('product-categories.toggle-status', $category->category_id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('PATCH')
                                                    </form>

                                                    <!-- Invisible form for Delete -->
                                                    <form id="delete-form-{{ $category->category_id }}" action="{{ route('product-categories.destroy', $category->category_id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">
                                                    No categories found. Click "Add Category" to create one.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div data-table-pagination-info="categories"></div>
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

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('product-categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required placeholder="e.g. Electronics" autocomplete="off">
                        </div>
                        @if(auth()->user()->role_id === '1001')
                        <div class="mb-3">
                            <label for="tenant_name" class="form-label">Tenant Name</label>
                            <select class="form-control text-start" id="tenant_name" name="tenant_name" required>
                                <!-- <option value="" disabled selected>Select Tenant...</option> -->
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->tenant_id }}">{{ $tenant->tenant_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                         <div class="mb-3">
                            <label for="store_name" class="form-label">Store Name</label>
                            <select class="form-control text-start" id="store_name" name="store_name" required>
                                <option value="" disabled selected>Select Store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->store_id }}">{{ $store->store_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                         @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                        <button type="submit" class="btn btn-primary">Save Category</button>
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
