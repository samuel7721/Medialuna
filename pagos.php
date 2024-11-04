<?php

include 'conexion.php';
$mensaje='';
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $id_ciudadano = $_POST['id_ciudadano'];
    $estatus_pago = $_POST['estatus_pago'];
    $monto_cuota = $_POST['monto_cuota'];
    $fecha_pago = $_POST['fecha_pago'];
    $metodo_pago = $_POST['metodo_pago'];
    $tipo_pago = $_POST['tipo_pago']; // Convertir el array en una cadena
    $comentarios = $_POST['comentarios'];
    $anio = $_POST['anio'];
    $fecha_registro = $_POST['fecha_registro'];

    // Preparar la consulta SQL para insertar los datos
    $sql = "INSERT INTO pagos (id_ciudadano, estatus_pago, monto_cuota, fecha_pago, metodo_pago, tipo_pago, comentarios, anio, fecha_registro) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la declaración
    if ($stmt = $conn->prepare($sql)) {
        // Vincular parámetros
        $stmt->bind_param("issssssss", $id_ciudadano, $estatus_pago, $monto_cuota, $fecha_pago, $metodo_pago, $tipo_pago, $comentarios, $anio, $fecha_registro);

        // Ejecutar la declaración
        if ($stmt->execute()) {
            $mensaje='Registro guardado con éxito.';
        } else {
            $mensaje='Error al guardar el registro: ' . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        $mensaje='Error al preparar la declaración: ' . $conn->error;
    }

  
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Pagos de Cuotas por Año y Calle</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<div id="toast" class="toast"></div>
<button onclick="goBack()" class="back-button">Regresar</button> 
    
        <script>
            function goBack() {
                window.history.back(); // Regresa a la página anterior
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
        showToast("<?php echo addslashes($mensaje); ?>");
    <?php endif; ?>
</script>


    <form action="pagos.php" method="POST">
    <h2>Registrar Pagos de Cuotas</h2>

        <?php
        // Realiza la consulta para obtener la información de los ciudadanos
        $sql = "SELECT id_ciudadano, curp, nombre FROM ciudadanos WHERE estado='ACTIVO'";
        $resultado = $conn->query($sql);

        if (!$resultado) {
            die("Error en la consulta: " . $conn->error);
        }
        ?>
        <label for="id_ciudadano">Ciudadano:</label>
        <select id="id_ciudadano" name="id_ciudadano" required>
            <option value="" disabled selected>Selecciona un ciudadano</option>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <option value="<?php echo $row['id_ciudadano']; ?>">
                    <?php echo $row['curp'] . ' - ' . $row['nombre']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="estatus_pago">Estatus de Pago:</label>
        <select id="estatus_pago" name="estatus_pago" required>
            <option value="pendiente">Pendiente</option>
            <option value="pagado">Pagado</option>
        </select>
        <br><br>

        <label for="monto_cuota">Monto de la Cuota:</label>
        <input type="number" id="monto_cuota" name="monto_cuota" step="0.01" required>
        <br><br>

        <label for="fecha_pago">Fecha de Pago:</label>
        <input type="date" id="fecha_pago" name="fecha_pago" required>
        <br><br>

        <label for="metodo_pago">Método de Pago:</label>
        <input type="text" id="metodo_pago" name="metodo_pago" value="efectivo" readonly>
        <br><br>

        <!-- Tipo de pago (puede seleccionar múltiples opciones) -->
        <label for="tipo_pago">Tipo de Pago:</label>
        <select id="tipo_pago" name="tipo_pago" required>
            <option value="ANUALIDAD">ANUALIDAD</option>
            <option value="FAENAS">FAENAS</option>
            <option value="DESASOLVE">DESASOLVE</option>
            <option value="APORTACION_EXTRAORDINARIA">APORTACION EXTRAORDINARIA</option>
        </select>
        <br><br>

        <label for="anio">Año:</label>
        <input type="number" id="anio" name="anio" required>
        <br><br>

        <label for="comentarios">Comentarios:</label>
        <textarea id="comentarios" name="comentarios" rows="4" cols="50"></textarea>
        <br><br>

        <input type="hidden" name="fecha_registro" value="<?php echo date('Y-m-d H:i:s'); ?>">

        <button type="submit" name="Registrar_Pago" class="back_button">Registrar Pago</button>

    </form>
    <?php

  // Cerrar la conexión
  $conn->close();
  exit; // Terminar el script aquí después de procesar el formulario
?>

</body>
</html>
