<?php
session_start();
include("admin/bd.php"); // Ajusta la ruta si tu bd.php está en otra carpeta

if ($_POST) {
    $nombre = trim($_POST["nombre"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $rol = "estudiante"; // Puedes cambiarlo si deseas otro rol por defecto

    if ($nombre && $password) {

        // 🔹 Verificar si el nombre de usuario o email ya existen
        $check = $conexion->prepare("SELECT COUNT(*) FROM tbl_usuarios WHERE nombre=:nombre OR email=:email");
        $check->bindParam(":nombre", $nombre);
        $check->bindParam(":email", $email);
        $check->execute();
        $existe = $check->fetchColumn();

        if ($existe > 0) {
            $mensaje = "El usuario o correo ya existe. Intenta con otro.";
        } else {
            // 🔹 Insertar nuevo usuario
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conexion->prepare("INSERT INTO tbl_usuarios (nombre, password, email, rol) VALUES (:nombre, :password, :email, :rol)");
            $insert->bindParam(":nombre", $nombre);
            $insert->bindParam(":password", $password_hashed);
            $insert->bindParam(":email", $email);
            $insert->bindParam(":rol", $rol);
            $insert->execute();

            $mensaje = "Usuario registrado correctamente. Ahora puedes iniciar sesión.";
        }

    } else {
        $mensaje = "Por favor completa todos los campos obligatorios.";
    }
}
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root {
    --verde-oscuro: #1e3d32;
    --verde-medio: #3a7054;
    --verde-claro: #a8d5b4;
    --blanco: #f4f9f5;
}

body {
    height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, var(--verde-oscuro), var(--verde-medio));
    font-family: 'Poppins', sans-serif;
    color: var(--blanco);
}

.register-card {
    background: rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(10px);
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 0 25px rgba(0, 0, 0, 0.5);
    width: 100%;
    max-width: 450px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.register-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 35px rgba(58, 112, 84, 0.8);
}

.register-card h2 {
    margin-bottom: 25px;
    color: var(--blanco);
    font-weight: 700;
    letter-spacing: 1px;
}

.register-card input {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--verde-claro);
    border-radius: 10px;
    color: var(--blanco);
    padding: 12px;
    margin-bottom: 15px;
    width: 100%;
    transition: all 0.3s ease;
}

.register-card input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.register-card input:focus {
    background: rgba(255, 255, 255, 0.1);
    outline: none;
    border-color: var(--verde-claro);
    color: var(--blanco);
    box-shadow: 0 0 10px rgba(168, 213, 180, 0.4);
}

.register-card button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background: var(--verde-medio);
    color: var(--blanco);
    font-weight: bold;
    letter-spacing: 1px;
}
</style>
</head>
<body>

<div class="register-card">
    <h2>Registro</h2>
    <?php if(isset($mensaje)){ ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php } ?>
    <form action="registro.php" method="post">
        <input type="text" name="nombre" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="email" name="email" placeholder="Correo electrónico (opcional)">
        <button type="submit">Registrarse</button>
    </form>
    <p class="mt-3">
        ¿Ya tienes cuenta? <br>
        <a href="login.php">Volver al login</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
