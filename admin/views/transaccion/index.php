<h2>Transacciones Realizadas</h2>

    <div class="centralizar more_space">
        <a href="admin_transaccion.php?accion=crear" class="btn dark">+ Nueva transacción</a>
    </div>

<?php foreach($transacciones as $transaccion): ?>

<div class="box-container">
    <div class="box-group centralizar">
    <label>No. Transacción</label>
        <div class="borde_negro">
            <?php echo e($transaccion['id_transaccion']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Sucursal</label>
        <div class="borde_negro">
            <?php echo e($transaccion['sucursal']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Empleado</label>
        <div class="borde_negro">
            <?php echo e($transaccion['empleado']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Cliente</label>
        <div class="borde_negro">
            <?php echo e($transaccion['cliente']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Fecha</label>
        <div class="borde_negro">
            <?php echo e($transaccion['fecha']); ?>
        </div>
    </div>

    <div class="box-group centralizar more_space">
        <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_transaccion.php?accion=detalle&id=<?php echo e($transaccion['id_transaccion']); ?>" class="btn light">Ver Detalles</a>
            <a href="#" class="btn dark" onclick="confirmarEliminacion(<?php echo (int) $transaccion['id_transaccion']; ?>); return false;">Eliminar</a>
        </div>
    </div>

</div>

<?php endforeach; ?>

<script>
    function confirmarEliminacion(id) {
        showModal("Confirmar eliminación", "¿Estás seguro de que deseas eliminar esta transacción?", [
            { texto: "Cancelar", clase: "btn-light" },
            { texto: "Eliminar", clase: "btn-danger", accion: () => {
                submitPostAction(`admin_transaccion.php?accion=eliminar&id=${id}`);
            }}
        ]);
    }
</script>
