<?php
require_once __DIR__ . '/../models/modelo_cliente.php';

header('Content-Type: application/json; charset=utf-8');

$web = new cliente();

if (!$web->sesionActiva() || !$web->checar('Empleado_Administrador')) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$action = $_SERVER['REQUEST_METHOD'];
$cliente_unico = isset($_GET['cliente_unico']) ? filter_var($_GET['cliente_unico'], FILTER_VALIDATE_INT) : null;
if (isset($_GET['cliente_unico']) && ($cliente_unico === false || $cliente_unico <= 0)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de cliente inválido']);
    exit();
}

$response = json_encode([]);

switch ($action) {
    case 'DELETE':
        if ($cliente_unico !== null) {
            $ok = $web->eliminar($cliente_unico);
            $resultado = [
                'ok' => $ok,
                'mensaje' => $ok ? 'Cliente eliminado' : 'No se pudo eliminar el cliente',
            ];
            $response = json_encode($resultado);
        } else {
            http_response_code(400);
            $response = json_encode(['error' => 'cliente_unico requerido']);
        }
        break;

    case 'POST':
        $datos = $_POST['datos'] ?? json_decode(file_get_contents('php://input'), true);
        if (!is_array($datos)) {
            http_response_code(400);
            $response = json_encode(['error' => 'Datos inválidos']);
            break;
        }
        if ($cliente_unico !== null) {
            $ok = $web->modificar($cliente_unico, $datos);
            $resultado = [
                'ok' => $ok,
                'mensaje' => $ok ? 'Cliente modificado' : 'No se pudo modificar el cliente',
            ];
            $response = json_encode($resultado);
        } else {
            $ok = $web->crear($datos);
            $resultado = [
                'ok' => $ok,
                'mensaje' => $ok ? 'Cliente creado' : 'No se pudo crear el cliente',
            ];
            $response = json_encode($resultado);
        }
        break;

    case 'GET':
    default:
        if ($cliente_unico !== null) {
            $clientes = $web->leerPorIdcliente($cliente_unico);
        } else {
            $clientes = $web->leer();
        }
        $response = json_encode($clientes);
        break;
}

echo $response;
