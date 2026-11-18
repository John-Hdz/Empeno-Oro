<?php
require_once __DIR__ . '/models/modelo_transaccion.php';
require_once __DIR__ . '/models/modelo_empleado.php';
require_once __DIR__ . '/models/modelo_cliente.php';
require_once __DIR__ . '/models/modelo_boleta.php';

$web = new Transaccion();
$web->requerirRol('Empleado_Administrador');
$web->requerirCsrf('admin_transaccion.php');

$web_empleado = new Empleado();
$web_cliente = new Cliente();
$web_boleta = new Boleta();

$accion = $_GET['accion'] ?? null;
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
$id_transaccion = $id;
$id_boleta = isset($_GET['id_boleta']) ? filter_var($_GET['id_boleta'], FILTER_VALIDATE_INT) : null;
$boleta = null;
$empenio = null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_empleado' => $_POST['id_empleado'] ?? '',
        'cliente_unico' => $_POST['cliente_unico'] ?? '',
        'id_boleta' => $_POST['id_boleta'] ?? '',
        'monto' => $_POST['monto'] ?? '',
        'referencia_motivo' => $_POST['referencia_motivo'] ?? '',
    ];
    header('Location: admin_transaccion.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $data = [
        'id_empleado' => $_POST['id_empleado'] ?? '',
        'cliente_unico' => $_POST['cliente_unico'] ?? '',
        'id_boleta' => $_POST['id_boleta'] ?? '',
        'monto' => $_POST['monto'] ?? '',
        'referencia_motivo' => $_POST['referencia_motivo'] ?? '',
    ];
    header('Location: admin_transaccion.php?msg=' . ($web->modificar($id, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $web->eliminar($id)) {
        header('Location: admin_transaccion.php?msg=deleted');
        exit();
    }
    header('Location: admin_transaccion.php?msg=error');
    exit();
}

if ($accion === 'transferencia' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $boleta = $web_boleta->leerPorID($id_boleta);
    if (empty($boleta)) {
        header('Location: admin_transaccion.php?msg=error');
        exit();
    }
    $empenio = $web_boleta->leerEmpenio($boleta[0]['num_empenio']);
    $data = [
        'id_empleado' => $empenio[0]['id_empleado'],
        'cliente_unico' => $boleta[0]['cliente_unico'],
        'id_boleta' => $id_boleta,
        'monto' => $_POST['monto'] ?? '',
        'referencia_motivo' => $_POST['referencia'] ?? '',
    ];
    header('Location: admin_transaccion.php?msg=' . ($web->crear($data) ? 'transaction_success' : 'error'));
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        $empleados = $web_empleado->leer();
        $clientes = $web_cliente->leer();
        $boletas = $web_boleta->leer();
        require_once __DIR__ . '/views/transaccion_detalle/form.php';
        break;

    case 'detalle':
        $transaccion = $web->leerTransaccionDetalle($id_transaccion);
        require_once __DIR__ . '/views/transaccion_detalle/index.php';
        break;

    case 'modificar':
        $empleados = $web_empleado->leer();
        $clientes = $web_cliente->leer();
        $boletas = $web_boleta->leer();
        if ($id) {
            $transaccion = $web->leerPorIdtransaccion($id);
            if ($transaccion) {
                require_once __DIR__ . '/views/transaccion_detalle/form.php';
            } else {
                echo 'transaccion no encontrado.';
            }
        }
        break;

    case 'pago':
        $boleta = $web_boleta->leerPorID($id_boleta);
        $empenio = $web_boleta->leerEmpenio($boleta[0]['num_empenio']);
        include_once __DIR__ . '/views/transaccion/pago.php';
        break;

    case 'transferencia':
        $boleta = $web_boleta->leerPorID($id_boleta);
        $empenio = $web_boleta->leerEmpenio($boleta[0]['num_empenio']);
        require_once __DIR__ . '/views/transaccion/pago.php';
        break;

    default:
        $transacciones = $web->leer();
        require_once __DIR__ . '/views/transaccion/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
