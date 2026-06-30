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
                            <h4 class="card-title">Stores <span class="text-muted fs-base fw-normal">({{ $stores->count() }} Stores)</span></h4>
                            <div>
                                @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fa fa-plus me-1"></i> Add Store
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
                                            <th data-table-sort>Store Name</th>
                                            @endif
                                             @if(auth()->user()->role_id === '1001')
                                            <th data-table-sort>Tenant Name</th>
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
                                        @forelse ($stores as $storeItem)
                                            <tr>
                                                <td>#{{ substr($storeItem->store_id, 0, 8) }}</td>
                                                <td>
                                                    <h5 class="m-0 fs-base">{{ strtoupper($storeItem->store_name) }}</h5>
                                                    <!-- <span class="text-muted fs-xs">Tenant: {{ $storeItem->tenant->tenant_name ?? 'N/A' }}</span> -->
                                                </td>
                                                
                                                 @if(auth()->user()->role_id === '1001')
                                                <td>{{ $storeItem->tenant ? $storeItem->tenant->tenant_name : 'N/A' }}</td>
                                                 @endif  
                                                 <td>{{ $storeItem->added_by ?? 'N/A' }}</td>
                                                <td>{{ $storeItem->added_date ? \Carbon\Carbon::parse($storeItem->added_date)->format('d M Y, h:i A') : 'N/A' }}</td>
                                                <td>
                                                    @if($storeItem->status == 'Active')
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
                                                            <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editStoreModal-{{ $storeItem->store_id }}">Edit</a></li>
                                                            <li>
                                                                <form action="{{ route('stores.destroy', $storeItem->store_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this store? This is a soft-delete action.');" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                @endif
                                            </tr>

                                            <!-- Edit Store Modal -->
                                            <div class="modal fade" id="editStoreModal-{{ $storeItem->store_id }}" tabindex="-1" aria-labelledby="editStoreModalLabel-{{ $storeItem->store_id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editStoreModalLabel-{{ $storeItem->store_id }}">Edit Store: {{ $storeItem->store_name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('stores.update', $storeItem->store_id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="mb-3">
                                                                        <label for="store_name-{{ $storeItem->store_id }}" class="form-label">Store Name</label>
                                                                        <input type="text" class="form-control" id="store_name-{{ $storeItem->store_id }}" name="store_name" value="{{ $storeItem->store_name }}" required>
                                                                    </div>
                                                    
                                                                </div>
                                                                <div class="mb-3">
                                                                        <label for="type_of_business-{{ $storeItem->type_of_business }}" class="form-label">Business Type</label>
                                                                        <input type="text" class="form-control" id="type_of_business-{{ $storeItem->type_of_business }}" name="type_of_business" value="{{ $storeItem->type_of_business }}" required>
                                                                    </div>
                                                                <!-- <div class="mb-3">
                                                                    <label for="email-{{ $storeItem->user_id }}" class="form-label">Email Address</label>
                                                                    <input type="text" class="form-control" id="email-{{ $storeItem->user_id }}" name="email" value="{{ $storeItem->email }}" required>
                                                                </div> -->
                                                                <div class="mb-3">
                                                                    <label for="telephone-{{ $storeItem->user_id }}" class="form-label">Telephone</label>
                                                                    <input type="text" class="form-control" id="telephone-{{ $storeItem->user_id }}" name="telephone" value="{{ $storeItem->telephone }}">
                                                                </div>
                                                                 @if(auth()->user()->role_id === '1001')
                                                                <!-- <div class="mb-3">
                                                                    <label for="tenant_id-{{ $storeItem->user_id }}" class="form-label">Tenant Name</label>
                                                                    <select class="form-select" id="tenant_id-{{ $storeItem->user_id }}" name="tenant_id" required>
                                                                        @foreach($tenants as $tenant)
                                                                            <option value="{{ $tenant->tenant_id }}" {{ $storeItem->tenant_id == $tenant->tenant_id ? 'selected' : '' }}>{{ $tenant->tenant_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div> -->
                                                                @endif
                                                                
        
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                              @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                                                                <button type="submit" class="btn btn-primary">Update Store</button>
                                                             @endif
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4 text-muted">
                                                    No Stores found. Click "Add Store" to create one.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div data-table-pagination-info="Stores"></div>
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
                    <h5 class="modal-title" id="addUserModalLabel">Add New Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('stores.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label for="store_name" class="form-label">Store Name</label>
                                <input type="text" class="form-control" id="store_name" name="store_name" required placeholder="e.g. Grocessary Store">
                            </div>
                        <div class="mb-3">
                            <label for="type_of_business" class="form-label">Business Type</label>
                            <input type="text" class="form-control" id="type_of_business" name="type_of_business" required placeholder="e.g. Pharmacy">
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone" placeholder="e.g. 0240000000">
                        </div>
                         @if(auth()->user()->role_id === '1001')
                        <div class="mb-3">
                            <label for="tenant_id" class="form-label">Tenant Name</label>
                            <select class="form-select" id="tenant_id" name="tenant_id" required>
                                <option value="" disabled selected>-Select Tenant-</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->tenant_id }}">{{ $tenant->tenant_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                     @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Store</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
