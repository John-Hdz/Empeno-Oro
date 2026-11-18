<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../models/modelo_boleta.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

$web = new Boleta();
if (!$web->sesionActiva() || !$web->checar_permiso('Leer')) {
    header('Location: ' . BASE_PATH . '/admin/views/cadenero.php?msg=forbidden');
    exit();
}

$id_boleta = filter_input(INPUT_GET, 'id_boleta', FILTER_VALIDATE_INT);
if (!$id_boleta || $id_boleta <= 0) {
    http_response_code(400);
    exit('ID de boleta inválido.');
}

$empenios = $web->leerPorBoleta($id_boleta);
if (empty($empenios)) {
    header('Location: ' . BASE_PATH . '/admin/admin_empenios.php?msg=boleta_no_encontrada');
    exit();
}

$esEmpleado = $web->checar('Valuador') || $web->checar('Empleado_Administrador') || $web->checar_permiso('Empeñar');
if (!$esEmpleado && (string) ($empenios[0]['cliente_unico'] ?? '') !== (string) ($_SESSION['cliente_unico'] ?? '')) {
    header('Location: ' . BASE_PATH . '/admin/views/cadenero.php?msg=forbidden');
    exit();
}

$pdf_url = APP_URL . '/admin/views/empenios/descarga_pdf.php?id_boleta=' . $id_boleta;

$builder = new Builder(
    writer: new PngWriter(),
    writerOptions: [],
    validateResult: false,
    data: $pdf_url,
    encoding: new Encoding('UTF-8'),
    errorCorrectionLevel: ErrorCorrectionLevel::High,
    size: 300,
    margin: 10,
    roundBlockSizeMode: RoundBlockSizeMode::Margin,
);
$result = $builder->build();

$qr_path = __DIR__ . '/../../../images/qr_boleta' . $id_boleta . '.png';
if (is_file($qr_path)) {
    unlink($qr_path);
}
$result->saveToFile($qr_path);

header('Location: ' . BASE_PATH . '/admin/admin_empenios.php?msg=qr_success');
exit();
