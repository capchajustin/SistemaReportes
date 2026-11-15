<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados | Sistema de Seguimiento</title>
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
        
        /* INPUTS Y SELECTS CON ALTO CONTRASTE Y PLACEHOLDERS CLAROS */
        .form-control-custom, .form-select-custom {
            background-color: #0b151b !important; 
            border: 1px solid #2a4150 !important;
            color: #ffffff !important; 
            border-radius: 8px; 
            padding: 10px 14px; 
            font-size: 0.9rem;
        }
        .form-control-custom::placeholder {
            color: #a0aec0 !important;
            opacity: 1;
        }
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: #00a8cc !important; 
            box-shadow: 0 0 0 3px rgba(0, 168, 204, 0.25) !important;
            color: #ffffff !important;
        }
        .form-select-custom option {
            background-color: #0b151b;
            color: #ffffff;
        }

        /* ESTILOS DE LA TABLA */
        .table-custom { color: #ffffff !important; border-color: #2a4150; }
        .table-custom th { background-color: #0f1c24; color: #00a8cc; border-bottom: 2px solid #2a4150; }
        .table-custom td { background-color: #15242e !important; color: #ffffff !important; border-color: #2a4150; vertical-align: middle; }
    </style>
</head>
<body class="bg-dark text-white">
<?php $active_page = 'employees'; ?>
<?php include_once 'views/layouts/navbar.php'; ?>

    <!-- Contenido Principal -->
    <div class="dashboard-container py-5 px-3">
        <div class="panel-card mb-4">
            
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h3 class="fw-bold text-uppercase mb-1" style="color: #00a8cc; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-users-gear me-2"></i> Gestión de Empleados
                    </h3>
                    <p class="text-secondary mb-0 small">Registra nuevos trabajadores, asigna roles y actualiza credenciales del sistema.</p>
                </div>
                <div class="col-auto d-flex align-items-center gap-2">
                    <!-- Selector de Filtro por Rol -->
                    <select id="filtroRol" class="form-select form-select-custom" style="width: 200px;" onchange="filtrarEmpleadosPorRol()">
                        <option value="">Todos los roles</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Colaborador">Colaborador</option>
                    </select>

                    <button class="btn btn-cyan text-uppercase fw-bold px-4" data-bs-toggle="modal" data-bs-target="#createEmployeeModal" style="background-color: #00a8cc; color: #fff;">
                        <i class="fa-solid fa-user-plus me-1"></i> Nuevo Empleado
                    </button>
                </div>
            </div>

            <hr class="border-secondary opacity-25 mb-4">

            <!-- Alertas -->
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

            <!-- Tabla de Empleados -->
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Nombre Completo</th>
                            <th>Correo Electrónico</th>
                            <th>Rol Actual</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaEmpleados">
                        <?php if(!empty($employees)): ?>
                            <?php 
                            $contador = 1; 
                            foreach($employees as $emp): 
                            ?>
                            <tr>
                                <td class="fw-bold text-info">#<?php echo $contador++; ?></td>
                                <td><?php echo htmlspecialchars($emp['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($emp['email']); ?></td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-25 text-info border border-info px-2 py-1 rol-badge">
                                        <?php echo htmlspecialchars($emp['nombre_rol']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-info px-3" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $emp['id_usuario']; ?>">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> Editar Rol / Clave
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal de Edición por Empleado -->
                            <div class="modal fade" id="editModal<?php echo $emp['id_usuario']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-white" style="background-color: #15242e; border: 1px solid #2a4150;">
                                        <div class="modal-header border-bottom border-secondary border-opacity-25">
                                            <h5 class="modal-title fw-bold text-info fs-6">Editar Empleado: <?php echo htmlspecialchars($emp['nombre']); ?></h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="index.php?controller=employees&action=index" method="POST">
                                            <div class="modal-body text-start">
                                                <input type="hidden" name="action_type" value="update">
                                                <input type="hidden" name="id_usuario" value="<?php echo $emp['id_usuario']; ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-light text-uppercase">Rol del Empleado</label>
                                                    <select name="id_rol" class="form-select form-select-custom" required>
                                                        <option value="1" <?php echo ($emp['id_rol'] == 1) ? 'selected' : ''; ?>>Administrador</option>
                                                        <option value="2" <?php echo ($emp['id_rol'] == 2) ? 'selected' : ''; ?>>Responsable / Supervisor</option>
                                                        <option value="3" <?php echo ($emp['id_rol'] == 3) ? 'selected' : ''; ?>>Colaborador / Trabajador</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-light text-uppercase">Nueva Contraseña (Opcional)</label>
                                                    <input type="password" name="password" class="form-control form-control-custom" placeholder="Dejar en blanco para mantener la actual">
                                                    <small class="text-light opacity-75" style="font-size: 0.75rem;">Si ingresas un texto, la contraseña anterior será reemplazada por seguridad.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top border-secondary border-opacity-25">
                                                <button type="button" class="btn btn-outline-light btn-sm px-3" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-cyan btn-sm px-4 fw-bold" style="background-color: #00a8cc; color:#fff;">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-light py-4">No se encontraron empleados registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal Registrar Nuevo Empleado -->
    <div class="modal fade" id="createEmployeeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white" style="background-color: #15242e; border: 1px solid #2a4150;">
                <div class="modal-header border-bottom border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold text-info fs-6"><i class="fa-solid fa-user-plus me-1"></i> Registrar Nuevo Empleado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="index.php?controller=employees&action=index" method="POST">
                    <div class="modal-body text-start">
                        <input type="hidden" name="action_type" value="create">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-light text-uppercase">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control form-control-custom" placeholder="Ej. Carlos Pérez" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-light text-uppercase">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control form-control-custom" placeholder="carlos@consigueventas.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-light text-uppercase">Contraseña Inicial</label>
                            <input type="password" name="password" class="form-control form-control-custom" placeholder="••••••••" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-light text-uppercase">Rol del Sistema</label>
                            <select name="id_rol" class="form-select form-select-custom" required>
                                <option value="3" selected>Colaborador / Trabajador</option>
                                <option value="2">Responsable / Supervisor</option>
                                <option value="1">Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary border-opacity-25">
                        <button type="button" class="btn btn-outline-light btn-sm px-3" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-cyan btn-sm px-4 fw-bold" style="background-color: #00a8cc; color:#fff;">Registrar Empleado</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script de filtrado instantáneo -->
    <script>
        function filtrarEmpleadosPorRol() {
            const selectFiltro = document.getElementById('filtroRol');
            const valorFiltro = selectFiltro.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaEmpleados tr');

            filas.forEach(fila => {
                const badgeRol = fila.querySelector('.rol-badge');
                if (badgeRol) {
                    const textoRol = badgeRol.textContent.toLowerCase();
                    if (valorFiltro === "" || textoRol.includes(valorFiltro)) {
                        fila.style.display = "";
                    } else {
                        fila.style.display = "none";
                    }
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>