<?php 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once(__DIR__ . '/../../../models/modelo_boleta.php');
require_once(__DIR__ . '/../../../../vendor/autoload.php');
$web = new Boleta;

if (!$web->sesionActiva() || (!$web->checar_permiso('Empeñar') && !$web->checar('Valuador') && !$web->checar('Empleado_Administrador'))) {
    header('Location: ' . BASE_PATH . '/admin/views/cadenero.php?msg=forbidden');
    exit();
}

$datos = $web->leerReporteClienteMoroso();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Encabezados
        $sheet->fromArray(['Cliente', 'Boleta', 'Monto', 'Fecha Limite', 'Dias de Atraso'], null, 'A1');
        
        // Cuerpo del reporte
        $fila = 2;
        foreach ($datos as $dato) {
            $sheet->setCellValue("A$fila", $dato['cliente']);
            $sheet->setCellValue("B$fila", $dato['id_boleta']);
            $sheet->setCellValue("C$fila", $dato['pago_total']);
            $sheet->setCellValue("D$fila", $dato['limite_pago_proximo']);
            $sheet->setCellValue("E$fila", $dato['dias_atraso']);
            $fila++;
        }
    
        // Descargar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_cliente_moroso.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
?>