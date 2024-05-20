<?php
// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conecta a la base de datos
    $servername = "localhost";
    $username = "root"; // Reemplaza con tu nombre de usuario de MySQL
    $password = "Asixdual2024."; // Reemplaza con tu contraseña de MySQL
    $dbname = "proyecto";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Recupera los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $dni = $_POST['dni'];

    // Prepara la consulta SQL para insertar los datos en la tabla Trabajadores
    $sql = "INSERT INTO Trabajadores (nombre, apellido, email, dni) VALUES ('$nombre', '$apellido', '$email', '$dni')";

    if ($conn->query($sql) === TRUE) {
        echo "Trabajador creado correctamente";
    } else {
        echo "Error al crear el trabajador: " . $conn->error;
    }

    // Cierra la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Taller de Reparaciones</title>
  <link rel="stylesheet" href="style_creartrabajador.css">
</head>
<body>
  <header>
    <div class="logo">
      <img src="techmaster_logo.png" alt="Logo de Techmaster">
    </div>
<body>
    <h2>Crear Trabajador</h2>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required><br>
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" id="apellido" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <label for="dni">DNI:</label>
        <input type="text" name="dni" id="dni" required><br>
        <input type="submit" value="Crear Trabajador">
    </form>
</body>
</html>
