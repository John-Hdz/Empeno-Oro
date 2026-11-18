<h2>Usuarios</h2>

    <div class="centralizar more_space">
        <a href="admin_usuario.php?accion=crear" class="btn dark">+ Nuevo Usuario</a>
    </div>

<?php foreach($usuarios as $usuario): ?>

<div class="box-container centralizar">
    <div class="box-group-large centralizar">
    <label>Correo</label>
        <div class="borde_negro">
            <?php echo e($usuario['correo']); ?>
        </div>
    </div>
    
    <div class="box-group-large centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_usuario.php?accion=modificar&id=<?php echo e($usuario['id_usuario']); ?>" class="btn light">Modificar</a>
            <form action="admin_usuario.php?accion=eliminar&id=<?php echo e($usuario['id_usuario']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<?php endforeach; ?>
