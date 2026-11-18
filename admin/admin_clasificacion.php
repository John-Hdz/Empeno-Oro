<?php
require_once __DIR__ . '/models/modelo_clasificacion.php';

$web = new Clasificacion();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_clasificacion.php');

$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = ['clasificacion' => $_POST['clasificacion'] ?? ''];
    header('Location: admin_clasificacion.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = ['clasificacion' => $_POST['clasificacion'] ?? ''];
    header('Location: admin_clasificacion.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_clasificacion.php?msg=deleted');
        exit();
    }
    header('Location: admin_clasificacion.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        require_once __DIR__ . '/views/clasificacion/form.php';
        break;

    case 'modificar':
        if ($id) {
            $clasificacion = $web->leerPorIdclasificacion($id);
            if ($clasificacion) {
                require_once __DIR__ . '/views/clasificacion/form.php';
            } else {
                echo 'Clasificación no encontrada.';
            }
        }
        break;

    default:
        $clasificaciones = $web->leer();
        require_once __DIR__ . '/views/clasificacion/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
