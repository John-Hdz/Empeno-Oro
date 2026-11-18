<h2><?php echo isset($clasificacion) ? 'Modificar clasificación' : 'Crear clasificación'; ?></h2>

<form action="admin_clasificacion.php?accion=<?php echo isset($clasificacion) ? 'modificar&id=' . e($clasificacion['id_clasificacion']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>
    <div class="box-container">
        <div class="box-group centralizar">
            <label for="clasificacion">clasificación</label>
            <input type="text" id="clasificacion" name="clasificacion" value="<?php echo isset($clasificacion) ? e($clasificacion['clasificacion']) : ''; ?>" required>
        </div>
        <div class="centralizar">
            <input type="submit" value="Guardar Cambios" class="btn light">
        </div>
    </div>
</form>
