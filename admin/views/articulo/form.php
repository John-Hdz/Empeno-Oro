<h2><?php echo isset($articulo) ? 'Modificar articulo' : 'Crear articulo'; ?></h2>

<form action="admin_articulo.php?accion=<?php echo isset($articulo) ? 'modificar&id=' . e($articulo['id_articulo']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>
    <div class="box-container">
        <div class="box-group centralizar">
            <label for="articulo">Articulo</label>
            <input type="text" id="articulo" name="articulo" value="<?php echo isset($articulo) ? e($articulo['articulo']) : ''; ?>" required>
        </div>
        <div class="centralizar">
            <input type="submit" value="Guardar Cambios" class="btn light">
        </div>
    </div>
</form>
