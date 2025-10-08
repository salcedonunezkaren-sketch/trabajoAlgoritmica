<?php
include("../../bd.php");

// Obtener ID del usuario a editar
$txtID = $_GET['txtID'] ?? null;
if(!$txtID) header("Location: index.php");

// Procesar formulario
if($_POST){
    $usuario = $_POST['nombre'] ?? "";
    $correo = $_POST['email'] ?? "";
    $rol = $_POST['rol'] ?? 'Estudiante';
    $password = $_POST['password'] ?? "";

    // Actualizar contraseña solo si se ingresó
    if($password != ""){
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE tbl_usuarios SET nombre=:usuario, email=:correo, password=:password, rol=:rol WHERE ID=:id";
    } else {
        $sql = "UPDATE tbl_usuarios SET nombre=:usuario, email=:correo, rol=:rol WHERE ID=:id";
    }

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(":usuario",$usuario);
    $stmt->bindParam(":correo",$correo);
    $stmt->bindParam(":rol",$rol);
    $stmt->bindParam(":id",$txtID);
    if($password != "") $stmt->bindParam(":password",$password_hashed);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

// Obtener datos actuales del usuario
$stmt = $conexion->prepare("SELECT * FROM tbl_usuarios WHERE ID=:id");
$stmt->bindParam(":id",$txtID);
$stmt->execute();
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>

<br>

<div class="card">
    <div class="card-header">
        <h3>Editar Usuario</h3>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Usuario:</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($registro['nombre']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo:</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($registro['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña (dejar vacío para no cambiar):</label>
                <input type="password" class="form-control" name="password">
            </div>

            <div class="mb-3">
                <label class="form-label">Rol:</label>
                <select name="rol" class="form-select">
                    <option value="Estudiante" <?php if($registro['rol']=='Estudiante') echo 'selected'; ?>>Estudiante</option>
                    <option value="Administrador" <?php if($registro['rol']=='Administrador') echo 'selected'; ?>>Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../templates/footer.php"); ?>
