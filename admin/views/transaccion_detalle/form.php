<h2><?php echo isset($transaccion) ? 'Modificar transacción: ' . e($transaccion['id_transaccion']) : 'Crear transaccion'; ?></h2>

<form action="admin_transaccion.php?accion=<?php echo isset($transaccion) ? 'modificar&id=' . e($transaccion['id_transaccion']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>

    <div class="box-container more_space">

        <div class="box-group centralizar">
            <label for="id_empleado">Empleado</label>
            <select name="id_empleado" id="id_empleado">
                <option value="">Seleccione un empleado</option>
                <?php foreach ($empleados as $empleado): ?>
                    <option value="<?php echo e($empleado['id_empleado']); ?>"
                        <?php echo (isset($transaccion) && $transaccion['id_empleado'] == $empleado['id_empleado']) ? 'selected' : ''; ?>>
                        <?php echo e($empleado['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="cliente_unico">Cliente</label>
            <select name="cliente_unico" id="cliente_unico">
                <option value="">Seleccione un cliente</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?php echo e($cliente['cliente_unico']); ?>"
                        <?php echo (isset($transaccion) && $transaccion['cliente_unico'] == $cliente['cliente_unico']) ? 'selected' : ''; ?>>
                        <?php echo e($cliente['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="id_boleta">Boleta</label>
            <select name="id_boleta" id="id_boleta">
                <option value="">Seleccione una boleta</option>
                <?php foreach ($boletas as $boleta): ?>
                    <option value="<?php echo e($boleta['id_boleta']); ?>"
                        <?php echo (isset($transaccion) && $transaccion['id_boleta'] == $boleta['id_boleta']) ? 'selected' : ''; ?>>
                        <?php echo e($boleta['id_boleta']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="referencia_motivo">Referencia</label>
            <select name="referencia_motivo" id="referencia_motivo">
                <option value="">Seleccione una referencia</option>
                <option value="Refrendo"
                    <?php echo (isset($transaccion) && $transaccion['referencia_motivo'] == 'Refrendo') ? 'selected' : ''; ?>>
                    Refrendo
                </option>
                <option value="Liquidación"
                    <?php echo (isset($transaccion) && $transaccion['referencia_motivo'] == 'Liquidación') ? 'selected' : ''; ?>>
                    Liquidación
                </option>
                <option value="Impresion de Boleta"
                    <?php echo (isset($transaccion) && $transaccion['referencia_motivo'] == 'Impresion de Boleta') ? 'selected' : ''; ?>>
                    Impresion de Boleta
                </option>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="monto">Monto</label>
            <input type="text" id="monto" name="monto" value="<?php echo isset($transaccion) ? e($transaccion['monto']) : ''; ?>" required>
        </div>
        
    </div>
    <div class="centralizar">
            <input type="submit" value="Guardar" class="btn light">
    </div>
</form>
