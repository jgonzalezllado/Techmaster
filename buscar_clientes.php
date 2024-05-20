<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="test.css">
</head>
<body>

<header>
    <div class="logo">
        <img src="techmaster_logo.png" alt="Logo de TechMaster">
    </div>
    <h1>TechMaster</h1>
</header>

<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
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

// Si se ha enviado el formulario de edición, actualizar la base de datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $dni = isset($_POST['dni']) ? $conn->real_escape_string($_POST['dni']) : '';
    $nombre = isset($_POST['nombre']) ? $conn->real_escape_string($_POST['nombre']) : '';
    $apellido = isset($_POST['apellido']) ? $conn->real_escape_string($_POST['apellido']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';

    if ($_POST['action'] == 'guardar') {
        $stmt = $conn->prepare("UPDATE Clientes SET nombre=?, apellido=?, email=? WHERE dni=?");
        $stmt->bind_param("ssss", $nombre, $apellido, $email, $dni);
        if ($stmt->execute()) {
            echo "Cliente actualizado correctamente";
        } else {
            echo "Error al actualizar el cliente: " . $stmt->error;
        }
        $stmt->close();
    } elseif ($_POST['action'] == 'eliminar') {
        $stmt = $conn->prepare("DELETE FROM Clientes WHERE dni=?");
        $stmt->bind_param("s", $dni);
        if ($stmt->execute()) {
            echo "Cliente eliminado correctamente";
        } else {
            echo "Error al eliminar el cliente: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Paginación
$clientes_por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual > 1) ? ($pagina_actual * $clientes_por_pagina - $clientes_por_pagina) : 0;

// Consulta SQL para obtener los clientes con paginación
$sql = "SELECT SQL_CALC_FOUND_ROWS dni, nombre, apellido, email FROM Clientes LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $inicio, $clientes_por_pagina);
$stmt->execute();
$result = $stmt->get_result();
$total_clientes = $conn->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];
$total_paginas = ceil($total_clientes / $clientes_por_pagina);
?>

<div class="pagination">
    <?php if ($pagina_actual > 1): ?>
        <a href="?pagina=<?php echo $pagina_actual - 1; ?>" class="button">Anterior</a>
    <?php endif; ?>

    <?php if ($pagina_actual < $total_paginas): ?>
        <a href="?pagina=<?php echo $pagina_actual + 1; ?>" class="button button-next">Siguiente</a>
    <?php endif; ?>
</div>

<div class="tabla-container">
    <!-- Tabla de clientes -->
    <table>
        <tr>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <form action='' method='post'>
                        <td><?php echo htmlspecialchars($row["dni"]); ?></td>
                        <td><input type='text' name='nombre' value='<?php echo htmlspecialchars($row["nombre"]); ?>' readonly></td>
                        <td><input type='text' name='apellido' value='<?php echo htmlspecialchars($row["apellido"]); ?>' readonly></td>
                        <td><input type='text' name='email' value='<?php echo htmlspecialchars($row["email"]); ?>' readonly></td>
                        <td class="actions">
                            <input type='hidden' name='dni' value='<?php echo htmlspecialchars($row["dni"]); ?>'>
                            <input type='hidden' name='action' value='guardar'>
                            <input type='submit' value='Guardar' class='button'>
                        </td>
                    </form>
                    <form action='' method='post'>
                        <td class="actions">
                            <input type='hidden' name='dni' value='<?php echo htmlspecialchars($row["dni"]); ?>'>
                            <input type='hidden' name='action' value='eliminar'>
                            <input type='submit' value='Eliminar' class='button'>
                        </td>
                    </form>
                    <td class="actions">
                        <button onclick='habilitarEdicion(this)' class='button'>Editar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No se encontraron clientes.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<footer>
    <p>© 2024 Techmaster</p>
</footer>

<script>
    function habilitarEdicion(btn) {
        var fila = btn.parentNode.parentNode;
        var campos = fila.querySelectorAll('input[type="text"]');
        campos.forEach(function(campo) {
            campo.readOnly = false;
        });
    }
</script>

</body>
</html>
