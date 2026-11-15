<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reportes y Observaciones | Sistema</title>
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
        .table-custom { color: #ffffff !important; border-color: #2a4150; }
        .table-custom th { background-color: #0f1c24; color: #00a8cc; border-bottom: 2px solid #2a4150; }
        .table-custom td { background-color: #15242e !important; color: #ffffff !important; border-color: #2a4150; vertical-align: middle; }
        
        /* Caja de observaciones con ancho mínimo y diseño en bloque espaciado */
        .observation-box {
            background-color: #0b151b;
            border: 1px solid #3a5060;
            border-left: 4px solid #ffc107;
            padding: 14px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-top: 6px;
            min-width: 280px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        }
    </style>
</head>
<body class="bg-dark text-white">

<?php $active_page = 'my_reports'; ?>
<?php include_once 'views/layouts/navbar.php'; ?>

    <!-- Contenido Principal -->
    <div class="dashboard-container py-5 px-3">
        <div class="panel-card">
            <h3 class="fw-bold text-uppercase mb-1" style="color: #00a8cc; letter-spacing: 0.5px;">
                <i class="fa-solid fa-clock-rotate-left me-2"></i> Mis Reportes y Observaciones
            </h3>
            <p class="text-light opacity-75 mb-4 small">Histórico de tus reportes diarios enviados y las observaciones realizadas por los responsables.</p>

            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 8%;">#ID</th>
                            <th style="width: 18%;">Marca / Proyecto</th>
                            <th style="width: 28%;">Título y Resumen</th>
                            <th style="width: 12%;">Evidencia</th>
                            <th style="width: 12%;">Estado</th>
                            <th style="width: 22%;">Observaciones del Líder</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($myReports)): ?>
                            <?php 
                            $contador = 1; // Inicializamos el contador incremental en 1
                            foreach($myReports as $rep): 
                            ?>
                            <tr>
                                <!-- Mostramos el contador correlativo en lugar del ID físico -->
                                <td class="fw-bold text-info">#<?php echo $contador++; ?></td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-50 text-light border border-secondary px-2 py-1 mb-1">
                                        <?php echo htmlspecialchars($rep['nombre_marca']); ?>
                                    </span>
                                    <div class="small opacity-75"><?php echo htmlspecialchars($rep['titulo_proyecto']); ?></div>
                                </td>
                                <td>
                                    <strong class="text-info"><?php echo htmlspecialchars($rep['titulo']); ?></strong>
                                    <p class="small text-light opacity-75 mb-0 mt-1"><?php echo nl2br(htmlspecialchars($rep['descripcion'])); ?></p>
                                </td>
                                <td>
                                    <?php if(!empty($rep['ruta_archivo'])): ?>
                                        <a href="<?php echo htmlspecialchars($rep['ruta_archivo']); ?>" target="_blank" class="btn btn-sm btn-outline-info px-2 py-1" style="font-size: 0.75rem;">
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
                                                <!-- Estructura en bloque vertical con espacio adecuado -->
                                                <div class="text-warning fw-bold mb-1" style="font-size: 0.8rem;">
                                                    <i class="fa-solid fa-user-tie me-1"></i> <?php echo htmlspecialchars($obs['autor_observacion']); ?>
                                                </div>
                                                <div class="text-light opacity-75 mb-2" style="font-size: 0.72rem;">
                                                    <i class="fa-regular fa-clock me-1"></i> <?php echo $obs['fecha_creacion']; ?>
                                                </div>
                                                <div class="border-top border-secondary border-opacity-25 pt-2 mb-2 text-light" style="font-size: 0.82rem;">
                                                    <?php echo htmlspecialchars($obs['comentario']); ?>
                                                </div>
                                                <div>
                                                    <span class="badge bg-dark border border-warning text-warning px-2 py-1" style="font-size: 0.7rem;">
                                                        <i class="fa-solid fa-clock-rotate-left me-1"></i> <?php echo $obs['estado_correccion']; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-secondary small italic">Sin observaciones</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-light py-4">Aún no has registrado ningún reporte en el sistema.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>