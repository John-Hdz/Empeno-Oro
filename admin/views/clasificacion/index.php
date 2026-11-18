<h2>Tipos de clasificaciones Disponibles</h2>

    <div class="centralizar more_space">
        <a href="admin_clasificacion.php?accion=crear" class="btn dark">+ Nueva clasificación</a>
    </div>

<?php foreach($clasificaciones as $clasificacion): ?>

<div class="box-container">
    <div class="box-group centralizar">
    <label>clasificación</label>
        <div class="borde_negro">
            <?php echo e($clasificacion['clasificacion']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_clasificacion.php?accion=modificar&id=<?php echo e($clasificacion['id_clasificacion']); ?>" class="btn light">Modificar</a>
            <form action="admin_clasificacion.php?accion=eliminar&id=<?php echo e($clasificacion['id_clasificacion']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta clasificación?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<?php endforeach; ?>
