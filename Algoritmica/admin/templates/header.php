<?php

$url_base = "http://localhost/Algoritmica/admin/";

?>


<!doctype html>
<html lang="es">
<head>
    <title>Administrador del sitio web</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- jQuery y DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    
        <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #edf7ed; /* verde muy claro */
            color: #2f4d2f; /* verde oscuro suave para texto */
            margin: 0;
            padding: 0;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(90deg, #a8e6a3, #81c784); /* verdes claros gradiente */
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 600;
            color: #1b5e20 !important; /* verde oscuro */
            font-size: 1.25rem;
        }

        .navbar-brand i {
            margin-right: 8px;
        }

        .navbar-nav .nav-link {
            color: #1b5e20 !important;
            font-weight: 500;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(130, 200, 150, 0.4); /* verde claro translúcido */
            border-radius: 6px;
        }

        .navbar-nav .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(60, 120, 60, 0.6);
            border-radius: 6px;
        }

        /* Botones */
        .btn-success {
            background-color: #81c784; /* verde claro */
            border: none;
            transition: 0.3s;
        }

        .btn-success:hover {
            background-color: #66bb6a; /* verde ligeramente más oscuro */
        }

        .btn-primary {
            background-color: #66bb6a;
            border: none;
        }

        .btn-primary:hover {
            background-color: #2c8a2fff;
        }

        /* Cards y tablas */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background-color: #f0faf0; /* verde muy muy claro */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #81c784;
            color: #1b5e20;
            font-weight: 500;
        }

        .table {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead {
            background-color: #a8e6a3;
            color: #1b5e20;
        }

        .table-hover tbody tr:hover {
            background-color: #e0f2e9; /* verde clarito al pasar el mouse */
        }

        /* Footer */
        footer {
            background: linear-gradient(90deg, #81c784, #a8e6a3);
            color: #1b5e20;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            font-size: 0.9rem;
        }

        /* Contenedor principal */
        main.container {
            background: #ffffff;
            border-radius: 12px;
            padding: 25px 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }

        h1, h2, h3 {
            color: #4caf50; /* verde medio */
            font-weight: 600;
        }

        a {
            text-decoration: none;
            color: #2e7d32; /* verde oscuro para enlaces */
        }

        a:hover {
            color: #81c784; /* verde claro */
        }

        @media (max-width: 768px) {
            .navbar-nav {
                text-align: center;
            }
            .navbar-nav .nav-link {
                margin: 5px 0;
            }
            main.container {
                padding: 15px;
            }
        }
    </style>


</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $url_base;?>index.php">
                <i class="bi bi-person-circle"></i> Administrador
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <a class="nav-item nav-link" href="<?php echo $url_base;?>seccion/informaciones/">Informaciones</a>
                    <a class="nav-item nav-link" href="<?php echo $url_base;?>seccion/info_extenso/">Informaciones extensas</a>
                    <a class="nav-item nav-link" href="<?php echo $url_base;?>seccion/nosotros/">Nosotros</a>
                    <a class="nav-item nav-link" href="<?php echo $url_base;?>seccion/contactos/">Contactos </a>
                    <a class="nav-item nav-link" href="<?php echo $url_base;?>seccion/usuarios/">Usuarios</a>
                    <a class="nav-item nav-link" href="<?php echo $url_base;?>../cerrar.php">Cerrar sesión</a>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="container mt-4">
