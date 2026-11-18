<div class="login-container">

    <form class="login-form" method="POST" action="admin_empenios.php?accion=transferencia&id_boleta=<?php echo e($boleta[0]['id_boleta']); ?>">
        <?php echo csrf_field(); ?>
        <h1 class="login-text">Pago de Boleta</h1>

        <!-- Número de cuenta -->
        <label for="numero_cuenta">Número de Cuenta</label>
        <input type="text" name="datos[numero_cuenta]" id="numero_cuenta" placeholder="Número de cuenta" required />

        <!-- Monto -->
        <label for="monto">Monto</label>
        <h1 class="h-light">$<?php echo e($boleta[0]['pago_total']); ?></h1>
        <input type="hidden" name="monto" id="monto" value="<?php echo e($boleta[0]['pago_total']); ?>" />

        <!-- Referencia -->
        <label for="referencia">Referencia</label>
        <h1 class="h-light">Liquidación</h1>
        <input type="hidden" value="Liquidación" name="referencia" id="referencia" />

        <!-- Checkbox para confirmar -->
        <div class="box-group centralizar">
            <input type="checkbox" name="confirmar" id="confirmar" required />
            <label for="confirmar" class="login-text">Confirmo que los datos son correctos</label>
        </div>

        <!-- Botón de envío -->
        <input name="enviar" type="submit" value="Realizar Transferencia" />
    </form>
</div>
