<?php
include 'conexion.php'; 
$mensaje='';

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_delegacion = $_POST['id_delegacion'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $encargado = $_POST['encargado'];

    // Si existe un ID, es una edición; si no, es un nuevo registro
    if (!empty($id_delegacion)) {
        // Editar el registro existente
        $sql = "UPDATE delegacion SET nombre='$nombre', direccion='$direccion', telefono='$telefono', email='$email', encargado='$encargado' WHERE id_delegacion='$id_delegacion'";
    } else {
        // Insertar un nuevo registro
        $sql = "INSERT INTO delegacion (nombre, direccion, telefono, email, encargado) VALUES ('$nombre', '$direccion', '$telefono', '$email', '$encargado')";
    }

    if (mysqli_query($conn, $sql)) {
        $mensaje='Registro guardado exitosamente';
    } else {
        $mensaje= 'Error: ' . $sql . "<br>" . mysqli_error($conn);
    }
}

// Si se envía un ID por GET, cargar los datos para editar
if (isset($_GET['id_delegacion'])) {
    $id_delegacion = $_GET['id_delegacion'];
    $sql = "SELECT * FROM delegacion WHERE id_delegacion='$id_delegacion'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Delegación</title>
    <link rel="stylesheet" href="delegacion.css">
</head>
<body>
<div id="toast" class="toast"></div>

<button onclick="goBack()" class="back-button">Regresar</button> 

        <script>
            function goBack() {
                window.location.href = "home.php";
            }
        </script>
<form class="delegacion" action="delegacion.php" method="POST">
<h2>Formulario de Delegación</h2>

    <input type="hidden" name="id_delegacion" value="<?php echo isset($row['id_delegacion']) ? $row['id_delegacion'] : ''; ?>">

    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" value="<?php echo isset($row['nombre']) ? $row['nombre'] : ''; ?>" required><br><br>

    <label for="direccion">Dirección:</label>
    <input type="text" name="direccion" value="<?php echo isset($row['direccion']) ? $row['direccion'] : ''; ?>" required><br><br>

    <label for="telefono">Teléfono:</label>
    <input type="text" name="telefono" value="<?php echo isset($row['telefono']) ? $row['telefono'] : ''; ?>" required><br><br>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" required><br><br>

    <label for="encargado">Cargo:</label>
    <input type="text" name="encargado" value="<?php echo isset($row['encargado']) ? $row['encargado'] : ''; ?>" required><br><br>
<input type="submit" value="Guardar" class="back-button">
    
</form>


<h3>Lista de Delegaciones</h3>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Dirección</th>
        <th>Teléfono</th>
        <th>Email</th>
        <th>Cargo</th>
        <th>Acción</th>
    </tr>

    <?php
    $sql = "SELECT * FROM delegacion";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Mostrar cada registro en la tabla
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id_delegacion'] . "</td>";
            echo "<td>" . $row['nombre'] . "</td>";
            echo "<td>" . $row['direccion'] . "</td>";
            echo "<td>" . $row['telefono'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['encargado'] . "</td>";
            echo "<td>
            <form class='editar' action='delegacion.php' method='get'>
        <input type='hidden' name='id_delegacion' value='" . $row['id_delegacion'] . "' />
        <button type='submit'>Editar</button>
      </form>
            </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No hay delegaciones registradas</td></tr>";
    }

    mysqli_close($conn);
    ?>
</table>

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
