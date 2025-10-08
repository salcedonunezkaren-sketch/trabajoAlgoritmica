<?php 
include("../../bd.php");

// Proceso de borrado
if(isset($_GET['txtID'])){

    $txtID = $_GET["txtID"];

    // Borrar foto
    $sentencia = $conexion->prepare("SELECT * FROM `tbl_contenido` WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID); 
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    if(isset($registro['foto']) && file_exists("../../../imagenes/".$registro['foto'])){
        unlink("../../../imagenes/".$registro['foto']);
    }

    // Borrar PDF
    if(isset($registro['archivo_adjunto']) && file_exists("../../../archivo_adjunto/".$registro['archivo_adjunto'])){
        unlink("../../../archivos_pdfs/".$registro['archivo_adjunto']);
    }

    // Borrar en la base de datos
    $sentencia = $conexion->prepare("DELETE FROM tbl_contenido WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    header("Location:index.php");
}

// Obtener registros
$sentencia = $conexion->prepare("SELECT * FROM `tbl_contenido`");
$sentencia->execute();
$lista_contenido = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php"); 
?>

<br>


<style>
    /* Ajustes visuales para mantener el mismo margen y formato que nosotros.php */
    .card {
        margin: 0 auto;
        max-width: 95%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-radius: 10px;
    }

    .card-header {
        background-color: #2e7d32; /* verde profesional */
        color: white;
        font-weight: bold;
    }

    .table {
        margin-bottom: 0;
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: left;
        padding: 10px;
        word-wrap: break-word;
        white-space: normal;
    }

    pre {
        background-color: #f8f9fa;
        padding: 8px;
        border-radius: 6px;
        overflow-x: auto;
        max-width: 100%;
    }

    img {
        border-radius: 8px;
    }

    .btn {
        border-radius: 6px;
        font-size: 0.9rem;
        padding: 5px 10px;
    }

    /* Evita que la tabla se desborde del contenedor */
    .table-responsive-sm {
        overflow-x: auto;
        padding: 5px;
    }
</style>


<div class="card">
    <div class="card-header">
        <a class="btn btn-primary" href="crear.php" role="button">Agregar Contenido</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Ejemplo Código</th>
                        <th>Foto</th>
                        <th>PDF</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_contenido as $registro){ ?>
                        <tr>
                            <td><?php echo $registro["id"]; ?></td>
                            <td><?php echo $registro["titulo"]; ?></td>
                            <td><?php echo $registro["descripcion"]; ?></td>
                            <td><?php echo $registro["tipo"]; ?></td>
                            <td>
                                <?php 
                                    if(!empty($registro["ejemplo_codigo"])){
                                        echo "<pre>".htmlspecialchars($registro["ejemplo_codigo"])."</pre>";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php if($registro['foto'] != ""): ?>
                                    <img src="../../../imagenes/<?php echo $registro['foto']; ?>" width="50" alt="">
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($registro['archivo_adjunto'] != ""): ?>
                                    <a href="../../../archivos_pdfs/<?php echo $registro['archivo_adjunto']; ?>" target="_blank" class="btn btn-info btn-sm">Ver/Descargar</a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $registro["fecha_creacion"]; ?></td>
                            <td>
                                <a class="btn btn-primary" href="editar.php?txtID=<?php echo $registro['id']; ?>">Editar</a>
                                <a class="btn btn-danger" href="index.php?txtID=<?php echo $registro['id']; ?>">Borrar</a>
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
