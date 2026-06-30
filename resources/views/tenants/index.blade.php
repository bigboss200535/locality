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
                            <h4 class="card-title">Tenants <span class="text-muted fs-base fw-normal">({{ $tenants->count() }} Tenants)</span></h4>
                            <div>
                                @if(auth()->user()->role_id === '1001')
                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fa fa-plus me-1"></i> Add Tenant
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
                                        @forelse ($tenants as $tenant)
                                            <tr>
                                                <td>#{{ substr($tenant->tenant_id, 0, 8) }}</td>
                                                  @if(auth()->user()->role_id === '1001')
                                                <td>
                                                    <h5 class="m-0 fs-base">{{ strtoupper($tenant->tenant_name) }}</h5>
                                                    <span class="text-muted fs-xs">Type of Business: {{ $tenant->type_of_business ?? 'N/A' }}</span>
                                                </td>
                                                 @endif  
                                                 <td>{{ $tenant->added_by ?? 'N/A' }}</td>
                                                <td>{{ $tenant->added_date ? \Carbon\Carbon::parse($tenant->added_date)->format('d M Y, h:i A') : 'N/A' }}</td>
                                                <td>
                                                    @if($tenant->status == 'Active')
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
                                                            <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editStoreModal-{{ $tenant->tenant_id }}">Edit</a></li>
                                                            <li>
                                                                <form action="{{ route('tenants.destroy', $tenant->tenant_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tenant? This is a soft-delete action.');" class="d-inline">
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
                                            <div class="modal fade" id="editStoreModal-{{ $tenant->tenant_id }}" tabindex="-1" aria-labelledby="editStoreModalLabel-{{ $tenant->tenant_id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editStoreModalLabel-{{ $tenant->tenant_id }}">Edit Tenant: {{ $tenant->tenant_name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('tenants.update', $tenant->tenant_id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="mb-3">
                                                                        <label for="tenant_name-{{ $tenant->tenant_id }}" class="form-label">Tenant Name</label>
                                                                        <input type="text" class="form-control" id="tenant_name-{{ $tenant->tenant_id }}" name="tenant_name" value="{{ $tenant->tenant_name }}" required>
                                                                    </div>
                                                    
                                                                </div>
                                                                <div class="mb-3">
                                                                        <label for="type_of_business-{{ $tenant->type_of_business }}" class="form-label">Business Type</label>
                                                                        <input type="text" class="form-control" id="type_of_business-{{ $tenant->type_of_business }}" name="type_of_business" value="{{ $tenant->type_of_business }}" required>
                                                                    </div>
                                                               
                                                                <div class="mb-3">
                                                                    <label for="telephone-{{ $tenant->user_id }}" class="form-label">Telephone</label>
                                                                    <input type="text" class="form-control" id="telephone-{{ $tenant->user_id }}" name="telephone" value="{{ $tenant->telephone }}">
                                                                </div>
                                                                
                                                                
        
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                              @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                                                                <button type="submit" class="btn btn-primary">Update Tenant</button>
                                                             @endif
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4 text-muted">
                                                    No Tenant found. Click "Add Tenant" to create one.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div data-table-pagination-info="Tenants"></div>
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
                    <h5 class="modal-title" id="addUserModalLabel">Add New Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tenants.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label for="tenant_name" class="form-label">Tenant Name</label>
                                <input type="text" class="form-control" id="tenant_name" name="tenant_name" required placeholder="e.g. Grocessary Store">
                            </div>
                        <div class="mb-3">
                            <label for="type_of_business" class="form-label">Business Type</label>
                            <input type="text" class="form-control" id="type_of_business" name="type_of_business" required placeholder="e.g. Pharmacy">
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone" placeholder="e.g. 0240000000">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="e.g. email@domain.com">
                        </div>
                         <!-- <div class="mb-3">
                            <label for="email" class="form-label">Email</label> 
                            <input type="email" class="form-control" id="email" name="email" placeholder="e.g. email@domain.com">
                        </div> -->
                    </div>
                     @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Tenant</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
