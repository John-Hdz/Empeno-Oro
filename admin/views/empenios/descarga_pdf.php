<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../models/modelo_boleta.php';

use Mpdf\Mpdf;

$web = new Boleta();
if (!$web->sesionActiva() || !$web->checar_permiso('Leer')) {
    header('Location: ' . BASE_PATH . '/admin/views/cadenero.php?msg=forbidden');
    exit();
}

$id_boleta = filter_input(INPUT_GET, 'id_boleta', FILTER_VALIDATE_INT);
if (!$id_boleta || $id_boleta <= 0) {
    header('Location: ' . BASE_PATH . '/admin/admin_empenios.php?msg=boleta_no_encontrada');
    exit();
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

foreach ($empenios as $empenio) {
    $mpdf = new Mpdf();

    ob_start();
    include 'info_imprimir.php';
    $html = ob_get_clean();

    $mpdf->WriteHTML($html);
    $mpdf->Output('boleta.pdf', 'I');
}
exit();
