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

  // Verificar si es un trabajador
  $sql_trabajador = "SELECT trabajador_id FROM trabajadores WHERE dni = ? AND password = ?";
  $stmt_trabajador = $conn->prepare($sql_trabajador);
  $stmt_trabajador->bind_param("ss", $user, $pass);
  $stmt_trabajador->execute();
  $stmt_trabajador->store_result();

  if ($stmt_trabajador->num_rows > 0) {
    $_SESSION['username'] = $user;
    $stmt_trabajador->close();
    header("Location: inicio.html");
  } else {
    // Verificar si es un administrador
    $sql_admin = "SELECT administrador_id FROM administradores WHERE dni = ? AND password = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("ss", $user, $pass);
    $stmt_admin->execute();
    $stmt_admin->store_result();

    if ($stmt_admin->num_rows > 0) {
      $_SESSION['username'] = $user;
      $stmt_admin->close();
      header("Location: inicio2.html");
    } else {
      echo "Usuario o contraseña incorrectos";
    }

    $stmt_admin->close();
  }

  $stmt_trabajador->close();
}

$conn->close();
?>