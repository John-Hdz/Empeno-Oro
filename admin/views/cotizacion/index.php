<h2>Tipos de Cotizaciones Disponibles</h2>

    <div class="centralizar more_space">
        <a href="admin_cotizacion.php?accion=crear" class="btn dark">+ Nueva Cotización</a>
    </div>

<?php foreach($cotizaciones as $cotizacion): ?>

<div class="box-container">
    <div class="box-group centralizar">
    <label>Cotización %</label>
        <div class="borde_negro">
            <?php echo e($cotizacion['cotizacion']); ?>%
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Tipo de Cotización</label>
        <div class="borde_negro">
            <?php echo e($cotizacion['tipo_cotizacion']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_cotizacion.php?accion=modificar&id=<?php echo e($cotizacion['id_cotizacion']); ?>" class="btn light">Modificar</a>
            <form action="admin_cotizacion.php?accion=eliminar&id=<?php echo e($cotizacion['id_cotizacion']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cotización?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<?php endforeach; ?>
