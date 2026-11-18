<h2>Estados Disponibles</h2>

    <div class="centralizar more_space">
        <a href="admin_estado.php?accion=crear" class="btn dark">+ Nuevo estado</a>
    </div>

<?php foreach($estados as $estado): ?>

<div class="box-container">
    <div class="box-group centralizar">
    <label>estado</label>
        <div class="borde_negro">
            <?php echo e($estado['estado']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_estado.php?accion=modificar&id=<?php echo e($estado['id_estado']); ?>" class="btn light">Modificar</a>
            <form action="admin_estado.php?accion=eliminar&id=<?php echo e($estado['id_estado']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este estado?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<?php endforeach; ?>
