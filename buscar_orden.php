<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

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
    // Recupera el DNI del cliente del formulario si está disponible
    $dni_cliente = isset($_POST['dni_cliente']) ? $_POST['dni_cliente'] : "";

    // Consulta SQL para obtener los tickets del cliente si se ha proporcionado el DNI
    if (!empty($dni_cliente)) {
        $sql_tickets_cliente = "SELECT t.id, t.fecha, t.comentarios, t.estado, t.modelo_reparar, CONCAT(tr.nombre, ' ', tr.apellido) AS nombre_trabajador 
                                FROM tickets t
                                INNER JOIN trabajadores tr ON t.trabajador_id = tr.trabajador_id
                                INNER JOIN Clientes c ON t.cliente_id = c.id
                                WHERE c.dni = ?";
        $stmt = $conn->prepare($sql_tickets_cliente);
        $stmt->bind_param("s", $dni_cliente);
        $stmt->execute();
        $result_tickets_cliente = $stmt->get_result();
    }

    // Actualiza el estado del ticket si se ha enviado el formulario de estado
    if (isset($_POST['estado'])) {
        $ticket_id = $_POST['id_ticket'];
        $nuevo_estado = $_POST['estado'];
        $sql_actualizar_estado = "UPDATE tickets SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_actualizar_estado);
        $stmt->bind_param("si", $nuevo_estado, $ticket_id);
        $stmt->execute();
        // Recarga la página para reflejar los cambios
        header("Refresh:0");
    }

    // Asigna el ticket a un trabajador si se ha enviado el formulario de asignación
    if (isset($_POST['trabajador'])) {
        $ticket_id = $_POST['id_ticket'];
        $trabajador_id = $_POST['trabajador'];
        $sql_asignar_trabajador = "UPDATE tickets SET trabajador_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_asignar_trabajador);
        $stmt->bind_param("ii", $trabajador_id, $ticket_id);
        $stmt->execute();
        // Recarga la página para reflejar los cambios
        header("Refresh:0");
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
  <title>Consulta de Tickets por Cliente</title>
  <link rel="stylesheet" href="orden.css">
</head>
<body>
  <header>
    <div class="logo">
      <img src="techmaster_logo.png" alt="Logo de Techmaster">
    </div>
    <h1>Consulta de Tickets por Cliente</h>
  </header>

  <main>
    <div class="form-container">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
              echo "<td>" . htmlspecialchars($row['id']) . "</td>";
              echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
              echo "<td>" . htmlspecialchars($row['comentarios']) . "</td>";
              echo "<td>";
              // Menú desplegable para seleccionar el estado
              echo "<form action='' method='post'>";
              echo "<input type='hidden' name='id_ticket' value='" . htmlspecialchars($row['id']) . "'>";
              echo "<select name='estado'>";
              echo "<option value='Recibido'" . ($row['estado'] == 'Recibido' ? ' selected' : '') . ">Recibido</option>";
              echo "<option value='En reparación'" . ($row['estado'] == 'En reparación' ? ' selected' : '') . ">En reparación</option>";
              echo "<option value='Cancelado'" . ($row['estado'] == 'Cancelado' ? ' selected' : '') . ">Cancelado</option>";
              echo "<option value='Finalizado'" . ($row['estado'] == 'Finalizado' ? ' selected' : '') . ">Finalizado</option>";
              echo "</select>";
              echo "<input type='submit' value='Guardar'>";
              echo "</form>";
              echo "</td>";
              echo "<td>" . htmlspecialchars($row['modelo_reparar']) . "</td>";
              echo "<td>";
              // Menú desplegable para seleccionar el trabajador asignado
              echo "<form action='' method='post'>";
              echo "<input type='hidden' name='id_ticket' value='" . htmlspecialchars($row['id']) . "'>";
              echo "<select name='trabajador'>";
              // Itera sobre los trabajadores para generar opciones
              if ($result_trabajadores->num_rows > 0)
              {
                while ($trabajador = $result_trabajadores->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($trabajador['trabajador_id']) . "'";
                    // Marca como seleccionado si el trabajador coincide
                    if ($row['nombre_trabajador'] == ($trabajador['nombre'] . ' ' . $trabajador['apellido'])) {
                        echo " selected";
                    }
                    echo ">" . htmlspecialchars($trabajador['nombre'] . ' ' . $trabajador['apellido']) . "</option>";
                }
            }
            echo "</select>";
            echo "<input type='submit' value='Guardar'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
            }
            echo "</table>";
            } else {
            echo "No se encontraron tickets para el cliente con DNI: " . htmlspecialchars($dni_cliente);
            }
            ?>
            </div>
            <?php endif; ?>
            </main>
            
            <footer>
            <p>© 2024 Techmaster</p>
            </footer>
            </body>
            </html>
            