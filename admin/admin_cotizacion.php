<?php
require_once __DIR__ . '/models/modelo_cotizacion.php';

$web = new Cotizacion();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_cotizacion.php');

$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'cotizacion' => $_POST['cotizacion'] ?? '',
        'tipo_cotizacion' => $_POST['tipo_cotizacion'] ?? '',
    ];
    header('Location: admin_cotizacion.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = [
        'cotizacion' => $_POST['cotizacion'] ?? '',
        'tipo_cotizacion' => $_POST['tipo_cotizacion'] ?? '',
    ];
    header('Location: admin_cotizacion.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_cotizacion.php?msg=deleted');
        exit();
    }
    header('Location: admin_cotizacion.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        require_once __DIR__ . '/views/cotizacion/form.php';
        break;

    case 'modificar':
        if ($id) {
            $cotizacion = $web->leerPorIdCotizacion($id);
            if ($cotizacion) {
                require_once __DIR__ . '/views/cotizacion/form.php';
            } else {
                echo 'Cotización no encontrada.';
            }
        }
        break;

    default:
        $cotizaciones = $web->leer();
        require_once __DIR__ . '/views/cotizacion/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
