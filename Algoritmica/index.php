<?php
session_start();

// Redirige al login si no está logueado
if (!isset($_SESSION["logueado"]) || $_SESSION["logueado"] !== true) {
    header("Location: login.php");
    exit();
}

// Redirige administradores al panel admin
if ($_SESSION["rol"] === "administrador") {
    header("Location: admin/index.php");
    exit();
}

include("admin/bd.php");

// Normalizar rol por seguridad
$rol = strtolower(trim($_SESSION["rol"]));

// Obtener contenidos
$sentencia = $conexion->prepare("SELECT * FROM tbl_contenido ORDER BY ID DESC");
$sentencia->execute();
$lista_contenido = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Obtener títulos para el select del navbar
$sentencia_titulos = $conexion->prepare("SELECT ID, titulo FROM tbl_contenido ORDER BY ID DESC");
$sentencia_titulos->execute();
$lista_titulos = $sentencia_titulos->fetchAll(PDO::FETCH_ASSOC);

// Obtener sección "Nosotros"
$sentencia_nosotros = $conexion->prepare("SELECT * FROM tbl_nosotros ORDER BY ID DESC");
$sentencia_nosotros->execute();
$lista_nosotros = $sentencia_nosotros->fetchAll(PDO::FETCH_ASSOC);

// Obtener mensajes de contacto
$sentencia_contactos = $conexion->prepare("SELECT * FROM tbl_contactos ORDER BY ID DESC");
$sentencia_contactos->execute();
$lista_contactos = $sentencia_contactos->fetchAll(PDO::FETCH_ASSOC);

// Manejo de envío de formulario de contacto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';

    if ($nombre && $correo && $mensaje) {
        $sentencia_contacto = $conexion->prepare("INSERT INTO tbl_contactos (nombre, correo, mensaje) VALUES (:nombre, :correo, :mensaje)");
        $sentencia_contacto->bindParam(':nombre', $nombre);
        $sentencia_contacto->bindParam(':correo', $correo);
        $sentencia_contacto->bindParam(':mensaje', $mensaje);
        $sentencia_contacto->execute();
        $mensaje_envio = "¡Gracias! Tu mensaje ha sido enviado correctamente.";
    } else {
        $mensaje_envio = "Por favor completa todos los campos.";
    }
}
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Centro de Información - Algorítmica</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Centro Info</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="#nosotros">Nosotros</a></li>
            <li class="nav-item"><a class="nav-link" href="#informaciones">Informaciones</a></li>
            <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
            <li class="nav-item"><a class="nav-item nav-link" href="cerrar.php">Cerrar Sesión</a></li>
        </ul>

      <!-- Select dinámico de temas -->
      <form class="d-flex">
        <select class="form-select" id="select-temas" onchange="redirigirTema()">
          <option value="">Selecciona un tema...</option>
          <?php foreach($lista_titulos as $tema): ?>
            <option value="detalle.php?titulo=<?php echo urlencode($tema['titulo']); ?>">
              <?php echo htmlspecialchars($tema['titulo']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </form>
    </div>
  </div>
</nav>

<!-- Banner principal -->
<header class="encabezado d-flex align-items-center justify-content-center text-center text-white">
  <div class="overlay"></div>
  <div class="contenido-header">
    <h1 class="fw-bold display-5">Bienvenido al Centro de Información</h1>
    <p class="lead">Explora nuestras informaciones y recursos destacados para aprender.</p>
  </div>
</header>

<!-- Publicaciones cortas -->
<section class="container seccion-index py-5" id="informaciones">
  <h2 class="text-center mb-4 fw-bold">Explora los temas</h2>
  <div class="row g-4">
    <?php foreach($lista_contenido as $info): ?>
      <div class="col-12 col-sm-6 col-lg-4 tarjeta-col">
        <div class="card h-100 shadow-sm info-card">
          <?php if(!empty($info['foto'])): ?>
            <img src="imagenes/<?php echo htmlspecialchars($info['foto']); ?>" class="card-img-top" alt="">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($info['titulo']); ?></h5>
            <p class="texto-corto">
              <?php echo nl2br(htmlspecialchars($info['descripcion'] ?? '')); ?>
              <?php echo nl2br(htmlspecialchars($info['ejemplo_codigo'] ?? '')); ?>
            </p>
          </div>
          <div class="card-footer bg-transparent border-0 text-center">
            <a href="detalle.php?titulo=<?php echo urlencode($info['titulo']); ?>" class="btn-leer btn">
              Leer más <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Sección Nosotros -->
<section class="container seccion-index py-5" id="nosotros">
  <h2 class="text-center mb-4 fw-bold">Sobre Nosotros</h2>
  <div class="row g-4">
    <?php foreach($lista_nosotros as $persona): ?>
      <div class="col-12 col-sm-6 col-lg-4 tarjeta-col">
        <div class="card h-100 shadow-sm info-card">
          <?php if(!empty($persona['foto'])): ?>
            <img src="imagenes/<?php echo htmlspecialchars($persona['foto']); ?>" class="card-img-top" alt="">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($persona['nombre']); ?></h5>
            <p class="texto-corto"><?php echo nl2br(htmlspecialchars($persona['descripcion'])); ?></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Sección Contáctanos -->
<section class="container seccion-index py-5" id="contacto">
  <h2 class="text-center mb-4 fw-bold">Contáctanos</h2>

  <form action="#contacto" method="POST">
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="mb-3">
      <label for="correo" class="form-label">Correo</label>
      <input type="email" class="form-control" id="correo" name="correo" required>
    </div>
    <div class="mb-3">
      <label for="mensaje" class="form-label">Mensaje</label>
      <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-success">Enviar Mensaje</button>
    </div>
  </form>

  <?php if(isset($mensaje_envio)): ?>
    <p class="mt-3 text-center" style="color:green;"><?php echo $mensaje_envio; ?></p>
  <?php endif; ?>
</section>

<!-- Mostrar comentarios recibidos -->
<?php if(!empty($lista_contactos)): ?>
<div class="mt-5 container">
  <h4 class="text-center mb-3 fw-bold">Mensajes Recibidos</h4>
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead class="table-success">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Mensaje</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($lista_contactos as $contacto): ?>
          <tr>
            <td><?php echo $contacto['ID']; ?></td>
            <td><?php echo htmlspecialchars($contacto['nombre']); ?></td>
            <td><?php echo htmlspecialchars($contacto['correo']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($contacto['mensaje'])); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

  <!-- Footer -->
  <footer class="footer">
    <p class="mb-0">&copy; 2025 Centro de Información - Todos los derechos reservados</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/funciones.js"></script>
</body>
</html>
