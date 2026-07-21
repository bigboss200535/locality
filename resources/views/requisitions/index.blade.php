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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-exclamation-circle me-1"></i> {{ session('error') }}
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
                            <h4 class="card-title">Item Requisitions <span class="text-muted fs-base fw-normal">({{ $requisitions->count() }} Requisitions)</span></h4>
                            <div>
                                @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#addRequisitionModal">
                                    <i class="fa fa-plus me-1"></i> Add Requisition
                                </button>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover w-100 mb-0">
                                    <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                                        <tr class="text-uppercase table-nowrap fs-xxs">
                                            <th>Requisition No</th>
                                            <th>Product</th>
                                            <th>Order Store</th>
                                            <th>Issue Store</th>
                                            <th>Qty</th>
                                            <th>Total Value</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        @forelse ($requisitions as $req)
                                            <tr>
                                                <td>
                                                    <h5 class="m-0 fs-base">{{ $req->requisition_no }}</h5>
                                                    <span class="text-muted fs-xs">Date: {{ $req->requisition_date ? \Carbon\Carbon::parse($req->requisition_date)->format('d M Y') : 'N/A' }}</span>
                                                </td>
                                                <td>{{ $req->product ? $req->product->product_name : 'N/A' }}</td>
                                                <td>{{ $req->orderStore ? $req->orderStore->store_name : 'N/A' }}</td>
                                                <td>{{ $req->issueStore ? $req->issueStore->store_name : 'N/A' }}</td>
                                                <td>{{ $req->quantity }}</td>
                                                <td>GHs {{ number_format($req->total_value ?? 0, 2) }}</td>
                                                <td>
                                                    @if($req->requsition_status === 'approved')
                                                        <span class="badge bg-success-subtle text-success">APPROVED</span>
                                                    @elseif($req->requsition_status === 'rejected')
                                                        <span class="badge bg-danger-subtle text-danger">REJECTED</span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning">REQUESTED</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewRequisitionModal-{{ $req->requisition_id }}">
                                                                    View Details
                                                                </button>
                                                            </li>
                                                            @if($req->requsition_status !== 'approved' && $req->requsition_status !== 'rejected')
                                                                <li>
                                                                    <form action="{{ route('requisitions.approve', $req->requisition_id) }}" method="POST" onsubmit="return confirm('Approve this requisition? This will transfer stock between stores.');" class="d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" class="dropdown-item text-success">Approve</button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                            <li>
                                                                <form action="{{ route('requisitions.destroy', $req->requisition_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this requisition?');" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                                </form>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- View Requisition Modal -->
                                            <div class="modal fade" id="viewRequisitionModal-{{ $req->requisition_id }}" tabindex="-1" aria-labelledby="viewRequisitionModalLabel-{{ $req->requisition_id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="viewRequisitionModalLabel-{{ $req->requisition_id }}">Requisition: {{ $req->requisition_no }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <p class="mb-1"><strong>Product:</strong> {{ $req->product ? $req->product->product_name : 'N/A' }}</p>
                                                                    <p class="mb-1"><strong>Quantity:</strong> {{ $req->quantity }}</p>
                                                                    <p class="mb-1"><strong>Unit Price:</strong> GHs {{ number_format($req->unit_price ?? 0, 2) }}</p>
                                                                </div>
                                                                <div class="col-md-6 text-md-end">
                                                                    <p class="mb-1"><strong>Order Store:</strong> {{ $req->orderStore ? $req->orderStore->store_name : 'N/A' }}</p>
                                                                    <p class="mb-1"><strong>Issue Store:</strong> {{ $req->issueStore ? $req->issueStore->store_name : 'N/A' }}</p>
                                                                    <p class="mb-1"><strong>Status:</strong> {{ ucfirst($req->requsition_status ?? 'requested') }}</p>
                                                                </div>
                                                            </div>
                                                            @if($req->comments)
                                                                <div class="mb-3">
                                                                    <strong>Comments:</strong>
                                                                    <p class="mb-0">{{ $req->comments }}</p>
                                                                </div>
                                                            @endif
                                                            <div class="d-flex justify-content-between border-top pt-3">
                                                                <span>Total Value</span>
                                                                <strong class="text-primary">GHs {{ number_format($req->total_value ?? 0, 2) }}</strong>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    No requisitions found. Click "Add Requisition" to create one.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div data-table-pagination-info="requisitions"></div>
                                <div data-table-pagination></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Requisition Modal -->
        <div class="modal fade" id="addRequisitionModal" tabindex="-1" aria-labelledby="addRequisitionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRequisitionModalLabel">Add New Requisition</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('requisitions.store') }}" method="POST" x-data="requisitionApp()" @submit.prevent="submitForm($event)">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="issue_store_id" class="form-label">Issue Store <span class="text-muted">(must be in same tenant)</span></label>
                                    <select class="form-select" id="issue_store_id" name="issue_store_id" x-model="issueStoreId" required>
                                        <option value="" disabled selected>-Select Issue Store-</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->store_id }}">{{ $store->store_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="requisition_date" class="form-label">Requisition Date</label>
                                    <input type="date" class="form-control" id="requisition_date" name="requisition_date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="comments" class="form-label">Comments</label>
                                <textarea class="form-control" id="comments" name="comments" rows="1" placeholder="Optional comments"></textarea>
                            </div>

                            <h6 class="fw-bold mb-2">Requisition Items</h6>
                            @if($products->isEmpty())
                                <div class="alert alert-warning py-2 mb-2">
                                    <i class="fa fa-circle-exclamation me-1"></i> No active products found. Please add products first.
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Product</th>
                                            <th style="width: 120px;">Qty</th>
                                            <th style="width: 150px;">Unit Price (GHs)</th>
                                            <th style="width: 120px;">Total</th>
                                            <th style="width: 60px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr>
                                                <td>
                                                    <select class="form-select form-select-sm" x-model="item.product_id" required>
                                                        <option value="" disabled>-Select Product-</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->product_id }}">{{ strtoupper($product->product_name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" class="form-control form-control-sm" x-model.number="item.quantity" min="0.01" required>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" class="form-control form-control-sm" x-model.number="item.unit_price" min="0" required>
                                                </td>
                                                <td class="text-end align-middle">
                                                    GHs <span x-text="(parseFloat(item.quantity || 0) * parseFloat(item.unit_price || 0)).toFixed(2)"></span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="btn btn-sm btn-link text-danger p-0" @click="removeItem(index)">
                                                        <i class="fa fa-trash-can"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" @click="addItem()">
                                <i class="fa fa-plus me-1"></i> Add Product
                            </button>

                            <template x-for="(item, index) in items" :key="'hidden-' + index">
                                <div>
                                    <input type="hidden" :name="'products[' + index + '][product_id]'" :value="item.product_id">
                                    <input type="hidden" :name="'products[' + index + '][quantity]'" :value="item.quantity">
                                    <input type="hidden" :name="'products[' + index + '][unit_price]'" :value="item.unit_price">
                                </div>
                            </template>

                            @if($stores->isEmpty())
                                <div class="alert alert-warning py-2 mb-0 mt-3">
                                    <i class="fa fa-circle-exclamation me-1"></i> No other stores found in your tenant. Requisitions require an issue store with stock.
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" {{ $stores->isEmpty() || $products->isEmpty() ? 'disabled' : '' }}>Submit Requisition</button>
                        </div>
                    </form>
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

    <script>
        function requisitionApp() {
            return {
                issueStoreId: '',
                items: [{ product_id: '', quantity: 1, unit_price: 0 }],
                addItem() {
                    this.items.push({ product_id: '', quantity: 1, unit_price: 0 });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                submitForm(event) {
                    if (this.items.length === 0) {
                        alert('Please add at least one product item.');
                        return;
                    }
                    for (let item of this.items) {
                        if (!item.product_id || parseFloat(item.quantity) <= 0) {
                            alert('Please select a product and enter a valid quantity for all items.');
                            return;
                        }
                    }
                    event.target.submit();
                }
            };
        }
    </script>
</x-app-layout>
