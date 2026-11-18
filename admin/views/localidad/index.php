<h2>localidades Disponibles</h2>

    <div class="centralizar more_space">
        <a href="admin_localidad.php?accion=crear" class="btn dark">+ Nueva localidad</a>
    </div>

<?php foreach($localidades as $localidad): ?>

<div class="box-container">
    <div class="box-group centralizar">
    <label>Ciudad</label>
        <div class="borde_negro">
            <?php echo e($localidad['ciudad']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Localidad</label>
        <div class="borde_negro">
            <?php echo e($localidad['localidad']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_localidad.php?accion=modificar&id=<?php echo e($localidad['id_localidad']); ?>" class="btn light">Modificar</a>
            <form action="admin_localidad.php?accion=eliminar&id=<?php echo e($localidad['id_localidad']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta localidad?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<?php endforeach; ?>
