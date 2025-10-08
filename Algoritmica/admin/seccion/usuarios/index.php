<?php
session_start();
include("../../bd.php");

// Eliminar usuario
if(isset($_GET["txtID"])){
    $txtID = $_GET["txtID"];

    // Prevenir que un admin se borre a sí mismo
    if($txtID != $_SESSION['usuario_id']){
        $sentencia = $conexion->prepare("DELETE FROM tbl_usuarios WHERE ID=:id");
        $sentencia->bindParam(":id",$txtID);
        $sentencia->execute();
    } else {
        echo "<script>alert('No puedes borrar tu propio usuario.');</script>";
    }
}

// Obtener lista de usuarios
$sentencia = $conexion->prepare("SELECT * FROM tbl_usuarios");
$sentencia->execute();
$lista_usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>

<br>

<div class="card">
    <div class="card-header">
        <a class="btn btn-primary" href="crear.php" role="button">Agregar Usuario</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Password</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_usuarios as $registro){ ?>
                    <tr>
                        <td><?php echo $registro["id"]; ?></td>
                        <td><?php echo htmlspecialchars($registro["nombre"]); ?></td>
                        <td>*****</td>
                        <td><?php echo htmlspecialchars($registro["email"]); ?></td>
                        <td><?php echo $registro["rol"]; ?></td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                            <?php if($registro['id'] != $_SESSION['usuario_id']){ ?>
                                <a class="btn btn-danger btn-sm" href="index.php?txtID=<?php echo $registro['id']; ?>" role="button" onclick="return confirm('¿Seguro que deseas borrar este usuario?')">Borrar</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../templates/footer.php"); ?>
