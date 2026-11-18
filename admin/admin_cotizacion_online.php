<?php
require_once __DIR__ . '/models/modelo_cotizacion_online.php';
require_once __DIR__ . '/models/modelo_kilataje.php';

$web = new CotizacionOnline();
$web_kilataje = new Kilataje();
$cotizaciones = $web->leer();
$accion = $_GET['accion'] ?? 'index';

if ($accion === 'cotizar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        header('Location: admin_cotizacion_online.php?msg=unauthorized');
        exit();
    }

    $kilataje = trim((string) ($_POST['kilataje'] ?? ''));
    $peso = filter_input(INPUT_POST, 'peso', FILTER_VALIDATE_FLOAT);
    $opcion = trim((string) ($_POST['opcion'] ?? ''));

    $cotizacion = $web->leerCotizacion($opcion);
    $kilatajeInfo = $web_kilataje->leerPorKilataje($kilataje . 'k');

    if (!$peso || $peso <= 0 || !$cotizacion || !$kilatajeInfo) {
        header('Location: admin_cotizacion_online.php?msg=invalid');
        exit();
    }

    $precio_gramo = (float) $kilatajeInfo['precio_gramo'];
    $interes = ((float) $cotizacion['cotizacion']) / 100;
    $total = $precio_gramo * $peso;
    $totalStr = number_format($total, 2, '.', '');
    $interesStr = number_format($interes * $total, 2, '.', '');
    header('Location: admin_cotizacion_online.php?msg=cotizacion&cotizacion=' . urlencode($totalStr) . '&totalInteres=' . urlencode($interesStr));
    exit();
}

require_once __DIR__ . '/views/header.php';
require_once __DIR__ . '/views/cotizacion_online/index.php';
require_once __DIR__ . '/views/footer.php';
