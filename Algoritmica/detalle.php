<?php 
include("admin/bd.php");

$info = false;

if(isset($_GET['titulo'])){
    $titulo = urldecode($_GET['titulo']);
    $sentencia = $conexion->prepare("SELECT * FROM tbl_conextenso WHERE LOWER(titulo) = LOWER(:titulo) LIMIT 1");
    $sentencia->bindValue(":titulo", $titulo);
    $sentencia->execute();
    $info = $sentencia->fetch(PDO::FETCH_ASSOC);
} elseif(isset($_GET['id'])){
    $id = $_GET['id'];
    $sentencia = $conexion->prepare("SELECT * FROM tbl_conextenso WHERE ID = :id");
    $sentencia->bindValue(":id", $id, PDO::PARAM_INT);
    $sentencia->execute();
    $info = $sentencia->fetch(PDO::FETCH_ASSOC);
}

if(!$info){
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($info['titulo']); ?> - Detalle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/style.css"> 
</head>
<body>

<header class="encabezado">
  <div class="overlay"></div>
  <div class="contenido-header text-center">
    <h1><?php echo htmlspecialchars($info['titulo']); ?></h1>
    <p>Información detallada</p>
  </div>
</header>

<main class="container seccion-detalle">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
      <div class="card detalle-card">
        <?php if(!empty($info['foto'])): ?>
          <img src="imagenes/<?php echo $info['foto']; ?>" class="card-img-top" alt="">
        <?php endif; ?>
        <div class="card-body">
          <div class="texto-detalle">
            <?php echo nl2br($info['info']); ?>
          </div>
        </div>
      </div>

      <div class="text-center mt-4">
        <a href="index.php" class="btn-volver">
          <i class="bi bi-arrow-left"></i> Volver al inicio
        </a>
      </div>
    </div>
  </div>
</main>

<footer class="footer">
  <p>&copy; 2025 Centro de Información - Todos los derechos reservados</p>
</footer>

</body>
</html>
