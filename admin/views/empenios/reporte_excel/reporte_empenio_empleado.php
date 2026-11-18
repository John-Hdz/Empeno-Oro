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

$datos = $web->leerReporteBoletaPorEmpleado();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Encabezados
        $sheet->fromArray(['Empleado', 'Articulo', 'Clasificación', 'Kilataje', 'Cotización', 
                                    'Avaluo de la Prenda', 'Boleta', 'Cliente Unico', 'Sucursal', 
                                    'Plazo del Crédito','Monto del Crédito', 'Estado del Empeño' ], null, 'A1');
        
        // Cuerpo del reporte
        $fila = 2;
        foreach ($datos as $dato) {
            $sheet->setCellValue("A$fila", $dato['empleado']);
            $sheet->setCellValue("B$fila", $dato['articulo']);
            $sheet->setCellValue("C$fila", $dato['clasificacion']);
            $sheet->setCellValue("D$fila", $dato['kilataje']);
            $sheet->setCellValue("E$fila", $dato['cotizacion']);
            $sheet->setCellValue("F$fila", $dato['avaluo_prenda']);
            $sheet->setCellValue("G$fila", $dato['id_boleta']);
            $sheet->setCellValue("H$fila", $dato['cliente_unico']);
            $sheet->setCellValue("I$fila", $dato['sucursal']);
            $sheet->setCellValue("J$fila", $dato['plazo_credito']);
            $sheet->setCellValue("K$fila", $dato['monto_credito']);
            $sheet->setCellValue("L$fila", $dato['estado_empeño']);
            $fila++;
        }
    
        // Descargar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_empleado_empenios.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
?>