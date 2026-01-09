<?php
require 'vendor/autoload.php';

// ⚠ Usa tu clave secreta REAL (no la publiques en internet)
\Stripe\Stripe::setApiKey('sk_test_51SSfoX0bwADDhpsjVaCzXrac4CubT7Q6gVVQv1jvQzVWUmkxblZbIdLxFJrZ0seJ5XVRAeCnMSTgTAsRSc3xdRwi00IF5238rm"
}');

header('Content-Type: application/json');

try {

    // 1. Leer JSON enviado desde el frontend
    $body = file_get_contents('php://input');
    $items = json_decode($body, true);

    if (!is_array($items)) {
        echo json_encode(['error' => 'JSON inválido']);
        exit;
    }

    // 2. Crear los line_items para Stripe
    $line_items = [];

    foreach ($items as $it) {

        $productName = $it['nombre'] ?? $it['name'] ?? 'Producto';
        $price = intval($it['precio'] ?? $it['price'] ?? 0);

        if ($price <= 0) {
            echo json_encode(['error' => 'Precio inválido']);
            exit;
        }

        $line_items[] = [
            'price_data' => [
                'currency' => 'mxn',
                'product_data' => [
                    'name' => $productName
                ],
                'unit_amount' => $price * 100
            ],
            'quantity' => 1
        ];
    }

    // 3. Obtener dominio automáticamente
    $domain = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];

    // 4. Crear sesión de Stripe
    $session = \Stripe\Checkout\Session::create([
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => $domain . '/proyecto4/success.html',
        'cancel_url' => $domain . '/proyecto4/cancel.html',
    ]);

    // 5. Responder el ID de la sesión
    echo json_encode(['id' => $session->id]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
