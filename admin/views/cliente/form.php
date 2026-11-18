<h2><?php echo isset($cliente) ? 'Modificar cliente' : 'Crear cliente'; ?></h2>

<form action="admin_cliente.php?accion=<?php echo isset($cliente) ? 'modificar&id=' . e($cliente['cliente_unico']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>

    <div class="box-container">
        <div class="box-group centralizar">
            <label for="primer_apellido">Primer Apellido</label>
            <input type="text" id="primer_apellido" name="primer_apellido" value="<?php echo isset($cliente) ? e($cliente['primer_apellido']) : ''; ?>" required>
        </div>

        <div class="box-group centralizar">
            <label for="segundo_apellido">Segundo Apellido</label>
            <input type="text" id="segundo_apellido" name="segundo_apellido" value="<?php echo isset($cliente) ? e($cliente['segundo_apellido']) : ''; ?>" required>
        </div>

        <div class="box-group centralizar">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo isset($cliente) ? e($cliente['nombre']) : ''; ?>" required>
        </div>

        <div class="box-group centralizar">
            <label for="curp">CURP</label>
            <input type="text" id="curp" name="curp" value="<?php echo isset($cliente) ? e($cliente['curp']) : ''; ?>" required>
        </div>

        <div class="box-group centralizar">
            <label for="nacimiento">Nacimiento</label>
            <input type="date" id="nacimiento" name="nacimiento" value="<?php echo isset($cliente) ? e($cliente['nacimiento']) : ''; ?>" required>
        </div>

        <div class="box-group centralizar">
            <label for="telefono">Telefono</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo isset($cliente) ? e($cliente['telefono']) : ''; ?>" required>
        </div>

        <div class="box-group centralizar">
            <label for="id_localidad">Localidad</label>
            <select name="id_localidad" id="id_localidad">
                <option value="">Seleciona Una Localidad</option>
                <?php foreach ($localidades as $localidad): ?>
                    <option value="<?php echo e($localidad['id_localidad']); ?>"
                        <?php echo isset($cliente) && $cliente['id_localidad'] == $localidad['id_localidad'] ? 'selected' : ''; ?>>
                        <?php echo e($localidad['localidad']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="box-group centralizar">
            <label for="direccion">Dirección</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo isset($cliente) ? e($cliente['direccion']) : ''; ?>" required>
        </div>

        <div class="box-group centralizar">
            <label for="codigo_postal">Codigo Postal</label>
            <input type="text" id="codigo_postal" name="codigo_postal" value="<?php echo isset($cliente) ? e($cliente['codigo_postal']) : ''; ?>" required>
        </div>

        <div class="box-container">
                <div class="box-group centralizar">
            <label for="id_sucursal">Sucursal</label>
            <select name="id_sucursal" id="id_sucursal">
                <option value="">Seleciona Una Sucursal</option>
                <?php foreach ($sucursales as $sucursal): ?>
                    <option value="<?php echo e($sucursal['id_sucursal']); ?>"
                        <?php echo isset($cliente) && $cliente['id_sucursal'] == $sucursal['id_sucursal'] ? 'selected' : ''; ?>>
                        <?php echo e($sucursal['sucursal']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    </div>

    <?php if (!isset($cliente)): ?>

    <div class="box-container">
        <div class="box-group-large centralizar">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" value="" required>
        </div>
    </div>

    <div class="box-container more_space">
        <div class="box-group-large centralizar">
            <label for="contrasenia">Contraseña</label>
            <input type="password" id="contrasenia" name="contrasenia" required>
        </div>

        <div class="box-group-large centralizar">
            <label for="confirmar_contrasenia">Confirmar Contraseña</label>
            <input type="password" id="confirmar_contrasenia" name="confirmar_contrasenia" required>
        </div>

    </div>
    <?php endif; ?>

    <div class="centralizar">
            <input type="submit" value="Guardar Cambios" class="btn light">
        </div>
</form>
