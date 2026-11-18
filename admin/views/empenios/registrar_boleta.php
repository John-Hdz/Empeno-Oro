<h2><?php echo isset($boleta) ? 'Modificar Boleta' : 'Registrar Boleta'; ?></h2>

<form action="admin_empenios.php?accion=registrar_boleta" method="post">
    <?php echo csrf_field(); ?>

    <div class="box-container more_space">

    <input type="hidden" name="id_empenio" value="<?php echo e($id_empenio ?? ''); ?>">
    <input type="hidden" name="cliente_unico" value="<?php echo e($cliente ?? ''); ?>">

        <div class="box-group centralizar">
            <label for="id_sucursal">Sucursal</label>
            <select name="id_sucursal" id="id_sucursal">
                <option value="">Selecciona la Sucursal</option>
                <?php foreach ($sucursales as $sucursal): ?>
                    <option value="<?php echo e($sucursal['id_sucursal']); ?>">
                        <?php echo e($sucursal['sucursal']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="plazo_credito">Plazo del credito</label>
            <select name="plazo_credito" id="plazo_credito">
                <option value="">Seleccione un plazo de credito</option>
                    <option value="7">7 Dias</option>
                    <option value="15">15 Dias</option>
                    <option value="30">30 Dias</option>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="monto_credito">Monto de credito</label>
            <input type="number" id="monto_credito" name="monto_credito" value="<?php echo e($avaluo_prenda ?? ''); ?>" max="<?php echo e($avaluo_prenda ?? ''); ?>" min="10">
        </div>
    </div>

    <div class="box-container">
        <div class="centralizar">
            <input type="submit" value="Guardar" class="btn light">
        </div>
    </div>
</form>
