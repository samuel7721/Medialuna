<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "sistema_usuarios"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mensaje='';
// Verificar si se ha enviado el id_ciudadano
if (isset($_POST['id_ciudadano'])) {
    $id_ciudadano = $_POST['id_ciudadano'];

    // Obtener los datos del ciudadano a editar
    $sql = "SELECT nombre, direccion,telefono FROM ciudadanos WHERE id_ciudadano = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_ciudadano);
    $stmt->execute();
    $result = $stmt->get_result();
    $ciudadano = $result->fetch_assoc();
} else {
    $mensaje='No se proporcionó un ID válido.';
    exit;
}

// Verificar si se ha enviado el formulario de actualización
if (isset($_POST['actualizar'])) {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    // Actualizar los datos del ciudadano en la base de datos
    $sql = "UPDATE ciudadanos SET nombre = ?, direccion = ?, telefono = ? WHERE id_ciudadano = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $direccion, $telefono, $id_ciudadano);

    if ($stmt->execute()) {
        $mensaje='Datos actualizados correctamente.';
        // Redirigir a la lista de ciudadanos después de la actualización
        header("Location: lista_ciudadano.php");
        exit; // Asegúrate de salir después de la redirección
    } else {
        $mensaje='Error al actualizar los datos: '. $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ciudadano</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .toast { 
    visibility: hidden;
    min-width: 250px;
    background-color: #f44336; /* Rojo para el mensaje de error */
    color: white;
    text-align: center;
    border-radius: 2px;
    padding: 16px;
    position: fixed;
    z-index: 1;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -600%); /* Centra tanto en el eje X como en el eje Y */
    font-size: 17px;
    opacity: 0;
    transition: opacity 0.5s, visibility 0.5s;
}

.toast.show {
    visibility: visible;
    opacity: 1;
}
    </style>
</head>

<div id="toast" class="toast"></div>
<body>

<form action="editar_ciudadano.php" method="POST">
    <h2>Editar Ciudadano</h2>

    <input type="hidden" name="id_ciudadano" value="<?php echo $id_ciudadano; ?>">

    <label for="nombre">Nombre completo:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($ciudadano['nombre']); ?>" required><br><br>

    <label for="direccion">Dirección:</label>
    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($ciudadano['direccion']); ?>"  required><br><br>

    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" minlength="10" maxlength="10" value="<?php echo htmlspecialchars($ciudadano['telefono']); ?>" required><br><br>

    <button type="submit" name="actualizar">Actualizar</button>
</form>


<button onclick="goBack()">Regresar</button>

<script>
    function goBack() {
        window.location.href = "lista_ciudadano.php";
    }
</script>

<script>
        
        function showToast(message) {
            var toast = document.getElementById("toast");
            toast.textContent = message;
            toast.classList.add("show");

            // Desaparece después de 3 segundos
            setTimeout(function() {
                toast.classList.remove("show");
            }, 3000);
        }

        <?php if ($mensaje): ?>
        showToast("<?php echo $mensaje; ?>");
        <?php endif; ?>
    </script>
</body>
</html>
