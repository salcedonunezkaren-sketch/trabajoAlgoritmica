<?php 
include("../../bd.php");

if($_POST){
    $txtID = (isset($_POST["txtID"])) ? $_POST["txtID"] : "";
    $nombre = (isset($_POST["nombre"])) ? $_POST["nombre"] : "";
    $descripcion = (isset($_POST["descripcion"])) ? $_POST["descripcion"] : "";

    // Actualizar nombre y descripción
    $sentencia = $conexion->prepare("UPDATE tbl_nosotros SET nombre=:nombre, descripcion=:descripcion WHERE ID=:id");
    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":descripcion", $descripcion);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    // Actualizar foto si se sube una nueva
    if(isset($_FILES['foto']['name']) && $_FILES['foto']['name'] != ""){
        $foto = $_FILES['foto']['name'];
        $fecha_foto = new DateTime();
        $nombre_foto = $fecha_foto->getTimestamp() . "_" . $foto;
        move_uploaded_file($_FILES['foto']['tmp_name'], "../../../imagenes/" . $nombre_foto);

        // Eliminar foto anterior
        $sentencia = $conexion->prepare("SELECT foto FROM tbl_nosotros WHERE ID=:id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $registro = $sentencia->fetch(PDO::FETCH_ASSOC);
        if(isset($registro['foto']) && file_exists("../../../imagenes/" . $registro['foto'])){
            unlink("../../../imagenes/" . $registro['foto']);
        }

        // Guardar nueva foto
        $sentencia = $conexion->prepare("UPDATE tbl_nosotros SET foto=:foto WHERE ID=:id");
        $sentencia->bindParam(":foto", $nombre_foto);
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
    }

    header("Location:index.php"); 
}

    
//Para recibir los valores
if(isset($_GET['txtID'])){
    
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:"";
    $sentencia = $conexion -> prepare("SELECT * FROM `tbl_nosotros` WHERE ID=:id");
    $sentencia -> bindParam(":id", $txtID);
    $sentencia -> execute();
    $registro = $sentencia -> fetch(PDO::FETCH_LAZY);

    //Recuperación de datos (asignar al formulario)
    $nombre = $registro["nombre"];
    $descripcion = $registro["descripcion"];
    $foto = $registro["foto"];

}

include("../../templates/header.php"); 
?>
<br>

<div class="card">
    <div class="card-header">Sobre los creadores</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="txtID" class="form-label">ID:</label>"
                <input type="text" class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" aria-describedby="helpId" placeholder=""/>
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" value="<?php echo $nombre; ?>" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Ingrese su nombre"/>
            </div>

            <div class="mb-3">
                <label for="ingredientes" class="form-label">Descripcion:</label>
                <input type="text" class="form-control" value="<?php echo $descripcion; ?>" name="descripcion" id="descripcion" aria-describedby="helpId" placeholder="Descripcion"/>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <br>
                <img width="80" src="../../../imagenes/<?php echo $foto; ?>" >
                <input type="file" class="form-control" name="foto" id="foto" placeholder="" aria-describedby="Foto.jpg"/>
            </div>

            <button type="submit" class="btn btn-success">Agregar Información</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>
    </div>
    <div class="card-footer text-muted"></div>
</div>


<?php include("../../templates/footer.php"); ?>