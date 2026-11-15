<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario | Sistema de Seguimiento y Gestión de Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .dashboard-container { max-width: 1100px; margin: 0 auto; }
        .nav-link-custom {
            color: #9ab0c0 !important;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            padding: 8px 12px !important;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            color: #00a8cc !important;
            background-color: rgba(0, 168, 204, 0.1);
        }
        .profile-card {
            background-color: #15242e;
            border: 1px solid #2a4150;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }
        .form-control-custom {
            background-color: #0b151b !important;
            border: 1px solid #2a4150 !important;
            color: #ffffff !important;
            border-radius: 8px;
            padding: 12px;
        }
        .form-control-custom:focus {
            border-color: #00a8cc !important;
            box-shadow: 0 0 0 3px rgba(0, 168, 204, 0.25) !important;
        }
        .role-badge {
            background-color: rgba(0, 168, 204, 0.2);
            color: #00a8cc;
            border: 1px solid #00a8cc;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body class="bg-dark text-white">

<?php $active_page = 'profile'; ?>
<?php include_once 'views/layouts/navbar.php'; ?>

    <!-- Contenido del Perfil -->
    <div class="dashboard-container py-5 px-3">
        <div class="profile-card">
            
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h3 class="fw-bold text-uppercase mb-1" style="color: #00a8cc; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-user-gear me-2"></i> Perfil de Usuario
                    </h3>
                    <p class="text-secondary mb-0 small">Visualiza y actualiza tu información personal en la base de datos corporativa.</p>
                </div>
                <div class="col-auto">
                    <span class="badge role-badge px-3 py-2 rounded-pill small">
                        <i class="fa-solid fa-shield-halved me-1"></i> Rol: <?php echo htmlspecialchars($_SESSION['role_name'] ?? 'Administrador'); ?>
                    </span>
                </div>
            </div>

            <hr class="border-secondary opacity-25 mb-4">

            <!-- Mensajes de éxito o error -->
            <?php if(!empty($mensaje)): ?>
                <div class="alert alert-success bg-success bg-opacity-10 text-success border border-success py-2 small mb-4 rounded-3">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($error)): ?>
                <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border border-danger py-2 small mb-4 rounded-3">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de Actualización -->
            <form action="index.php?controller=profile&action=index" method="POST">
                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-uppercase text-secondary">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control form-control-custom" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-uppercase text-secondary">Correo Electrónico Corporativo</label>
                        <input type="email" name="email" class="form-control form-control-custom" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? 'admin@consigueventas.com'); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-uppercase text-secondary">Departamento Asignado</label>
                        <input type="text" class="form-control form-control-custom text-light bg-opacity-50" value="Departamento de Tecnología" disabled readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-uppercase text-secondary">Estado de Cuenta</label>
                        <input type="text" class="form-control form-control-custom text-success fw-bold" value="Activo (Verificado)" disabled readonly>
                    </div>

                </div>

                <div class="mt-4 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                    <span class="text-secondary small">Los cambios se actualizarán inmediatamente en el sistema.</span>
                    <button type="submit" class="btn btn-cyan text-uppercase px-4 fw-bold" style="background-color: #00a8cc; color: #fff;">Guardar Cambios</button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>