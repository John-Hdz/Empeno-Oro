<?php
require_once __DIR__ . '/model.php';

$app = new Model();
$accion = $_GET['accion'] ?? null;

switch ($accion) {
    case 'logout':
        $app->logout();
        header('Location: admin_login.php?msg=logout_success');
        exit();

    case 'olvidar':
        require_once __DIR__ . '/views/header_login.php';
        include_once __DIR__ . '/login/olvidar.php';
        require_once __DIR__ . '/views/footer.php';
        exit();

    case 'cambiar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['datos']) && verify_csrf()) {
            $datos = $_POST['datos'];
            $correo = trim((string) ($datos['correo'] ?? ''));
            if ($app->validar_correo($correo)) {
                $token = $app->cambiar_contraseña($correo);
                if ($token) {
                    $resetUrl = APP_URL . '/admin/admin_login.php?accion=restablecer&correo='
                        . urlencode($correo) . '&token=' . urlencode($token);
                    $mensaje = "Estimado usuario, has solicitado un cambio de contraseña,
                    por favor presiona el siguiente enlace para restablecerla.<br>
                    <a href='" . e($resetUrl) . "'>Presiona Aquí</a>";
                    $app->enviar_correo($correo, 'Restablecimiento de contraseña Presta Prenda', $mensaje);
                }
                header('Location: admin_login.php?msg=email_sent');
                exit();
            }
        }
        header('Location: admin_login.php?msg=error');
        exit();

    case 'restablecer':
        $correo = (string) ($_GET['correo'] ?? '');
        $token = (string) ($_GET['token'] ?? '');
        if ($app->validar_correo($correo) && $app->validar_token($correo, $token)) {
            $datos = ['correo' => $correo, 'token' => $token];
            require_once __DIR__ . '/views/header_login.php';
            include_once __DIR__ . '/login/restablacer.php';
            require_once __DIR__ . '/views/footer.php';
            exit();
        }
        header('Location: admin_login.php?msg=error');
        exit();

    case 'nueva':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['datos']) || !verify_csrf()) {
            header('Location: admin_login.php?msg=error');
            exit();
        }
        $datos = $_POST['datos'];
        $correo = (string) ($datos['correo'] ?? '');
        $token = (string) ($datos['token'] ?? '');
        $pass = (string) ($datos['contrasenia'] ?? '');
        $confirm = (string) ($datos['confirmar_contrasenia'] ?? '');

        if (
            $pass !== '' &&
            $pass === $confirm &&
            $app->validar_correo($correo) &&
            $app->validar_token($correo, $token) &&
            $app->restablecer($correo, $pass, $token)
        ) {
            if ($app->sesionActiva()) {
                header('Location: admin_usuario.php?msg=password_changed');
                exit();
            }
            header('Location: admin_login.php?msg=password_changed');
            exit();
        }
        header('Location: admin_login.php?msg=error');
        exit();

    case 'login':
    default:
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
            if (!verify_csrf()) {
                header('Location: admin_login.php?msg=unauthorized');
                exit();
            }
            $datos = $_POST['datos'] ?? [];
            $correo = (string) ($datos['correo'] ?? '');
            $contrasenia = (string) ($datos['contrasenia'] ?? '');

            if ($app->login($correo, $contrasenia)) {
                header('Location: admin_empenios.php?msg=login_success');
                exit();
            }
            header('Location: admin_login.php?msg=invalid');
            exit();
        }

        require_once __DIR__ . '/views/header_login.php';
        include_once __DIR__ . '/login/login.php';
        require_once __DIR__ . '/views/footer.php';
        break;
}
