<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Pagos</title>
    <style>
        /* Estilos generales del cuerpo */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Estilo del encabezado principal */
h1 {
    text-align: center;
    color: #4a90e2;
    margin-top: 50px;
}

/* Estilo del subencabezado */
h2 {
    text-align: center;
    color: #333;
}

/* Estilos de la lista de tipos de pago */
ul {
    list-style-type: none;
    padding: 0;
    max-width: 600px;
    margin: 20px auto;
}

/* Estilo de cada elemento de la lista */
li {
    background-color: #ffffff;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin: 10px 0;
    transition: background-color 0.3s, transform 0.3s;
}

/* Estilo de los enlaces */
a {
    display: block;
    text-decoration: none;
    color: #4a90e2;
    padding: 15px;
    text-align: center;
}

/* Efecto al pasar el ratón sobre el elemento de la lista */
li:hover {
    background-color: #e0f7fa;
    transform: scale(1.02);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

    </style>
</head>
<body>
    <h1>Registro de Pagos</h1>
    <h2>Selecciona el tipo de pago:</h2>
    <ul>
        <li><a href="pagos.php">ANUALIDAD</a></li>
        <li><a href="registro_faenas.php">FAENAS</a></li>
        <li><a href="registro_desasolve.php">DESASOLVE</a></li>
        <li><a href="registro_aportacion_extraordinaria.php">APORTACION EXTRAORDINARIA</a></li>
        
    </ul>
</body>
</html>
