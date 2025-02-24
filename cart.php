<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/class/shop-class.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$response = [
    'success' => false,
    'message' => '',
    'cartItemCount' => 0,
    'cartSidebarContent' => ''
];

try {
    if (!isset($_GET['action'])) {
        throw new Exception('Missing action parameter');
    }

    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    switch($_GET['action']) {
        case 'add':
            if ($productId <= 0) {
                throw new Exception('Invalid product ID');
            }
            
            if (!in_array($productId, $_SESSION['cart'])) {
                $_SESSION['cart'][] = $productId;
                $response['success'] = true;
                $response['message'] = 'Product added to cart';
            } else {
                $response['message'] = 'Product already in cart';
            }
            break;
            
        case 'remove':
            if ($productId <= 0) {
                throw new Exception('Invalid product ID');
            }
            
            $key = array_search($productId, $_SESSION['cart']);
            if ($key !== false) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
                $response['success'] = true;
                $response['message'] = 'Product removed from cart';
            } else {
                $response['message'] = 'Product not found in cart';
            }
            break;
            
        case 'get':
            $response['success'] = true;
            break;
            
        default:
            throw new Exception('Invalid action');
    }
    
    // Update cart count
    $response['cartItemCount'] = count($_SESSION['cart']);
    
    // Get cart content
    ob_start();
    include 'fetch_cart.php';
    $response['cartSidebarContent'] = ob_get_clean();
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Send response
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
echo json_encode($response);
exit;