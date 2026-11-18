<?php
require_once __DIR__ . '/models/modelo_rol.php';
require_once __DIR__ . '/models/modelo_permiso.php';

$web = new Rol();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_rol.php');

$web2 = new Permiso();
$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
$permisos = $web2->leer();

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'rol' => $_POST['rol'] ?? '',
        'permisos' => $_POST['permisos'] ?? [],
    ];
    header('Location: admin_rol.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = [
        'rol' => $_POST['rol'] ?? '',
        'permisos' => $_POST['permisos'] ?? [],
    ];
    header('Location: admin_rol.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_rol.php?msg=deleted');
        exit();
    }
    header('Location: admin_rol.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        require_once __DIR__ . '/views/rol/form.php';
        break;

    case 'modificar':
        if ($id) {
            $permisos_rol = $web->leerPermisosRol($id);
            $permisos_rol = array_column($permisos_rol, 'permiso');
            $rol = $web->leerPorIdrol($id);
            if ($rol) {
                require_once __DIR__ . '/views/rol/form.php';
            } else {
                echo 'rol no encontrado.';
            }
        }
        break;

    default:
        $roles = $web->leer();
        require_once __DIR__ . '/views/rol/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
