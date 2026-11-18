<div class="cotizacion-header">
  <h1>¡Realiza tu Cotización en Linea!</h1>
  <p>Calcula el valor estimado de tu prenda en segundos, sin compromiso.</p>
</div>

<h2>Caracteristicas de tu Prenda</h2>

<form action="admin_cotizacion_online.php?accion=cotizar" method="POST" id="cotizacion-form">
  <?php echo csrf_field(); ?>
  <h3>¿Cual es el Kilataje de tu prenda?</h3>
<div class="slider-container">
  <div class="slider-wrapper">
  <input class="slider" type="range" min="8" max="24" value="8" step="2" name="kilataje" id="kilataje-slider" />
  <output for="kilataje-slider" id="kilataje-output"></output>
</div>

<h3>¿Cuál es el peso de tu prenda en gramos?</h3>
<div class="slider-wrapper">
  <input class="slider" type="range" min="0.5" max="20" value="0.5" step="0.5" name="peso" id="peso-slider" />
  <output for="peso-slider" id="peso-output"></output>
</div>
</div>

<h3>Elige una opion de Cotización</h3>

<div class="radio-container more_space">

<?php foreach($cotizaciones as $cotizacion): ?>
  <label class="radio-card">
    <input type="radio" name="opcion" value="<?php echo e($cotizacion['tipo_cotizacion']); ?>" required />
    <div class="content">
      <h4><?php echo e($cotizacion['tipo_cotizacion']); ?></h4>
      <?php if($cotizacion['cotizacion'] > 0): ?>
      <p>Con un interéz del <?php echo e($cotizacion['cotizacion']); ?>%</p>
      <?php else: ?>
      <p>Sin interéz Venta total de la preda</p>
      <?php endif; ?>
    </div>
  </label>
<?php endforeach; ?>
</div>

<div class="centralizar more_space">
    <input type="submit" class="btn dark" value="Cotizar" />
</div>

</form>
