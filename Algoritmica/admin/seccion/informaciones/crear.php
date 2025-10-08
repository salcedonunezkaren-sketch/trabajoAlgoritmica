<?php 
include("../../bd.php");

// Inicializar variables para evitar warnings
$titulo = "";
$descripcion = "";
$tipo = "";
$ejemplo_codigo = "";
$foto = "";
$pdf = "";

if($_POST){
    // print_r($_POST); // puedes descomentar para depuración

    $titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : "";
    $descripcion = isset($_POST["descripcion"]) ? $_POST["descripcion"] : "";
    $tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : "";
    $ejemplo_codigo = isset($_POST["ejemplo_codigo"]) ? $_POST["ejemplo_codigo"] : "";

    // Manejo de imagen
    if(isset($_FILES['foto']) && $_FILES['foto']['name'] != ""){
        $foto = $_FILES['foto']['name'];
        $fecha_foto = new DateTime();
        $nombre_foto = $fecha_foto->getTimestamp() . "_" . $foto;
        $tmp_foto = $_FILES['foto']['tmp_name'];
        move_uploaded_file($tmp_foto, "../../../imagenes/" . $nombre_foto);
    } else {
        $nombre_foto = ""; // si no se sube imagen
    }

    // Manejo de PDF
    if(isset($_FILES['pdf']) && $_FILES['pdf']['name'] != ""){
        $pdf = $_FILES['pdf']['name'];
        $fecha_pdf = new DateTime();
        $nombre_pdf = $fecha_pdf->getTimestamp() . "_" . $pdf;
        $tmp_pdf = $_FILES['pdf']['tmp_name'];
        move_uploaded_file($tmp_pdf, "../../../archivos_pdfs/" . $nombre_pdf);
    } else {
        $nombre_pdf = ""; // si no se sube pdf
    }

    // Inserción en base de datos
    $sentencia = $conexion->prepare("
        INSERT INTO `tbl_contenido` 
        (`ID`, `titulo`, `descripcion`, `tipo`, `ejemplo_codigo`, `foto`) 
        VALUES 
        (NULL, :titulo, :descripcion, :tipo, :ejemplo_codigo, :foto);
    ");

    $sentencia->bindParam(":titulo", $titulo);
    $sentencia->bindParam(":descripcion", $descripcion);
    $sentencia->bindParam(":tipo", $tipo);
    $sentencia->bindParam(":ejemplo_codigo", $ejemplo_codigo);
    $sentencia->bindParam(":foto", $nombre_foto);

    $sentencia->execute();

    header("Location:index.php"); 
    exit;
}

include("../../templates/header.php"); 
?>

<br>

<div class="card">
    <div class="card-header">Informaciones extensas</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="titulo" class="form-label">Tema:</label>
                <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Tema" value="<?= htmlspecialchars($titulo) ?>"/>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion:</label>
                <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Información" value="<?= htmlspecialchars($descripcion) ?>"/>
            </div>

            <label for="tipo">Seleccione el tipo de tema:</label>
            <select class="form-control" name="tipo">
                <option value="">Seleccione un tipo</option>
                <option value="Teoría" <?= $tipo == "Array" ? "selected" : "" ?>>Array</option>
                <option value="Práctica" <?= $tipo == "Pilas" ? "selected" : "" ?>>Pilas</option>
                <option value="Otro" <?= $tipo == "Colas" ? "selected" : "" ?>>Colas</option>
                <option value="Otro" <?= $tipo == "Listas" ? "selected" : "" ?>>Listas Ligadas</option>
                <option value="Otro" <?= $tipo == "Grafos" ? "selected" : "" ?>>Grafos</option>
                <option value="Otro" <?= $tipo == "Árboles" ? "selected" : "" ?>>Árboles</option>
                <option value="Otro" <?= $tipo == "Metodos" ? "selected" : "" ?>>Métodos de Ordenación</option>
            </select>
            <br>

            <div class="mb-3">
                <label class="form-label">Ejemplo de código:</label>
                <textarea class="form-control" name="ejemplo_codigo"><?= htmlspecialchars($ejemplo_codigo) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto:</label>
                <?php if($foto) echo "<br><img width='80' src='../../../imagenes/".$foto."'>"; ?>
                <input type="file" class="form-control" name="foto"/>
            </div>

            <div class="mb-3">
                <label class="form-label">Archivo adjunto (PDF):</label>
                <?php if($pdf) echo "<br><a href='../../../archivos_pdfs/".$pdf."' target='_blank'>Ver PDF</a>"; ?>
                <input type="file" class="form-control" name="pdf" accept="application/pdf"/>
            </div>

            <button type="submit" class="btn btn-success">Agregar Información</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>
    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../templates/footer.php"); ?>
