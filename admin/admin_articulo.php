<?php
require_once __DIR__ . '/models/modelo_articulo.php';

$web = new Articulo();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_articulo.php');

$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = ['articulo' => $_POST['articulo'] ?? ''];
    header('Location: admin_articulo.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = ['articulo' => $_POST['articulo'] ?? ''];
    header('Location: admin_articulo.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_articulo.php?msg=deleted');
        exit();
    }
    header('Location: admin_articulo.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        require_once __DIR__ . '/views/articulo/form.php';
        break;

    case 'modificar':
        if ($id) {
            $articulo = $web->leerPorIdArticulo($id);
            if ($articulo) {
                require_once __DIR__ . '/views/articulo/form.php';
            } else {
                echo 'articulo no encontrado.';
            }
        }
        break;

    default:
        $articulos = $web->leer();
        require_once __DIR__ . '/views/articulo/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
