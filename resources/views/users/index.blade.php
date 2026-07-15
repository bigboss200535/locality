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
                            <h4 class="card-title">User Accounts <span class="text-muted fs-base fw-normal">({{ $users->count() }} Users)</span></h4>
                            <div>
                                @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fa fa-plus me-1"></i> Add User
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
                                            <th data-table-sort>Full Name</th>
                                            <th data-table-sort>Email / Contact</th>
                                            <th data-table-sort>Role</th>
                                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                            <th data-table-sort>Store Name</th>
                                            @endif
                                             @if(auth()->user()->role_id === '1001')
                                            <th data-table-sort>Tenant Name</th>
                                            @endif
                                            <th data-table-sort>Block Status</th>
                                            <!-- <th data-table-sort>Date Added</th> -->
                                            <th data-table-sort>Status</th>
                                            @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003' )
                                            <th data-table-sort>Action</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        @forelse ($users as $userItem)
                                            <tr>
                                                <td>#{{ substr($userItem->user_id, 0, 8) }}</td>
                                                <td>
                                                    <h5 class="m-0 fs-base">{{ strtoupper($userItem->firstname) }} {{ strtoupper($userItem->othername) }}</h5>
                                                    <!-- <span class="text-muted fs-xs">Username: {{ $userItem->email }}</span> -->
                                                </td>
                                                <td>
                                                    <span class="fs-base fw-semibold">{{ $userItem->email }}</span>
                                                    @if($userItem->telephone)
                                                        <br><span class="text-muted fs-xs"><i class="fa fa-phone me-1"></i>{{ $userItem->telephone }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary-subtle text-primary">{{ $userItem->role ? strtoupper($userItem->role->role_name) : 'N/A' }}</span>
                                                </td>
                                                 @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003' )
                                                 <td>{{ $userItem->store ? $userItem->store->store_name : 'N/A' }}</td>
                                                 @endif
                                                 @if(auth()->user()->role_id === '1001')
                                                <td>{{ $userItem->tenant ? $userItem->tenant->tenant_name : 'N/A' }}</td>
                                                 @endif  
                                                <td>
                                                    @if($userItem->blocked === 'Yes')
                                                        <span class="badge bg-danger text-white">BLOCKED</span>
                                                    @else
                                                        <span class="badge bg-success-subtle text-success">ACTIVE (UNBLOCKED)</span>
                                                    @endif
                                                </td>
                                                <!-- <td>{{ $userItem->added_date ? \Carbon\Carbon::parse($userItem->added_date)->format('d M Y, h:i A') : 'N/A' }}</td> -->
                                                <td>
                                                    @if($userItem->status == 'Active')
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
                                                            <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $userItem->user_id }}">Edit</a></li>
                                                            @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002')
                                                            <li>
                                                                <form action="{{ route('users.destroy', $userItem->user_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user account? This is a soft-delete action.');" class="d-inline">
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

                                            <!-- Edit User Modal -->
                                            <div class="modal fade" id="editUserModal-{{ $userItem->user_id }}" tabindex="-1" aria-labelledby="editUserModalLabel-{{ $userItem->user_id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editUserModalLabel-{{ $userItem->user_id }}">Edit User: {{ strtoupper($userItem->firstname) .' '. strtoupper($userItem->othername) }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('users.update', $userItem->user_id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="firstname-{{ $userItem->user_id }}" class="form-label">First Name</label>
                                                                        <input type="text" class="form-control" id="firstname-{{ $userItem->user_id }}" name="firstname" value="{{ $userItem->firstname }}" required>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="othername-{{ $userItem->user_id }}" class="form-label">Other Name</label>
                                                                        <input type="text" class="form-control" id="othername-{{ $userItem->user_id }}" name="othername" value="{{ $userItem->othername }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="email-{{ $userItem->user_id }}" class="form-label">Email Address</label>
                                                                    <input type="email" class="form-control" id="email-{{ $userItem->user_id }}" name="email" value="{{ $userItem->email }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="telephone-{{ $userItem->user_id }}" class="form-label">Telephone</label>
                                                                    <input type="text" class="form-control" id="telephone-{{ $userItem->user_id }}" name="telephone" value="{{ $userItem->telephone }}">
                                                                </div>
                                                                 @if(auth()->user()->role_id === '1001')
                                                                <div class="mb-3">
                                                                    <label for="tenant_id-{{ $userItem->user_id }}" class="form-label">Tenant Name</label>
                                                                    <select class="form-select" id="tenant_id-{{ $userItem->user_id }}" name="tenant_id" required>
                                                                        @foreach($tenants as $tenant)
                                                                            <option value="{{ $tenant->tenant_id }}" {{ $userItem->tenant_id == $tenant->tenant_id ? 'selected' : '' }}>{{ $tenant->tenant_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @endif
                                                                 @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                                <div class="mb-3">
                                                                    <label for="store_id-{{ $userItem->user_id }}" class="form-label">Store Name</label>
                                                                    <select class="form-select" id="store_id-{{ $userItem->user_id }}" name="store_id" required>
                                                                        @foreach($stores as $store)
                                                                            <option value="{{ $store->store_id }}" {{ $userItem->store_id == $store->store_id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @endif
                                                                @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                                <div class="mb-3">
                                                                    <label for="role_id-{{ $userItem->user_id }}" class="form-label">User Role</label>
                                                                    <select class="form-select" id="role_id-{{ $userItem->user_id }}" name="role_id" required>
                                                                        @foreach($roles as $role)
                                                                            <option value="{{ $role->role_id }}" {{ $userItem->role_id == $role->role_id ? 'selected' : '' }}>{{ strtoupper($role->role_name) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @endif
                                                                <div class="mb-3">
                                                                    <label for="blocked-{{ $userItem->user_id }}" class="form-label">Block State</label>
                                                                    <select class="form-select" id="blocked-{{ $userItem->user_id }}" name="blocked" required>
                                                                        <option value="No" {{ $userItem->blocked === 'No' ? 'selected' : '' }}>Active (Unblocked)</option>
                                                                        <option value="Yes" {{ $userItem->blocked === 'Yes' ? 'selected' : '' }}>Blocked</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="password-{{ $userItem->user_id }}" class="form-label">New Password (<b style="color:red">leave blank to keep current</b>)</label>
                                                                    <input type="password" class="form-control" id="password-{{ $userItem->user_id }}" name="password" placeholder="Min 6 characters">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                              @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                                <button type="submit" class="btn btn-primary">Update User</button>
                                                             @endif
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4 text-muted">
                                                    No users found. Click "Add User" to create one.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div data-table-pagination-info="users"></div>
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
                    <h5 class="modal-title" id="addUserModalLabel">Add New User Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" required placeholder="e.g. John">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="othername" class="form-label">Other Name</label>
                                <input type="text" class="form-control" id="othername" name="othername" required placeholder="e.g. Doe">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="e.g. john.doe@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone" placeholder="e.g. +233240000000">
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
                         @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                        <div class="mb-3">
                            <label for="store_id" class="form-label">Store Name</label>
                            <select class="form-select" id="store_id" name="store_id" required>
                                <option value="" disabled selected>-Select Store-</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->store_id }}">{{ $store->store_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="mb-3">
                            <label for="role_id" class="form-label">User Role</label>
                            <select class="form-select" id="role_id" name="role_id" required>
                                <option value="" disabled selected>-Select Role-</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->role_id }}">{{ strtoupper($role->role_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="blocked" class="form-label">Block State</label>
                            <select class="form-select" id="blocked" name="blocked" required>
                                <option value="No" selected>Active (Unblocked)</option>
                                <option value="Yes">Blocked</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Min 6 characters">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
