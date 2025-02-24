<?php
header('Content-Type: application/json');
require_once '../../class/dashboard-admin-class.php';

$admin = new DashboardAdmin();
$response = ['success' => false, 'message' => '', 'data' => null];

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $category = isset($_GET['category']) ? $_GET['category'] : '';
            
            $products = $admin->getProducts($page, $search, $category);
            $total = $admin->getTotalProducts($search, $category);
            
            $response['success'] = true;
            $response['data'] = [
                'products' => $products,
                'total' => $total,
                'page' => $page,
                'totalPages' => ceil($total / 10)
            ];
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data) {
                $productId = $admin->addProduct($data);
                if ($productId) {
                    $response['success'] = true;
                    $response['message'] = 'Product added successfully';
                    $response['data'] = ['id' => $productId];
                } else {
                    $response['message'] = 'Error adding product';
                }
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'])) {
                $id = $data['id'];
                unset($data['id']);
                if ($admin->updateProduct($id, $data)) {
                    $response['success'] = true;
                    $response['message'] = 'Product updated successfully';
                } else {
                    $response['message'] = 'Error updating product';
                }
            }
            break;

        case 'DELETE':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id && $admin->deleteProduct($id)) {
                $response['success'] = true;
                $response['message'] = 'Product deleted successfully';
            } else {
                $response['message'] = 'Error deleting product';
            }
            break;
    }
} catch (Exception $e) {
    $response['message'] = 'Server error';
    error_log($e->getMessage());
}

echo json_encode($response);