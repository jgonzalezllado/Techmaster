<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root"; 
    $password = "Asixdual2024."; 
    $dbname = "proyecto";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $apellido = $conn->real_escape_string(trim($_POST['apellido']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $dni = $conn->real_escape_string(trim($_POST['dni']));

    $sql = $conn->prepare("INSERT INTO clientes (nombre, apellido, email, dni) VALUES (?, ?, ?, ?)");
    $sql->bind_param("ssss", $nombre, $apellido, $email, $dni);

    if ($sql->execute() === TRUE) {
        $message = "Cliente creado correctamente";
    } else {
        $message = "Error al crear el cliente: " . $sql->error;
    }

    $sql->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Taller de Reparaciones - Crear Cliente</title>
  <link rel="stylesheet" href="cliente.css">
</head>
<body>
  <header>
    <div class="logo">
      <img src="techmaster_logo.png" alt="Logo de Techmaster">
    </div>
    <h2>Crear Cliente</h2>
  </header>

  <main class="container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" id="apellido" required>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="dni">DNI:</label>
        <input type="text" name="dni" id="dni" required>
        
        <div class="button-container">
            
            <button type="button" class="button-home" onclick="window.location.href = 'inicio.html';">Inicio</button>
            <input type="submit" value="Crear Cliente">
        </div>
    </form>
  </main>

  <footer>
    <p>© 2024 Techmaster</p>
  </footer>

  <div class="message-container">
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
  </div>
</body>
</html>
