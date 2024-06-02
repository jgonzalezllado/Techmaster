<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "Asixdual2024.";
$dbname = "proyecto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $trabajador_id = $_POST['trabajador_id'];
    $password = $_POST['password'];

    $sql = "INSERT INTO trabajadores (dni, nombre, apellido, email, trabajador_id, password) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $dni, $nombre, $apellido, $email, $trabajador_id, $password);

    if ($stmt->execute()) {
        echo "Trabajador añadido con éxito";
    } else {
        echo "Error al añadir trabajador: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Trabajador</title>
    <link rel="stylesheet" href="estilotrabajador.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="techmaster_logo.png" alt="Logo de Techmaster">
        </div>
        <h2>Añadir trabajador</h2>
    </header>
    <main>
        <form method="POST" action="administrador.php">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" required>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="trabajador_id">ID de Trabajador:</label>
            <input type="text" id="trabajador_id" name="trabajador_id" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Añadir Trabajador</button>
        </form>
    </main>
    <footer>
        <p>© 2024 Techmaster</p>
    </footer>
</body>
</html>
