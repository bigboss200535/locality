<x-app-layout>
    <!-- Start Main Content -->
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
                    <div data-table data-table-rows-per-page="10" class="card card-h-100">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Suppliers <span class="text-muted fs-base fw-normal">({{ $suppliers->count() }} suppliers)</span></h4>
                            <div>
                                @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fa fa-plus me-1"></i> Add Supplier
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
                                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                            <th data-table-sort>Supplier Name</th>
                                            @endif
                                             @if(auth()->user()->role_id === '1001')
                                            <th data-table-sort>Tenant Name</th>
                                            @endif
                                            @if(auth()->user()->role_id === '1001')
                                            <th data-table-sort>Store Name</th>
                                            @endif
                                            <th data-table-sort>Added By</th>
                                            <th data-table-sort>Date Added</th>
                                            <th data-table-sort>Status</th>
                                            @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                                            <th data-table-sort>Action</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        @forelse ($suppliers as $supplier)
                                            <tr>
                                                <td>#{{ substr($supplier->supplier_id, 0, 8) }}</td>
                                                <td>
                                                    <h5 class="m-0 fs-base">{{ strtoupper($supplier->supplier_name) }}</h5>
                                                    <span class="text-muted fs-xs">Store: {{ $supplier->store->store_name ?? 'N/A' }}</span>
                                                </td>
                                                 @if(auth()->user()->role_id === '1001')
                                                <td>{{ $supplier->tenant ? $supplier->tenant->tenant_name : 'N/A' }}</td>
                                                 @endif  
                                                 @if(auth()->user()->role_id === '1001')
                                                <td>{{ $supplier->store ? $supplier->store->store_name : 'N/A' }}</td>
                                                 @endif  
                                                <td>{{ $supplier->added_by ?? 'N/A' }}</td>
                                                <td>{{ $supplier->added_date ? \Carbon\Carbon::parse($supplier->added_date)->format('d M Y, h:i A') : 'N/A' }}</td>
                                                <td>
                                                    @if($supplier->status == 'Active')
                                                        <span class="badge bg-success-subtle text-success">ACTIVE</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">INACTIVE</span>
                                                    @endif
                                                </td>
                                                @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                                                <td>
                                                    <div class="">
                                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                            <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editSupplierModal-{{ $supplier->supplier_id }}">Edit</a></li>
                                                            @endif
                                                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                            <li>
                                                                <form action="{{ route('suppliers.destroy', $supplier->supplier_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this supplier? This is a soft-delete action.');" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                                </form>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                                @endif
                                            </tr>

                                            <!-- Edit Supplier Modal -->
                                            <div class="modal fade" id="editSupplierModal-{{ $supplier->supplier_id }}" tabindex="-1" aria-labelledby="editSupplierModalLabel-{{ $supplier->supplier_id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editSupplierModalLabel-{{ $supplier->supplier_id }}">Edit Supplier: {{ strtoupper($supplier->supplier_name) }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('suppliers.update', $supplier->supplier_id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="mb-3">
                                                                        <label for="supplier_name-{{ $supplier->supplier_id }}" class="form-label">Supplier Name</label>
                                                                        <input type="text" class="form-control" id="supplier_name-{{ $supplier->supplier_id }}" name="supplier_name" value="{{ $supplier->supplier_name }}" required>
                                                                    </div>
                                                    
                                                                </div>
                                                                 <div class="mb-3">
                                                                        <label for="supplier_name-{{ $supplier->email }}" class="form-label">Email</label>
                                                                        <input type="email" class="form-control" id="supplier_name-{{ $supplier->email }}" name="email" value="{{ $supplier->email }}" placeholder="e.g. john@example.com">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="telephone" class="form-label">Telephone</label>
                                                                        <input type="text" class="form-control" id="telephone" name="telephone" value="{{ $supplier->telephone }}" placeholder="e.g. 0240000000">
                                                                   </div>
                                                                   <div class="mb-3">
                                                                        <label for="store_id" class="form-label">Store Name</label>
                                                                        <select class="form-control" id="store_id" name="store_id" value="{{ $supplier->store_id }}">
                                                                        <option value="" disabled {{ is_null($supplier->store_id) ? 'selected' : '' }}>Select Store</option>  
                                                                            @foreach($stores as $store)
                                                                                <option value="{{ $store->store_id }}" {{ $supplier->store_id === $store->store_id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                                                                            @endforeach
                                                                        </select>  
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="status-{{ $supplier->status }}" class="form-label">Status</label>
                                                                            <select class="form-control" id="status-{{ $supplier->status }}" name="status-{{ $supplier->status }}">
                                                                                <option value="Active" {{ $supplier->status === 'Active' ? 'selected' : '' }}>ACTIVE</option>
                                                                                <option value="Inactive" {{ $supplier->status === 'Inactive' ? 'selected' : '' }}>INACTIVE</option>
                                                                            </select>
                                                                        <!-- <input type="text" class="form-control" id="telephone-{{ $supplier->status }}" name="telephone" value="{{ $supplier->status }}"> -->
                                                                    </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                              @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                                                                <button type="submit" class="btn btn-primary">Update Supplier</button>
                                                             @endif
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4 text-muted">
                                                    No suppliers found. Click "Add Supplier" to create one.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div data-table-pagination-info="suppliers"></div>
                                <div data-table-pagination></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label for="supplier_name" class="form-label">Supplier Name</label>
                                <input type="text" class="form-control" id="supplier_name" name="supplier_name" required placeholder="e.g. Grocessary Store">
                            </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="e.g. john.doe@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone" placeholder="e.g. 0240000000">
                        </div>
                         @if(auth()->user()->role_id === '1001')
                         <div class="mb-3">
                            <label for="tenant" class="form-label">Tenant Name</label>
                            <select class="form-select" id="tenant" name="tenant">
                                <option value="" disabled selected>Select Tenant</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->tenant_id }}">{{ $tenant->tenant_name }}</option>
                                @endforeach
                                </select>
                        </div>
                        @endif
                         @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                         <div class="mb-3">
                            <label for="store" class="form-label">Store Name</label>
                            <select class="form-select" id="store" name="store">
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
                        <button type="submit" class="btn btn-primary">Save Supplier</button>
                          @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
