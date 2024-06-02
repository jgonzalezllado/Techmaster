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

// Verifica si se ha enviado el formulario para modificar el estado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modificar_estado'])) {
    $ticket_id_estado = $_POST['modificar_estado'];
    $nuevo_estado = $_POST['nuevo_estado'];
    // Consulta SQL para actualizar el estado del ticket
    $sql_actualizar_estado = "UPDATE tickets SET estado = '$nuevo_estado' WHERE id = '$ticket_id_estado'";
    if ($conn->query($sql_actualizar_estado) === TRUE) {
        echo "El estado del ticket ha sido modificado correctamente.";
    } else {
        echo "Error al intentar modificar el estado del ticket: " . $conn->error;
    }
}

// Verifica si se ha enviado el formulario para modificar el trabajador
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modificar_trabajador'])) {
    $ticket_id_trabajador = $_POST['modificar_trabajador'];
    $nuevo_trabajador_id = $_POST['nuevo_trabajador'];
    // Consulta SQL para actualizar el trabajador asignado al ticket
    $sql_actualizar_trabajador = "UPDATE tickets SET trabajador_id = '$nuevo_trabajador_id' WHERE id = '$ticket_id_trabajador'";
    if ($conn->query($sql_actualizar_trabajador) === TRUE) {
        echo "El trabajador asignado al ticket ha sido modificado correctamente.";
    } else {
        echo "Error al intentar modificar el trabajador asignado al ticket: " . $conn->error;
    }
}

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['modificar_estado']) && !isset($_POST['modificar_trabajador'])) {
    // Recupera el DNI del cliente del formulario si está disponible
    $dni_cliente = isset($_POST['dni_cliente']) ? $_POST['dni_cliente'] : "";

    // Consulta SQL para obtener los tickets del cliente si se ha proporcionado el DNI
    if (!empty($dni_cliente)) {
        $sql_tickets_cliente = "SELECT t.id, t.fecha, t.comentarios, t.estado, t.modelo_reparar, CONCAT(tr.nombre, ' ', tr.apellido) AS nombre_trabajador, tr.trabajador_id 
                              FROM tickets t
                              INNER JOIN trabajadores tr ON t.trabajador_id = tr.trabajador_id
                              INNER JOIN clientes c ON t.cliente_id = c.id
                              WHERE c.dni = '$dni_cliente'";
        $result_tickets_cliente = $conn->query($sql_tickets_cliente);

        if ($result_tickets_cliente === false) {
            die("Error en la consulta SQL: " . $conn->error);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consulta de Tickets por Cliente</title>
  <link rel="stylesheet" href="cssorden.css">
</head>
<body>
  <header>
    <div class="logo">
      <img src="techmaster_logo.png" alt="Logo de Techmaster">
    </div>
    <h2>Consulta de Tickets por Cliente</h2>
  </header>

  <main>
    <div class="form-container">
      <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
          <label for="dni_cliente">Introduce el DNI del cliente:</label>
          <input type="text" name="dni_cliente" id="dni_cliente" required><br>
          <input type="submit" value="Buscar">
      </form>
      <div class="button-wrapper">
        <a href="inicio.html" class="button">Volver a la página principal</a>
      </div>
    </div>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($result_tickets_cliente)): ?>
    <div class="ticket-results">
      <h3>Resultados de la búsqueda:</h3>
      <?php
      // Muestra los resultados de la búsqueda si existen
      if ($result_tickets_cliente->num_rows > 0) {
          echo "<table>";
          echo "<tr><th>ID</th><th>Fecha</th><th>Comentarios</th><th>Estado</th><th>Modelo a reparar</th><th>Trabajador asignado</th></tr>";
          while ($row = $result_tickets_cliente->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['id'] . "</td>";
              echo "<td>" . $row['fecha'] . "</td>";
              echo "<td>" . $row['comentarios'] . "</td>";
              echo "<td>";
              echo "<form action='" . $_SERVER["PHP_SELF"] . "' method='post'>";
              echo "<input type='hidden' name='modificar_estado' value='" . $row['id'] . "'>";
              echo "<select name='nuevo_estado'>";
              // Estados disponibles
              $estados = array('Recibido', 'En reparación', 'Cancelado', 'Finalizado');
              foreach ($estados as $estado) {
                  if ($estado == $row['estado']) {
                      echo "<option value='$estado' selected>$estado</option>";
                  } else {
                      echo "<option value='$estado'>$estado</option>";
                  }
              }
              echo "</select>";
              echo "<input type='submit' value='Modificar'>";
              echo "</form>";
              echo "</td>";
              echo "<td>" . $row['modelo_reparar'] . "</td>";
              echo "<td>";
              echo "<form action='" . $_SERVER["PHP_SELF"] . "' method='post'>";
              echo "<input type='hidden' name='modificar_trabajador' value='" . $row['id'] . "'>";
              echo "<select name='nuevo_trabajador'>";
              // Consulta SQL para obtener los trabajadores disponibles
              $sql_trabajadores = "SELECT trabajador_id, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM trabajadores";
              $result_trabajadores = $conn->query($sql_trabajadores);
              while ($trabajador = $result_trabajadores->fetch_assoc()) {
                  if ($trabajador['trabajador_id'] == $row['trabajador_id']) {
                      echo "<option value='" . $trabajador['trabajador_id'] . "' selected>" . $trabajador['nombre_completo'] . "</option>";
                  } else {
                      echo "<option value='" . $trabajador['trabajador_id'] . "'>" . $trabajador['nombre_completo'] . "</option>";
                  }
              }
              echo "</select>";
              echo "<input type='submit' value='Modificar'>";
              echo "</form>";
              echo "</td>";
              echo "</tr>";
          }
          echo "</table>";
      } else {
          echo "No se encontraron tickets para el cliente con DNI: " . $dni_cliente;
      }
      ?>
    </div>
  <?php endif; ?>

  <footer>
    <p>© 2024 Techmaster</p>
  </footer>
</body>
</html>
