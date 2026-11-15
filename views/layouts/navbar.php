<!-- views/layouts/navbar.php -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom px-4 py-3 sticky-top" style="background-color: #0f1c24; border-bottom: 1px solid rgba(255,255,255,0.05);">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center me-4" href="index.php?controller=dashboard&action=index">
            <img src="assets/img/logo.png" alt="Consigue Ventas" style="height: 32px; width: auto;" class="me-2">
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                
                <!-- 1. INICIO -->
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?php echo ($active_page == 'dashboard') ? 'active' : ''; ?>" href="index.php?controller=dashboard&action=index">
                        <i class="fa-solid fa-house me-1"></i> Inicio
                    </a>
                </li>

                <!-- 2. ACTIVIDADES -->
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?php echo ($active_page == 'activities') ? 'active' : ''; ?>" href="index.php?controller=activities&action=index">
                        <i class="fa-solid fa-list-check me-1"></i> Actividades
                    </a>
                </li>

                <!-- 3. PROYECTOS (Admin y Responsable) -->
                <?php if(isset($_SESSION['role_id']) && ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2)): ?>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?php echo ($active_page == 'projects') ? 'active' : ''; ?>" href="index.php?controller=projects&action=index">
                        <i class="fa-solid fa-folder-open me-1"></i> Proyectos
                    </a>
                </li>
                <?php endif; ?>

                <!-- 4. MIS REPORTES -->
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?php echo ($active_page == 'my_reports') ? 'active' : ''; ?>" href="index.php?controller=reports&action=myReports">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Mis Reportes
                    </a>
                </li>

                <!-- 5. MARCAS (Admin y Responsable) -->
                <?php if(isset($_SESSION['role_id']) && ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2)): ?>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?php echo ($active_page == 'brands') ? 'active' : ''; ?>" href="index.php?controller=brands&action=index">
                        <i class="fa-solid fa-tags me-1"></i> Marcas
                    </a>
                </li>
                <?php endif; ?>

                <!-- 6. EMPLEADOS (Solo Administrador) -->
                <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom <?php echo ($active_page == 'employees') ? 'active' : ''; ?>" href="index.php?controller=employees&action=index">
                        <i class="fa-solid fa-users-gear me-1"></i> Empleados
                    </a>
                </li>
                <?php endif; ?>

            </ul>

            <!-- Sección de Usuario y Salir (Siempre con Negrita en el Nombre) -->
            <div class="d-flex align-items-center gap-3">
                <a href="index.php?controller=profile&action=index" class="text-decoration-none small d-flex align-items-center gap-2 <?php echo ($active_page == 'profile') ? 'active px-2 py-1 rounded' : ''; ?>" style="<?php echo ($active_page == 'profile') ? 'background-color: rgba(0, 168, 204, 0.1);' : ''; ?>">
                    <i class="fa-solid fa-user-circle fs-5" style="color: #00a8cc;"></i>
                    <strong class="text-white"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuario'); ?></strong>
                </a>
                <a href="index.php?controller=auth&action=logout" class="btn btn-outline-danger btn-sm px-3 py-1 text-uppercase fw-bold" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Salir
                </a>
            </div>
        </div>
    </div>
</nav>