<div class="box-group"><a href="admin_transaccion.php" class="btn light">Volver</a></div>
<div class="light-container">
    <h2>No. <?php echo e($transaccion['id_transaccion']); ?></h2>

<div class="row-information">

    <div class="column-information">
        <label class="label-120">No. Boleta</label>
        <label class="label-120">Cliente</label>
        <label class="label-120">Correo del Cliente</label>
        <label class="label-120">Fecha</label>
        <label class="label-120">Monto</label>
        <label class="label-120">Referencia</label>
    </div>

    <div class="column-information">
        <div><?php echo e($transaccion['id_boleta']); ?></div> <br>
        <div><?php echo e($transaccion['cliente']); ?></div> <br>
        <div><?php echo e($transaccion['correo']); ?></div> <br>
        <div><?php echo e($transaccion['fecha']); ?></div> <br>
        <div>$<?php echo e($transaccion['monto']); ?></div>    <br>
        <div><?php echo e($transaccion['referencia_motivo']); ?></div>
    </div>
</div>

</div>
<div class="box-container centralizar">
    <div class="box-group centralizar">
        <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_transaccion.php?accion=modificar&id=<?php echo e($transaccion['id_transaccion']); ?>" class="btn light">Modificar</a>
            <form action="admin_transaccion.php?accion=eliminar&id=<?php echo e($transaccion['id_transaccion']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta transacción?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>
</div>
