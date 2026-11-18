<h1>Modificar Empeño <?php echo e($empenio['id_boleta']); ?></h1>

<form action="admin_empenios.php?accion=modificar&id_boleta=<?php echo e($empenio['id_boleta']); ?>" method="post">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id_boleta" value="<?php echo e($empenio['id_boleta']); ?>">
    <input type="hidden" name="id_empenio" value="<?php echo e($empenio['id_empenio']); ?>">
    <div class="box-container">
        <div class="box-group centralizar">
            <label for="carac_prenda">Características de la Prenda</label>
            <input type="text" id="carac_prenda" name="carac_prenda" value="<?php echo e($empenio['caracteristicas_prenda']); ?>" required>
        </div>

        <div class="box-group centralizar">
            <label for="id_articulo">Artículo</label>
            <select name="id_articulo" id="id_articulo">
                <option value="">Selecciona un Artículo</option>
                <?php foreach ($articulos as $articulo): ?>
                    <option value="<?php echo e($articulo['id_articulo']); ?>"
                        <?php echo $empenio['id_articulo'] == $articulo['id_articulo'] ? 'selected' : ''; ?>>
                        <?php echo e($articulo['articulo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="id_clasificacion">Clasificación</label>
            <select name="id_clasificacion" id="id_clasificacion">
                <option value="">Selecciona una Clasificación</option>
                <?php foreach ($clasificaciones as $clasificacion): ?>
                    <option value="<?php echo e($clasificacion['id_clasificacion']); ?>"
                        <?php echo $empenio['id_clasificacion'] == $clasificacion['id_clasificacion'] ? 'selected' : ''; ?>>
                        <?php echo e($clasificacion['clasificacion']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="id_empleado">Empleado</label>
            <select name="id_empleado" id="id_empleado">
                <option value="">Selecciona un Empleado</option>
                <?php foreach ($empleados as $empleado): ?>
                    <option value="<?php echo e($empleado['id_empleado']); ?>"
                        <?php echo $empenio['id_empleado'] == $empleado['id_empleado'] ? 'selected' : ''; ?>>
                        <?php echo e($empleado['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="cliente_unico">Cliente</label>
            <select name="cliente_unico" id="cliente_unico">
                <option value="">Selecciona un Cliente</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?php echo e($cliente['cliente_unico']); ?>"
                        <?php echo $empenio['cliente_unico'] == $cliente['cliente_unico'] ? 'selected' : ''; ?>>
                        <?php echo e($cliente['nombre'] . ' ' . $cliente['primer_apellido'] . ' ' . $cliente['segundo_apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="id_sucursal">Sucursal</label>
            <select name="id_sucursal" id="id_sucursal">
                <option value="">Selecciona una Sucursal</option>
                <?php foreach ($sucursales as $sucursal): ?>
                    <option value="<?php echo e($sucursal['id_sucursal']); ?>"
                        <?php echo $empenio['id_sucursal'] == $sucursal['id_sucursal'] ? 'selected' : ''; ?>>
                        <?php echo e($sucursal['sucursal']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="plazo_credito">Plazo del Crédito</label>
            <input type="number" id="plazo_credito" name="plazo_credito" value="<?php echo e($empenio['plazo_credito']); ?>" required>
        </div>

        <div class="box-group centralizar">
            <label for="limite_pago_proximo">Fecha límite del próximo pago</label>
            <input type="date" id="limite_pago_proximo" name="limite_pago_proximo" value="<?php echo e($empenio['limite_pago_proximo']); ?>" required>
        </div>

        <div class="box-group centralizar">
            <label for="estado_empenio">Estado del Empeño</label>
            <select name="estado_empenio" id="estado_empenio">
                <option value="Activo" <?php echo $empenio['estado_empeño'] == 'Activo' ? 'selected' : ''; ?>>Activo</option>
                <option value="Atrasado" <?php echo $empenio['estado_empeño'] == 'Atrasado' ? 'selected' : ''; ?>>Atrasado</option>
                <option value="Liquidado" <?php echo $empenio['estado_empeño'] == 'Liquidado' ? 'selected' : ''; ?>>Liquidado</option>
            </select>

        </div>
    </div>

    <div class="box-container">
        <div class="box-group centralizar">
            <input type="submit" value="Modificar Empeño" class="btn btn-primary">
        </div>

    </div>
</form>
