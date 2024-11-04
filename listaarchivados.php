<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "sistema_usuarios"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mensaje = '';
$search = ''; // Inicializar la variable $search

// Verificar si se ha enviado una búsqueda
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search = trim($_POST['search']);
    
    // Obtener los ciudadanos que están archivados (estado 'INACTIVO') y coinciden con la búsqueda
    $sql = "SELECT * FROM ciudadanos WHERE estado = 'INACTIVO' AND (nombre LIKE '%$search%' OR curp LIKE '%$search%')";
} else {
    // Obtener los ciudadanos que están archivados (estado 'INACTIVO')
    $sql = "SELECT * FROM ciudadanos WHERE estado = 'INACTIVO'";
}

$result = $conn->query($sql);

// Cerrar la conexión
$conn->close();
?>

<div class="message">
    <?php
    session_start();
    if (isset($_SESSION['mensaje'])) {
        echo $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
    }
    ?>
</div>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Ciudadanos Archivados</title>
    <link rel="stylesheet" href="listas.css">
</head>
<body>
    <h2>Lista de Ciudadanos Archivados</h2>
    
    <!-- Formulario de búsqueda -->
    <form action="" method="POST" class="busqueda">
        <input type="text" name="search" placeholder="Buscar por nombre o CURP" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="button">Buscar</button>
    </form>

    <button onclick="goBack()" class="back-button">Regresar</button> 
    <script>
        function goBack() {
            window.location.href = "lista_ciudadano.php"; 
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
            <th>Estado</th>
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
                echo "<td>" . htmlspecialchars($row['estado']) . "</td>";

                echo "<td>
                    <form action='archivar_ciudadano.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='id_ciudadano' value='" . $row['id_ciudadano'] . "'>
                        <input type='submit' class='button' value='Archivar' onclick='return confirm(\"¿Estás seguro de que quieres archivar este ciudadano?\")'>
                    </form>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No se encontraron registros.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
