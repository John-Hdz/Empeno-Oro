<h2><?php echo isset($cotizacion) ? 'Modificar Cotización' : 'Crear Cotización'; ?></h2>

<form action="admin_cotizacion.php?accion=<?php echo isset($cotizacion) ? 'modificar&id=' . e($cotizacion['id_cotizacion']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>
    <div class="box-container">
        <div class="box-group centralizar">
            <label for="cotizacion">Cotización %</label>
            <input type="text" id="cotizacion" name="cotizacion" value="<?php echo isset($cotizacion) ? e($cotizacion['cotizacion']) : ''; ?>" required>
        </div>
        <div class="box-group centralizar">
            <label for="tipo_cotizacion">Tipo de Cotización</label>
            <input type="text" id="tipo_cotizacion" name="tipo_cotizacion" value="<?php echo isset($cotizacion) ? e($cotizacion['tipo_cotizacion']) : ''; ?>" required>
        </div>
        <div class="centralizar">
            <input type="submit" value="Guardar Cambios" class="btn light">
        </div>
    </div>
</form>
