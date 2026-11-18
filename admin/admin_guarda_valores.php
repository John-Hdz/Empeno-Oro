<?php
require_once __DIR__ . '/models/modelo_guarda_valores.php';
require_once __DIR__ . '/models/modelo_cliente.php';
require_once __DIR__ . '/models/modelo_boleta.php';

$web = new GuardaValores();
$web->requerirRol('Lider_Tienda');
$web->requerirCsrf('admin_guarda_valores.php');

$web_cliente = new Cliente();
$web_boleta = new Boleta();
$accion = $_GET['accion'] ?? null;
$id_empenio = isset($_GET['id_empenio']) ? filter_var($_GET['id_empenio'], FILTER_VALIDATE_INT) : null;
$cliente_unico = isset($_GET['cliente_unico']) ? filter_var($_GET['cliente_unico'], FILTER_VALIDATE_INT) : null;
$cliente = $_GET['cliente'] ?? null;

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_empenio' => $_POST['id_empenio'] ?? '',
        'cliente_unico' => $_POST['cliente_unico'] ?? '',
        'columna' => $_POST['columna'] ?? '',
        'fila' => $_POST['fila'] ?? '',
        'deposito' => $_POST['deposito'] ?? '',
    ];
    header('Location: admin_guarda_valores.php?msg=' . ($web->crear($data) ? 'success' : 'error'));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id_empenio && $cliente_unico) {
    $data = [
        'id_empenio' => $_POST['id_empenio'] ?? '',
        'cliente_unico' => $_POST['cliente_unico'] ?? '',
        'columna' => $_POST['columna'] ?? '',
        'fila' => $_POST['fila'] ?? '',
        'deposito' => $_POST['deposito'] ?? '',
    ];
    header('Location: admin_guarda_valores.php?msg=' . ($web->modificar($id_empenio, $cliente_unico, $data) ? 'updated' : 'error'));
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id_empenio && $cliente_unico && $web->eliminar($id_empenio, $cliente_unico)) {
        header('Location: admin_guarda_valores.php?msg=deleted');
        exit();
    }
    header('Location: admin_guarda_valores.php?msg=error');
    exit();
}

require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'crear':
        $clientes = $web_cliente->leer();
        $empenios = $web_boleta->leer();
        require_once __DIR__ . '/views/guarda_valores/form.php';
        break;

    case 'modificar':
        $clientes = $web_cliente->leer();
        $empenios = $web_boleta->leer();
        if ($id_empenio && $cliente_unico) {
            $guarda_valores = $web->leerPorId($id_empenio, $cliente_unico);
            if ($guarda_valores) {
                require_once __DIR__ . '/views/guarda_valores/form.php';
            } else {
                echo 'guarda_valores no encontrado';
            }
        }
        break;

    case 'buscarPorNombre':
        if ($cliente) {
            $ar_guarda_valores = $web->leerPorCliente($cliente);
            require_once __DIR__ . '/views/guarda_valores/index.php';
        }
        break;

    default:
        $ar_guarda_valores = $web->leer();
        require_once __DIR__ . '/views/guarda_valores/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
