<?php
include('conexion.php'); 
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password']);

// Consultar el usuario en la base de datos
$sql = "SELECT id_usuario, nombre, password, role FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $nombre, $hashed_password, $role);
    $stmt->fetch();

    // Verificar la contraseña
    if (password_verify($password, $hashed_password)) {
        // Iniciar sesión y almacenar datos en la sesión
        $_SESSION['user_id'] = $id;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['role'] = $role; // Guardar el rol del usuario en la sesión
        
        // Redirigir según el rol
        if ($role === 'admin') {
            header("Location: home.php"); // Redirigir a la página del administrador
        } else {
 // Redirigir a la página del usuario normal
        }
        exit();
    } else {
        $mensaje = 'Contraseña incorrecta.';
    }
} else {
    $mensaje = 'No se encontró un usuario con ese correo electrónico.';
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
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
    <div class="image-side">
        <img src="images/payment.png" alt="image">
</div>

    <div class="login-container">
        <h2>INICIO DE SESION</h2>
        <form action="index.php" method="POST">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <a href=""> ¿Olvidaste tu contraseña? </a>
            <button type="submit">ACCEDER</button>
            <p>o inicia sesión con:</p>
    <div class="social-login">
    <button class="facebook-btn">Iniciar sesión con Facebook</button>
    <button class="google-btn" onclick="loginWithEmail()">Iniciar sesión con Google</button>
    <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
        </form>
        <div>
            <button onclick="goBack()" >Regresar</button> 
        </div>
        <script>
            function goBack() {
                window.history.back(); 
            }
        </script>
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

<script>
    // Efecto de desvanecimiento en el contenedor al cargar la página
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.querySelector('.container');
        container.style.opacity = 0;
        container.style.transition = "opacity 1.5s ease-in-out";
        setTimeout(() => {
            container.style.opacity = 1;
        }, 100);
    });

    // Efecto de enfoque en los campos de entrada
    const inputs = document.querySelectorAll(".login-container input[type='email'], .login-container input[type='password']");
    inputs.forEach(input => {
        input.addEventListener("focus", () => {
            input.style.border = "2px solid #4a90e2";
            input.style.transition = "border 0.3s ease";
        });
        input.addEventListener("blur", () => {
            input.style.border = "1px solid #ddd";
        });
    });

    // Animación de botón al pasar el cursor
    const buttons = document.querySelectorAll(".login-container button[type='submit'], .facebook-btn, .google-btn");
    buttons.forEach(button => {
        button.addEventListener("mouseover", () => {
            button.style.transform = "scale(1.05)";
            button.style.transition = "transform 0.2s ease";
        });
        button.addEventListener("mouseout", () => {
            button.style.transform = "scale(1)";
        });
    });
</script>
</body>
</html>
