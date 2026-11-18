<h2>Tipos de articulos Disponibles</h2>

    <div class="centralizar more_space">
        <a href="admin_articulo.php?accion=crear" class="btn dark">+ Nuevo articulo</a>
    </div>

<?php foreach($articulos as $articulo): ?>

<div class="box-container">
    <div class="box-group centralizar">
    <label>Articulo</label>
        <div class="borde_negro">
            <?php echo e($articulo['articulo']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_articulo.php?accion=modificar&id=<?php echo e($articulo['id_articulo']); ?>" class="btn light">Modificar</a>
            <form action="admin_articulo.php?accion=eliminar&id=<?php echo e($articulo['id_articulo']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este articulo?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>

</div>

<?php endforeach; ?>
