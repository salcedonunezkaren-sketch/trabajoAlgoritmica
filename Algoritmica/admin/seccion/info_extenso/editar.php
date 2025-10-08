<?php 
include("../../bd.php");

if($_POST){

    $txtID=(isset($_POST["txtID"]))?$_POST["txtID"]:"";

    $titulo=(isset($_POST['titulo']))?$_POST['titulo']:"";
    $info=(isset($_POST['info']))?$_POST['info']:"";
    
    $sentencia = $conexion -> prepare("UPDATE `tbl_conextenso` SET titulo=:titulo, info=:info WHERE id=:id");

    $sentencia -> bindParam(":titulo", $titulo);
    $sentencia -> bindParam(":info", $info);
    $sentencia -> bindParam(":id", $txtID);
    $sentencia -> execute();

    //Proceso de actualización de foto
    $foto=(isset($_FILES['foto']['name']))?$_FILES['foto']['name']:"";
    $tmp_foto= $_FILES['foto']['tmp_name'];

    if($foto != ""){
        $fecha_foto= new DateTime();
        $nombre_foto= $fecha_foto -> getTimestamp()."_".$foto;

        move_uploaded_file($tmp_foto, "../../../imagenes/". $nombre_foto);

        $sentencia=$conexion -> prepare("SELECT * FROM `tbl_conextenso` WHERE ID=:id");
        $sentencia -> bindParam(":id", $txtID); 
        $sentencia -> execute();
        
        $registro_foto=$sentencia -> fetch(PDO::FETCH_LAZY);
        

        if(isset($registro_foto['foto'])){//para saber si existe el archivo
            if(file_exists("../../../imagenes/".$registro_foto['foto'])){
                unlink("../../../imagenes/".$registro_foto['foto']);
            }
        }

        $sentencia= $conexion -> prepare("UPDATE `tbl_conextenso` SET foto=:foto WHERE ID=:id");

        $sentencia -> bindParam(":foto", $nombre_foto);
        $sentencia -> bindParam(":id", $txtID);

        $sentencia -> execute();

    }

    header("Location:index.php"); 

}
    
//Para recibir los valores
if(isset($_GET['txtID'])){
    
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:"";
    $sentencia = $conexion -> prepare("SELECT * FROM `tbl_conextenso` WHERE ID=:id");
    $sentencia -> bindParam(":id", $txtID);
    $sentencia -> execute();
    $registro = $sentencia -> fetch(PDO::FETCH_LAZY);

    //Recuperación de datos (asignar al formulario)
    $titulo = $registro["titulo"];
    $info = $registro["info"];
    $foto = $registro["foto"];

}

include("../../templates/header.php"); 
?>
<br>

<div class="card">
    <div class="card-header">Informaciones correspondientes</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="txtID" class="form-label">ID:</label>"
                <input type="text" class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" aria-describedby="helpId" placeholder=""/>
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Tema:</label>
                <input type="text" class="form-control" value="<?php echo $titulo; ?>" name="titulo" id="titulo" aria-describedby="helpId" placeholder="Temas correspondientes"/>
            </div>

            <div class="mb-3">
                <label for="ingredientes" class="form-label">Informaciónes correspondientes:</label>
                <input type="text" class="form-control" value="<?php echo $info; ?>" name="info" id="info" aria-describedby="helpId" placeholder="Informaciones correspondientes"/>
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