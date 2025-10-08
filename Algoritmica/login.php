<?php
session_start();
include("admin/bd.php");

if ($_POST) {
    $usuario_input = trim($_POST["usuario"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // Buscar usuario por nombre o email
    $sentencia = $conexion->prepare(
        "SELECT * FROM tbl_usuarios WHERE nombre=:usuario OR email=:usuario LIMIT 1"
    );
    $sentencia->bindParam(":usuario", $usuario_input);
    $sentencia->execute();
    $usuario_encontrado = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($usuario_encontrado && password_verify($password, $usuario_encontrado["password"])) {
        $_SESSION["usuario_id"] = $usuario_encontrado["id"];
        $_SESSION["nombre"] = $usuario_encontrado["nombre"];
        $_SESSION["email"] = $usuario_encontrado["email"];
        $_SESSION["rol"] = strtolower(trim($usuario_encontrado["rol"]));
        $_SESSION["logueado"] = true;

        // Redirección según rol
        if ($_SESSION["rol"] === "administrador") {
            header("Location: admin/index.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $mensaje = "Usuario o contraseña incorrectos";
    }
}
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
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
.login-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 0 25px rgba(0, 0, 0, 0.4);
    width: 100%;
    max-width: 420px;
    text-align: center;
}
.login-card h2 { margin-bottom: 25px; color: var(--blanco); font-weight: 700; }
.login-card input {
    background: rgba(255, 255, 255, 1);
    border: 1px solid var(--verde-claro);
    border-radius: 10px;
    color: var(--verde-oscuro);
    padding: 12px;
    margin-bottom: 15px;
    width: 100%;
}
.login-card button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background: var(--verde-medio);
    color: var(--blanco);
    font-weight: bold;
}
.alert { max-width: 420px; margin: 0 auto 20px auto; }
</style>
</head>
<body>
<div class="login-card">
    <h2>Iniciar Sesión</h2>
    <?php if (isset($mensaje)) { ?>
        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
    <?php } ?>
    <form action="" method="post">
        <input type="text" name="usuario" placeholder="Nombre de usuario o correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
    <p class="mt-3">¿No tienes cuenta? <br>
    <a href="registro.php">Crear cuenta</a>
    </p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
