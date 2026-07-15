<x-app-layout>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Start Main Content -->
    <div class="content-page" x-data="{
        products: [],
        allProducts: [],
        searchQuery: '',
        cart: [],
        paymentMethod: 'Cash',
        currentPage: 1,
        lastPage: 1,
        loading: false,
        hasMore: true,
        perPage: 20,
        initialLoad: true,
        cartDiscountType: 'percentage', // 'percentage' or 'fixed'
        cartDiscountValue: 0,
        cartDiscountAmount: 0,
        
        get serializedCart() {
            return JSON.stringify(this.cart);
        },
        
        async loadProducts(page = 1) {
            if (this.loading || !this.hasMore) return;
            
            this.loading = true;
            
            try {
                const response = await fetch(`{{ route('sales.products') }}?page=${page}&search=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();
                
                if (page === 1) {
                    this.products = data.data;
                    this.allProducts = data.data;
                } else {
                    this.products = [...this.products, ...data.data];
                    this.allProducts = [...this.allProducts, ...data.data];
                }
                
                this.currentPage = data.current_page;
                this.lastPage = data.last_page;
                this.hasMore = data.current_page < data.last_page;
                this.initialLoad = false;
            } catch (error) {
                console.error('Error loading products:', error);
                alert('Failed to load products. Please refresh the page.');
            } finally {
                this.loading = false;
            }
        },
        
        async searchProducts() {
            this.currentPage = 1;
            this.hasMore = true;
            this.products = [];
            this.allProducts = [];
            await this.loadProducts(1);
        },
        
        loadMore() {
            if (this.hasMore && !this.loading) {
                this.loadProducts(this.currentPage + 1);
            }
        },
        
        handleScroll(event) {
            const container = event.target;
            if (container.scrollTop + container.clientHeight >= container.scrollHeight - 100) {
                this.loadMore();
            }
        },
        
        addToCart(product) {
            if (product.stock_quantity <= 0) {
                alert('This product is out of stock!');
                return;
            }
            let existing = this.cart.find(item => String(item.product_id) === String(product.product_id));
            if (existing) {
                if (existing.quantity >= product.stock_quantity) {
                    alert('Cannot exceed available stock (' + product.stock_quantity + ')');
                    return;
                }
                existing.quantity++;
                this.applyQuantityDiscount(existing);
            } else {
                this.cart.push({
                    product_id: product.product_id,
                    product_name: product.product_name,
                    unit_price: product.unit_price,
                    quantity: 1,
                    discount: 0,
                    discount_type: 'percentage', // 'percentage' or 'fixed'
                    discount_value: 0,
                    max_stock: product.stock_quantity
                });
            }
            this.calculateCartDiscount();
        },
        
        removeFromCart(productId) {
            this.cart = this.cart.filter(item => item.product_id !== productId);
            this.calculateCartDiscount();
        },
        
        updateQuantity(item, qty) {
            let newQty = parseInt(qty);
            if (isNaN(newQty) || newQty <= 0) newQty = 1;
            if (newQty > item.max_stock) {
                alert('Cannot exceed available stock (' + item.max_stock + ')');
                newQty = item.max_stock;
            }
            item.quantity = newQty;
            this.applyQuantityDiscount(item);
            this.calculateCartDiscount();
        },
        
        applyQuantityDiscount(item) {
            let discountPct = 0;
            //<!-- if (item.quantity >= 10) {
              //  discountPct = 0.10;
            //} else if (item.quantity >= 5) {
              //  discountPct = 0.05;
            //} -->
            // Only apply quantity discount if no manual discount is set
            if (!item.discount_value || item.discount_value === 0) {
                item.discount = parseFloat((item.unit_price * item.quantity * discountPct).toFixed(2));
            } else {
                this.applyManualDiscount(item);
            }
        },
        
        applyManualDiscount(item) {
            const subtotal = item.unit_price * item.quantity;
            if (item.discount_type === 'percentage') {
                const discountPct = parseFloat(item.discount_value) || 0;
                item.discount = parseFloat((subtotal * (discountPct / 100)).toFixed(2));
            } else {
                // Fixed amount discount
                const discountAmount = parseFloat(item.discount_value) || 0;
                item.discount = Math.min(discountAmount, subtotal);
            }
        },
        
        updateItemDiscount(item, type, value) {
            item.discount_type = type;
            item.discount_value = parseFloat(value) || 0;
            this.applyManualDiscount(item);
            this.calculateCartDiscount();
        },
        
        calculateLineTotal(item) {
            return ((item.unit_price * item.quantity) - item.discount).toFixed(2);
        },
        
        subtotal() {
            return this.cart.reduce(
                (sum,item) => sum + (item.unit_price * item.quantity),
                0
            );
        },
        
        totalQuantityDiscount() {
            return this.cart.reduce(
                (sum,item) => sum + item.discount,
                0
            );
        },
        
        applyCartDiscount() {
            const subtotal = this.subtotal();
            const cartDiscount = parseFloat(this.cartDiscountValue) || 0;
            
            if (this.cartDiscountType === 'percentage') {
                this.cartDiscountAmount = parseFloat((subtotal * (cartDiscount / 100)).toFixed(2));
            } else {
                this.cartDiscountAmount = Math.min(cartDiscount, subtotal);
            }
        },
        
        calculateCartDiscount() {
            // Recalculate cart-level discount when cart changes
            this.applyCartDiscount();
        },
        
        grandTotal() {
            const subtotal = this.subtotal();
            const totalDiscount = this.totalQuantityDiscount() + this.cartDiscountAmount;
            return (subtotal - totalDiscount).toFixed(2);
        },
        
        filteredProducts() {
            return this.products;
        },
        
        init() {
            this.loadProducts(1);
        }
    }"
    x-init="init()">
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

            @if(session('receipt'))
                <!-- Receipt Modal -->
                <div class="modal fade show" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-modal="true" role="dialog" style="display: block; background: rgba(0,0,0,0.5); z-index: 1050;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="receiptModalLabel"><i class="fa fa-receipt me-2"></i> Payment Receipt</h5>
                                <button type="button" class="btn-close" onclick="document.getElementById('receiptModal').style.display='none'"></button>
                            </div>
                            <div class="modal-body text-start" id="printable-receipt" style="white-space: normal;">
                                <div class="text-center mb-4">
                                    <h4 class="mb-1">PACES POS</h4>
                                    <p class="text-muted fs-sm mb-0">PAYMENT RECEIPT</p>
                                </div>
                                <div class="row mb-3 fs-xs text-muted">
                                    <div class="col-6">
                                        <strong>Invoice:</strong> {{ session('receipt.invoice_no') }}<br>
                                        <strong>Date:</strong> {{ session('receipt.date') }}
                                    </div>
                                    <div class="col-6 text-end">
                                        <strong>Cashier:</strong> {{ session('receipt.added_by') }}<br>
                                        <strong>Method:</strong> {{ session('receipt.payment_method') }}
                                    </div>
                                </div>
                                <table class="table table-sm table-borderless fs-sm mb-3">
                                    <thead>
                                        <tr class="border-bottom text-uppercase fs-xxs text-muted">
                                            <th>Item</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(session('receipt.items') as $item)
                                            <tr>
                                                <td>
                                                    <strong>{{ strtoupper($item['product_name']) }}</strong>
                                                    @if(floatval($item['discount']) > 0)
                                                        <br><small class="text-success">Discount: -GHs {{ number_format($item['discount'], 2) }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $item['quantity'] }}</td>
                                                <td class="text-end">GHs {{ number_format($item['unit_price'], 2) }}</td>
                                                <td class="text-end">GHs {{ number_format((floatval($item['unit_price']) * intval($item['quantity'])) - floatval($item['discount']), 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="border-top pt-3 fs-sm">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Subtotal</span>
                                        <span>GHs {{ number_format(session('receipt.subtotal'), 2) }}</span>
                                    </div>
                                    @if(session('receipt.discount') > 0)
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-success">Discount</span>
                                            <span class="text-success">-GHs {{ number_format(session('receipt.discount'), 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between fw-bold fs-base border-top pt-2">
                                        <span>Grand Total</span>
                                        <span class="text-primary">GHs {{ number_format(session('receipt.grand_total'), 2) }}</span>
                                    </div>
                                </div>
                                <div class="text-center mt-4">
                                    <p class="text-muted fs-xs mb-0">Thank you for your business!</p>
                                    <p class="text-muted fs-xxs">Powered by WebEdge Technologies</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="document.getElementById('receiptModal').style.display='none'">Close</button>
                                <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print me-1"></i> Print Receipt</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
           
            <div class="row">
                <!-- Products Selection (Left) -->
                <div class="col-xxl-8 col-lg-8 mb-4">
                    <div class="card card-h-100">
                        <div class="card-header justify-content-between align-items-center">
                            <h4 class="card-title">Products Directory <span class="text-muted fs-base fw-normal">(<span x-text="filteredProducts().length"></span> Products)</span></h4>
                            <div class="w-50">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-light-subtle"><i class="fa fa-search"></i></span>
                                    <input type="text" 
                                           x-model="searchQuery" 
                                           @input.debounce.500ms="searchProducts()"
                                           class="form-control" 
                                           placeholder="Search by name, category or brand...">
                                    <span class="input-group-text bg-light" x-show="loading">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 550px; overflow-y: auto;" x-ref="productTable" @scroll="handleScroll($event)">
                                <table class="table table-custom table-centered table-hover w-100 mb-0">
                                    <thead class="bg-light align-middle bg-opacity-25 thead-sm sticky-top" style="z-index: 5;">
                                        <tr class="text-uppercase table-nowrap fs-xxs">
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Available Stock</th>
                                            <th>Price (GHs)</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-nowrap">
                                        <!-- Loading State -->
                                        <template x-if="loading && filteredProducts().length === 0">
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </div>
                                                    <p class="text-muted mt-2">Loading products...</p>
                                                </td>
                                            </tr>
                                        </template>

                                        <!-- Products List -->
                                        <template x-for="product in filteredProducts()" :key="product.product_id">
                                            <tr>
                                                <td>
                                                    <h5 class="m-0 fs-base" x-text="product.product_name.toUpperCase()"></h5>
                                                    <span class="text-muted fs-xs" x-show="product.product_type" x-text="product.product_type"></span>
                                                </td>
                                                <td x-text="product.category_name"></td>
                                                <td>
                                                    <span class="badge" :class="product.stock_quantity > 10 ? 'bg-success-subtle text-success' : (product.stock_quantity > 0 ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger')" x-text="product.stock_quantity + ' available'"></span>
                                                </td>
                                                <td>GHs <span x-text="product.unit_price.toFixed(2)"></span></td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-primary" :disabled="product.stock_quantity <= 0" @click="addToCart(product)">
                                                        <i class="fa fa-shopping-cart me-1"></i> Add to Cart
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>

                                        <!-- No Products Found -->
                                        <template x-if="!loading && filteredProducts().length === 0">
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">
                                                    <i class="fa fa-box-open fs-1 d-block mb-3"></i>
                                                    No products found matching your search query.
                                                </td>
                                            </tr>
                                        </template>

                                        <!-- Load More Indicator -->
                                        <template x-if="hasMore && filteredProducts().length > 0">
                                            <tr>
                                                <td colspan="5" class="text-center py-3">
                                                    <div x-show="loading" class="d-flex justify-content-center">
                                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                            <span class="visually-hidden">Loading more...</span>
                                                        </div>
                                                        <span class="ms-2 text-muted">Loading more products...</span>
                                                    </div>
                                                    <button x-show="!loading" @click="loadMore()" class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-chevron-down me-1"></i> Load More Products
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>

                                        <!-- End of Products -->
                                        <template x-if="!hasMore && filteredProducts().length > 0">
                                            <tr>
                                                <td colspan="5" class="text-center py-3 text-muted">
                                                    <i class="fa fa-check-circle text-success me-1"></i> All products loaded
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shopping Cart (Right) -->
                <div class="col-xxl-4 col-lg-4 mb-4">
                    <div class="card card-h-100">
                        <div class="card-header bg-light bg-opacity-50">
                            <h4 class="card-title d-flex align-items-center"><i class="fa fa-shopping-cart me-2"></i> POS Cart</h4>
                        </div>
                        <div class="card-body d-flex flex-column p-0">
                            <!-- Cart Items -->
                            <div class="flex-grow-1 p-3" style="max-height: 380px; overflow-y: auto;">
                                <template x-if="cart.length === 0">
                                    <div class="text-center py-5 text-muted">
                                        <i class="fa fa-bag-shopping fs-1 text-light mb-3"></i>
                                        <p class="mb-0">Your Cart is Empty.</p>
                                        <p class="fs-xs">Add Products from the directory on the left.</p>
                                    </div>
                                </template>

                                <template x-if="cart.length > 0">
                                    <div class="d-flex flex-column gap-3">
                                        <template x-for="item in cart" :key="item.product_id">
                                            <div class="border-bottom border-light-subtle pb-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h6 class="m-0 fs-base" x-text="item.product_name.toUpperCase()"></h6>
                                                        <!-- <small class="text-muted">GHs <span x-text="item.unit_price.toFixed(2)"></span> per unit</small> -->
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" @click="removeFromCart(item.product_id)">
                                                        <i class="fa fa-trash-can"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Quantity and Discount Controls -->
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="input-group input-group-sm w-40">
                                                        <button class="btn btn-outline-secondary" type="button" @click="updateQuantity(item, item.quantity - 1)">-</button>
                                                        <input type="text" class="form-control text-center" :value="item.quantity" @change="updateQuantity(item, $event.target.value)">
                                                        <button class="btn btn-outline-secondary" type="button" @click="updateQuantity(item, item.quantity + 1)">+</button>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="fw-semibold">GHs <span x-text="calculateLineTotal(item)"></span></div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Item Discount Input -->
                                                <!-- <div class="d-flex align-items-center gap-2 mt-1">
                                                    <span class="text-muted fs-xs" style="min-width: 60px;">Discount:</span>
                                                    <div class="input-group input-group-sm" style="width: 130px;">
                                                        <input type="number" 
                                                               class="form-control text-center" 
                                                               :value="item.discount_value" 
                                                               @input="updateItemDiscount(item, item.discount_type, $event.target.value)"
                                                               min="0"
                                                               step="0.01"
                                                               placeholder="0">
                                                        <span class="input-group-text p-0">
                                                            <select class="form-select form-select-sm border-0" 
                                                                    style="min-width: 70px;"
                                                                    x-model="item.discount_type"
                                                                    @change="updateItemDiscount(item, item.discount_type, item.discount_value)">
                                                                <option value="percentage">%</option>
                                                                <option value="fixed">GHs</option>
                                                            </select>
                                                        </span>
                                                    </div>
                                                    <template x-if="item.discount > 0">
                                                        <span class="text-success fs-xs fw-bold">-GHs <span x-text="item.discount.toFixed(2)"></span></span>
                                                    </template>
                                                </div> -->
                                                
                                                <!-- Quantity discount indicator -->
                                                <!-- <div x-show="item.quantity >= 5 && (!item.discount_value || item.discount_value === 0)" 
                                                     class="text-info fs-xxs mt-1">
                                                    <i class="fa fa-tag me-1"></i> 
                                                    <span x-text="item.quantity >= 10 ? '10% bulk discount applied' : '5% bulk discount applied'"></span>
                                                </div> -->
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <!-- Cart Summary -->
                            <div class="bg-light p-3 border-top border-light-subtle">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-semibold">GHs <span x-text="subtotal().toFixed(2)"></span></span>
                                </div>
                                
                                <!-- Cart-level Discount Input -->
                                <div class="mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted fs-xs" style="min-width: 60px;">Cart Discount:</span>
                                        <div class="input-group input-group-sm" style="width: 200px;">
                                            <input type="number" 
                                                   class="form-control text-center" 
                                                   x-model="cartDiscountValue"
                                                   @input="applyCartDiscount()"
                                                   min="0"
                                                   step="0.01"
                                                   placeholder="0">
                                            <span class="input-group-text p-0">
                                                <select class="form-select form-select-sm border-0" 
                                                        style="min-width: 70px;"
                                                        x-model="cartDiscountType"
                                                        @change="applyCartDiscount()">
                                                    <option value="percentage">%</option>
                                                    <option value="fixed">GHs</option>
                                                </select>
                                            </span>
                                        </div>
                                        <template x-if="cartDiscountAmount > 0">
                                            <span class="text-success fs-xs fw-bold">-GHs <span x-text="cartDiscountAmount.toFixed(2)"></span></span>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Discount Breakdown -->
                                <div x-show="totalQuantityDiscount() > 0 || cartDiscountAmount > 0" class="mb-2">
                                    <div x-show="totalQuantityDiscount() > 0" class="d-flex justify-content-between mb-1">
                                        <span class="text-muted fs-xs">Item Discounts</span>
                                        <span class="text-success fs-xs">-GHs <span x-text="totalQuantityDiscount().toFixed(2)"></span></span>
                                    </div>
                                    <div x-show="cartDiscountAmount > 0" class="d-flex justify-content-between mb-1">
                                        <span class="text-muted fs-xs">Cart Discount</span>
                                        <span class="text-success fs-xs">-GHs <span x-text="cartDiscountAmount.toFixed(2)"></span></span>
                                    </div>
                                </div>
                                
                                <hr class="my-2 border-light-subtle">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="fs-base fw-bold">Grand Total</span>
                                    <span class="fs-base fw-bold text-primary">GHs <span x-text="grandTotal()"></span></span>
                                </div>

                                <!-- Payment Method & Checkout -->
                                <form action="{{ route('sales.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_data" :value="serializedCart">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label fs-xs fw-semibold text-uppercase text-muted">Payment Method</label>
                                        <select class="form-select" id="payment_method" name="payment_method" x-model="paymentMethod" required>
                                            <option value="Cash">Cash</option>
                                            <option value="Mobile Money">Mobile Money (MoMo)</option>
                                            <option value="Card">Visa / Mastercard</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-2 d-flex align-items-center justify-content-center" :disabled="cart.length === 0">
                                        <i class="fa fa-check-circle me-2"></i> Complete Sale & Checkout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
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
    </div>
</x-app-layout>