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
                            <h4 class="card-title">Product Spoilage Records <span class="text-muted fs-base fw-normal">({{ $spoilages->count() }} Records)</span></h4>
                            <div>
                                @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                <button type="button" class="btn btn-sm btn-danger me-1" data-bs-toggle="modal" data-bs-target="#addSpoilageModal">
                                    <i class="fa fa-plus me-1"></i> Record Spoilage
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
                                            <th data-table-sort>Product</th>
                                              @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                            <th data-table-sort>Store</th>
                                            @endif
                                             @if(auth()->user()->role_id === '1001')
                                            <th data-table-sort>Tenant</th>
                                            @endif
                                            <th data-table-sort>Quantity</th>
                                            <th data-table-sort>Unit Cost</th>
                                            <th data-table-sort>Total Cost</th>
                                            <th data-table-sort>Reason</th>
                                            <th data-table-sort>Spoiled Date</th>
                                            <th data-table-sort>Recorded By</th>
                                            @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                            <th data-table-sort>Action</th>
                                            @endif
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        @forelse ($spoilages as $spoilage)
                                            <tr>
                                                <td>#{{ substr($spoilage->product_management_id, 0, 8) }}</td>
                                                <td>
                                                    <h5 class="m-0 fs-base">{{ strtoupper($spoilage->product ? $spoilage->product->product_name : 'N/A') }}</h5>
                                                    @if($spoilage->product && $spoilage->product->product_type)
                                                        <span class="text-muted fs-xs">{{ strtoupper($spoilage->product->product_type) }}</span>
                                                    @endif
                                                </td>
                                                 @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                <td>{{ $spoilage->product->store ? $spoilage->product->store->store_name : 'N/A'}}</td>
                                                @endif
                                                @if(auth()->user()->role_id === '1001')
                                                <td>{{ $spoilage->product->tenant ? $spoilage->product->tenant->tenant_name : 'N/A'}}</td>
                                                 @endif
                                            
                                                <td>
                                                    <span class="badge bg-danger-subtle text-danger fw-semibold">{{ $spoilage->quantity }}</span>
                                                </td>
                                                <td>GHs {{ number_format($spoilage->unit_cost, 2) }}</td>
                                                <td>GHs {{ number_format($spoilage->unit_cost * $spoilage->quantity, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-warning-subtle text-warning">{{ strtoupper($spoilage->reason) }}</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($spoilage->spoiled_date)->format('d M Y') }}</td>
                                                <td>
                                                    <span class="text-muted fs-xs">{{ strtoupper($spoilage->added_by) }}</span>
                                                    <br/>
                                                    <span class="text-muted fs-xxs">{{ $spoilage->added_date ? \Carbon\Carbon::parse($spoilage->added_date)->format('d M Y, h:i A') : 'N/A' }}</span>
                                                </td>
                                                @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                <td>
                                                    <form action="{{ route('spoilages.destroy', $spoilage->product_management_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this spoilage record? Doing so will return the quantity back to stock.');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fa fa-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">
                                                    No spoilage records found. Click "Record Spoilage" to create one.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div data-table-pagination-info="spoilages"></div>
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

    <!-- Record Spoilage Modal -->
    <div class="modal fade" id="addSpoilageModal" tabindex="-1" aria-labelledby="addSpoilageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSpoilageModalLabel">Record New Spoilage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('spoilages.store') }}" method="POST" id="recordSpoilageForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                <option value="" disabled selected>-Select Product-</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->product_id }}" 
                                            data-cost="{{ $product->price ? $product->price->unit_cost : 0 }}"
                                            data-stock="{{ $product->stock ? $product->stock->stock_quantity : 0 }}">
                                        {{ strtoupper($product->product_name) }} 
                                        @if($product->product_type) ({{ $product->product_type }}) @endif 
                                        [Stock: {{ $product->stock ? $product->stock->stock_quantity : 0 }}]
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity Spoiled</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required min="1" placeholder="0">
                                <div class="form-text text-muted" id="stock-feedback">Select a product to see stock.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="unit_cost" class="form-label">Unit Cost (GHs)</label>
                                <input type="number" readonly step="0.01" class="form-control" id="unit_cost" name="unit_cost" required min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <select class="form-select" id="reason" name="reason" required>
                                <option value="" disabled selected>-Select Reason-</option>
                                <option value="Expired">Expired</option>
                                <option value="Damaged">Damaged</option>
                                <option value="Lost">Lost</option>
                                <option value="Stolen">Stolen</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="spoiled_date" class="form-label">Spoiled Date</label>
                            <input type="date" class="form-control" id="spoiled_date" name="spoiled_date" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label for="comments" class="form-label">Comments / Remarks</label>
                            <textarea class="form-control" id="comments" name="comments" rows="3" placeholder="Additional details..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to record this product spoilage? This action is permanent and will subtract the entered quantity from stock balance.');">Confirm & Save Spoilage</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script to auto-populate unit cost and check stock limit -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('product_id');
            const unitCostInput = document.getElementById('unit_cost');
            const quantityInput = document.getElementById('quantity');
            const stockFeedback = document.getElementById('stock-feedback');

            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const cost = selectedOption.getAttribute('data-cost');
                const stock = selectedOption.getAttribute('data-stock');

                if (cost) {
                    unitCostInput.value = parseFloat(cost).toFixed(2);
                } else {
                    unitCostInput.value = '0.00';
                }

                if (stock) {
                    stockFeedback.textContent = `Available Stock: ${stock}`;
                    quantityInput.setAttribute('max', stock);
                } else {
                    stockFeedback.textContent = 'Available Stock: 0';
                    quantityInput.setAttribute('max', 0);
                }
            });
        });
    </script>
</x-app-layout>
