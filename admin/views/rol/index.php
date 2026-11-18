<h2>Roles Disponibles</h2>

    <div class="centralizar more_space">
        <a href="admin_rol.php?accion=crear" class="btn dark">+ Nuevo Rol</a>
    </div>

<?php foreach($roles as $rol): ?>
    <?php $permisos = explode(",", (string) ($rol['permisos'] ?? '')); ?>

<div class="box-container">
    <div class="box-group-large centralizar">
    <label>Rol</label>
        <div class="borde_negro">
            <?php echo e($rol['rol']); ?>
        </div>
        <label style="color: orange;">
            <?php
            foreach($permisos as $permiso)
            {
                echo e($permiso) . " ";
                if(count($permisos) > 3 && $permiso === $permisos[3])
                {
                    echo "<br>";
                }
            }
            ?>
        </label>
    </div>
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_rol.php?accion=modificar&id=<?php echo e($rol['id_rol']); ?>" class="btn light">Modificar</a>
            <form action="admin_rol.php?accion=eliminar&id=<?php echo e($rol['id_rol']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este rol?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<?php endforeach; ?>
