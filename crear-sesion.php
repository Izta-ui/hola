<?php
// Mostrar errores (solo desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Stripe\Stripe;

// ===============================
// 1. Variables de entorno
// ===============================
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? null;

if (!$secretKey) {
    http_response_code(500);
    echo json_encode(['error' => 'Stripe Secret Key no encontrada']);
    exit;
}

Stripe::setApiKey($secretKey);

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

// ===============================
// 3. Datos del producto
// ===============================
$nombre = $data['nombre'] ?? 'Producto';
$precio = intval($data['precio'] ?? 0);

if ($precio <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Precio inválido']);
    exit;
}

// ===============================
// 4. Dominio
// ===============================
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$domain = $protocol . '://' . $_SERVER['HTTP_HOST'];

// ===============================
// 5. Crear sesión de Stripe
// ===============================
try {
    $session = \Stripe\Checkout\Session::create([
        'mode' => 'payment',
        'line_items' => [[
            'price_data' => [
                'currency' => 'mxn',
                'product_data' => [
                    'name' => $nombre,
                ],
                'unit_amount' => $precio * 100,
            ],
            'quantity' => 1,
        ]],
        'success_url' => $domain . '/proyecto4/success.html',
        'cancel_url'  => $domain . '/proyecto4/cancel.html',
    ]);

    echo json_encode(['id' => $session->id]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
