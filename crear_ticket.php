<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Reemplaza con tu nombre de usuario de MySQL
$password = "Asixdual2024."; // Reemplaza con tu contraseña de MySQL
$dbname = "proyecto";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener los trabajadores
$sql_trabajadores = "SELECT trabajador_id, nombre, apellido FROM trabajadores";
$result_trabajadores = $conn->query($sql_trabajadores);

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera los datos del formulario
    $fecha = $_POST['fecha'];
    $comentarios = $_POST['comentarios'];
    $estado = $_POST['estado'];
    $trabajador_id = $_POST['trabajador_id'];
    $modelo_reparar = $_POST['modelo_reparar'];
    $dni_cliente = $_POST['dni_cliente'];

    // Consulta SQL para obtener el ID del cliente usando el DNI
    $sql_get_cliente_id = "SELECT id FROM Clientes WHERE dni = '$dni_cliente'";
    $result_get_cliente_id = $conn->query($sql_get_cliente_id);

    // Verifica si se encontró el cliente
    if ($result_get_cliente_id->num_rows > 0) {
        $row = $result_get_cliente_id->fetch_assoc();
        $cliente_id = $row['id'];

        // Consulta SQL para insertar los datos en la tabla Tickets
        $sql_insert_ticket = "INSERT INTO tickets (fecha, comentarios, estado, trabajador_id, modelo_reparar, cliente_id) VALUES ('$fecha', '$comentarios', '$estado', '$trabajador_id', '$modelo_reparar', '$cliente_id')";

        // Realiza la inserción del ticket
        if ($conn->query($sql_insert_ticket) === TRUE) {
            // Incrementa el contador de tickets para el cliente
            $sql_update_cliente = "UPDATE Clientes SET num_tickets = num_tickets + 1 WHERE id = '$cliente_id'";
            $conn->query($sql_update_cliente);
            echo "Ticket creado correctamente";
        } else {
            echo "Error al crear el ticket: " . $conn->error;
        }
    } else {
        echo "Cliente no encontrado";
    }
}

// Cierra la conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Taller de Reparaciones</title>
  <link rel="stylesheet" href="style_ticket.css">
</head>
<body>
  <header>
    <div class="logo">
      <img src="techmaster_logo.png" alt="Logo de Techmaster">
    </div>
    <h2>Crear Ticket</h2>
  </header>

  <main>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
      <label for="dni_cliente">DNI del cliente:</label>
      <input type="text" name="dni_cliente" id="dni_cliente" required><br>
      
      <label for="modelo_reparar">Modelo a reparar:</label>
      <input type="text" name="modelo_reparar" id="modelo_reparar" required><br>
      
      <label for="comentarios">Comentarios:</label>
      <input type="text" name="comentarios" id="comentarios" required><br>
      
      <label for="trabajador_id">Asignar a trabajador:</label>
      <select name="trabajador_id" id="trabajador_id">
          <?php
          // Genera las opciones del desplegable con los trabajadores
          if ($result_trabajadores->num_rows > 0) {
              while ($row = $result_trabajadores->fetch_assoc()) {
                  echo "<option value='" . $row['trabajador_id'] . "'>" . $row['trabajador_id'] . ": " . $row['nombre'] . " " . $row['apellido'] . "</option>";
              }
          }
          ?>
      </select><br>
      
      <label for="estado">Estado:</label>
      <select name="estado" id="estado">
          <option value="recibido">Recibido</option>
          <option value="asignado">Asignado a técnico</option>
          <option value="finalizado">Finalizado</option>
      </select><br>
      
      <label for="fecha">Fecha:</label>
      <input type="date" name="fecha" id="fecha" required><br>
      
      <div class="button-container">
        <button type="button" class="button" onclick="window.location.href = 'inicio.html';">Inicio</button>
        <input type="submit" value="Crear Ticket" class="button">
      </div>
    </form>
  </main>
  
  <footer>
    <p>© 2024 Techmaster</p>
  </footer>
</body>
</html>
