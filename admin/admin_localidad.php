<?php
require_once __DIR__ . '/models/modelo_localidad.php';
require_once __DIR__ . '/models/modelo_ciudad.php';

$web = new localidad();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_localidad.php');

$webCiudad = new ciudad();
$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = ['localidad' => $_POST['localidad'] ?? '', 'id_ciudad' => $_POST['id_ciudad'] ?? ''];
    header('Location: admin_localidad.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = ['localidad' => $_POST['localidad'] ?? '', 'id_ciudad' => $_POST['id_ciudad'] ?? ''];
    header('Location: admin_localidad.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_localidad.php?msg=deleted');
        exit();
    }
    header('Location: admin_localidad.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        $ciudades = $webCiudad->leer();
        require_once __DIR__ . '/views/localidad/form.php';
        break;

    case 'modificar':
        $ciudades = $webCiudad->leer();
        if ($id) {
            $localidad = $web->leerPorIdlocalidad($id);
            if ($localidad) {
                require_once __DIR__ . '/views/localidad/form.php';
            } else {
                echo 'localidad no encontrado.';
            }
        }
        break;

    default:
        $localidades = $web->leer();
        require_once __DIR__ . '/views/localidad/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
