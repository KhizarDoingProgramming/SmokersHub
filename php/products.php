<?php
// ============================================
// products.php  –  Products REST API
// GET  /php/products.php          → all products
// GET  /php/products.php?id=3     → single product
// POST (admin only) add product
// ============================================

header('Content-Type: application/json');

$pdo = require __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int) $_GET['id'] : null;

// ── GET ─────────────────────────────────────
if ($method === 'GET') {
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Product not found.']);
            exit;
        }
        echo json_encode(['success' => true, 'product' => $product]);
    } else {
        $stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC');
        echo json_encode(['success' => true, 'products' => $stmt->fetchAll()]);
    }
    exit;
}

// ── POST (admin only) ────────────────────────
if ($method === 'POST') {

    if (($_SESSION['user_role'] ?? '') !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Forbidden.']);
        exit;
    }

    $data  = json_decode(file_get_contents('php://input'), true);
    $name  = htmlspecialchars(trim($data['name']  ?? ''));
    $desc  = htmlspecialchars(trim($data['description'] ?? ''));
    $price = filter_var($data['price'] ?? 0, FILTER_VALIDATE_FLOAT);
    $img   = htmlspecialchars(trim($data['image_url'] ?? ''));
    $stock = (int) ($data['stock'] ?? 0);

    if (!$name || $price === false || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Name and valid price are required.']);
        exit;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO products (name, description, price, image_url, stock)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$name, $desc, $price, $img, $stock]);

    echo json_encode([
        'success'    => true,
        'message'    => 'Product added.',
        'product_id' => (int) $pdo->lastInsertId()
    ]);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
