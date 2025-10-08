<?php 
include("../../bd.php");

    if(isset($_GET['txtID'])){

        $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:"";

        //Proceso de borrado que busque la imagen y la pueda borrar.
        $sentencia=$conexion -> prepare("SELECT * FROM `tbl_nosotros` WHERE ID=:id");
        $sentencia -> bindParam(":id", $txtID); 
        $sentencia -> execute();
    
        $registro_foto=$sentencia -> fetch(PDO::FETCH_LAZY);
    

        if(isset($registro_foto['foto'])){//para saber si existe el archivo
            if(file_exists("../../../imagenes/".$registro_foto['foto'])){
                unlink("../../../imagenes/".$registro_foto['foto']);
            }
     }

        //borra en la base de datos
        $sentencia= $conexion -> prepare("DELETE FROM tbl_nosotros WHERE ID=:id");
        $sentencia -> bindParam(":id", $txtID);
        $sentencia -> execute();

        header("Location:index.php");


    }

    $sentencia=$conexion -> prepare("SELECT * FROM `tbl_nosotros`");
    $sentencia -> execute();
    $lista_nosotros= $sentencia -> fetchAll(PDO::FETCH_ASSOC); 

include("../../templates/header.php"); 
?>

<br>

<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Contenido</a>
    </div>
    <div class="card-body">
        
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach($lista_nosotros as $registro){ ?>

                        <tr class="">

                            <td><?php echo $registro["ID"]; ?></td>
                            <td><?php echo $registro["nombre"]; ?></td>
                            <td><?php echo $registro["descripcion"]; ?></td>
                            <td><img src="../../../imagenes/<?php echo $registro['foto']; ?>" width="50" alt="" srcset=""></td>
                            <td>
                                <a name="" id="" class="btn btn-primary" href="editar.php?txtID=<?php echo $registro['ID']; ?>" role="button">Editar</a>
                                <a name="" id="" class="btn btn-danger" href="index.php?txtID=<?php echo $registro['ID']; ?>" role="button">Borrar</a>
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