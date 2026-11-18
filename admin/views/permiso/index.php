<h2>Permisos Existentes</h2>

    <div class="centralizar more_space">
        <a href="admin_permiso.php?accion=crear" class="btn dark">+ Nuevo permiso</a>
    </div>

<?php foreach($permisos as $permiso): ?>

<div class="box-container">
    <div class="box-group centralizar">
    <label>Permiso</label>
        <div class="borde_negro">
            <?php echo e($permiso['permiso']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_permiso.php?accion=modificar&id=<?php echo e($permiso['id_permiso']); ?>" class="btn light">Modificar</a>
            <form action="admin_permiso.php?accion=eliminar&id=<?php echo e($permiso['id_permiso']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este permiso?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<?php endforeach; ?>
