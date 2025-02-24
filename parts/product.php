<?php
require_once __DIR__ . '/../config/connexion.php';

class Product {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function getFeaturedProducts() {
        $stmt = $this->pdo->query('SELECT * FROM products LIMIT 2'); // Adjust the query as needed
        return $stmt->fetchAll();
    }
}

$product = new Product();
$featuredProducts = $product->getFeaturedProducts();
?>

<style>
@media (max-width: 768px) {
    .object-cover {
        object-fit: contain;
    }
}
</style>
<section class="py-20 bg-white px-6">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-center mb-2">Top-Rated Products</h1>
        <p class="text-gray-600 text-center mb-16">Handpicked selections for you</p>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 max-w-7xl mx-auto">
            <?php foreach ($featuredProducts as $product): ?>
            <?php 
// Split the title into words and take the first three
$productWords = explode(' ', trim($product['title'])); 
$firstThreeWords = array_slice($productWords, 0, 3); // Get the first three words

// Join the words with hyphens, remove unwanted characters, and clean the result
$productUrl = htmlspecialchars(
    strtolower(implode('-', $firstThreeWords))  // Join the first three words with hyphens and convert to lowercase
);

// Optionally, remove any unwanted characters like special symbols or spaces (already handled in implode)
$productUrl = preg_replace('/[^a-z0-9\-]/', '', $productUrl); // Remove non-alphanumeric characters


?>
                <div class="flex flex-col md:flex-row gap-8 bg-white p-8 rounded-xl shadow-sm">
                    <img src="/product-image/<?php echo htmlspecialchars($product['image1']); ?>" 
                         alt="<?php echo htmlspecialchars($product['title']); ?>"
                         class="w-full md:w-48 h-64 object-cover bg-gray-50 rounded-lg">
                    <a href="product/<?php echo urlencode($productUrl); ?>">
                    <div class="flex-1 flex flex-col">
                        <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($product['title']); ?></h2>
                        <p class="text-gray-600 mb-3">by <?php echo htmlspecialchars($product['author']); ?></p>
                        <p class="text-gray-700 mb-4 line-clamp-3"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="mt-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <span class="text-2xl font-bold">$<?php echo number_format($product['price'], 2); ?></span>
                            <button class="bg-black text-white px-6 py-2.5 rounded-full font-medium hover:bg-gray-800 transition-colors inline-flex items-center justify-center gap-2 add-to-cart" data-product-id="<?php echo htmlspecialchars($product['id']); ?>">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-16">
            <a href="./shop.php" class="inline-flex items-center gap-2 bg-black text-white px-8 py-3 rounded-full font-medium hover:bg-gray-800 transition-colors">
                View All Books
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>
    </div>
</section>

<!-- Cart Sidebar -->
<div id="cartOverlay" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity z-40 hidden"></div>
<div id="cartSidebar" class="fixed top-0 right-0 h-full w-[400px] bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50">
    <div class="p-6" id="cartSidebarContent">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-2">
                <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                <h2 class="text-xl font-semibold">Shopping Cart</h2>
                <span class="text-sm text-gray-500">(<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?> items)</span>
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
                                <img src="<?php echo htmlspecialchars($product['image1']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="w-full h-full object-cover">
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
                    <button class="w-full bg-black text-white py-3 rounded-lg font-medium hover:bg-gray-800 transition-colors">
                        Proceed to Checkout
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize Lucide icons
        lucide.createIcons();

        const cartButton = document.getElementById('cartButton');
        const cartSidebar = document.getElementById('cartSidebar');
        const cartOverlay = document.getElementById('cartOverlay');
        const closeCart = document.getElementById('closeCart');

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

        // Add to cart functionality
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', async function() {
                const productId = this.getAttribute('data-product-id');

                try {
                    const response = await fetch('cart.php?action=add&id=' + productId, {
                        method: 'GET',
                        credentials: 'same-origin'
                    });
                    if (!response.ok) throw new Error('Network response was not ok');

                    const result = await response.json();
                    if (result.success) {
                        // Update cart count in navbar
                        document.getElementById('cartItemCount').textContent = result.cartItemCount;

                        // Fetch updated cart items
                        fetchCartItems();
                        openCart(); // Open the cart sidebar
                    } else {
                        console.error('Error adding to cart:', result.message);
                    }
                } catch (error) {
                    console.error('Error adding to cart:', error);
                }
            });
        });

        // Fetch updated cart items
        async function fetchCartItems() {
            try {
                const response = await fetch('cart.php?action=get', {
                    method: 'GET',
                    credentials: 'same-origin'
                });
                if (!response.ok) throw new Error('Network response was not ok');

                const result = await response.json();
                document.getElementById('cartSidebarContent').innerHTML = result.cartSidebarContent;

                // Reinitialize event listeners
                initializeEventListeners();
            } catch (error) {
                console.error('Error fetching cart items:', error);
            }
        }

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
                            // Update cart count in navbar
                            document.getElementById('cartItemCount').textContent = result.cartItemCount;

                            // Fetch updated cart items
                            fetchCartItems();
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
    });
</script>
