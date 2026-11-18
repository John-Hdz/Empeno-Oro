<h2>Registrar Empeño</h2>

<form action="admin_empenios.php?accion=registrar_empenio" method="post">
    <?php echo csrf_field(); ?>

    <input type="hidden" name="cliente_unico" id="cliente_unico" value="<?php echo e($cliente ?? ''); ?>">

    <div class="box-container">
        <div class="box-group centralizar">
            <label for="id_articulo">Articulo</label>
            <select name="id_articulo" id="id_articulo">
                <option value="">Seleccione un articulo</option>
                <?php foreach ($articulos as $articulo) : ?>
                    <option value="<?php echo e($articulo['id_articulo']); ?>">
                        <?php echo e($articulo['articulo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="id_clasificacion">Clasificación</label>
            <select name="id_clasificacion" id="id_clasificacion">
                <option value="">Seleccione una clasificacion</option>
                <?php foreach ($clasificaciones as $clasificacion) : ?>
                    <option value="<?php echo e($clasificacion['id_clasificacion']); ?>">
                        <?php echo e($clasificacion['clasificacion']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="id_kilataje">kilataje</label>
            <select name="id_kilataje" id="id_kilataje">
                <option value="">Seleccione un kilataje</option>
                <?php foreach ($kilatajes as $kilataje) : ?>
                    <option value="<?php echo e($kilataje['id_kilataje']); ?>">
                        <?php echo e($kilataje['kilataje']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="id_cotizacion">Cotizacion</label>
            <select name="id_cotizacion" id="id_cotizacion">
                <option value="">Seleccione una cotizacion</option>
                <?php foreach ($cotizaciones as $cotizacion) : ?>
                    <option value="<?php echo e($cotizacion['id_cotizacion']); ?>">
                        <?php echo e($cotizacion['cotizacion']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="box-container">
                    <div class="box-group centralizar">
            <label for="carac_prenda">Caracteristicas de la prenda</label>
            <input type="text" id="carac_prenda" name="carac_prenda">
        </div>

        <div class="box-group centralizar">
            <label for="peso_prenda">Peso de la Prenda</label>
            <input type="number" id="peso_prenda" name="peso_prenda" step="0.01" min="0.5" required>
        </div>
    </div>

    <div class="box-container more_space">

    <input type="hidden" name="id_empenio" value="<?php echo e($id_empenio ?? ''); ?>">

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
            <input type="number" id="monto_credito" name="monto_credito" value="<?php echo e($avaluo_prenda ?? ''); ?>" min="10" step="0.01" required>
        </div>
    </div>

    <div class="box-container">
        <div class="centralizar">
            <input type="submit" value="Guardar" class="btn light">
        </div>
    </div>
</form>
