<?php
include('conexion.php');

// Manejar búsqueda
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Usar declaraciones preparadas para evitar inyecciones SQL
$sql = "SELECT * FROM ciudadanos WHERE estado='ACTIVO' ";
if ($search) {
    $sql .= " AND (nombre LIKE ? OR curp LIKE ?)";
}

$stmt = $conn->prepare($sql);

if ($search) {
    $param = '%' . $search . '%';
    $stmt->bind_param('ss', $param, $param); // Dos parámetros para nombre y curp
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Ciudadanos</title>
    <link rel="stylesheet" href="listas.css">
</head>
<body>
    <h2>Lista de Ciudadanos</h2>
    
    <!-- Formulario de búsqueda -->
    <form action="" method="POST" class="busqueda">
        <input type="text" name="search" placeholder="Buscar por nombre o CURP" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="button">Buscar</button>
    </form>

    <button onclick="goBack()" class="back-button">Regresar</button> 
    <script>
        function goBack() {
            window.location.href = "home.php"; 
        }
    </script>

    <!-- Tabla de ciudadanos -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>CURP</th>
            <th>Teléfono</th>
            <th>Fecha de Registro</th>
            <th>Acciones</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id_ciudadano'] . "</td>";
                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($row['direccion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['curp']) . "</td>";
                echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
                echo "<td>" . $row['fecha_registro'] . "</td>";
                echo "<td>
                    <form action='editar_ciudadano.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='id_ciudadano' value='" . $row['id_ciudadano'] . "'>
                        <input type='submit' class='button' value='Editar'>
                    </form>
                    
                    <form action='archivar_ciudadano.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='id_ciudadano' value='" . $row['id_ciudadano'] . "'>
                        <input type='submit' class='button' value='Archivar' onclick='return confirm(\"¿Estás seguro de que quieres archivar este ciudadano?\")'>
                    </form>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No se encontraron registros.</td></tr>";
        }
        ?>
    </table>
    
    <div class="message">
        <?php
        // Mostrar mensajes de éxito o error
        if (isset($_SESSION['mensaje'])) {
            echo $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        }
        ?>
    </div>

    <button onclick="goArc()" class="back-button">Archivados</button> 
    <script>
        function goArc() {
            window.location.href = "listaarchivados.php";
        }
    </script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
