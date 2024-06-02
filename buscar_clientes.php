<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Verificación de la conexión segura
$servername = "localhost";
$username = "root";
$password = "Asixdual2024.";
$dbname = "proyecto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializar $result para evitar errores de "undefined variable"
$result = null;

// Manejo de búsqueda por DNI
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    // Prevenir inyección SQL
    $search_dni = $conn->real_escape_string($_POST['search_dni']);
    $sql = "SELECT dni, nombre, apellido, email FROM clientes WHERE dni=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $search_dni);
    $stmt->execute();
    $result = $stmt->get_result();

    // Liberar resultados de la búsqueda
    $stmt->close();
}

// Manejo de la edición de cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'guardar') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE clientes SET nombre=?, apellido=?, email=? WHERE dni=?");
    $stmt->bind_param("ssss", $nombre, $apellido, $email, $dni);
    if ($stmt->execute()) {
        echo "Cliente actualizado correctamente";
    } else {
        echo "Error al actualizar el cliente: " . $stmt->error;
    }
    $stmt->close();
}

// Manejo de la eliminación de cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'eliminar') {
    $dni = $_POST['dni'];

    $stmt = $conn->prepare("DELETE FROM clientes WHERE dni=?");
    $stmt->bind_param("s", $dni);
    if ($stmt->execute()) {
        echo "Cliente eliminado correctamente";
    } else {
        echo "Error al eliminar el cliente: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="buscarcliente.css">
</head>
<body>

<header>
    <div class="logo">
        <img src="techmaster_logo.png" alt="Logo de TechMaster">
    </div>
    <h1>Techmaster</h1>
</header>

<main>
    <form method="post" action="">
        <label for="search_dni">Buscar por DNI:</label>
        <input type="text" id="search_dni" name="search_dni">
        <button type="button" onclick="window.location.href='inicio.html';" class="inicio-button">Inicio</button>
        <button type="submit" name="search">Buscar</button>
        
    </form>

    <?php if (isset($result) && $result->num_rows > 0): ?>
        <div class="tabla-container">
            <!-- Tabla de clientes -->
            <table>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Acciones</th> <!-- Mover la columna de acciones a la cabecera -->
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td style="background-color: #fff;"><?php echo htmlspecialchars($row["dni"]); ?></td>
                        <td style="background-color: #fff;">
                            <form action='' method='post' style="display: inline;">
                                <input type='text' name='nombre' value='<?php echo htmlspecialchars($row["nombre"]); ?>'>
                        </td>
                        <td style="background-color: #fff;">
                            <input type='text' name='apellido' value='<?php echo htmlspecialchars($row["apellido"]); ?>'>
                        </td>
                        <td style="background-color: #fff;">
                            <input type='text' name='email' value='<?php echo htmlspecialchars($row["email"]); ?>'>
                        </td>
                        <td class="actions" style="background-color: #fff;">
                                <input type='hidden' name='dni' value='<?php echo htmlspecialchars($row["dni"]); ?>'>
                                <input type='hidden' name='action' value='guardar'>
                                <input type='submit' value='Guardar' class='button'>
                            </form>
                            <form action='' method='post' style="display: inline;">
                                <input type='hidden' name='dni' value='<?php echo htmlspecialchars($row["dni"]); ?>'>
                                <input type='hidden' name='action' value='eliminar'>
                                <input type='submit' value='Eliminar' class='button'>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    <?php endif; ?>
</main>


<footer>
    <p>© 2024 Techmaster</p>
</footer>

</body>
</html>
