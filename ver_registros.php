<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';

// Inicializar variable para la búsqueda
$search = "";
// Verificar si se ha enviado el formulario de búsqueda
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search'];
}

// Consulta para obtener los registros de pagos
$sql = "SELECT p.*, c.curp, c.nombre 
        FROM pagos p 
        JOIN ciudadanos c ON p.id_ciudadano = c.id_ciudadano 
        WHERE p.estatus_pago = 'PAGADO' ";

// Si hay un término de búsqueda, agregar condición a la consulta
if (!empty($search)) {
    $sql .= " AND (c.curp LIKE ? OR c.nombre LIKE ? OR p.estatus_pago LIKE ?)";
}

// Preparar y ejecutar la consulta
if ($stmt = $conn->prepare($sql)) {
    if (!empty($search)) {
        $searchTerm = "%" . $search . "%";
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    }
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    die("Error al preparar la consulta: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Pagos</title>
    <link rel="stylesheet" href="pago.css">
    <style>
        /* Estilo para el botón Regresar */
        .back-button {
            padding: 12px 30px;
            background-color: #4CAF50; /* Verde */
            color: white;
            border: none;
            border-radius: 30px;
            margin-left: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease, transform 0.2s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            background-color: #45a049; /* Verde oscuro */
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .back-button:active {
            transform: translateY(0);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <button onclick="goBack()" class="back-button">Regresar</button> 
    <script>
        function goBack() {
            window.history.back(); // Regresa a la página anterior
        }
    </script>
    <div class="container">
        <h1>Registros de Pagos</h1>

        <!-- Formulario de búsqueda -->
        <form action="ver_registros.php" method="POST">
            <input type="text" name="search" placeholder="Buscar por Nombre o CURP" value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Buscar">
        </form>
    </div>
    <!-- Tabla de registros -->
    <table>
        <thead>
            <tr>
                <th>CURP</th>
                <th>Nombre del Ciudadano</th>
                <th>Estatus de Pago</th>
                <th>Monto de la Cuota</th>
                <th>Fecha de Pago</th>
                <th>Método de Pago</th>
                <th>Tipo de Pago</th>
                <th>Comentarios</th>
                <th>Año</th>
                <th>Fecha de Registro</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar los resultados en la tabla
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['curp']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estatus_pago']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['monto_cuota']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fecha_pago']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['metodo_pago']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tipo_pago']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['comentarios']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['anio']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fecha_registro']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No se encontraron registros.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
