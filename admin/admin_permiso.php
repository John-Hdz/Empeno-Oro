<?php
require_once __DIR__ . '/models/modelo_permiso.php';

$web = new Permiso();
$web->requerirRol('Administrador');
$web->requerirCsrf('admin_permiso.php');

$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = ['permiso' => $_POST['permiso'] ?? ''];
    header('Location: admin_permiso.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = ['permiso' => $_POST['permiso'] ?? ''];
    header('Location: admin_permiso.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_permiso.php?msg=deleted');
        exit();
    }
    header('Location: admin_permiso.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        require_once __DIR__ . '/views/permiso/form.php';
        break;

    case 'modificar':
        if ($id) {
            $permiso = $web->leerPorIdpermiso($id);
            if ($permiso) {
                require_once __DIR__ . '/views/permiso/form.php';
            } else {
                echo 'permiso no encontrado.';
            }
        }
        break;

    default:
        $permisos = $web->leer();
        require_once __DIR__ . '/views/permiso/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
