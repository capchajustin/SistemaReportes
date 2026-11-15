<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Seguimiento y Gestión de Proyectos | Consigue Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Header Superior -->
    <nav class="navbar navbar-custom px-4 py-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                
                <!-- LOGO CORPORATIVO -->
                <a href="index.php?controller=home&action=index" class="text-decoration-none">
                    <img src="assets/img/logo.png" alt="Consigue Ventas" style="height: 38px; width: auto;" class="d-inline-block align-text-top">
                </a>

                <span class="text-secondary mx-2">|</span>
                <span class="brand-text small text-uppercase">DEPT. TECNOLOGÍA</span>
            </div>
            <a href="index.php?controller=auth&action=login" class="btn btn-cyan text-uppercase px-4 py-2 small">INICIAR SESIÓN</a>
        </div>
    </nav>

    <!-- Contenido Principal Centrado -->
    <main class="flex-grow-1 d-flex align-items-center justify-content-center p-3">
        <div class="main-card text-center">
            
            <!-- Badge superior -->
            <div class="mb-3">
                <span class="badge-pill-custom text-uppercase">DEPARTAMENTO DE TECNOLOGÍA</span>
            </div>

            <!-- Título Principal -->
            <h1 class="title-hero text-uppercase mb-3">
                BIENVENIDO AL SISTEMA DE<br>
                <span class="cyan-text">SEGUIMIENTO Y GESTIÓN DE PROYECTOS</span>
            </h1>

            <!-- Descripción -->
            <p class="description-text mx-auto mb-4" style="max-width: 540px;">
                Este es un sistema web propuesto para la gestión centralizada de marcas, seguimiento de reportes de proyectos y carga de evidencias técnicas, optimizando el control operativo del equipo.
            </p>

            <!-- Botones de Acción -->
            <div class="d-flex justify-content-center gap-3 mb-2">
                <a href="index.php?controller=auth&action=login" class="btn btn-cyan text-uppercase fs-6 py-2 px-4">LOGUEARSE EN EL SISTEMA</a>
                <button type="button" class="btn btn-outline-custom fs-6 py-2 px-4" onclick="alert('Contacte al líder o responsable del área para solicitar sus credenciales de acceso.')">Solicitar Credenciales</button>
            </div>

            <!-- Caja de Aviso Inferior -->
            <div class="notice-box">
                <div class="notice-title">Aviso:</div>
                <p class="notice-body">
                    Este sistema web sustituye formalmente los reportes basados en las anteriores hojas de cálculo compartidas de Excel, debe utilizar esta herramienta para garantizar la aprobación de sus actividades.
                </p>
            </div>

        </div>
    </main>

</body>
</html>