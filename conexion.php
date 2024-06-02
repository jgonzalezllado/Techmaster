<?php
$servername = "localhost:3306";
$username = "julia";
$password = "Asixdual2024.";
$dbname = "proyecto";

// Crear conexión
$conn= new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

echo "Conexión exitosa"; // Verificación de conexión

// Recibir datos del formulario
$nombre=$_POST['nombre'];
$apellido=$_POST['apellido'];
$email=$_POST['email'];
$dni=$_POST['dni'];

// Validar datos
if (empty($nombre) || empty($apellido) || empty($email) || empty($dni)) {
    die("Error: Por favor, complete todos los campos del formulario.");
}

// Mostrar valores de las variables
echo "Valores: Nombre=$nombre, Apellido=$apellido, Email=$email, DNI=$dni";
//mysqli_error($conn)
// Insertar datos en la tabla
//$stmt = $conn->prepare("INSERT INTO `proyecto.trabajadores` VALUES (?,?,?,?)");
$consulta = "INSERT INTO `proyecto.trabajadores`(nombre,apellido,email,dni)  VALUES ('$nombre','$apellido','$email','$dni')";
$resultado =mysqli_query($conn,$consulta);
if($resultado){
echo " ok";
} else {
echo "no";
}

//echo "despues de insertar";
//$stmt->bind_param("ssss",$nombre,$apellido,$email,$dni);
//echo "antes de execute";
//$stmt->execute();
//echo $stmt->errorno;

echo "despues de execute";
// Ejecutar consulta
//if ($conn->query($sql) === TRUE) {
  // echo "Datos insertados correctamente";
//} else {
  //  echo "Error al insertar datos: " . mysqli_error($conn);;
//}
//$stmt->close();
$conn->close();
?>
