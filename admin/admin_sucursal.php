<?php
require_once __DIR__ . '/models/modelo_sucursal.php';
require_once __DIR__ . '/models/modelo_localidad.php';

$web = new Sucursal();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_sucursal.php');

$web_localidad = new Localidad();
$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'sucursal' => $_POST['sucursal'] ?? '',
        'id_localidad' => $_POST['id_localidad'] ?? '',
    ];
    header('Location: admin_sucursal.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = [
        'sucursal' => $_POST['sucursal'] ?? '',
        'id_localidad' => $_POST['id_localidad'] ?? '',
    ];
    header('Location: admin_sucursal.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_sucursal.php?msg=deleted');
        exit();
    }
    header('Location: admin_sucursal.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        $localidades = $web_localidad->leer();
        require_once __DIR__ . '/views/sucursal/form.php';
        break;

    case 'modificar':
        $localidades = $web_localidad->leer();
        if ($id) {
            $sucursal = $web->leerPorIdsucursal($id);
            if ($sucursal) {
                require_once __DIR__ . '/views/sucursal/form.php';
            } else {
                echo 'Sucursal no encontrada.';
            }
        }
        break;

    default:
        $sucursales = $web->leer();
        require_once __DIR__ . '/views/sucursal/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
