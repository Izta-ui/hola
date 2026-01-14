<?php
// Mostrar errores (solo desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
use Stripe\Stripe;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? null;
if (!$secretKey) {
    http_response_code(500);
    echo json_encode(['error' => 'Stripe Secret Key no encontrada']);
    exit;
}

Stripe::setApiKey($secretKey);
header('Content-Type: application/json');

// Leer JSON del body
$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (!$data || !is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'JSON inválido']);
    exit;
}

// Crear line items para Stripe
$line_items = [];
foreach ($data as $item) {
    $name = $item['nombre'] ?? 'Producto';
    $price = intval($item['precio'] ?? 0);
    $quantity = intval($item['cantidad'] ?? 1);

    if ($price <= 0) continue;

    $line_items[] = [
        'price_data' => [
            'currency' => 'mxn',
            'product_data' => ['name' => $name],
            'unit_amount' => $price * 100,
        ],
        'quantity' => $quantity,
    ];
}

if (empty($line_items)) {
    http_response_code(400);
    echo json_encode(['error' => 'No hay productos válidos']);
    exit;
}

// Dominio dinámico
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$domain = $protocol . '://' . $_SERVER['HTTP_HOST'];

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
    echo json_encode(['error' => $e->getMessage()]);
}
