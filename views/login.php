<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Sistema de Seguimiento y Gestión de Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-card {
            background-color: #15242e;
            border-radius: 14px;
            padding: 40px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.5);
        }

        .login-input {
            background-color: #0b151b !important;
            border: 1px solid #2a4150 !important;
            color: #ffffff !important;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        /* Estilo para que el texto de marcador de posición (placeholder) se vea claro y legible */
        .login-input::placeholder {
            color: #8fa3b0 !important;
            opacity: 1;
        }

        .login-input:focus {
            border-color: #00a8cc !important;
            box-shadow: 0 0 0 3px rgba(0, 168, 204, 0.25) !important;
        }

        .form-label-custom {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #ffffff;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .text-cyan {
            color: #00a8cc !important;
        }

        .form-check-input {
            background-color: #0b151b;
            border-color: #2a4150;
        }

        .form-check-input:checked {
            background-color: #00a8cc;
            border-color: #00a8cc;
        }
    </style>
</head>
<body class="bg-dark text-white d-flex align-items-center justify-content-center vh-100">

    <div class="login-card text-center">
        
        <!-- Logo Superior -->
        <div class="mb-4">
            <img src="assets/img/logo.png" alt="Consigue Ventas" style="height: 48px; width: auto;" class="mb-3">
            <h4 class="fw-bold text-uppercase mb-1" style="font-size: 1.25rem; letter-spacing: 0.5px;">CONTROL DE ACCESO</h4>
            <span class="small text-uppercase text-cyan fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">DEPARTAMENTO DE TECNOLOGÍA</span>
        </div>

        <!-- Alerta de Error si existe -->
<!-- Mensaje de error corporativo (Solo se renderiza si existe un error real) -->
<?php if (!empty($error)): ?>
    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border border-danger py-2 small mb-4 rounded-3 text-center">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

        <!-- Formulario -->
        <form action="index.php?controller=auth&action=authenticate" method="POST" class="text-start">
            
            <div class="mb-3">
                <label class="form-label-custom d-block">CORREO ELECTRÓNICO</label>
                <input type="email" name="email" class="form-control login-input" placeholder="usuario@consigueventas.com" required autocomplete="email">
            </div>

            <div class="mb-3">
                <label class="form-label-custom d-block">CONTRASEÑA</label>
                <div class="position-relative">
                    <input type="password" name="password" id="passwordField" class="form-control login-input pe-5" placeholder="••••••••" required>
                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary" style="cursor: pointer;" onclick="togglePassword()">
                        <i class="fa-regular fa-eye" id="toggleIcon"></i>
                    </span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 small">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label text-light" for="remember" style="font-size: 0.82rem;">Recordar sesión</label>
                </div>
                <a href="#" class="text-decoration-none text-cyan fw-semibold" style="font-size: 0.82rem;" onclick="alert('Por favor contacte al líder o responsable del área para restablecer su contraseña.')">¿Olvidó su contraseña?</a>
            </div>

            <button type="submit" class="btn btn-cyan w-100 text-uppercase py-2 fs-6 fw-bold">INGRESAR AL SISTEMA</button>
        </form>

        <!-- Volver al inicio -->
        <div class="text-center mt-4">
            <a href="index.php?controller=home&action=index" class="text-decoration-none text-secondary small" style="transition: color 0.2s;">
                &larr; Volver a la página de inicio
            </a>
        </div>

    </div>

    <!-- Script para alternar visibilidad de contraseña -->
    <script>
        function togglePassword() {
            const pwd = document.getElementById('passwordField');
            const icon = document.getElementById('toggleIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>