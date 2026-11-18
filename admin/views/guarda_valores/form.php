<h2><?php echo isset($guarda_valores) ? 'Modificar Guarda Valores' : 'Registrar Guarda Valores'; ?></h2>

<form action="admin_guarda_valores.php?accion=<?php echo isset($guarda_valores) ? 'modificar&id_empenio=' . e($guarda_valores['id_empenio']) . '&cliente_unico=' . e($guarda_valores['cliente_unico']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>

    <div class="box-container">
        <div class="box-group centralizar">
            <label for="deposito">Deposito</label>
            <input type="text" id="deposito" name="deposito" value="<?php echo isset($guarda_valores) ? e($guarda_valores['deposito']) : ''; ?>" required>
        </div>
        <div class="box-group centralizar">
            <label for="columna">Columna</label>
            <input type="text" id="columna" name="columna" value="<?php echo isset($guarda_valores) ? e($guarda_valores['columna']) : ''; ?>" required>
        </div>
        <div class="box-group centralizar">
            <label for="fila">Fila</label>
            <input type="text" id="fila" name="fila" value="<?php echo isset($guarda_valores) ? e($guarda_valores['fila']) : ''; ?>" required>
        </div>

        <?php if(!isset($guarda_valores['id_empenio']) && !isset($cliente_unico['cliente_unico'])) :?>
        <div class="box-group centralizar">
            <label for="id_empenio">Empeño</label>
            <select name="id_empenio" id="id_empenio">
                <option value="">Seleccione un Empenio</option>
                <?php foreach ($empenios as $empenio): ?>
                    <option value="<?php echo e($empenio['num_empenio']); ?>"
                        <?php echo (isset($guarda_valores) && $guarda_valores['id_empenio'] == $empenio['num_empenio']) ? 'selected' : ''; ?>>
                        <?php echo e($empenio['num_empenio']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <select name="cliente_unico" id="cliente_unico">
                <option value="">Seleccione un Cliente</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?php echo e($cliente['cliente_unico']); ?>"
                        <?php echo (isset($guarda_valores) && $guarda_valores['cliente_unico'] == $cliente['cliente_unico']) ? 'selected' : ''; ?>>
                        <?php echo e($cliente['nombre'] . ' ' . $cliente['primer_apellido'] . ' ' . $cliente['segundo_apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php endif; ?>
    </div>

    <div class="box-container">
        <div class="box-group centralizar">
            <button type="submit" class="btn btn-primary">
                <?php echo isset($guarda_valores) ? 'Modificar' : 'Registrar'; ?>
            </button>
        </div>
    </div>
</form>
