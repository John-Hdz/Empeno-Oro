<h2>Guarda Valores</h2>

    <div class="centralizar more_space">
        <a href="admin_guarda_valores.php?accion=crear" class="btn dark">+ Nuevo Guarda Valor</a>
    </div>

    <form class="searchBy" action="admin_guarda_valores.php" method="get">
        <input type="hidden" name="accion" value="buscarPorNombre">
        <label>Buscar por Cliente</label>
        <input type="text" name="cliente" required>
        <input type="submit" value="Buscar" class="btn light">
    </form>


<?php foreach($ar_guarda_valores as $guarda_valores): ?>

<div class="accordion-item">
<h3><?php echo e($guarda_valores['cliente']); ?></h3>

<div class="box-container">
    <div class="box-group centralizar">
    <label>Cliente Unico</label>
        <div class="borde_negro">
            <?php echo e($guarda_valores['cliente_unico']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Num. Empeño</label>
        <div class="borde_negro">
            <?php echo e($guarda_valores['id_empenio']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Num. Boleta</label>
        <div class="borde_negro">
            <?php echo e($guarda_valores['id_boleta']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Guarda Valores</label>
        <div class="borde_negro">
            <?php echo e($guarda_valores['deposito']); ?>
            <?php echo e($guarda_valores['columna']); ?>
            <?php echo e($guarda_valores['fila']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_guarda_valores.php?accion=modificar&id_empenio=<?php echo e($guarda_valores['id_empenio']); ?>&cliente_unico=<?php echo e($guarda_valores['cliente_unico']); ?>"
                class="btn light">Modificar</a>
            <form action="admin_guarda_valores.php?accion=eliminar&id_empenio=<?php echo e($guarda_valores['id_empenio']); ?>&cliente_unico=<?php echo e($guarda_valores['cliente_unico']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>

</div>

</div>
<?php endforeach; ?>
