<h2><?php echo isset($localidad) ? 'Modificar Localidad' : 'Crear Localidad'; ?></h2>

<form action="admin_localidad.php?accion=<?php echo isset($localidad) ? 'modificar&id=' . e($localidad['id_localidad']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>

    <div class="box-container more_space">
        <div class="box-group centralizar">
            <label for="localidad">localidad</label>
            <input type="text" id="localidad" name="localidad" value="<?php echo isset($localidad) ? e($localidad['localidad']) : ''; ?>" required>
        </div>
        <div class="box-group centralizar">
            <label for="id_ciudad">Ciudad</label>
            <select id="id_ciudad" name="id_ciudad" required>
                <option value="">Seleccione un ciudad</option>
                <?php foreach ($ciudades as $ciudad): ?>
                    <option value="<?php echo e($ciudad['id_ciudad']); ?>"
                        <?php echo isset($localidad) && $localidad['id_ciudad'] == $ciudad['id_ciudad'] ? 'selected' : ''; ?>>
                        <?php echo e($ciudad['ciudad']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="box-container">
        <div class="box-group centralizar">
            <input type="submit" value="Guardar" class="btn light">
        </div>
    </div>
</form>
