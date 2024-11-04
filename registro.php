<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
include('conexion.php');

$mensaje='';
// Recoge y limpia los datos del formulario
$nombre = trim($_POST['nombre']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    // Insertar el nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre, email, password, role) VALUES (?, ?, ?, ?)";
    $role = 'admin'; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss',$nombre, $email, $password, $role);
    $stmt->execute();

    

    if ($stmt->affected_rows > 0) {
        $mensaje='Registro exitoso. Ahora puedes iniciar sesión.';
    } else {
        $mensaje= 'Error en el registro.';
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="login.css">
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
    transform: translate(-50%, -580%); /* Centra tanto en el eje X como en el eje Y */
    font-size: 17px;
    opacity: 0;
    transition: opacity 0.5s, visibility 0.5s;
}

.toast.show {
    visibility: visible;
    opacity: 1;
}
        /* Estilos para los campos de entrada */
        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-container input[type="password"],
        .password-container input[type="text"] {
            width: 100%;
            padding: 10px;
            padding-right: 40px; /* Espacio para el botón de mostrar/ocultar */
            font-size: 16px;
        }

        /* Estilos para el botón dentro del input */
        .toggle-password {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            color: #333;
        }

        .toggle-password:focus {
            outline: none;
        }

    </style>
</head>
<body>
<div id="toast" class="toast"></div>

    <div class="login-container">
        <h2>Registro de Usuario</h2>

        <form action="registro.php" method="POST" onsubmit="return validateForm()">
    <label for="nombre">Nombre Completo:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="email">Correo Electrónico:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Contraseña:</label>
    <div class="password-container">
        <input type="password" id="password" name="password" 
               minlength="8" 
               pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)[A-Za-z\d\W]{8,}" 
               title="La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula, un número y un carácter especial.">
        <button type="button" onclick="togglePasswordVisibility('password')">Mostrar</button>
    </div>

    <label for="confirm_password">Confirmar Contraseña:</label>
    <div class="password-container">
        <input type="password" id="confirm_password" name="confirm_password" required>
        <button type="button" onclick="togglePasswordVisibility('confirm_password')">Mostrar</button>
    </div>

    <button type="submit">Registrarse</button>
</form>

<script>
    function validateForm() {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;

    if (password !== confirmPassword) {
        alert('Las contraseñas no coinciden.');
        return false;  // Evita que el formulario se envíe
    }
    return true;
}

</script>

<script>
    function togglePasswordVisibility(id) {
    var input = document.getElementById(id);
    var button = input.nextElementSibling;
    if (input.type === 'password') {
        input.type = 'text';
        button.innerText = 'Ocultar';
    } else {
        input.type = 'password';
        button.innerText = 'Mostrar';
    }
}

</script>


        <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión aquí</a></p>
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
