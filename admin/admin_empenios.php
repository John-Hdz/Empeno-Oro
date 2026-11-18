<?php
require_once __DIR__ . '/models/modelo_boleta.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/models/modelo_transaccion.php';
require_once __DIR__ . '/models/modelo_articulo.php';
require_once __DIR__ . '/models/modelo_clasificacion.php';
require_once __DIR__ . '/models/modelo_kilataje.php';
require_once __DIR__ . '/models/modelo_cotizacion.php';
require_once __DIR__ . '/models/modelo_sucursal.php';
require_once __DIR__ . '/models/modelo_cliente.php';
require_once __DIR__ . '/models/modelo_empleado.php';

$web = new Boleta();
$web->requerirPermiso('Leer');

$esEmpleado = $web->checar_permiso('Empeñar') || $web->checar('Valuador') || $web->checar('Empleado_Administrador');
$cliente_unico_actual = $_SESSION['cliente_unico'] ?? null;

$web_transaccion = new Transaccion();
$web_articulo = new Articulo();
$web_clasificacion = new Clasificacion();
$web_kilataje = new Kilataje();
$web_cotizacion = new Cotizacion();
$web_sucursal = new Sucursal();
$web_cliente = new Cliente();
$web_empleado = new Empleado();

$accion = $_GET['accion'] ?? null;
$cliente = $_GET['cliente'] ?? null;
$id_boleta = isset($_GET['id_boleta']) ? filter_var($_GET['id_boleta'], FILTER_VALIDATE_INT) : null;
$id_empenio = isset($_GET['id_empenio']) ? filter_var($_GET['id_empenio'], FILTER_VALIDATE_INT) : null;
$numero_credito = $_GET['numero_credito'] ?? null;
$cliente_unico = isset($_GET['cliente_unico']) ? filter_var($_GET['cliente_unico'], FILTER_VALIDATE_INT) : null;
$boleta = null;
$empenio = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !verify_csrf()) {
    header('Location: admin_empenios.php?msg=unauthorized');
    exit();
}

// Acciones de empleado solamente
$accionesEmpleado = ['buscarEmpeños', 'reporte', 'registrar_empenio', 'seleccionar_cliente', 'modificar', 'eliminar'];
if (in_array($accion, $accionesEmpleado, true) && !$esEmpleado) {
    header('Location: ' . BASE_PATH . '/admin/views/cadenero.php?msg=forbidden');
    exit();
}

// Helper para verificar acceso a una boleta (empleado o dueño de la boleta)
$verificarAccesoBoleta = function (?int $boletaId) use ($web, $esEmpleado, $cliente_unico_actual): array {
    if (!$boletaId || $boletaId <= 0) {
        header('Location: ' . BASE_PATH . '/admin/admin_empenios.php?msg=boleta_no_encontrada');
        exit();
    }
    $empenios = $web->leerPorBoleta($boletaId);
    if (empty($empenios)) {
        header('Location: ' . BASE_PATH . '/admin/admin_empenios.php?msg=boleta_no_encontrada');
        exit();
    }
    if (!$esEmpleado && (string) ($empenios[0]['cliente_unico'] ?? '') !== (string) $cliente_unico_actual) {
        header('Location: ' . BASE_PATH . '/admin/views/cadenero.php?msg=forbidden');
        exit();
    }
    return $empenios;
};

// Acciones que generan PDF o solo redireccionan (antes de imprimir HTML del header)
if ($accion === 'imprimirBoleta' || $accion === 'descargarBoleta') {
    $empenios = $verificarAccesoBoleta($id_boleta ?: null);
    $modo = $accion === 'descargarBoleta' ? 'D' : 'I';
    foreach ($empenios as $empenio) {
        $mpdf = new \Mpdf\Mpdf();
        ob_start();
        include 'views/empenios/info_imprimir.php';
        $html = ob_get_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output('boleta.pdf', $modo);
    }
    exit();
}

if ($accion === 'transferencia' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $boleta = $verificarAccesoBoleta($id_boleta ?: null);
    $empenio = $web->leerEmpenio($boleta[0]['id_empenio'] ?? $boleta[0]['num_empenio']);
    $referencia = (string) ($_POST['referencia'] ?? '');

    if ($referencia === 'Refrendo') {
        $monto = $boleta[0]['total_intereses'];
    } elseif ($referencia === 'Liquidación') {
        $monto = $boleta[0]['pago_total'];
    } else {
        header('Location: admin_empenios.php?msg=invalid');
        exit();
    }

    $data = [
        'id_empleado' => $empenio[0]['id_empleado'],
        'cliente_unico' => $boleta[0]['cliente_unico'],
        'id_boleta' => $id_boleta,
        'monto' => $monto,
        'referencia_motivo' => $referencia,
    ];
    if ($web_transaccion->crear($data)) {
        header('Location: admin_empenios.php?msg=transaction_success');
        exit();
    }
    header('Location: admin_empenios.php?msg=error');
    exit();
}

if ($accion === 'registrar_empenio' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $pesoPrenda = filter_input(INPUT_POST, 'peso_prenda', FILTER_VALIDATE_FLOAT);
    $montoCredito = filter_input(INPUT_POST, 'monto_credito', FILTER_VALIDATE_FLOAT);
    $idKilataje = filter_input(INPUT_POST, 'id_kilataje', FILTER_VALIDATE_INT);

    if (!$pesoPrenda || $pesoPrenda <= 0 || !$montoCredito || $montoCredito <= 0 || !$idKilataje) {
        header('Location: admin_empenios.php?msg=invalid');
        exit();
    }

    $kilatajeData = $web_kilataje->leerPorIdkilataje($idKilataje);
    if (!$kilatajeData) {
        header('Location: admin_empenios.php?msg=error');
        exit();
    }

    $avaluo_prenda = $pesoPrenda * (float) $kilatajeData['precio_gramo'];
    $data = [
        'id_articulo' => $_POST['id_articulo'] ?? '',
        'id_clasificacion' => $_POST['id_clasificacion'] ?? '',
        'id_kilataje' => $idKilataje,
        'id_cotizacion' => $_POST['id_cotizacion'] ?? '',
        'carac_prenda' => $_POST['carac_prenda'] ?? '',
        'cliente_unico' => $_POST['cliente_unico'] ?? '',
        'id_empleado' => $_SESSION['id_empleado'],
        'peso_prenda' => $pesoPrenda,
        'avaluo_prenda' => $avaluo_prenda,
        'id_sucursal' => $_POST['id_sucursal'] ?? '',
        'plazo_credito' => $_POST['plazo_credito'] ?? '',
        'monto_credito' => $montoCredito,
    ];

    if ($avaluo_prenda >= $montoCredito) {
        if ($web->crearEmpenio($data)) {
            header('Location: admin_empenios.php?msg=success');
            exit();
        }
        header('Location: admin_empenios.php?msg=error');
        exit();
    }
    header('Location: admin_empenios.php?msg=avaluo_menor');
    exit();
}

if ($accion === 'seleccionar_cliente' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientePost = (string) ($_POST['cliente_unico'] ?? '');
    header('Location: admin_empenios.php?accion=registrar_empenio&cliente=' . urlencode($clientePost));
    exit();
}

if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_boleta' => $_POST['id_boleta'] ?? '',
        'id_empenio' => $_POST['id_empenio'] ?? '',
        'caracteristicas_prenda' => $_POST['carac_prenda'] ?? '',
        'id_articulo' => $_POST['id_articulo'] ?? '',
        'id_clasificacion' => $_POST['id_clasificacion'] ?? '',
        'id_empleado' => $_POST['id_empleado'] ?? '',
        'cliente_unico' => $_POST['cliente_unico'] ?? '',
        'id_sucursal' => $_POST['id_sucursal'] ?? '',
        'plazo_credito' => $_POST['plazo_credito'] ?? '',
        'limite_pago_proximo' => $_POST['limite_pago_proximo'] ?? '',
        'estado_empeño' => $_POST['estado_empenio'] ?? '',
    ];

    if ($web->modificar($data)) {
        header('Location: admin_empenios.php?msg=updated');
        exit();
    }
    header('Location: admin_empenios.php?msg=error');
    exit();
}

if ($accion === 'eliminar') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id_boleta && $web->eliminar($id_boleta)) {
        header('Location: ' . BASE_PATH . '/admin/admin_empenios.php?msg=deleted');
        exit();
    }
    header('Location: ' . BASE_PATH . '/admin/admin_empenios.php?msg=error');
    exit();
}

if ($accion === null || $accion === '') {
    if (!isset($cliente_unico_actual) && !$web->checar('Valuador') && !$esEmpleado) {
        header('Location: ' . BASE_PATH . '/admin/admin_index.php?msg=boleta_no_encontrada');
        exit();
    }
}

$clientes = $esEmpleado ? $web_cliente->leer() : [];
require_once __DIR__ . '/views/header.php';

switch ($accion) {
    case 'buscarEmpeños':
        $tipo = $_GET['tipoBusqueda'] ?? '';
        $valor = trim((string) ($_GET['valorBusqueda'] ?? ''));

        if ($valor !== '') {
            switch ($tipo) {
                case 'cliente':
                    $empenios = $web->leerPorCliente($valor);
                    break;
                case 'numero_credito':
                    $empenios = $web->leerPorNumeroCredito($valor);
                    break;
                case 'cliente_unico':
                    $empenios = $web->leerPorClienteUnico($valor);
                    break;
                default:
                    $empenios = [];
            }
        } else {
            $empenios = [];
        }

        require_once __DIR__ . '/views/empenios/index.php';
        break;

    case 'reporte':
        $labels = [];
        $valores = [];
        $datosClientesSucursal = $web->leerClientesPorSucursal();
        foreach ($datosClientesSucursal as $fila) {
            $labels[] = $fila['nombre_sucursal'];
            $valores[] = $fila['total_clientes'];
        }

        $labels2 = [];
        $valores2 = [];
        $datosClientesEstado = $web->leerClientesEstado();
        foreach ($datosClientesEstado as $fila) {
            $labels2[] = $fila['estado'];
            $valores2[] = $fila['total_clientes'];
        }
        require_once __DIR__ . '/views/empenios/reporte.php';
        break;

    case 'pago_refrendo':
        $boleta = $verificarAccesoBoleta($id_boleta ?: null);
        $empenio = $web->leerEmpenio($boleta[0]['num_empenio']);
        include_once __DIR__ . '/views/transaccion/pago_refrendo.php';
        break;

    case 'pago_liquidar':
        $boleta = $verificarAccesoBoleta($id_boleta ?: null);
        $empenio = $web->leerEmpenio($boleta[0]['num_empenio']);
        include_once __DIR__ . '/views/transaccion/pago_liquidar.php';
        break;

    case 'registrar_empenio':
        $articulos = $web_articulo->leer();
        $clasificaciones = $web_clasificacion->leer();
        $kilatajes = $web_kilataje->leer();
        $cotizaciones = $web_cotizacion->leer();
        $sucursales = $web_sucursal->leer();
        $avaluo_prenda = '';
        require_once __DIR__ . '/views/empenios/registrar_empenio.php';
        break;

    case 'seleccionar_cliente':
        require_once __DIR__ . '/views/empenios/seleccionar_cliente.php';
        break;

    case 'modificar':
        $empenio = $web->leerEmpenioBoleta($id_boleta);
        $articulos = $web_articulo->leer();
        $clasificaciones = $web_clasificacion->leer();
        $kilatajes = $web_kilataje->leer();
        $cotizaciones = $web_cotizacion->leer();
        $sucursales = $web_sucursal->leer();
        $empleados = $web_empleado->leer();
        require_once __DIR__ . '/views/empenios/form_modificar.php';
        break;

    default:
        if (isset($cliente_unico_actual)) {
            $empenios = $web->leerPorCliente_unico($cliente_unico_actual);
        } else {
            $empenios = $web->leer();
        }
        require_once __DIR__ . '/views/empenios/index.php';
        break;
}

require_once __DIR__ . '/views/footer.php';
