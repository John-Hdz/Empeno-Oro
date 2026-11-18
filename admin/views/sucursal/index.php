<h2>Tipos de sucursales Disponibles</h2>

    <div class="centralizar more_space">
        <a href="admin_sucursal.php?accion=crear" class="btn dark">+ Nueva Sucursal</a>
    </div>

<?php foreach($sucursales as $sucursal): ?>

<div class="box-container">
    <div class="box-group centralizar">
    <label>Sucursal</label>
        <div class="borde_negro">
            <?php echo e($sucursal['sucursal']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Localidad</label>
        <div class="borde_negro">
            <?php echo e($sucursal['localidad']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_sucursal.php?accion=modificar&id=<?php echo e($sucursal['id_sucursal']); ?>" class="btn light">Modificar</a>
            <form action="admin_sucursal.php?accion=eliminar&id=<?php echo e($sucursal['id_sucursal']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta sucursal?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<?php endforeach; ?>
