<?php
// MOSTRAR ERRORES (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Stripe\Stripe;

// ===============================
// 1. Variables de entorno
// ===============================
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load(); // <- importante

$secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? null;

if (!$secretKey) {
    http_response_code(500);
    echo json_encode(['error' => 'Stripe Secret Key no encontrada']);
    exit;
}

Stripe::setApiKey($secretKey);
header('Content-Type: application/json');

// ===============================
// 2. Leer JSON
// ===============================
$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'JSON inválido']);
    exit;
}

$items = isset($data[0]) ? $data : [$data];

// ===============================
// 3. Line items
// ===============================
$line_items = [];

foreach ($items as $item) {
    $name  = $item['nombre'] ?? $item['name'] ?? 'Producto';
    $price = intval($item['precio'] ?? $item['price'] ?? 0);

    if ($price <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Precio inválido']);
        exit;
    }

    $line_items[] = [
        'price_data' => [
            'currency' => 'mxn',
            'product_data' => [
                'name' => $name,
            ],
            'unit_amount' => $price * 100,
        ],
        'quantity' => 1,
    ];
}

// ===============================
// 4. Dominio
// ===============================
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$domain = $protocol . '://' . $_SERVER['HTTP_HOST'];

// ===============================
// 5. Crear sesión
// ===============================
try {
    $session = \Stripe\Checkout\Session::create([
        'mode' => 'payment',
        'line_items' => $line_items,
        'success_url' => $domain . '/proyecto4/success.html',
        'cancel_url'  => $domain . '/proyecto4/cancel.html',
    ]);

    echo json_encode(['id' => $session->id]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
