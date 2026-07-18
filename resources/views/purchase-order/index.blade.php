<x-app-layout>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Start Main Content -->
    <div class="content-page" x-data="purchaseOrderApp()">
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
                    <div data-table data-table-rows-per-page="5" class="card card-h-100">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Purchase Orders <span class="text-muted fs-base fw-normal">({{ $purchaseOrders->count() }} Orders)</span></h4>
                            <div>
                                @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#addPurchaseOrderModal">
                                    <i class="fa fa-plus me-1"></i> Add Purchase Order
                                </button>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover w-100">
                                    <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                                        <tr class="text-uppercase table-nowrap fs-xxs">
                                            <th>Order No</th>
                                            <th>Supplier</th>
                                            <th>Invoice No</th>
                                            <th>Order Date</th>
                                            <th>Items</th>
                                            <th>Total Value</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-nowrap">
                                        @forelse ($purchaseOrders as $order)
                                            <tr>
                                                <td>
                                                    <h5 class="m-0 fs-base">{{ $order->order_no }}</h5>
                                                    <span class="text-muted fs-xs">Added by: {{ $order->added_by ?? 'N/A' }}</span>
                                                </td>
                                                <td>{{ $order->supplier ? $order->supplier->supplier_name : 'N/A' }}</td>
                                                <td>{{ $order->invoice_no ?? 'N/A' }}</td>
                                                <td>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : 'N/A' }}</td>
                                                <td>{{ $order->details->count() }}</td>
                                                <td>GHs {{ number_format($order->total_value, 2) }}</td>
                                                <td>
                                                    @if($order->status == 'Active')
                                                        <span class="badge bg-success-subtle text-success">ACTIVE</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">INACTIVE</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn">
                                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                             <!-- <li>
                                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewPurchaseOrderModal-{{ $order->purchase_order_id }}">
                                                                    View Details
                                                                </button>
                                                            </li> -->
                                                            <li>
                                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editPurchaseOrderModal-{{ $order->purchase_order_id }}">
                                                                    Edit 
                                                                </button>
                                                            </li>
                                                             @if(auth()->user()->role_id === '1001' || auth()->user()->role_id === '1002' || auth()->user()->role_id === '1003')
                                                            <li>
                                                                
                                                                <form action="{{ route('purchase-orders.destroy', $order->purchase_order_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this purchase order?');" class="d-inline">
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
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    No Purchase Orders Found. Click "Add Purchase Order" to create one.
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
                             <br> <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- ############################################## -->
        <!-- Add Purchase Order Modal -->
        <div class="modal fade" id="addPurchaseOrderModal" tabindex="-1" aria-labelledby="addPurchaseOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPurchaseOrderModalLabel">Add New Purchase Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('purchase-orders.store') }}" method="POST" @submit.prevent="submitForm($event)">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="supplier_id" class="form-label">Supplier</label>
                                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                                        <option value="" disabled selected>-Select Supplier-</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->supplier_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="order_date" class="form-label">Order Date</label>
                                    <input type="date" class="form-control" id="order_date" name="order_date" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="invoice_no" class="form-label">Invoice No</label>
                                    <input type="text" class="form-control" id="invoice_no" name="invoice_no" placeholder="Invoice number">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="discount" class="form-label">Discount (GHs)</label>
                                    <input type="number" step="0.01" class="form-control" id="discount" name="discount" x-model.number="discount" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="vat" class="form-label">VAT (GHs)</label>
                                    <input type="number" step="0.01" class="form-control" id="vat" name="vat" x-model.number="vat" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3 d-flex align-items-end">
                                    <div class="bg-light p-2 rounded w-100">
                                        <div class="d-flex justify-content-between">
                                            <span>Subtotal:</span>
                                            <strong>GHs <span x-text="subtotal().toFixed(2)"></span></strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Total:</span>
                                            <strong class="text-primary">GHs <span x-text="grandTotal().toFixed(2)"></span></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h6 class="fw-bold mb-2">Order Items</h6>
                            @if($products->isEmpty())
                                <div class="alert alert-warning py-2 mb-2">
                                    <i class="fa fa-circle-exclamation me-1"></i> No Active Products Found. Please add products first.
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Product</th>
                                            <th style="width: 120px;">Qty</th>
                                            <th style="width: 150px;">Unit Price</th>
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
                                                            <option value="{{ $product->product_id }}">{{ strtoupper($product->product_name) }} (GHs {{ $product->price ? number_format($product->price->unit_cost, 2) : '0.00' }})</option>
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
                                                    GHs <span x-text="(item.quantity * item.unit_price).toFixed(2)"></span>
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
                                <i class="fa fa-plus me-1"></i> Add Item
                            </button>

                            <template x-for="(item, index) in items" :key="'hidden-' + index">
                                <div>
                                    <input type="hidden" :name="'products[' + index + '][product_id]'" :value="item.product_id">
                                    <input type="hidden" :name="'products[' + index + '][quantity]'" :value="item.quantity">
                                    <input type="hidden" :name="'products[' + index + '][unit_price]'" :value="item.unit_price">
                                </div>
                            </template>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" :disabled="items.length === 0">Save Purchase Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<!-- ADD NEW PURCHASE ORDER -->
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
        function purchaseOrderApp() {
            return {
                items: [{ product_id: '', quantity: 1, unit_price: 0 }],
                discount: 0,
                vat: 0,
                addItem() {
                    console.log('[DEBUG:PurchaseOrderApp] Add item clicked. Current items:', JSON.parse(JSON.stringify(this.items)));
                    this.items.push({ product_id: '', quantity: 1, unit_price: 0 });
                    console.log('[DEBUG:PurchaseOrderApp] Item added. New items:', JSON.parse(JSON.stringify(this.items)));
                },
                removeItem(index) {
                    console.log('[DEBUG:PurchaseOrderApp] Remove item clicked. Index:', index);
                    this.items.splice(index, 1);
                },
                subtotal() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.quantity || 0) * parseFloat(item.unit_price || 0)), 0);
                },
                grandTotal() {
                    return Math.max(0, this.subtotal() - parseFloat(this.discount || 0) + parseFloat(this.vat || 0));
                },
                submitForm(event) {
                    console.log('[DEBUG:PurchaseOrderApp] Submit form. Items:', JSON.parse(JSON.stringify(this.items)));
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
                    console.log('[DEBUG:PurchaseOrderApp] Form validation passed. Submitting...');
                    event.target.submit();
                }
            };
        }

        function editOrderForm() {
            return {
                items: [],
                discount: 0,
                vat: 0,
                init(orderData) {
                    console.log('[DEBUG:EditOrderForm] init called with:', JSON.parse(JSON.stringify(orderData)));
                    this.discount = orderData.discount || 0;
                    this.vat = orderData.vat || 0;
                    this.items = orderData.items && orderData.items.length ? orderData.items.map(d => ({ ...d })) : [{ product_id: '', quantity: 1, unit_price: 0 }];
                    console.log('[DEBUG:EditOrderForm] initialized items:', JSON.parse(JSON.stringify(this.items)));
                },
                addItem() {
                    console.log('[DEBUG:EditOrderForm] Add item clicked. Current items:', JSON.parse(JSON.stringify(this.items)));
                    this.items.push({ product_id: '', quantity: 1, unit_price: 0 });
                    console.log('[DEBUG:EditOrderForm] Item added. New items:', JSON.parse(JSON.stringify(this.items)));
                },
                removeItem(index) {
                    console.log('[DEBUG:EditOrderForm] Remove item clicked. Index:', index);
                    this.items.splice(index, 1);
                },
                subtotal() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.quantity || 0) * parseFloat(item.unit_price || 0)), 0);
                },
                grandTotal() {
                    return Math.max(0, this.subtotal() - parseFloat(this.discount || 0) + parseFloat(this.vat || 0));
                },
                submitForm(event) {
                    console.log('[DEBUG:EditOrderForm] Submit form. Items:', JSON.parse(JSON.stringify(this.items)));
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
                    console.log('[DEBUG:EditOrderForm] Form validation passed. Submitting...');
                    event.target.submit();
                }
            };
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reset form when modal opens
            const modal = document.getElementById('addPurchaseOrderModal');
            if (modal) {
                modal.addEventListener('show.bs.modal', function() {
                    if (window.purchaseOrderApp && typeof window.purchaseOrderApp.resetForm === 'function') {
                        window.purchaseOrderApp.resetForm();
                    }
                });
            }
            
            // Debug: Check Alpine.js initialization
            // console.log('[DEBUG] DOM loaded. Checking Alpine.js...');
            if (typeof Alpine === 'undefined') {
                console.error('[DEBUG] Alpine.js is not loaded!');
            } else {
                console.log('[DEBUG] Alpine.js is loaded successfully.');
            }
        });
</script>
</x-app-layout>
