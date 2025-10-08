<?php 
include("../../bd.php");

if($_POST){
    
    $nombre = (isset($_POST["nombre"])) ? $_POST["nombre"] : "";
    $descripcion = (isset($_POST["descripcion"])) ? $_POST["descripcion"] : "";

    $foto = (isset($_FILES['foto']['name'])) ? $_FILES['foto']['name'] : "";
    $nombre_foto = "";
    if($foto != ""){
        $fecha_foto = new DateTime();
        $nombre_foto = $fecha_foto->getTimestamp() . "_" . $foto;
        move_uploaded_file($_FILES['foto']['tmp_name'], "../../../imagenes/" . $nombre_foto);
    }

    $sentencia = $conexion->prepare("INSERT INTO tbl_nosotros (nombre, descripcion, foto) VALUES (:nombre, :descripcion, :foto)");
    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":descripcion", $descripcion);
    $sentencia->bindParam(":foto", $nombre_foto);
    $sentencia->execute();

    header("Location:index.php"); 
}


include("../../templates/header.php"); 
?>
<br>

<div class="card">
    <div class="card-header">Sobre los creadores</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Ingresar nombre"/>
            </div>

            <div class="mb-3">
                <label for="ingredientes" class="form-label">Descripcion:</label>
                <input type="text" class="form-control" name="descripcion" id="descripcion" aria-describedby="helpId" placeholder="Descripcion"/>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <input type="file" class="form-control" name="foto" id="foto" placeholder="" aria-describedby="Foto.jpg"/>
            </div>

            <button type="submit" class="btn btn-success">Agregar Informacion</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>
    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../templates/footer.php"); ?>