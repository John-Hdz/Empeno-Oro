<?php
require_once(__DIR__.'/../model.php');

$web = new Model();

include_once(__DIR__ . '/header.php'); ?>
<div class="dark-container">
<h1 >
    ACCESO PROHIBIDO
</h1>
<p>
    Si deseas acceder a esta área, te invitamos a iniciar sesión con las credenciales adecuadas.
    <br>
    <a href="/Empenio/admin/admin_login.php" class="login-ref">Volver a Iniciar Sesión</a> 
</p>
<button class="btn light" onclick="history.back()">Volver</button>

</div>
<?php include_once(__DIR__ . '/footer.php'); ?>