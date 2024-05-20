<?php
session_start();

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
  $user = $_POST['username'];
  $pass = $_POST['password'];

  // Consulta para verificar usuario y contraseña
  $sql = "SELECT trabajador_id FROM trabajadores WHERE dni = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $user, $pass);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $_SESSION['username'] = $user;
    header("Location: inicio.html");
  } else {
    echo "Usuario o contraseña incorrectos";
  }

  $stmt->close();
}

$conn->close();
?>
