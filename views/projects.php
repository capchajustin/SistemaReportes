<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Proyectos y Reportes | Sistema</title>
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
        
        .form-select-custom {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(2);
            cursor: pointer;
        }
        .form-select-custom option {
            background-color: #0b151b !important; color: #ffffff !important; padding: 10px;
        }
        
        .table-custom { color: #ffffff !important; border-color: #2a4150; }
        .table-custom th { background-color: #0f1c24; color: #00a8cc; border-bottom: 2px solid #2a4150; }
        .table-custom td { background-color: #15242e !important; color: #ffffff !important; border-color: #2a4150; vertical-align: middle; }
        
        .observation-box {
            background-color: #0b151b; border-left: 3px solid #ffc107; padding: 8px 12px; border-radius: 4px; font-size: 0.8rem; margin-top: 6px;
        }

        /* Mejora visual de los 3 minidashboards */
        .stat-card {
            background-color: #0f1c24; border: 1px solid #2a4150; border-radius: 12px; padding: 24px; text-align: center;
            transition: transform 0.2s ease, border-color 0.2s ease;
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        .stat-card:hover {
            border-color: #00a8cc;
            transform: translateY(-3px);
        }
        .stat-card.border-cyan { border-top: 4px solid #00a8cc; }
        .stat-card.border-green { border-top: 4px solid #198754; }
        .stat-card.border-yellow { border-top: 4px solid #ffc107; }
    </style>
</head>
<body class="bg-dark text-white">

<?php $active_page = 'projects'; ?>
<?php include_once 'views/layouts/navbar.php'; ?>

    <!-- Contenido Principal -->
    <div class="dashboard-container py-5 px-3">

        <!-- Alertas -->
        <?php if(!empty($mensaje)): ?>
            <div class="alert alert-success bg-success bg-opacity-10 text-success border border-success py-2 small mb-4 rounded-3 shadow-sm">
                <i class="fa-solid fa-circle-check me-1"></i> <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border border-danger py-2 small mb-4 rounded-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- SECCIÓN DE FILTRO: MARCA Y FECHA / RANGO -->
        <div class="panel-card mb-4">
            <h4 class="fw-bold text-uppercase mb-1" style="color: #00a8cc; letter-spacing: 0.5px;">
                <i class="fa-solid fa-filter me-2"></i> Filtrar Proyectos y Reportes Diarios
            </h4>
            <p class="text-light opacity-75 mb-4 small">Selecciona una marca, define el filtro temporal y analiza los entregables.</p>

            <form action="index.php" method="GET" class="row g-3">
                <input type="hidden" name="controller" value="projects">
                <input type="hidden" name="action" value="index">

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-light text-uppercase">Seleccionar Marca</label>
                    <select name="id_marca" class="form-select form-select-custom" required>
                        <option value="" disabled <?php echo ($id_marca == 0) ? 'selected' : ''; ?>>-- Selecciona una marca --</option>
                        <?php foreach($brands as $brand): ?>
                            <option value="<?php echo $brand['id_marca']; ?>" <?php echo ($id_marca == $brand['id_marca']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($brand['nombre_marca']); ?> (<?php echo htmlspecialchars($brand['cliente_asociado']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Selector de modo de fecha -->
                <div class="col-md-8">
                    <div class="row g-2">
                        <div class="col-12 d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-bold text-light text-uppercase mb-0">Criterio Temporal</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="usarRango" name="usar_rango" value="1" <?php echo !empty($_GET['usar_rango']) ? 'checked' : ''; ?> onchange="toggleRangoFechas()">
                                <label class="form-check-label text-light small" for="usarRango">Activar Rango de Fechas (Desde / Hasta)</label>
                            </div>
                        </div>

                        <!-- Fecha Única -->
                        <div class="col-md-4" id="divFechaUnica">
                            <input type="date" name="fecha" class="form-control form-control-custom" value="<?php echo htmlspecialchars($fecha); ?>">
                        </div>

                        <!-- Rango Desde -->
                        <div class="col-md-4 div-rango" style="display: none;">
                            <input type="date" name="fecha_inicio" class="form-control form-control-custom" value="<?php echo htmlspecialchars($_GET['fecha_inicio'] ?? $fecha); ?>" placeholder="Fecha Inicio">
                        </div>

                        <!-- Rango Hasta -->
                        <div class="col-md-4 div-rango" style="display: none;">
                            <input type="date" name="fecha_fin" class="form-control form-control-custom" value="<?php echo htmlspecialchars($_GET['fecha_fin'] ?? date('Y-m-d')); ?>" placeholder="Fecha Fin">
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-cyan w-100 fw-bold text-white py-2 shadow-sm" style="background-color: #00a8cc;">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Consultar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- RESULTADOS SEGÚN SELECCIÓN -->
        <?php if($id_marca == 0): ?>
            <div class="panel-card py-5 px-4 text-start border-start border-4 border-info">
                <div class="d-flex align-items-center gap-3">
                    <div class="fs-1 text-info"><i class="fa-solid fa-circle-info"></i></div>
                    <div>
                        <h5 class="fw-bold text-white mb-1">Aviso del Sistema</h5>
                        <p class="text-light opacity-75 mb-0 small">Por favor, selecciona una marca en el filtro superior para visualizar el proyecto asociado y sus respectivos reportes diarios.</p>
                    </div>
                </div>
            </div>
        <?php elseif(!$selectedProject): ?>
            <div class="panel-card py-5 px-4 text-start border-start border-4 border-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="fs-1 text-warning"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div>
                        <h5 class="fw-bold text-white mb-1">Sin Proyecto Registrado</h5>
                        <p class="text-light opacity-75 mb-0 small">La marca seleccionada aún no cuenta con un proyecto generado en el sistema.</p>
                    </div>
                </div>
            </div>
        <?php else: ?>

            <?php 
                // Calcular métricas para los 3 minidashboards exactos
                $totalReportes = count($reports);
                $completosSinObs = 0;
                $observados = 0;

                if(!empty($reports)) {
                    foreach($reports as $r) {
                        // Se consideran sin observaciones si fueron aceptados o no tienen observaciones registradas
                        if(($r['estado_actividad'] === 'Aceptado' && empty($r['observaciones'])) || (empty($r['observaciones']) && $r['estado_actividad'] !== 'Observado')) {
                            $completosSinObs++;
                        } elseif($r['estado_actividad'] === 'Observado' || !empty($r['observaciones'])) {
                            $observados++;
                        }
                    }
                }
            ?>

            <!-- INFORMACIÓN DEL PROYECTO SELECCIONADO -->
            <div class="panel-card mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <span class="badge bg-info bg-opacity-25 text-info border border-info px-2 py-1 mb-2">Proyecto #<?php echo $selectedProject['id_proyecto']; ?></span>
                        <h3 class="fw-bold text-white mb-1"><?php echo htmlspecialchars($selectedProject['titulo_proyecto']); ?></h3>
                        <p class="text-light opacity-75 mb-0 small"><i class="fa-solid fa-user-tie me-1 text-info"></i> Responsable: <strong><?php echo htmlspecialchars($selectedProject['responsable'] ?? 'No asignado'); ?></strong></p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-secondary bg-opacity-50 text-light px-3 py-2 border border-secondary">
                            <i class="fa-solid fa-calendar-day me-1"></i> 
                            <?php if(!empty($_GET['usar_rango'])): ?>
                                Rango: <?php echo htmlspecialchars($_GET['fecha_inicio'] ?? $fecha); ?> al <?php echo htmlspecialchars($_GET['fecha_fin'] ?? date('Y-m-d')); ?>
                            <?php else: ?>
                                Fecha consultada: <?php echo $fecha; ?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- 3 MINIDASHBOARDS SIMÉTRICOS -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="stat-card border-cyan">
                        <div class="text-info fs-2 fw-bold mb-1"><i class="fa-solid fa-file-lines me-2"></i><?php echo $totalReportes; ?></div>
                        <div class="text-light opacity-75 small text-uppercase fw-semibold">Total de Reportes Recibidos</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card border-green">
                        <div class="text-success fs-2 fw-bold mb-1"><i class="fa-solid fa-circle-check me-2"></i><?php echo $completosSinObs; ?></div>
                        <div class="text-light opacity-75 small text-uppercase fw-semibold">Completos sin Observaciones</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card border-yellow">
                        <div class="text-warning fs-2 fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo $observados; ?></div>
                        <div class="text-light opacity-75 small text-uppercase fw-semibold">Reportes Observados</div>
                    </div>
                </div>
            </div>

            <!-- LISTADO DE REPORTES / ENTREGABLES CON FILTRO POR TRABAJADOR -->
            <div class="panel-card">
                <div class="row align-items-center mb-4">
                    <div class="col-md-8">
                        <h4 class="fw-bold text-uppercase mb-1" style="color: #00a8cc; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-list-check me-2"></i> Reportes Diarios Recibidos
                        </h4>
                        <p class="text-light opacity-75 mb-0 small">Revisa los entregables de los colaboradores, acepta los correctos o añade observaciones.</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-light text-uppercase mb-1">Filtrar por Colaborador</label>
                        <select id="filtroColaborador" class="form-select form-select-custom" onchange="filtrarPorColaborador()">
                            <option value="">-- Todos los colaboradores --</option>
                            <?php 
                                $nombresUnicos = [];
                                foreach($reports as $r) {
                                    $nombresUnicos[$r['colaborador']] = true;
                                }
                                foreach(array_keys($nombresUnicos) as $nombreColab):
                            ?>
                                <option value="<?php echo htmlspecialchars($nombreColab); ?>"><?php echo htmlspecialchars($nombreColab); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Colaborador</th>
                                <th>Actividad y Resumen</th>
                                <th>Avance</th>
                                <th>Evidencia</th>
                                <th>Estado</th>
                                <th>Observaciones</th>
                                <?php if(isset($_SESSION['role_id']) && ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2)): ?>
                                <th class="text-center">Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tablaReportes">
                            <?php if(!empty($reports)): ?>
                                <?php 
                                $contador = 1;
                                foreach($reports as $rep): 
                                ?>
                                <tr data-colaborador="<?php echo htmlspecialchars($rep['colaborador']); ?>">
                                    <td class="fw-bold text-info">#<?php echo $contador++; ?></td>
                                    <td class="fw-bold nombre-colab"><?php echo htmlspecialchars($rep['colaborador']); ?></td>
                                    <td>
                                        <span class="text-info fw-bold"><?php echo htmlspecialchars($rep['titulo']); ?></span>
                                        <p class="small text-light opacity-75 mb-0 mt-1" style="max-width: 280px;"><?php echo nl2br(htmlspecialchars($rep['descripcion'])); ?></p>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark border border-info text-info px-2 py-1"><?php echo $rep['porcentaje_avance']; ?>%</span>
                                    </td>
                                    <td>
                                        <?php if(!empty($rep['ruta_archivo'])): ?>
                                            <a href="<?php echo htmlspecialchars($rep['ruta_archivo']); ?>" target="_blank" class="btn btn-sm btn-outline-info px-2 py-1 shadow-sm" style="font-size: 0.75rem;">
                                                <i class="fa-solid fa-image me-1"></i> Ver Imagen
                                            </a>
                                        <?php else: ?>
                                            <span class="text-secondary small">Sin evidencia</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($rep['estado_actividad'] === 'Aceptado'): ?>
                                            <span class="badge bg-success bg-opacity-25 text-success border border-success px-2 py-1">Aceptado</span>
                                        <?php elseif($rep['estado_actividad'] === 'Observado'): ?>
                                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning px-2 py-1">Observado</span>
                                        <?php else: ?>
                                            <span class="badge bg-info bg-opacity-25 text-info border border-info px-2 py-1"><?php echo htmlspecialchars($rep['estado_actividad']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($rep['observaciones'])): ?>
                                            <?php foreach($rep['observaciones'] as $obs): ?>
                                                <div class="observation-box">
                                                    <div class="text-warning fw-bold mb-1" style="font-size: 0.7rem;">
                                                        <i class="fa-solid fa-user-shield me-1"></i> <?php echo htmlspecialchars($obs['autor_observacion']); ?>:
                                                    </div>
                                                    <p class="mb-0 text-light"><?php echo htmlspecialchars($obs['comentario']); ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-secondary small italic">Sin observaciones</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if(isset($_SESSION['role_id']) && ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2)): ?>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <form action="index.php?controller=projects&action=index&id_marca=<?php echo $id_marca; ?>&fecha=<?php echo $fecha; ?>" method="POST" class="d-inline">
                                                <input type="hidden" name="action_type" value="accept_report">
                                                <input type="hidden" name="id_actividad" value="<?php echo $rep['id_actividad']; ?>">
                                                <button type="submit" class="btn btn-sm btn-success px-2 py-1 fw-bold shadow-sm" title="Aceptar reporte">
                                                    <i class="fa-solid fa-check"></i> Aceptar
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-sm btn-warning px-2 py-1 fw-bold text-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#observeModal<?php echo $rep['id_actividad']; ?>" title="Marcar como observado">
                                                <i class="fa-solid fa-triangle-exclamation"></i> Observar
                                            </button>
                                        </div>

                                        <div class="modal fade" id="observeModal<?php echo $rep['id_actividad']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content text-white" style="background-color: #15242e; border: 1px solid #2a4150;">
                                                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                                                        <h5 class="modal-title fw-bold text-warning fs-6">Observar Reporte #<?php echo $rep['id_actividad']; ?></h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="index.php?controller=projects&action=index&id_marca=<?php echo $id_marca; ?>&fecha=<?php echo $fecha; ?>" method="POST">
                                                        <div class="modal-body text-start">
                                                            <input type="hidden" name="action_type" value="observe_report">
                                                            <input type="hidden" name="id_actividad" value="<?php echo $rep['id_actividad']; ?>">
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold text-light text-uppercase">Comentario / Motivo de la Observación</label>
                                                                <textarea name="comentario" rows="3" class="form-control form-control-custom" placeholder="Indica detalladamente qué debe corregir el colaborador..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top border-secondary border-opacity-25">
                                                            <button type="button" class="btn btn-outline-light btn-sm px-3" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-warning btn-sm px-4 fw-bold text-dark">Guardar Observación</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-light py-5">
                                        <i class="fa-solid fa-folder-open display-6 text-secondary mb-2 d-block"></i>
                                        No hay reportes ni entregables registrados para el periodo seleccionado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <!-- Scripts de interactividad para fechas y filtro por colaborador -->
    <script>
        function toggleRangoFechas() {
            const usarRango = document.getElementById('usarRango').checked;
            const divUnica = document.getElementById('divFechaUnica');
            const divsRango = document.querySelectorAll('.div-rango');

            if (usarRango) {
                divUnica.style.display = 'none';
                divsRango.forEach(el => el.style.display = 'block');
            } else {
                divUnica.style.display = 'block';
                divsRango.forEach(el => el.style.display = 'none');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleRangoFechas();
        });

        function filtrarPorColaborador() {
            const selectColab = document.getElementById('filtroColaborador');
            const valorFiltro = selectColab.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaReportes tr');

            filas.forEach(fila => {
                const colabAttr = fila.getAttribute('data-colaborador');
                if (colabAttr) {
                    if (valorFiltro === "" || colabAttr.toLowerCase().includes(valorFiltro)) {
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