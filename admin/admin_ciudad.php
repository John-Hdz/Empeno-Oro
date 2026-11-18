<?php
require_once __DIR__ . '/models/modelo_ciudad.php';
require_once __DIR__ . '/models/modelo_estado.php';

$web = new Ciudad();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_ciudad.php');

$webEstado = new Estado();
$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = ['ciudad' => $_POST['ciudad'] ?? '', 'id_estado' => $_POST['id_estado'] ?? ''];
    header('Location: admin_ciudad.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = ['ciudad' => $_POST['ciudad'] ?? '', 'id_estado' => $_POST['id_estado'] ?? ''];
    header('Location: admin_ciudad.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_ciudad.php?msg=deleted');
        exit();
    }
    header('Location: admin_ciudad.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        $estados = $webEstado->leer();
        require_once __DIR__ . '/views/ciudad/form.php';
        break;

    case 'modificar':
        $estados = $webEstado->leer();
        if ($id) {
            $ciudad = $web->leerPorIdciudad($id);
            if ($ciudad) {
                require_once __DIR__ . '/views/ciudad/form.php';
            } else {
                echo 'ciudad no encontrado.';
            }
        }
        break;

    default:
        $ciudades = $web->leer();
        require_once __DIR__ . '/views/ciudad/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
