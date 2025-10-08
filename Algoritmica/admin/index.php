<?php
session_start();

// Redirige al login si no está logueado
if (!isset($_SESSION["logueado"]) || $_SESSION["logueado"] !== true) {
    header("Location: ../login.php");
    exit();
}

// Solo permitir administradores
if ($_SESSION["rol"] !== "administrador") {
    header("Location: ../index.php");
    exit();
}

include("templates/header.php");
?>
<br>
<div class="row align-items-md-stretch">
    <div class="col-md-12">
        <div class="h-100 p-5 border rounded-3">
            <h2>Bienvenid@ al Administrador, <?php echo htmlspecialchars($_SESSION["nombre"]); ?></h2>
            <p>Este espacio es para administrar su sitio web.</p>
        </div>
    </div>
</div>
<?php include("templates/footer.php"); ?>
