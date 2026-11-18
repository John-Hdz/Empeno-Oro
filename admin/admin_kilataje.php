<?php
require_once __DIR__ . '/models/modelo_kilataje.php';

$web = new Kilataje();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_kilataje.php');

$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = ['kilataje' => $_POST['kilataje'] ?? '', 'precio_gramo' => $_POST['precio_gramo'] ?? ''];
    header('Location: admin_kilataje.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = ['kilataje' => $_POST['kilataje'] ?? '', 'precio_gramo' => $_POST['precio_gramo'] ?? ''];
    header('Location: admin_kilataje.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_kilataje.php?msg=deleted');
        exit();
    }
    header('Location: admin_kilataje.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        require_once __DIR__ . '/views/kilataje/form.php';
        break;

    case 'modificar':
        if ($id) {
            $kilataje = $web->leerPorIdkilataje($id);
            if ($kilataje) {
                require_once __DIR__ . '/views/kilataje/form.php';
            } else {
                echo 'kilataje no encontrado.';
            }
        }
        break;

    default:
        $kilatajes = $web->leer();
        require_once __DIR__ . '/views/kilataje/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
