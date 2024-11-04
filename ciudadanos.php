<?php
 include('conexion.php');
    $mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $curp = $_POST['curp'];
    $telefono = $_POST['telefono'];

    // Verificar si la CURP ya existe
    $sql_check = "SELECT * FROM ciudadanos WHERE curp = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $curp);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // La CURP ya está registrada
        $mensaje = 'Error: La CURP ya está registrada.';
    } else {
        // Preparar la consulta SQL para insertar
        $sql = "INSERT INTO ciudadanos (nombre, direccion, curp, telefono)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $direccion, $curp, $telefono);

        // Ejecutar la consulta e insertar los datos
        if ($stmt->execute()) {
            $mensaje= 'Registro de ciudadano exitoso'; // Establecer mensaje de éxito
        } else {
            $mensaje= 'Error: '. $stmt->error; // Establecer mensaje de error
        }

        $stmt->close();
    }

    // Cerrar la conexión
    $conn->close();
    
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Ciudadanos</title>
    <style>
        
        body {
         background-color: #f2f6fa;
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 0;
        }

        .header {
         display: flex;
         align-items: center;
         justify-content: space-between;
         padding: 20px;
         background: linear-gradient(to right, #003b8b, #2b98f0);
         color: white;
        }

        .header img {
         width: 80px;
         height: auto;
         margin-right: 20px;
        }

        .header div {
         flex-grow: 1;
         text-align: right;
        }

        .header h1 {
         font-size: 28px;
         margin: 0;
        }

        .header p {
         margin: 5px 0 0;
        }

        .back-button img {
         width: 40px;
         height: 40px;
         cursor: pointer;
        }

        .container {
         display: flex;
         flex-direction: column;
         align-items: center;
         padding-top: 20px;
        }

        .form-container {
         background-color: white;
         padding: 30px;
         width: 400px;
         border-radius: 15px;
         box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
         text-align: left;
        }

        .form-group {
         margin-bottom: 20px;
        }

        .form-group label {
         display: block;
         font-weight: bold;
         margin-bottom: 5px;
         color: #333;
        }

        .form-group input {
         width: 100%;
         padding: 10px;
         border-radius: 8px;
         border: 1px solid #ddd;
         font-size: 16px;
        }

        .button-container {
         display: flex;
         flex-direction: column;
         gap: 10px;
         text-align: center;
        }

        button {
         background-color: #AFC1D0;
         border: none;
         padding: 10px;
         font-size: 16px;
         border-radius: 8px;
         cursor: pointer;
        }


        
    </style>
</head>
<body>
        
         <div class="header">
          <button onclick="goHome()" class="back-button">
              <img src="images/regresar.png" alt="regresar">
                <script>
            function goHome() {
                window.location.href = "home.php"; // Regresa a la página anterior
            }
        </script>
                <img src="images/derechos-de-autor.png" alt="image">
                <div>
                    <h1>Nuevo ciudadano</h1>
                    <p>Registre a un nuevo ciudadano</p>
                </div>
            </div>

         <div class="container">   
        <div class="form-container">
            <form action="ciudadanos.php" method="POST">
                <div class="form-group">
                <label for="nombre">Nombre completo:</label>
                <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>
                </div>
                <div class="form-group">
                <label for="curp">CURP:</label>
                <input type="text" id="curp" name="curp" maxlength="18" minlength="18" style="text-transform:uppercase" required>
                </div>
                <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" required>
                </div>
                <div class="button-container">
                <button type="submit"  class="back-button">REGISTRAR CIUDADANO</button>
                <button type="button" class="button view-button" onclick="window.location.href='lista_ciudadano.php'">VER LISTA DE CIUDADANOS</button>
                </div>
            </form>
        </div>
        </div>

    

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
