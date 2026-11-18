<?php
require_once __DIR__ . '/models/modelo_usuario.php';
require_once __DIR__ . '/models/modelo_rol.php';
require_once __DIR__ . '/model.php';

$web = new Usuario();
$web->requerirRol('Administrador');
$web->requerirCsrf('admin_usuario.php');

$web2 = new Rol();
$app = new Model();
$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
$roles = $web2->leerRoles();

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'correo' => $_POST['correo'] ?? '',
        'contrasenia' => $_POST['contrasenia'] ?? '',
        'contrasenia2' => $_POST['contrasenia2'] ?? '',
        'roles' => $_POST['roles'] ?? [],
    ];
    header('Location: admin_usuario.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = [
        'correo' => $_POST['correo'] ?? '',
        'roles' => $_POST['roles'] ?? [],
    ];
    header('Location: admin_usuario.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_usuario.php?msg=deleted');
        exit();
    }
    header('Location: admin_usuario.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        require_once __DIR__ . '/views/usuario/form.php';
        break;

    case 'modificar':
        if ($id) {
            $roles_usuario = $web2->leerRolUsuario($id);
            $usuario = $web->leerPorIdusuario($id);
            if ($usuario) {
                require_once __DIR__ . '/views/usuario/form.php';
            } else {
                echo 'usuario no encontrado.';
            }
        }
        break;

    default:
        $usuarios = $web->leer();
        require_once __DIR__ . '/views/usuario/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
