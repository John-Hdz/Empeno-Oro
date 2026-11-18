<div>

    <h2 style="text-align:center; border-bottom:1px solid #000; padding-bottom:5px;">Acreditado y/o Gerente Prendario</h2>
    <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse; margin-bottom:20px;">
        <tr>
            <td><strong>Apellido Paterno</strong></td>
            <td><?php echo e($empenio['primer_apellido']); ?></td>
        </tr>
        <tr>
            <td><strong>Apellido Materno</strong></td>
            <td><?php echo e($empenio['segundo_apellido']); ?></td>
        </tr>
        <tr>
            <td><strong>Nombre(s)</strong></td>
            <td><?php echo e($empenio['nombre']); ?></td>
        </tr>
    </table>

    <h2 style="text-align:center; border-bottom:1px solid #000; padding-bottom:5px;">Descripción de la Prenda</h2>
    <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse; margin-bottom:20px;">
        <tr>
            <td><strong>Descripción Genérica</strong></td>
            <td><?php echo e($empenio['articulo']); ?></td>
        </tr>
        <tr>
            <td><strong>Características</strong></td>
            <td><?php echo e($empenio['caracteristicas_prenda']); ?></td>
        </tr>
        <tr>
            <td><strong>Kilataje</strong></td>
            <td><?php echo e($empenio['kilataje']); ?></td>
        </tr>
        <tr>
            <td><strong>Peso en Gramos</strong></td>
            <td><?php echo e($empenio['peso_prenda']); ?></td>
        </tr>
        <tr>
            <td><strong>Avalúo</strong></td>
            <td><?php echo e($empenio['avaluo_prenda']); ?></td>
        </tr>
    </table>

    <h2 style="text-align:center; border-bottom:1px solid #000; padding-bottom:5px;">Referencias del Crédito Dispuesto</h2>
    <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse; margin-bottom:20px;">
        <tr>
            <td><strong>Monto del Crédito</strong></td>
            <td>$<?php echo e($empenio['monto_credito']); ?></td>
        </tr>
        <tr>
            <td><strong>Monto Total a Pagar</strong></td>
            <td>$<?php echo e($empenio['pago_total']); ?></td>
        </tr>
        <tr>
            <td><strong>Pago Total de Intereses</strong></td>
            <td>$<?php echo e($empenio['total_intereses']); ?></td>
        </tr>
        <tr>
            <td><strong>Plazo del Crédito</strong></td>
            <td><?php echo e($empenio['plazo_credito']); ?> días</td>
        </tr>
    </table>

    <h2 style="text-align:center;">Número de Crédito</h2>
    <div style="text-align:center;">
        <barcode code="<?= e($empenio['numero_credito']); ?>" type="C128A" size="0.8" height="1.5" text="1" />
    </div>
    <h1 style="text-align:center;"><?php echo e($empenio['numero_credito']); ?></h1>

    <h3 style="text-align:center;">Sucursal</h3>
    <h2 style="text-align:center;"><?php echo e($empenio['sucursal']); ?></h2>

</div>
