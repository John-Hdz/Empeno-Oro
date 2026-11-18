<div>
    <h2>Acreditado y/o Gerente Prendario</h2>

    <div class="box-container">
        <div class="box-group centralizar">
            <label for="apellido-paterno">Apellido Paterno</label>
            <div class="borde_negro" id="apellido-paterno"><?php echo e($empenio['primer_apellido']); ?></div>
        </div>
        <div class="box-group centralizar">
            <label for="apellido-materno">Apellido Materno</label>
            <div class="borde_negro" id="apellido-materno"><?php echo e($empenio['segundo_apellido']); ?></div>
        </div>
        <div class="box-group centralizar">
            <label for="nombre">Nombre(s)</label>
            <div class="borde_negro" id="nombre"><?php echo e($empenio['nombre']); ?></div>
        </div>
    </div>

    <h2>Descripción de la Prenda</h2>

    <div class="box-container">
        <div class="box-group centralizar">
            <label for="desc_generica">Descripción Generica</label>
            <div class="borde_negro" id="desc_generica"><?php echo e($empenio['articulo']); ?></div>
        </div>
        <div class="box-group centralizar">
            <label for="caracteristicas">Características</label>
            <div class="borde_negro" id="caracteristicas"><?php echo e($empenio['caracteristicas_prenda']); ?></div>
        </div>
        <div class="box-group centralizar">
            <label for="kilataje">Kilataje</label>
            <div class="borde_negro" id="kilataje"><?php echo e($empenio['kilataje']); ?></div>
        </div>
        <div class="box-group centralizar">
            <label for="peso">Peso en Gramos</label>
            <div class="borde_negro" id="peso"><?php echo e($empenio['peso_prenda']); ?></div>
        </div>
        <div class="box-group centralizar">
            <label for="avaluo">Avalúo</label>
            <div class="borde_negro" id="avaluo"><?php echo e($empenio['avaluo_prenda']); ?></div>
        </div>
    </div>

    <h2>Referencias del Crédito Dispuesto</h2>

    <div class="card_container">
        <div class="card">
            <p class="title">Monto del Crédito</p>
            <p class="amount">$<?php echo e($empenio['monto_credito']); ?></p>
        </div>
        <div class="card">
            <p class="title">Monto Total a Pagar</p>
            <p class="amount">$<?php echo e($empenio['pago_total']); ?></p>
        </div>
        <div class="card">
            <p class="title">Pago Total de Intereses</p>
            <p class="amount">$<?php echo e($empenio['total_intereses']); ?></p>
        </div>
        <div class="card">
            <p class="title">Plazo del Crédito</p>
            <p class="amount"><?php echo e($empenio['plazo_credito']); ?> días</p>
        </div>
    </div>

    <h2>Numero de Crédito</h2>
    <h1><?php echo e($empenio['numero_credito']); ?></h1>

    <div class="box-container more_space">
        <div class="box-group centralizar">
            <label>Cliente Unico</label>
            <div class="borde_negro"><?php echo e($empenio['cliente_unico']); ?></div>
        </div>
        <div class="box-group centralizar">
            <label>Fecha de pago Proximo</label>
            <div class="borde_negro"><?php echo e($empenio['limite_pago_proximo']); ?></div>
        </div>
        <div class="box-group centralizar">
            <label>Sucursal</label>
            <div class="borde_negro"><?php echo e($empenio['sucursal']); ?></div>
        </div>
    </div>
        <div class="qr-container more_space" style="margin-top: 15px; text-align: center;">
            <a href="<?php echo e(BASE_PATH); ?>/admin/views/empenios/qr_boleta.php?id_boleta=<?php echo e($empenio['id_boleta']); ?>">
                <img src="<?php echo e(BASE_PATH); ?>/images/qr_boleta<?php echo e($empenio['id_boleta']); ?>.png" alt="CLICK para generar un QR para descargar PDF" width="150" />
            </a>
            <p style="font-size: 0.9em;">Escanea para ver la boleta</p>
        </div>
</div>
