<?php 
include("../../bd.php");

// Guardar cambios
if($_POST){

    $txtID = $_POST["txtID"] ?? "";

    $titulo = $_POST["titulo"] ?? "";
    $descripcion = $_POST["descripcion"] ?? "";
    $tipo = $_POST["tipo"] ?? "";
    $ejemplo_codigo = $_POST["ejemplo_codigo"] ?? "";

    // Actualizar datos básicos
    $sentencia = $conexion->prepare("UPDATE tbl_contenido SET titulo=:titulo, descripcion=:descripcion, tipo=:tipo, ejemplo_codigo=:ejemplo_codigo WHERE ID=:id");
    $sentencia->bindParam(":titulo", $titulo);
    $sentencia->bindParam(":descripcion", $descripcion);
    $sentencia->bindParam(":tipo", $tipo);
    $sentencia->bindParam(":ejemplo_codigo", $ejemplo_codigo);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    // FOTO
    if(!empty($_FILES['foto']['name'])){
        $foto = $_FILES['foto']['name'];
        $tmp_foto = $_FILES['foto']['tmp_name'];
        $nombre_foto = (new DateTime())->getTimestamp() . "_" . $foto;
        move_uploaded_file($tmp_foto, "../../../imagenes/".$nombre_foto);

        // Eliminar foto anterior
        $sentencia = $conexion->prepare("SELECT foto FROM tbl_contenido WHERE ID=:id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $registro_foto = $sentencia->fetch(PDO::FETCH_ASSOC);
        if(!empty($registro_foto['foto']) && file_exists("../../../imagenes/".$registro_foto['foto'])){
            unlink("../../../imagenes/".$registro_foto['foto']);
        }

        // Actualizar foto
        $sentencia = $conexion->prepare("UPDATE tbl_contenido SET foto=:foto WHERE ID=:id");
        $sentencia->bindParam(":foto", $nombre_foto);
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
    }

    // PDF
    if(!empty($_FILES['pdf']['name'])){
        $pdf = $_FILES['pdf']['name'];
        $tmp_pdf = $_FILES['pdf']['tmp_name'];
        $nombre_pdf = (new DateTime())->getTimestamp() . "_" . $pdf;
        move_uploaded_file($tmp_pdf, "../../../archivos_pdfs/".$nombre_pdf);

        // Eliminar PDF anterior
        $sentencia = $conexion->prepare("SELECT pdf FROM tbl_contenido WHERE ID=:id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $registro_pdf = $sentencia->fetch(PDO::FETCH_ASSOC);
        if(!empty($registro_pdf['pdf']) && file_exists("../../../archivos_pdfs/".$registro_pdf['pdf'])){
            unlink("../../../archivos_pdfs/".$registro_pdf['pdf']);
        }

        // Actualizar PDF
        $sentencia = $conexion->prepare("UPDATE tbl_contenido SET pdf=:pdf WHERE ID=:id");
        $sentencia->bindParam(":pdf", $nombre_pdf);
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
    }

    header("Location:index.php"); 
    exit;
}

// Recuperar valores existentes
$titulo = $descripcion = $tipo = $ejemplo_codigo = $foto = $pdf = "";
if(isset($_GET['txtID'])){
    $txtID = $_GET['txtID'];
    $sentencia = $conexion->prepare("SELECT * FROM tbl_contenido WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_ASSOC);

    if($registro){
        $titulo = $registro["titulo"] ?? "";
        $descripcion = $registro["descripcion"] ?? "";
        $tipo = $registro["tipo"] ?? "";
        $ejemplo_codigo = $registro["ejemplo_codigo"] ?? "";
        $foto = $registro["foto"] ?? "";
        $pdf = $registro["pdf"] ?? "";
    } else {
        echo "<div class='alert alert-warning'>Registro no encontrado.</div>";
        $txtID = 0; // para evitar errores en el formulario
    }
}

include("../../templates/header.php"); 
?>

<div class="card">
    <div class="card-header">Editar Contenido</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="txtID" value="<?php echo htmlspecialchars($txtID); ?>">

            <div class="mb-3">
                <label class="form-label">Título:</label>
                <input type="text" class="form-control" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>"/>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción:</label>
                <textarea class="form-control" name="descripcion"><?php echo htmlspecialchars($descripcion); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo:</label>
                <input type="text" class="form-control" name="tipo" value="<?php echo htmlspecialchars($tipo); ?>"/>
            </div>

            <div class="mb-3">
                <label class="form-label">Ejemplo de código:</label>
                <textarea class="form-control" name="ejemplo_codigo"><?php echo htmlspecialchars($ejemplo_codigo); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto:</label>
                <?php if($foto) echo "<br><img width='80' src='../../../imagenes/".htmlspecialchars($foto)."'>"; ?>
                <input type="file" class="form-control" name="foto"/>
            </div>

            <div class="mb-3">
                <label class="form-label">Archivo adjunto (PDF):</label>
                <?php if($pdf) echo "<br><a href='../../../archivos_pdfs/".htmlspecialchars($pdf)."' target='_blank'>Ver PDF</a>"; ?>
                <input type="file" class="form-control" name="pdf" accept="application/pdf"/>
            </div>

            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
