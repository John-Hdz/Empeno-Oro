<h1>Seleccionar Cliente</h1>

<form action="admin_empenios.php?accion=seleccionar_cliente" method="post">
    <?php echo csrf_field(); ?>
    <div class="box-container">
    <div class="box-group centralizar">
        <select name="cliente_unico" id="cliente_unico">
            <option value="">Seleccionar Cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?php echo e($cliente['cliente_unico']); ?>">
                    <?php echo e($cliente['nombre'] . ' ' . $cliente['primer_apellido'] . ' ' . $cliente['segundo_apellido'] . ' ' . $cliente['cliente_unico']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
    <div class="box-container">
        <div class="box-group centralizar">
            <input type="submit" value="Seleccionar" class="btn light">
        </div>
    </div>
</form>
