<?php
require_once __DIR__ . '/models/modelo_empleado.php';
require_once __DIR__ . '/models/modelo_sucursal.php';
require_once __DIR__ . '/models/modelo_localidad.php';

$web = new Empleado();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_empleado.php');

$web_sucursal = new Sucursal();
$web_localidad = new Localidad();
$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['contrasenia'] ?? '') !== ($_POST['confirmar_contrasenia'] ?? '')) {
        header('Location: admin_empleado.php?msg=error_contrasenia');
        exit();
    }
    $data = [
        'primer_apellido' => $_POST['primer_apellido'] ?? '',
        'segundo_apellido' => $_POST['segundo_apellido'] ?? '',
        'nombre' => $_POST['nombre'] ?? '',
        'nacimiento' => $_POST['nacimiento'] ?? '',
        'rfc' => $_POST['rfc'] ?? '',
        'curp' => $_POST['curp'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'id_localidad' => $_POST['id_localidad'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'id_sucursal' => $_POST['id_sucursal'] ?? '',
        'codigo_postal' => $_POST['codigo_postal'] ?? '',
        'correo' => $_POST['correo'] ?? '',
        'contrasenia' => $_POST['contrasenia'] ?? '',
    ];
    header('Location: admin_empleado.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = [
        'primer_apellido' => $_POST['primer_apellido'] ?? '',
        'segundo_apellido' => $_POST['segundo_apellido'] ?? '',
        'nombre' => $_POST['nombre'] ?? '',
        'nacimiento' => $_POST['nacimiento'] ?? '',
        'rfc' => $_POST['rfc'] ?? '',
        'curp' => $_POST['curp'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'id_localidad' => $_POST['id_localidad'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'id_sucursal' => $_POST['id_sucursal'] ?? '',
        'codigo_postal' => $_POST['codigo_postal'] ?? '',
        'correo' => $_POST['correo'] ?? '',
    ];
    header('Location: admin_empleado.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_empleado.php?msg=deleted');
        exit();
    }
    header('Location: admin_empleado.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        $localidades = $web_localidad->leer();
        $sucursales = $web_sucursal->leer();
        require_once __DIR__ . '/views/empleado/form.php';
        break;

    case 'modificar':
        $sucursales = $web_sucursal->leer();
        $localidades = $web_localidad->leer();
        if ($id) {
            $empleado = $web->leerPorIdempleado($id);
            if ($empleado) {
                require_once __DIR__ . '/views/empleado/form.php';
            } else {
                echo 'empleado no encontrado.';
            }
        }
        break;

    default:
        $empleados = $web->leer();
        require_once __DIR__ . '/views/empleado/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
