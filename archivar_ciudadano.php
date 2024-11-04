<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "sistema_usuarios"; 

$conn = new mysqli($servername, $username, $password, $dbname);

$mensaje = '';
// Verificar si se ha enviado el id_ciudadano
if (isset($_POST['id_ciudadano'])) {
    $id_ciudadano = $_POST['id_ciudadano'];

    // Cambiar el estado del ciudadano a 'INACTIVO'
    $sql = "UPDATE ciudadanos SET estado = 'INACTIVO' WHERE id_ciudadano = ?";
    $stmt = $conn->prepare($sql);

    // Verificar si prepare() fue exitoso
    if ($stmt === false) {
        die('Error en prepare(): ' . htmlspecialchars($conn->error));
    }

    // Vincular parámetros
    $stmt->bind_param("i", $id_ciudadano);

    if ($stmt->execute()) {
        session_start();
        $mensaje = 'Ciudadano archivado correctamente.';
    } else {
        session_start();
        $mensaje = 'Error al archivar el ciudadano: ' . $stmt->error;
    }

    // Redirigir a la lista de ciudadanos archivados
    header("Location: listaarchivados.php");
    exit;
}


$conn->close();
?>
