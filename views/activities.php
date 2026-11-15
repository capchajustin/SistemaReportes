<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario de Actividades | Sistema</title>
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
    </style>
</head>
<body class="bg-dark text-white">

<?php $active_page = 'activities'; ?>
<?php include_once 'views/layouts/navbar.php'; ?>

    <!-- Contenido Principal -->
    <div class="dashboard-container py-5 px-3">

        <!-- ALERTA DE ÉXITO O ERROR -->
        <?php if(!empty($mensaje)): ?>
            <div class="alert alert-success bg-success bg-opacity-10 text-success border border-success py-2 small mb-4 rounded-3">
                <i class="fa-solid fa-circle-check me-1"></i> <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border border-danger py-2 small mb-4 rounded-3">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- EVALUACIÓN: ¿EL USUARIO TIENE MARCAS / PROYECTOS ASIGNADOS? -->
        <?php if(empty($myProjects)): ?>
            
            <div class="panel-card text-center py-5">
                <div class="py-4">
                    <i class="fa-solid fa-folder-minus display-1 text-warning mb-3 opacity-75"></i>
                    <h4 class="fw-bold text-white mb-2">No tienes marcas asignadas</h4>
                    <p class="text-light opacity-75">Pídele al líder de área que te asigne una para registrar tus actividades diarias.</p>
                </div>
            </div>

        <?php else: ?>

            <!-- FORMULARIO DE REPORTE DIARIO CON PORCENTAJE DE AVANCE -->
            <div class="panel-card mb-5">
                <h4 class="fw-bold text-uppercase mb-1" style="color: #00a8cc; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Registrar Reporte Diario de Actividades
                </h4>
                <p class="text-light opacity-75 mb-4 small">Selecciona la marca asignada, indica tu porcentaje de avance y detalla las tareas realizadas.</p>

                <form action="index.php?controller=activities&action=index" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action_type" value="create_report">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-light text-uppercase">Marca / Proyecto Asignado</label>
                            <select name="id_proyecto" class="form-select form-select-custom" required>
                                <option value="" disabled selected>Selecciona una marca/proyecto...</option>
                                <?php foreach($myProjects as $proj): ?>
                                    <option value="<?php echo $proj['id_proyecto']; ?>">
                                        <?php echo htmlspecialchars($proj['nombre_marca']); ?> - <?php echo htmlspecialchars($proj['titulo_proyecto']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-light text-uppercase">Título General del Trabajo</label>
                            <input type="text" name="titulo" class="form-control form-control-custom" placeholder="Ej. Avance en maquetación frontend" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-light text-uppercase">Porcentaje de Avance (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="porcentaje_avance" class="form-control form-control-custom" placeholder="Ej. 50 o 100" required>
                            <small class="text-light opacity-75 mt-1 d-block" style="font-size: 0.75rem;">Indica el porcentaje completado en la jornada (0 al 100).</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-light text-uppercase">Subir Evidencia Fotográfica (Captura / Imagen)</label>
                            <input type="file" name="evidencia" class="form-control form-control-custom" accept="image/*" required>
                            <small class="text-light opacity-75 mt-1 d-block" style="font-size: 0.75rem;">Formatos aceptados: JPG, PNG, GIF, WEBP.</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-light text-uppercase">Resumen Diario de lo Realizado</label>
                        <textarea name="descripcion" rows="4" class="form-control form-control-custom" placeholder="Escribe en detalle todas las tareas y entregables logrados en el día..." required></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-cyan text-uppercase fw-bold px-4" style="background-color: #00a8cc; color: #fff;">
                            <i class="fa-solid fa-paper-plane me-1"></i> Guardar y Enviar Reporte
                        </button>
                    </div>
                </form>
            </div>

        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>