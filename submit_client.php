<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.html");
  exit();
}

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conecta a la base de datos
    $servername = "localhost";
    $username = "root"; 
    $password = "Asixdual2024."; 
    $dbname = "proyecto";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Recupera y sanea los datos del formulario
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $email = $conn->real_escape_string($_POST['email']);
    $dni = $conn->real_escape_string($_POST['dni']);

    // Prepara la consulta SQL para insertar los datos en la tabla Clientes
    $sql = $conn->prepare("INSERT INTO Clientes (nombre, apellido, email, dni) VALUES (?, ?, ?, ?)");
    $sql->bind_param("ssss", $nombre, $apellido, $email, $dni);

    if ($sql->execute() === TRUE) {
        echo "Cliente creado correctamente";
    } else {
        echo "Error al crear el cliente: " . $sql->error;
    }

    // Cierra la consulta y la conexión
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
  <link rel="stylesheet" href="style_crearcliente.css">
</head>
<body>
  <header>
    <div class="logo">
      <img src="techmaster_logo.png" alt="Logo de Techmaster">
    </div>
    <h2>Crear Cliente</h2>
  </header>

  <div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required><br>
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" id="apellido" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <label for="dni">DNI:</label>
        <input type="text" name="dni" id="dni" required><br>
        <div class="button-container">
            <input type="submit" value="Crear Cliente">
            <button type="button" class="button-home" onclick="window.location.href = 'inicio.html';">Inicio</button>
        </div>
    </form>
  </div>

  <footer>
    <p>© 2024 Techmaster</p>
  </footer>
</body>
</html>
