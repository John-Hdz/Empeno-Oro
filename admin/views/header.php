<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Presta Prenda</title>
    <link rel="stylesheet" href="/Empenio/CSS/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <script defer src="/Empenio/JS/script.js"></script>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img class="icon-logo" src="/Empenio/images/iconos/logo-icon.png" alt="">
            <a href="/Empenio/admin/admin_index.php">Presta Prenda</a>
        </div>
        <div class="nav-container">
        <ul class="nav-links">
            <li><a href="/Empenio/admin/admin_index.php">Inicio</a></li>
            <li><a href="/Empenio/admin/admin_cotizacion_online.php">Cotización Online</a></li>
            <?php if( $web -> sesionActiva()): ?>
                <li><a href="/Empenio/admin/admin_empenios.php">Empeños</a></li>
            <?php endif; ?>
            <?php if( $web -> checar_permiso('Administracion')): ?>
            <li class="dropdown">
                    <a href="#">Administración▼</a>
                    <ul class="dropdown-menu">
                        <li><a href="/Empenio/admin/admin_kilataje.php">Kilatajes</a></li>
                        <li><a href="/Empenio/admin/admin_cotizacion.php">Cotizaciones</a></li>
                        <li><a href="/Empenio/admin/admin_articulo.php">Articulos</a></li>
                        <li><a href="/Empenio/admin/admin_clasificacion.php">Clasificaciones</a></li>
                        <li><a href="/Empenio/admin/admin_guarda_valores.php">Guarda Valores</a></li>
                        <li><a href="/Empenio/admin/admin_transaccion.php">Transacciones</a></li>
                        <li><a href="/Empenio/admin/admin_sucursal.php">Sucursales</a></li>
                        <li><a href="/Empenio/admin/admin_estado.php">Estados</a></li>
                        <li><a href="/Empenio/admin/admin_ciudad.php">Ciudades</a></li>
                        <li><a href="/Empenio/admin/admin_localidad.php">Localidades</a></li>
                    </ul>
            </li>
            <?php endif; ?>
            <?php if( $web -> checar_permiso('Acceso')): ?>
            <li class="dropdown">
                    <a href="#">Acceso▼</a>
                    <ul class="dropdown-menu">
                        <li><a href="/Empenio/admin/admin_empleado.php">Empleados</a></li>
                        <li><a href="/Empenio/admin/admin_cliente.php">Clientes</a></li>
                        <li><a href="/Empenio/admin/admin_rol.php">Roles</a></li>
                        <li><a href="/Empenio/admin/admin_permiso.php">Permisos</a></li>
                        <li><a href="/Empenio/admin/admin_usuario.php">Usuarios</a></li>
                    </ul>
            </li>
            <?php endif; ?>
        </ul>
        <div class="buttons">
            <?php if( !$web -> sesionActiva()): ?>
            <a href="/Empenio/admin/admin_login.php" class="btn light">Iniciar Sesión</a>
            <?php endif; ?>
            <?php if( $web -> sesionActiva()): ?>
            <a href="/Empenio/admin/admin_login.php?accion=logout" class="btn dark">Cerar Sesión</a>
            <?php endif; ?>
        </div>
        </div>
        <div class="menu-toggle">
            ☰
        </div>
    </nav>

<!-- ALERTA GLOBAL -->
<div id="background-alert"><div  id="alert-container"></div></div>

<!-- MODAL GLOBAL -->
<div id="customModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">Título</h2>
        <div id="modalBody">Contenido</div>
        <div id="modalFooter" class="modal-footer centralizar"></div>
    </div>
</div>