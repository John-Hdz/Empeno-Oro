<h2><?php echo isset($sucursal) ? 'Modificar Sucursal' : 'Crear Sucursal'; ?></h2>

<form action="admin_sucursal.php?accion=<?php echo isset($sucursal) ? 'modificar&id=' . e($sucursal['id_sucursal']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>
    <div class="box-container">
        <div class="box-group centralizar">
            <label for="sucursal">Sucursal</label>
            <input type="text" id="sucursal" name="sucursal" value="<?php echo isset($sucursal) ? e($sucursal['sucursal']) : ''; ?>" required>
        </div>
        <div class="box-group centralizar">
            <label for="id_localidad">Localidad</label>
            <select name="id_localidad" id="id_localidad">
                <?php foreach ($localidades as $localidad): ?>
                    <option value="<?php echo e($localidad['id_localidad']); ?>"
                        <?php echo isset($sucursal) && $sucursal['id_localidad'] == $localidad['id_localidad'] ? 'selected' : ''; ?>>
                        <?php echo e($localidad['localidad']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="centralizar">
            <input type="submit" value="Guardar Cambios" class="btn light">
        </div>
    </div>
</form>
