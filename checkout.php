<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Texas Treasures - Checkout</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="style/checkout.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-gray-50">
<?php 
include 'parts/navbar.php'; 
require_once 'class/checkout-class.php';
require_once 'config/stripe.php';

$stripeConfig = include 'config/stripe.php';
$checkout = new Checkout();
$cartProducts = isset($_SESSION['cart']) ? $checkout->getCartProducts($_SESSION['cart']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['stripeToken'];
    $userId = $_SESSION['user_id']; // Assuming user_id is stored in session
    $cardNumber = $_POST['card_number'];
    $expiryDate = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Save card info in database
    if ($checkout->saveCardInfo($userId, $cardNumber, $expiryDate, $cvv)) {
        // Process payment with Stripe
        // ...
    } else {
        echo '<p>Error saving card information.</p>';
    }
}
?>

<div class="checkout-container">
    <div class="form-section">
        <h2 class="section-title text-2xl font-bold mb-6">Contact Information</h2>
        <div class="form-group">
            <label>Email Address *</label>
            <input type="email" placeholder="Enter your email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" placeholder="e.g., +1234567890" pattern="^\+?[1-9]\d{1,14}$">
        </div>

        <h2 class="section-title text-2xl font-bold mb-6" style="margin-top: 2rem;">Shipping Address</h2>
        <div class="input-row">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" pattern="[A-Za-z]+" required>
            </div>
            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" pattern="[A-Za-z]+" required>
            </div>
        </div>
        <div class="form-group">
            <label>Address *</label>
            <input type="text" required>
        </div>
        <div class="form-group">
            <label>Apartment (optional)</label>
            <input type="text">
        </div>
        <div class="input-row">
            <div class="form-group">
                <label>City *</label>
                <input type="text" pattern="[A-Za-z]+" required>
            </div>
            <div class="form-group">
                <label>State *</label>
                <input type="text" pattern="[A-Za-z]+" required>
            </div>
            <div class="form-group">
                <label>ZIP Code *</label>
                <input type="text" pattern="\d{5}(-\d{4})?" required>
            </div>
        </div>
        <div class="form-group">
            <label>Country</label>
            <select>
                <option>United States (Free Shipping)</option>
            </select>
        </div>

        <h2 class="section-title text-2xl font-bold mb-6" style="margin-top: 2rem;">Shipping Method</h2>
        <div class="shipping-method">
            <label>
                <input type="radio" name="shipping" checked>
                <span>
                    <strong>Standard Shipping</strong>
                    <div style="color: #666; font-size: 0.9rem;">3-5 business days</div>
                </span>
                <span style="margin-left: auto; color: #22c55e;">FREE</span>
            </label>
        </div>
        
        <div class="shipping-info-box">
            <p>• Enjoy free standard shipping on all orders over $50 within the U.S.</p>
            <p>• Standard shipping takes approximately 5-7 business days.</p>
            <p>• Receive tracking information via email once your order has been shipped.</p>

        </div>


        <h2 class="section-title text-2xl font-bold mb-6" style="margin-top: 2rem;">Payment</h2>
        <form id="payment-form" method="POST">
            <div class="form-group">
                <div id="card-element"></div>
            </div>
            <div id="card-errors" role="alert"></div>
            <input type="hidden" name="card_number" id="card_number">
            <input type="hidden" name="expiry_date" id="expiry_date">
            <input type="hidden" name="cvv" id="cvv">
            <button class="pay-button" id="submit">Pay $<?php echo number_format(array_sum(array_column($cartProducts, 'price')), 2); ?></button>
        </form>
        <div style="text-align: center; margin-top: 1rem; font-size: 0.85rem; color: #666;">
            Your payment information is encrypted and secure. We never store your full card details.
        </div>
    </div>

    <div class="order-summary">
        <h2 class="section-title text-2xl font-bold mb-6">Order Summary</h2>
        <?php if (empty($cartProducts)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <?php foreach ($cartProducts as $product): ?>
                <div class="product-card">
                    <img style="object-fit: contain;" src="/product-image/<?php echo htmlspecialchars($product['image1']); ?>" alt="Book" class="product-image">
                    <div class="product-details">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                        <div class="product-meta">Size: <?php echo htmlspecialchars($product['format'] ?? 'N/A'); ?></div>
                        <div class="product-meta">Qty: 1</div>
                    </div>
                    <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                </div>
            <?php endforeach; ?>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>$<?php echo number_format(array_sum(array_column($cartProducts, 'price')), 2); ?></span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span style="color: #22c55e;">FREE</span>
            </div>
            <div class="total-row">
                <span>Total</span>
                <span>$<?php echo number_format(array_sum(array_column($cartProducts, 'price')), 2); ?></span>
            </div>
        <?php endif; ?>

        <div class="shipping-info">
            ✓ Free US Shipping
            <div style="font-size: 0.85rem; margin-top: 0.25rem;">Delivery within 5-7 business days</div>
        </div>

        <div style="color: #666; font-size: 0.9rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Secure checkout
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Free returns within 30 days
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                 Enjoy free standard shipping on orders over $50 within the U.S
            </div>
        </div>
    </div>
</div>

<?php require_once 'parts/footer.php'; ?>
<script>
    lucide.createIcons();

    var stripe = Stripe('<?php echo $stripeConfig['publishable_key']; ?>');
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                document.getElementById('card_number').value = result.token.card.last4;
                document.getElementById('expiry_date').value = result.token.card.exp_month + '/' + result.token.card.exp_year;
                document.getElementById('cvv').value = result.token.card.cvc_check;
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        form.submit();
    }
</script>
</body>
</html>