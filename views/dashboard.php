<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control Principal | Sistema de Seguimiento y Gestión de Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .dashboard-container { max-width: 1350px; margin: 0 auto; }
        .nav-link-custom {
            color: #9ab0c0 !important; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;
            padding: 8px 12px !important; border-radius: 6px; transition: all 0.2s ease;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            color: #00a8cc !important; background-color: rgba(0, 168, 204, 0.1);
        }
        .panel-card {
            background-color: #15242e; border: 1px solid #2a4150; border-radius: 14px; padding: 35px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.5);
        }
        .module-card {
            background-color: #0b151b; border: 1px solid #2a4150; border-radius: 12px; padding: 25px;
            transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column; justify-content: space-between;
        }
        .module-card:hover {
            border-color: #00a8cc; transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0, 168, 204, 0.15);
        }
        .module-icon {
            font-size: 2rem; color: #00a8cc; background-color: rgba(0, 168, 204, 0.1);
            width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;
            border-radius: 10px; margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-dark text-white">

    <!-- Navbar Modularizado (Marca automáticamente 'Inicio' activo y mantiene el usuario en negrita) -->
    <?php $active_page = 'dashboard'; ?>
    <?php include_once 'views/layouts/navbar.php'; ?>

    <!-- Contenido Principal del Dashboard -->
    <div class="dashboard-container py-5 px-3">
        <div class="panel-card">
            
            <div class="mb-4">
                <h3 class="fw-bold text-uppercase mb-1" style="color: #00a8cc; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-gauge-high me-2"></i> Panel de Control Principal
                </h3>
                <p class="text-light opacity-75 small mb-0">Plataforma corporativa optimizada para el registro operativo, validación de marcas y seguimiento de actividades del equipo.</p>
            </div>

            <hr class="border-secondary opacity-25 mb-4">

            <!-- Tarjetas de Módulos -->
            <div class="row g-4">
                
                <!-- 1. Actividades (Visible para todos) -->
                <div class="col-md-4">
                    <a href="index.php?controller=activities&action=index" class="text-decoration-none">
                        <div class="module-card">
                            <div>
                                <div class="module-icon">
                                    <i class="fa-solid fa-list-check"></i>
                                </div>
                                <h5 class="fw-bold text-white mb-2">Actividades</h5>
                                <p class="text-light opacity-75 small mb-0">Espacio donde los trabajadores podrán realizar y registrar su reporte operativo diario.</p>
                            </div>
                            <div class="mt-3 text-info small fw-bold">
                                Acceder al módulo <i class="fa-solid fa-arrow-right ms-1"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 2. Proyectos en General (Admin y Responsable) -->
                <?php if(isset($_SESSION['role_id']) && ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2)): ?>
                <div class="col-md-4">
                    <a href="index.php?controller=projects&action=index" class="text-decoration-none">
                        <div class="module-card">
                            <div>
                                <div class="module-icon">
                                    <i class="fa-solid fa-folder-open"></i>
                                </div>
                                <h5 class="fw-bold text-white mb-2">Proyectos en General</h5>
                                <p class="text-light opacity-75 small mb-0">Visualiza reportes por marca, coloca observaciones, aprueba reportes y supervisa avances.</p>
                            </div>
                            <div class="mt-3 text-info small fw-bold">
                                Acceder al módulo <i class="fa-solid fa-arrow-right ms-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <!-- 3. Mis Reportes Anteriores (Visible para todos) -->
                <div class="col-md-4">
                    <a href="index.php?controller=reports&action=myReports" class="text-decoration-none">
                        <div class="module-card">
                            <div>
                                <div class="module-icon">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </div>
                                <h5 class="fw-bold text-white mb-2">Mis Reportes Anteriores</h5>
                                <p class="text-light opacity-75 small mb-0">Consulta tus reportes pasados y verifica si poseen observaciones pendientes por corregir.</p>
                            </div>
                            <div class="mt-3 text-info small fw-bold">
                                Acceder al módulo <i class="fa-solid fa-arrow-right ms-1"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 4. Gestión de Marcas (Admin y Responsable) -->
                <?php if(isset($_SESSION['role_id']) && ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2)): ?>
                <div class="col-md-4">
                    <a href="index.php?controller=brands&action=index" class="text-decoration-none">
                        <div class="module-card">
                            <div>
                                <div class="module-icon">
                                    <i class="fa-solid fa-tags"></i>
                                </div>
                                <h5 class="fw-bold text-white mb-2">Gestión de Marcas</h5>
                                <p class="text-light opacity-75 small mb-0">Visualiza las marcas corporativas y asigna los empleados encargados de su gestión.</p>
                            </div>
                            <div class="mt-3 text-info small fw-bold">
                                Acceder al módulo <i class="fa-solid fa-arrow-right ms-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <!-- 5. Gestión de Empleados (SOLO Administrador) -->
                <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                <div class="col-md-4">
                    <a href="index.php?controller=employees&action=index" class="text-decoration-none">
                        <div class="module-card">
                            <div>
                                <div class="module-icon">
                                    <i class="fa-solid fa-users-gear"></i>
                                </div>
                                <h5 class="fw-bold text-white mb-2">Gestión de Empleados</h5>
                                <p class="text-light opacity-75 small mb-0">Registra nuevos trabajadores, asigna roles y actualiza credenciales de acceso al sistema.</p>
                            </div>
                            <div class="mt-3 text-info small fw-bold">
                                Acceder al módulo <i class="fa-solid fa-arrow-right ms-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>

                <!-- 6. Perfil de Usuario (Visible para todos) -->
                <div class="col-md-4">
                    <a href="index.php?controller=profile&action=index" class="text-decoration-none">
                        <div class="module-card">
                            <div>
                                <div class="module-icon">
                                    <i class="fa-solid fa-user-gear"></i>
                                </div>
                                <h5 class="fw-bold text-white mb-2">Perfil de Usuario</h5>
                                <p class="text-light opacity-75 small mb-0">Visualiza y actualiza la información personal de tu cuenta y datos de trabajador.</p>
                            </div>
                            <div class="mt-3 text-info small fw-bold">
                                Acceder al módulo <i class="fa-solid fa-arrow-right ms-1"></i>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>