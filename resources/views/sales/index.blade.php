<x-app-layout>
    <!-- Start Main Content -->
    <div class="content-page" x-data="{
        products: [
            @foreach($products as $product)
            {
                product_id: {!! json_encode($product->product_id) !!},
                product_name: {!! json_encode($product->product_name) !!},
                product_type: {!! json_encode($product->product_type) !!},
                category_name: {!! json_encode($product->category ? $product->category->category_name : 'N/A') !!},
                unit_price: {{ $product->price ? $product->price->unit_price : 0 }},
                unit_cost: {{ $product->price ? $product->price->unit_cost : 0 }},
                stock_quantity: {{ $product->stock ? $product->stock->stock_quantity : 0 }}
            },
            @endforeach
        ],
        searchQuery: '',
        cart: [],
        paymentMethod: 'Cash',
        addToCart(product) {
            if (product.stock_quantity <= 0) {
                alert('This product is out of stock!');
                return;
            }
            let existing = this.cart.find(item => item.product_id === product.product_id);
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
                    max_stock: product.stock_quantity
                });
            }
        },
        removeFromCart(productId) {
            this.cart = this.cart.filter(item => item.product_id !== productId);
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
        },
        applyQuantityDiscount(item) {
            // Bulk quantity discount rules:
            // - Buy 5 or more items: get 5% discount
            // - Buy 10 or more items: get 10% discount
            let discountPct = 0;
            if (item.quantity >= 10) {
                discountPct = 0.10;
            } else if (item.quantity >= 5) {
                discountPct = 0.05;
            }
            item.discount = parseFloat((item.unit_price * item.quantity * discountPct).toFixed(2));
        },
        calculateLineTotal(item) {
            return ((item.unit_price * item.quantity) - item.discount).toFixed(2);
        },
        subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0).toFixed(2);
        },
        totalDiscount() {
            return this.cart.reduce((sum, item) => sum + item.discount, 0).toFixed(2);
        },
        grandTotal() {
            return (this.subtotal() - this.totalDiscount()).toFixed(2);
        },
        filteredProducts() {
            if (!this.searchQuery) return this.products;
            let query = this.searchQuery.toLowerCase();
            return this.products.filter(p => 
                (p.product_name && p.product_name.toLowerCase().includes(query)) || 
                (p.product_type && p.product_type.toLowerCase().includes(query)) ||
                (p.category_name && p.category_name.toLowerCase().includes(query))
            );
        }
    }">
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
                <!-- Products Selection (Left) -->
                <div class="col-xxl-8 col-lg-8 mb-4">
                    <div class="card card-h-100">
                        <div class="card-header justify-content-between align-items-center">
                            <h4 class="card-title">Products Directory <span class="text-muted fs-base fw-normal">(<span x-text="filteredProducts().length"></span> Products)</span></h4>
                            <div class="w-50">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-light-subtle"><i class="fa fa-search"></i></span>
                                    <input type="text" x-model="searchQuery" class="form-control" placeholder="Search by name, category or brand...">
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
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
                                        <template x-if="filteredProducts().length === 0">
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">
                                                    No products found matching your search query.
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
                                        <p class="mb-0">Your cart is empty.</p>
                                        <p class="fs-xs">Add products from the directory on the left.</p>
                                    </div>
                                </template>

                                <template x-if="cart.length > 0">
                                    <div class="d-flex flex-column gap-3">
                                        <template x-for="item in cart" :key="item.product_id">
                                            <div class="border-bottom border-light-subtle pb-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h6 class="m-0 fs-base" x-text="item.product_name.toUpperCase()"></h6>
                                                        <small class="text-muted">GHs <span x-text="item.unit_price.toFixed(2)"></span> per unit</small>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" @click="removeFromCart(item.product_id)">
                                                        <i class="fa fa-trash-can"></i>
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <!-- Quantity input with step counters -->
                                                    <div class="input-group input-group-sm w-50">
                                                        <button class="btn btn-outline-secondary" type="button" @click="updateQuantity(item, item.quantity - 1)">-</button>
                                                        <input type="text" class="form-control text-center" :value="item.quantity" @change="updateQuantity(item, $event.target.value)">
                                                        <button class="btn btn-outline-secondary" type="button" @click="updateQuantity(item, item.quantity + 1)">+</button>
                                                    </div>
                                                    <div class="text-end">
                                                        <template x-if="item.discount > 0">
                                                            <div class="text-success fs-xs mb-1">Discount: -GHs <span x-text="item.discount.toFixed(2)"></span></div>
                                                        </template>
                                                        <div class="fw-semibold">GHs <span x-text="calculateLineTotal(item)"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <!-- Cart Summary -->
                            <div class="bg-light p-3 border-top border-light-subtle">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-semibold">GHs <span x-text="subtotal()"></span></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-success d-flex align-items-center">Quantity Discounts <i class="fa fa-circle-info text-success ms-1" title="Discounts applied: 5+ items gets 5% off, 10+ items gets 10% off"></i></span>
                                    <span class="text-success fw-semibold">-GHs <span x-text="totalDiscount()"></span></span>
                                </div>
                                <hr class="my-2 border-light-subtle">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="fs-base fw-bold">Grand Total</span>
                                    <span class="fs-base fw-bold text-primary">GHs <span x-text="grandTotal()"></span></span>
                                </div>

                                <!-- Payment Method & Checkout -->
                                <form action="{{ route('sales.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_data" :value="JSON.stringify(cart)">
                                    
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
