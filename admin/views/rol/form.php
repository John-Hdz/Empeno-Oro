<h2><?php echo isset($rol) ? 'Modificar rol' : 'Crear rol'; ?></h2>

<form action="admin_rol.php?accion=<?php echo isset($rol) ? 'modificar&id=' . e($rol['id_rol']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>

    <div class="box-container">
        <div class="box-group centralizar">
            <label for="rol">Rol</label>
            <input type="text" id="rol" name="rol" value="<?php echo isset($rol) ? e($rol['rol']) : ''; ?>" required>
        </div>
    </div>

    <div>
        <h3>Permisos</h3>
        <div class="box-container more_space">
            <?php foreach ($permisos as $permiso): ?>
            <?php $permiso = $permiso['permiso']; ?>
            <div class="box-group centralizar">
                <label for="<?php echo e($permiso); ?>"><?php echo e($permiso); ?></label>
                <input type="checkbox" id="<?php echo e($permiso); ?>" name="permisos[]"
                        value="<?php echo e($permiso); ?>"
                <?php echo (isset($permisos_rol) && in_array($permiso, $permisos_rol, true)) ? 'checked' : ''; ?>>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

        <div class="centralizar">
            <input type="submit" value="Guardar Cambios" class="btn light">
        </div>
</form>
