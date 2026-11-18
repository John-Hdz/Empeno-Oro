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

$datos = $web->leerReporteTransacciones();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Encabezados
        $sheet->fromArray(['Fecha', 'Cliente', 'Boleta', 'Monto', 'Referencia', 'Sucursal', 'Empleado'], null, 'A1');
        
        // Cuerpo del reporte
        $fila = 2;
        foreach ($datos as $dato) {
            $sheet->setCellValue("A$fila", $dato['fecha']);
            $sheet->setCellValue("B$fila", $dato['cliente']);
            $sheet->setCellValue("C$fila", $dato['id_boleta']);
            $sheet->setCellValue("D$fila", $dato['monto']);
            $sheet->setCellValue("E$fila", $dato['referencia_motivo']);
            $sheet->setCellValue("F$fila", $dato['sucursal']);
            $sheet->setCellValue("G$fila", $dato['empleado']);
            $fila++;
        }
    
        // Descargar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_transacciones.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
?>