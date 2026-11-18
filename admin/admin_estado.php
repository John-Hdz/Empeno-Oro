<?php
require_once __DIR__ . '/models/modelo_estado.php';

$web = new Estado();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_estado.php');

$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = ['estado' => $_POST['estado'] ?? ''];
    header('Location: admin_estado.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = ['estado' => $_POST['estado'] ?? ''];
    header('Location: admin_estado.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_estado.php?msg=deleted');
        exit();
    }
    header('Location: admin_estado.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        require_once __DIR__ . '/views/estado/form.php';
        break;

    case 'modificar':
        if ($id) {
            $estado = $web->leerPorIdestado($id);
            if ($estado) {
                require_once __DIR__ . '/views/estado/form.php';
            } else {
                echo 'estado no encontrado.';
            }
        }
        break;

    default:
        $estados = $web->leer();
        require_once __DIR__ . '/views/estado/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
