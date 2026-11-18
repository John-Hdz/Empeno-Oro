<?php
require_once __DIR__ . '/models/modelo_cliente.php';
require_once __DIR__ . '/models/modelo_sucursal.php';
require_once __DIR__ . '/models/modelo_localidad.php';

$web = new cliente();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_cliente.php');

$web_sucursal = new Sucursal();
$web_localidad = new Localidad();
$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['contrasenia'] ?? '') !== ($_POST['confirmar_contrasenia'] ?? '')) {
        header('Location: admin_cliente.php?msg=error_contrasenia');
        exit();
    }
    $data = [
        'primer_apellido' => $_POST['primer_apellido'] ?? '',
        'segundo_apellido' => $_POST['segundo_apellido'] ?? '',
        'nombre' => $_POST['nombre'] ?? '',
        'nacimiento' => $_POST['nacimiento'] ?? '',
        'curp' => $_POST['curp'] ?? '',
        'correo' => $_POST['correo'] ?? '',
        'contrasenia' => $_POST['contrasenia'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'codigo_postal' => $_POST['codigo_postal'] ?? '',
        'id_localidad' => $_POST['id_localidad'] ?? '',
        'id_sucursal' => $_POST['id_sucursal'] ?? '',
    ];
    header('Location: admin_cliente.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = [
        'primer_apellido' => $_POST['primer_apellido'] ?? '',
        'segundo_apellido' => $_POST['segundo_apellido'] ?? '',
        'nombre' => $_POST['nombre'] ?? '',
        'nacimiento' => $_POST['nacimiento'] ?? '',
        'curp' => $_POST['curp'] ?? '',
        'correo' => $_POST['correo'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'codigo_postal' => $_POST['codigo_postal'] ?? '',
        'id_localidad' => $_POST['id_localidad'] ?? '',
        'id_sucursal' => $_POST['id_sucursal'] ?? '',
    ];
    header('Location: admin_cliente.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_cliente.php?msg=deleted');
        exit();
    }
    header('Location: admin_cliente.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        $localidades = $web_localidad->leer();
        $sucursales = $web_sucursal->leer();
        require_once __DIR__ . '/views/cliente/form.php';
        break;

    case 'modificar':
        $sucursales = $web_sucursal->leer();
        $localidades = $web_localidad->leer();
        if ($id) {
            $cliente = $web->leerPorIdcliente($id);
            if ($cliente) {
                require_once __DIR__ . '/views/cliente/form.php';
            } else {
                echo 'cliente no encontrado.';
            }
        }
        break;

    default:
        $clientes = $web->leer();
        require_once __DIR__ . '/views/cliente/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
