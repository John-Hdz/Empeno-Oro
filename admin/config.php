<?php
require_once __DIR__ . '/env_loader.php';

loadEnv(dirname(__DIR__) . '/.env');

$appDebug = filter_var(env('APP_DEBUG', false), FILTER_VALIDATE_BOOL);
ini_set('display_errors', $appDebug ? '1' : '0');
ini_set('display_startup_errors', $appDebug ? '1' : '0');
error_reporting($appDebug ? E_ALL : (E_ALL & ~E_DEPRECATED & ~E_STRICT));

if (session_status() === PHP_SESSION_NONE) {
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

define('APP_ENV', (string) env('APP_ENV', 'production'));
define('APP_DEBUG', $appDebug);
define('APP_URL', rtrim((string) env('APP_URL', 'http://localhost/Empenio'), '/'));
define('BASE_PATH', '/' . trim((string) env('BASE_PATH', 'Empenio'), '/'));

define('SGBD', (string) env('DB_CONNECTION', 'mysql'));
define('HOST', (string) env('DB_HOST', 'localhost'));
define('DB_PORT', (string) env('DB_PORT', '3306'));
define('DB', (string) env('DB_NAME', ''));
define('USER', (string) env('DB_USER', ''));
define('PASSWORD', (string) env('DB_PASSWORD', ''));

$uploadDirEnv = (string) env('UPLOAD_DIR', 'uploads');
$isAbsoluteUpload = preg_match('/^(?:[A-Za-z]:[\\\\\\/]|\\/)/', $uploadDirEnv) === 1;
$resolvedUploadDir = $isAbsoluteUpload
    ? rtrim($uploadDirEnv, "\\/") . DIRECTORY_SEPARATOR
    : dirname(__DIR__) . DIRECTORY_SEPARATOR . trim($uploadDirEnv, "\\/") . DIRECTORY_SEPARATOR;
define('UPLOADDIR', $resolvedUploadDir);

define('MAILSMTP', (string) env('MAIL_HOST', ''));
define('MAILSTMP', MAILSMTP);
define('MAILPORT', (int) env('MAIL_PORT', 465));
define('MAILENCRYPTION', (string) env('MAIL_ENCRYPTION', 'smtps'));
define('MAILUSER', (string) env('MAIL_USERNAME', ''));
define('MAILPASSWORD', (string) env('MAIL_PASSWORD', ''));
define('MAILFROMNAME', (string) env('MAIL_FROM_NAME', 'Presta Prenda'));

if (!function_exists('e')) {
    /**
     * Escapa salida HTML para prevenir XSS.
     */
    function e(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Obtiene o genera el token CSRF de la sesión actual.
     */
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Devuelve el campo oculto HTML con el token CSRF.
     */
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('verify_csrf')) {
    /**
     * Verifica el token CSRF recibido en una petición.
     */
    function verify_csrf(?string $token = null): bool
    {
        $submitted = $token ?? ($_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        if (!is_string($submitted) || $submitted === '' || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $submitted);
    }
}
