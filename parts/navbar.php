<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../class/shop-class.php';

$cartItemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

$cartProducts = [];
if ($cartItemCount > 0) {
    $shop = new Shop();
    foreach ($_SESSION['cart'] as $productId) {
        $product = $shop->getProductById($productId);
        if ($product) {
            $cartProducts[] = $product;
        }
    }
}

require_once __DIR__ . '/../class/entire-website-controle.php';
$websiteEdite = new Entire_Website_Controle();
$settings = $websiteEdite->Affiche_logo();

if ($settings) {
    $store_name = $settings['store_name'];

} else {
    $store_name = "Default Store Name";
}
?>
<style>
    .text-gray-900 {
        font-weight:900 !important;
    }
    @media (max-width: 768px) {
        #mobileMenu {
            display: none;
        }
        #mobileMenu.open {
            display: block;
        }
    }
</style>


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZD3TTKW352"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-ZD3TTKW352');
</script>



<!-- Navbar -->
    <link rel="icon" type="image/svg+xml" href="./images/favicon.svg">
<nav class="bg-white border-b">
    <div class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
                <a href="/../" class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="shirt" class="lucide lucide-shirt w-6 h-6"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>&nbsp;<strong><?php echo $store_name; ?></strong></span>
                </a>
            </div>

            <div class="hidden md:flex space-x-8">
                <a href="/../index" class="text-gray-600 hover:text-gray-900" style="font-weight:600">Home</a>
                <a href="/../shop" class="text-gray-600 hover:text-gray-900" style="font-weight:600">Shop</a>
                <a href="/../our-story" class="text-gray-600 hover:text-gray-900" style="font-weight:600">About Us</a>
                <a href="/../contact" class="text-gray-600 hover:text-gray-900" style="font-weight:600">Contact Us</a>
                <a href="/../track-order" class="text-gray-600 hover:text-gray-900" style="font-weight:600">Track Order</a>
            </div>

            <div class="flex items-center space-x-6">
                <button id="cartButton" class="text-gray-600 hover:text-gray-900 relative p-2 group">
                    <i data-lucide="shopping-cart" class="w-6 h-6 transition-colors duration-200 group-hover:text-gray-900"></i>
                    <span id="cartItemCount" class="absolute -top-1 -right-1 bg-black text-white text-xs font-medium px-2 py-0.5 rounded-full min-w-[20px] flex items-center justify-center">
                        <?php echo $cartItemCount; ?>
                    </span>
                </button>
                
                <div class="relative dropdown" id="userDropdown">
                    <button class="text-gray-600 hover:text-gray-900" onclick="toggleDropdown()">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </button>
                    <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50" id="dropdownMenu">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['type'] == 'admin'): ?>
                                <a href="/../dashboard-admin" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Dashboard</a>
                            <?php else: ?>
                                <a href="/../dashboard" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Dashboard</a>
                            <?php endif; ?>
                            <a href="/../parts/logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Logout</a>
                        <?php else: ?>
                            <a href="/../login" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Sign In</a>
                            <a href="/../register" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Create Account</a>
                        <?php endif; ?>
                    </div>
                  
                </div>
                <button id="menuButton" class="text-gray-600 hover:text-gray-900 md:hidden">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden">
        <a href="/../index" class="block px-4 py-2 text-gray-600 hover:text-gray-900">Home</a>
        <a href="/../shop" class="block px-4 py-2 text-gray-600 hover:text-gray-900">Shop</a>
        <a href="/../our-story" class="block px-4 py-2 text-gray-600 hover:text-gray-900">About Us</a>
        <a href="/../contact" class="block px-4 py-2 text-gray-600 hover:text-gray-900">Contact Us</a>
        <a href="/../track-order" class="block px-4 py-2 text-gray-600 hover:text-gray-900">Track Order</a>
    </div>
</nav>

<!-- Cart Sidebar -->
<div id="cartOverlay" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity z-40 hidden"></div>
<div id="cartSidebar" class="fixed top-0 right-0 h-full w-[400px] bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50">
    <div class="p-6" id="cartSidebarContent">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-2">
                <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                <h2 class="text-xl font-semibold">Shopping Cart</h2>
                <span class="text-sm text-gray-500">(<?php echo $cartItemCount; ?> items)</span>
            </div>
            <button id="closeCart" class="text-gray-500 hover:text-gray-700">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div class="flex flex-col h-[calc(100vh-200px)]">
            <?php if (empty($cartProducts)): ?>
                <!-- Empty cart state -->
                <div class="flex-1 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 mb-4 text-gray-300">
                        <i data-lucide="shopping-bag" class="w-full h-full"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                    <p class="text-gray-500 mb-6">Looks like you haven't added any items yet</p>
                    <button class="bg-black text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-800 transition-colors">
                        Continue Shopping
                    </button>
                </div>
            <?php else: ?>
                <!-- Cart items -->
                <div class="flex-1 overflow-y-auto">
                    <?php foreach ($cartProducts as $product): ?>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16">
                                <img style="object-fit: contain;" src="/product-image/<?php echo htmlspecialchars($product['image1']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['title']); ?></h3>
                                <p class="text-sm text-gray-500">by <?php echo htmlspecialchars($product['author']); ?></p>
                                <p class="text-sm text-gray-900">$<?php echo number_format($product['price'], 2); ?></p>
                            </div>
                            <button class="text-gray-500 hover:text-gray-700 remove-from-cart" data-product-id="<?php echo $product['id']; ?>">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Cart footer -->
                <div class="border-t mt-auto pt-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">
                            $<?php echo number_format(array_sum(array_column($cartProducts, 'price')), 2); ?>
                        </span>
                    </div>
                    <div class="flex justify-between mb-4">
                        <span class="text-gray-600">Shipping</span>
                        <span class="text-green-600">FREE</span>
                    </div>
                    <div class="flex justify-between mb-6">
                        <span class="text-lg font-semibold">Total</span>
                        <span class="text-lg font-semibold">
                            $<?php echo number_format(array_sum(array_column($cartProducts, 'price')), 2); ?>
                        </span>
                    </div>
                    <a href="/../checkout.php">
                    <button  class="w-full bg-black text-white py-3 rounded-lg font-medium hover:bg-gray-800 transition-colors">
                        Proceed to Checkout
                    </button>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
    
    // Function to update cart content
    async function updateCartContent() {
        try {
            const response = await fetch('cart.php?action=get', {
                method: 'GET',
                credentials: 'same-origin'
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            const result = await response.json();
            if (result.success) {
                document.getElementById('cartSidebarContent').innerHTML = result.cartSidebarContent;
                document.getElementById('cartItemCount').textContent = result.cartItemCount;
                lucide.createIcons();
                initializeEventListeners();
            }
        } catch (error) {
            console.error('Error updating cart:', error);
        }
    }

    // Cart functionality
    const cartButton = document.getElementById('cartButton');
    const cartSidebar = document.getElementById('cartSidebar');
    const cartOverlay = document.getElementById('cartOverlay');
    const closeCart = document.getElementById('closeCart');
    const menuButton = document.getElementById('menuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    function openCart() {
        cartSidebar.classList.remove('translate-x-full');
        cartOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        updateCartContent(); // Update cart content when opening
    }

    function closeCartSidebar() {
        cartSidebar.classList.add('translate-x-full');
        cartOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    cartButton.addEventListener('click', openCart);
    closeCart.addEventListener('click', closeCartSidebar);
    cartOverlay.addEventListener('click', closeCartSidebar);
    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('open');
    });
    
    // Listen for custom event when item is added to cart
    window.addEventListener('cartUpdated', function() {
        updateCartContent();
    });
    
    // User dropdown functionality
    function toggleDropdown() {
        const dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('hidden');
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdown');
        const isClickInside = dropdown.contains(event.target);
        
        if (!isClickInside) {
            const dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.classList.add('hidden');
        }
    });
    
    // Initialize event listeners for cart sidebar
    function initializeEventListeners() {
        document.querySelectorAll('.remove-from-cart').forEach(button => {
            button.addEventListener('click', async function() {
                const productId = this.getAttribute('data-product-id');
                
                try {
                    const response = await fetch('cart.php?action=remove&id=' + productId, {
                        method: 'GET',
                        credentials: 'same-origin'
                    });
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const result = await response.json();
                    if (result.success) {
                        updateCartContent();
                    } else {
                        console.error('Error removing from cart:', result.message);
                    }
                } catch (error) {
                    console.error('Error removing from cart:', error);
                }
            });
        });
    }

    // Initialize event listeners on page load
    initializeEventListeners();
</script>