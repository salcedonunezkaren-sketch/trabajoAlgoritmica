<?php 
include("../../bd.php");

if($_POST){
    print_r($_POST);

    $titulo=(isset($_POST["titulo"]))?$_POST["titulo"]:"";
    $info=(isset($_POST["info"]))?$_POST["info"]:"";

    $sentencia = $conexion -> prepare("INSERT INTO `tbl_conextenso` (`ID`, `titulo`, `info`, `foto`) VALUES (NULL, :titulo, :info, :foto);");

    $foto=(isset($_FILES['foto']['name']))?$_FILES['foto']['name']:"";
    $fecha_foto= new DateTime();
    $nombre_foto= $fecha_foto -> getTimestamp()."_".$foto;
    $tmp_foto= $_FILES['foto']['tmp_name'];

    if($tmp_foto != ""){
        move_uploaded_file($tmp_foto, "../../../imagenes/". $nombre_foto);
    }
    
    $sentencia -> bindParam(":titulo", $titulo);
$sentencia -> bindParam(":info", $info);
$sentencia -> bindParam(":foto", $nombre_foto);


    $sentencia -> execute();
    header("Location:index.php"); 

}

include("../../templates/header.php"); 
?>
<br>

<div class="card">
    <div class="card-header">Informaciones extensas</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="nombre" class="form-label">Tema:</label>
                <input type="text" class="form-control" name="titulo" id="titulo" aria-describedby="helpId" placeholder="Tema"/>
            </div>

            <div class="mb-3">
                <label for="ingredientes" class="form-label">Información:</label>
                <input type="text" class="form-control" name="info" id="info" aria-describedby="helpId" placeholder="Información"/>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <input type="file" class="form-control" name="foto" id="foto" placeholder="" aria-describedby="Foto.jpg"/>
            </div>

            <button type="submit" class="btn btn-success">Agregar Tema</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>
    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../templates/footer.php"); ?>