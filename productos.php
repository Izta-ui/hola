<?php
// Conexión a la base de datos
$servername = "localhost"; // o tu servidor
$username = "root";        // tu usuario
$password = "";            // tu contraseña
$dbname = "tienda";        // tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Array con todos los productos
$productos = [
    ["nombre" => "Elefante", "precio" => 450, "descripcion" => "Blusa bordada a mano con motivos oaxaqueños.", "imagen" => "alebrijes/A1.png"],
    ["nombre" => "Ardilla", "precio" => 380, "descripcion" => "Vestido hecho a mano, ideal para ocasiones especiales.", "imagen" => "alebrijes/A2.jpg"],
    ["nombre" => "Lobo", "precio" => 500, "descripcion" => "Blusa bordada a mano con motivos oaxaqueños.", "imagen" => "alebrijes/A3.webp"],
    ["nombre" => "Ajolote", "precio" => 700, "descripcion" => "Figura artesanal de madera pintada a mano.", "imagen" => "alebrijes/A4.jfif"],
    ["nombre" => "Lagarto", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/A5.jpg"],
    ["nombre" => "Iguana", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/A6.jpg"],
    ["nombre" => "Ajolote", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/A7.jpg"],
    ["nombre" => "Perezoso", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/A8.jpg"],
    ["nombre" => "Oso", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/A9.jpg"],
    ["nombre" => "Ratón", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/A10.jpg"],
    ["nombre" => "Colibri", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/7ca8000d-215b-4560-8642-22cbbcdaa85c.jpg"],
    ["nombre" => "Elefante", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/A1.png"],
    ["nombre" => "Ardilla", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/A2.jpg"],
    ["nombre" => "Lobo", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/A3.webp"],
    ["nombre" => "Ajolote", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/A4.jfif"],
    ["nombre" => "Perezoso", "precio" => 420, "descripcion" => "Blusa artesanal con bordados florales.", "imagen" => "alebrijes/A8.jpg"]
];

// Insertar productos en la base de datos
foreach ($productos as $producto) {
    $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, descripcion, imagen) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $producto['nombre'], $producto['precio'], $producto['descripcion'], $producto['imagen']);
    $stmt->execute();
}

// Mensaje de éxito
echo "Productos agregados correctamente.";

$conn->close();
?>
