<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Marcas y Proyectos | Sistema</title>
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
        .form-control-custom, .form-select-custom {
            background-color: #0b151b !important; border: 1px solid #2a4150 !important;
            color: #ffffff !important; border-radius: 8px; padding: 10px 14px; font-size: 0.9rem;
        }
        .form-control-custom::placeholder { color: #a0aec0 !important; opacity: 1; }
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: #00a8cc !important; box-shadow: 0 0 0 3px rgba(0, 168, 204, 0.25) !important; color: #ffffff !important;
        }
        .form-select-custom option { background-color: #0b151b; color: #ffffff; }
        
        .table-custom { color: #ffffff !important; border-color: #2a4150; }
        .table-custom th { background-color: #0f1c24; color: #00a8cc; border-bottom: 2px solid #2a4150; }
        .table-custom td { background-color: #15242e !important; color: #ffffff !important; border-color: #2a4150; vertical-align: middle; }
        
        .workers-box {
            background-color: #0b151b; border: 1px solid #2a4150; border-radius: 8px; max-height: 140px; overflow-y: auto; padding: 10px;
        }
    </style>
</head>
<body class="bg-dark text-white">

<?php $active_page = 'brands'; ?>
<?php include_once 'views/layouts/navbar.php'; ?>

    <!-- Contenido Principal -->
    <div class="dashboard-container py-5 px-3">
        
        <!-- SECCIÓN 1: GESTIÓN DE MARCAS -->
        <div class="panel-card mb-5">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h3 class="fw-bold text-uppercase mb-1" style="color: #00a8cc; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-tags me-2"></i> Gestión de Marcas
                    </h3>
                    <p class="text-light opacity-75 mb-0 small">Administra las cuentas corporativas y sus estados operativos.</p>
                </div>
                <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                <div class="col-auto">
                    <button class="btn btn-cyan text-uppercase fw-bold px-4" data-bs-toggle="modal" data-bs-target="#createBrandModal" style="background-color: #00a8cc; color: #fff;">
                        <i class="fa-solid fa-plus me-1"></i> Nueva Marca
                    </button>
                </div>
                <?php endif; ?>
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

            <!-- Tabla de Marcas -->
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Nombre de Marca</th>
                            <th>Cliente Asociado</th>
                            <th>Estado Operativo</th>
                            <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                            <th class="text-center">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($brands)): ?>
                            <?php 
                            $contador = 1; // Inicializamos contador correlativo para marcas
                            foreach($brands as $brand): 
                            ?>
                            <tr>
                                <td class="fw-bold text-info">#<?php echo $contador++; ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($brand['nombre_marca']); ?></td>
                                <td><?php echo htmlspecialchars($brand['cliente_asociado']); ?></td>
                                <td>
                                    <?php if($brand['estado'] == 1): ?>
                                        <span class="badge bg-info bg-opacity-25 text-info border border-info px-2 py-1">En Proceso (Activo)</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary px-2 py-1">Completado / Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-info px-3 py-1 fw-bold" data-bs-toggle="modal" data-bs-target="#editBrandModal<?php echo $brand['id_marca']; ?>">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> Editar Marca
                                    </button>
                                </td>
                                <?php endif; ?>
                            </tr>

                            <!-- Modal Editar Marca -->
                            <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                            <div class="modal fade" id="editBrandModal<?php echo $brand['id_marca']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-white" style="background-color: #15242e; border: 1px solid #2a4150;">
                                        <div class="modal-header border-bottom border-secondary border-opacity-25">
                                            <h5 class="modal-title fw-bold text-info fs-6">Editar Marca: <?php echo htmlspecialchars($brand['nombre_marca']); ?></h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="index.php?controller=brands&action=index" method="POST">
                                            <div class="modal-body text-start">
                                                <input type="hidden" name="action_type" value="update_brand">
                                                <input type="hidden" name="id_marca" value="<?php echo $brand['id_marca']; ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-light text-uppercase">Nombre de la Marca</label>
                                                    <input type="text" name="nombre_marca" class="form-control form-control-custom" value="<?php echo htmlspecialchars($brand['nombre_marca']); ?>" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-light text-uppercase">Cliente Asociado</label>
                                                    <input type="text" name="cliente_asociado" class="form-control form-control-custom" value="<?php echo htmlspecialchars($brand['cliente_asociado']); ?>" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-light text-uppercase">Responsable del Proyecto</label>
                                                    <select name="id_responsable" class="form-select form-select-custom" required>
                                                        <option value="" disabled>Seleccione un responsable...</option>
                                                        <?php 
                                                        $currentResp = !empty($brand['proyectos']) ? $brand['proyectos'][0]['id_responsable'] : 0;
                                                        foreach($allEmployees as $emp): 
                                                        ?>
                                                            <option value="<?php echo $emp['id_usuario']; ?>" <?php echo ($currentResp == $emp['id_usuario']) ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($emp['nombre']); ?> (<?php echo htmlspecialchars($emp['nombre_rol']); ?>)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-light text-uppercase">Estado Operativo</label>
                                                    <select name="estado" class="form-select form-select-custom" required>
                                                        <option value="1" <?php echo ($brand['estado'] == 1) ? 'selected' : ''; ?>>En Proceso (Activo)</option>
                                                        <option value="0" <?php echo ($brand['estado'] == 0) ? 'selected' : ''; ?>>Completado / Inactivo</option>
                                                    </select>
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
                            <?php endif; ?>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-light py-4">No se encontraron marcas registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECCIÓN 2: APARTADO INFERIOR - PROYECTOS Y TRABAJADORES ASIGNADOS -->
        <div class="panel-card">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h3 class="fw-bold text-uppercase mb-1" style="color: #00a8cc; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-folder-open me-2"></i> Proyectos Generados y Personal Asignado
                    </h3>
                    <p class="text-light opacity-75 mb-0 small">Visualiza los proyectos, el personal asignado y administra los trabajadores habilitados para reportes.</p>
                </div>
            </div>

            <hr class="border-secondary opacity-25 mb-4">

            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#Proyecto</th>
                            <th>Título del Proyecto</th>
                            <th>Marca Asociada</th>
                            <th>Responsable</th>
                            <th>Trabajadores Asignados</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $hasProjects = false;
                        $contadorProyecto = 1; // Inicializamos contador correlativo para proyectos
                        foreach($brands as $brand) {
                            if(!empty($brand['proyectos'])) {
                                foreach($brand['proyectos'] as $proj) {
                                    $hasProjects = true;
                        ?>
                        <tr>
                            <td class="fw-bold text-info">#<?php echo $contadorProyecto++; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($proj['titulo_proyecto']); ?></td>
                            <td><?php echo htmlspecialchars($brand['nombre_marca']); ?></td>
                            <td><?php echo htmlspecialchars($proj['responsable'] ?? 'No asignado'); ?></td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php if(!empty($proj['trabajadores'])): ?>
                                        <?php foreach($proj['trabajadores'] as $worker): ?>
                                            <span class="badge bg-secondary bg-opacity-75 text-light border border-secondary d-flex align-items-center gap-1 px-2 py-1" style="font-size: 0.75rem;">
                                                <i class="fa-solid fa-user me-1"></i> <?php echo htmlspecialchars($worker['nombre']); ?>
                                                <!-- Formulario para eliminar al trabajador del proyecto -->
                                                <form action="index.php?controller=brands&action=index" method="POST" class="d-inline ms-1">
                                                    <input type="hidden" name="action_type" value="remove_worker">
                                                    <input type="hidden" name="id_proyecto" value="<?php echo $proj['id_proyecto']; ?>">
                                                    <input type="hidden" name="id_usuario" value="<?php echo $worker['id_usuario']; ?>">
                                                    <button type="submit" class="btn btn-link text-danger p-0 m-0" style="font-size: 0.75rem;" title="Remover trabajador" onclick="return confirm('¿Deseas remover a este trabajador del proyecto?');">
                                                        <i class="fa-solid fa-circle-xmark"></i>
                                                    </button>
                                                </form>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-secondary small italic">Sin trabajadores asignados</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-cyan px-3 py-1 fw-bold text-white" style="background-color: #00a8cc;" data-bs-toggle="modal" data-bs-target="#assignWorkersModal<?php echo $proj['id_proyecto']; ?>">
                                    <i class="fa-solid fa-user-plus me-1"></i> Asignar
                                </button>
                            </td>
                        </tr>

                        <!-- Modal para Asignar Trabajadores al Proyecto -->
                        <div class="modal fade" id="assignWorkersModal<?php echo $proj['id_proyecto']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content text-white" style="background-color: #15242e; border: 1px solid #2a4150;">
                                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                                        <h5 class="modal-title fw-bold text-info fs-6">Asignar Trabajadores: <?php echo htmlspecialchars($proj['titulo_proyecto']); ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="index.php?controller=brands&action=index" method="POST">
                                        <div class="modal-body text-start">
                                            <input type="hidden" name="action_type" value="assign_workers_to_project">
                                            <input type="hidden" name="id_proyecto" value="<?php echo $proj['id_proyecto']; ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-light text-uppercase">Seleccione colaboradores</label>
                                                <div class="workers-box">
                                                    <?php if(!empty($allEmployees)): ?>
                                                        <?php foreach($allEmployees as $emp): ?>
                                                            <div class="form-check py-1">
                                                                <input class="form-check-input" type="checkbox" name="trabajadores_proyecto[]" value="<?php echo $emp['id_usuario']; ?>" id="proj_emp_<?php echo $proj['id_proyecto']; ?>_<?php echo $emp['id_usuario']; ?>">
                                                                <label class="form-check-label text-light small" for="proj_emp_<?php echo $proj['id_proyecto']; ?>_<?php echo $emp['id_usuario']; ?>">
                                                                    <?php echo htmlspecialchars($emp['nombre']); ?> <span class="text-info" style="font-size: 0.75rem;">(<?php echo htmlspecialchars($emp['nombre_rol']); ?>)</span>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <span class="text-secondary small">No hay empleados registrados.</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top border-secondary border-opacity-25">
                                            <button type="button" class="btn btn-outline-light btn-sm px-3" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-cyan btn-sm px-4 fw-bold" style="background-color: #00a8cc; color:#fff;">Guardar Asignación</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php 
                                }
                            }
                        }
                        if(!$hasProjects):
                        ?>
                        <tr>
                            <td colspan="6" class="text-center text-light py-4">No hay proyectos generados todavía.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Registrar Nueva Marca -->
    <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
    <div class="modal fade" id="createBrandModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white" style="background-color: #15242e; border: 1px solid #2a4150;">
                <div class="modal-header border-bottom border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold text-info fs-6"><i class="fa-solid fa-tags me-1"></i> Registrar Nueva Marca y Proyecto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="index.php?controller=brands&action=index" method="POST">
                    <div class="modal-body text-start">
                        <input type="hidden" name="action_type" value="create_brand">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-light text-uppercase">Nombre de la Marca</label>
                            <input type="text" name="nombre_marca" class="form-control form-control-custom" placeholder="Ej. Zona Impecable" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-light text-uppercase">Cliente Asociado</label>
                            <input type="text" name="cliente_asociado" class="form-control form-control-custom" placeholder="Ej. Corporación S.A.C." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-light text-uppercase">Responsable del Proyecto</label>
                            <select name="id_responsable" class="form-select form-select-custom" required>
                                <option value="" disabled selected>Seleccione un responsable...</option>
                                <?php if(!empty($allEmployees)): ?>
                                    <?php foreach($allEmployees as $emp): ?>
                                        <option value="<?php echo $emp['id_usuario']; ?>"><?php echo htmlspecialchars($emp['nombre']); ?> (<?php echo htmlspecialchars($emp['nombre_rol']); ?>)</option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-light text-uppercase">Asignar Trabajadores Iniciales</label>
                            <div class="workers-box">
                                <?php if(!empty($allEmployees)): ?>
                                    <?php foreach($allEmployees as $emp): ?>
                                        <div class="form-check py-1">
                                            <input class="form-check-input" type="checkbox" name="trabajadores[]" value="<?php echo $emp['id_usuario']; ?>" id="emp_new_<?php echo $emp['id_usuario']; ?>">
                                            <label class="form-check-label text-light small" for="emp_new_<?php echo $emp['id_usuario']; ?>">
                                                <?php echo htmlspecialchars($emp['nombre']); ?> <span class="text-info" style="font-size: 0.75rem;">(<?php echo htmlspecialchars($emp['nombre_rol']); ?>)</span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-secondary small">No hay empleados registrados.</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-light text-uppercase">Estado Inicial</label>
                            <select name="estado" class="form-select form-select-custom" required>
                                <option value="1" selected>En Proceso (Activo)</option>
                                <option value="0">Completado / Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary border-opacity-25">
                        <button type="button" class="btn btn-outline-light btn-sm px-3" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-cyan btn-sm px-4 fw-bold" style="background-color: #00a8cc; color:#fff;">Guardar Marca y Proyecto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>