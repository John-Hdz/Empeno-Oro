<?php
require_once __DIR__ . '/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Model
{
    public $conn;
    public $tipos;
    public $max_tam;

    public function get_max_tam(): int
    {
        $this->max_tam = 1024 * 1024;
        return $this->max_tam;
    }

    public function get_tipos(): array
    {
        $this->tipos = ['image/jpeg', 'image/png', 'image/gif'];
        return $this->tipos;
    }

    public function conectar(): void
    {
        if ($this->conn instanceof PDO) {
            return;
        }

        try {
            $dsn = SGBD . ':host=' . HOST . ';port=' . DB_PORT . ';dbname=' . DB . ';charset=utf8mb4';
            $this->conn = new PDO($dsn, USER, PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            error_log('Error de conexión a la base de datos: ' . $e->getMessage());
            die('Error de conexión a la base de datos');
        }
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    protected function verifyPassword(string $password, string $storedHash): bool
    {
        if ($storedHash === '') {
            return false;
        }

        if (password_verify($password, $storedHash)) {
            return true;
        }

        // Compatibilidad con contraseñas heredadas en MD5
        if (strlen($storedHash) === 32 && ctype_xdigit($storedHash)) {
            return hash_equals(strtolower($storedHash), md5($password));
        }

        return false;
    }

    public function cargar_img()
    {
        if (empty($_FILES)) {
            return false;
        }

        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
        ];

        $finfo = new finfo(FILEINFO_MIME_TYPE);

        foreach ($_FILES as $imagen) {
            if (!isset($imagen['error'], $imagen['tmp_name'], $imagen['size']) || $imagen['error'] !== UPLOAD_ERR_OK) {
                continue;
            }
            if (!is_uploaded_file($imagen['tmp_name'])) {
                continue;
            }
            if ($imagen['size'] <= 0 || $imagen['size'] > $this->get_max_tam()) {
                continue;
            }

            $mime = $finfo->file($imagen['tmp_name']);
            if (!isset($mimeToExt[$mime]) || !in_array($mime, $this->get_tipos(), true)) {
                continue;
            }
            if (@getimagesize($imagen['tmp_name']) === false) {
                continue;
            }

            if (!is_dir(UPLOADDIR)) {
                mkdir(UPLOADDIR, 0750, true);
            }

            $nombre = bin2hex(random_bytes(16)) . '.' . $mimeToExt[$mime];
            $destino = UPLOADDIR . $nombre;

            if (!file_exists($destino) && move_uploaded_file($imagen['tmp_name'], $destino)) {
                return $nombre;
            }
        }

        return false;
    }

    public function validar_correo($correo): int|false
    {
        if (!is_string($correo)) {
            return false;
        }
        return filter_var($correo, FILTER_VALIDATE_EMAIL) !== false ? 1 : 0;
    }

    public function login($correo, $contrasenia): bool
    {
        $_SESSION['validado'] = false;
        $_SESSION['roles'] = [];
        $_SESSION['permisos'] = [];
        $_SESSION['id_empleado'] = null;
        $_SESSION['id_cliente'] = null;
        $_SESSION['id_sucursal'] = null;
        $_SESSION['cliente_unico'] = null;

        if (!is_string($correo) || !is_string($contrasenia) || !$this->validar_correo($correo)) {
            return false;
        }

        $this->conectar();
        $sql = "SELECT * FROM usuario WHERE correo = :correo LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$res || !$this->verifyPassword($contrasenia, (string) ($res['contrasenia'] ?? ''))) {
            return false;
        }

        // Migrar automáticamente hashes MD5 heredados o algoritmos antiguos a password_hash()
        if (password_needs_rehash((string) $res['contrasenia'], PASSWORD_DEFAULT)) {
            try {
                $nuevoHash = $this->hashPassword($contrasenia);
                $upd = $this->conn->prepare("UPDATE usuario SET contrasenia = :contrasenia WHERE id_usuario = :id_usuario");
                $upd->bindParam(':contrasenia', $nuevoHash, PDO::PARAM_STR);
                $upd->bindParam(':id_usuario', $res['id_usuario'], PDO::PARAM_INT);
                $upd->execute();
            } catch (PDOException $e) {
                error_log('Error al actualizar hash de contraseña: ' . $e->getMessage());
            }
        }

        session_regenerate_id(true);
        $_SESSION['validado'] = true;
        $_SESSION['id_usuario'] = $res['id_usuario'];
        $_SESSION['correo'] = $res['correo'];
        $_SESSION['cliente_unico'] = null;

        $roles = [];
        $sql = "SELECT r.rol FROM usuario u
                JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                JOIN rol r ON ur.id_rol = r.id_rol
                WHERE u.correo = :correo;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $rol) {
            $roles[] = $rol['rol'];
        }

        $permisos = [];
        $sql = "SELECT p.permiso
                FROM permiso p
                JOIN permiso_rol pr ON p.id_permiso = pr.id_permiso
                JOIN rol r ON pr.id_rol = r.id_rol
                JOIN usuario_rol ur ON r.id_rol = ur.id_rol
                JOIN usuario u ON ur.id_usuario = u.id_usuario
                WHERE u.correo = :correo;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $permiso) {
            $permisos[] = $permiso['permiso'];
        }

        $_SESSION['roles'] = array_values(array_unique($roles));
        $_SESSION['permisos'] = array_values(array_unique($permisos));

        $sql = "SELECT cliente_unico, id_sucursal FROM cliente c
                JOIN usuario u ON c.id_usuario = u.id_usuario
                WHERE u.correo = :correo LIMIT 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($resultado['cliente_unico'])) {
            $_SESSION['cliente_unico'] = $resultado['cliente_unico'];
            $_SESSION['id_sucursal'] = $resultado['id_sucursal'] ?? null;
        }

        $sql = "SELECT id_empleado, id_sucursal FROM empleado e
                JOIN usuario u ON e.id_usuario = u.id_usuario
                WHERE u.correo = :correo LIMIT 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($resultado['id_empleado'])) {
            $_SESSION['id_empleado'] = $resultado['id_empleado'];
            $_SESSION['id_sucursal'] = $resultado['id_sucursal'] ?? $_SESSION['id_sucursal'];
        }

        return true;
    }

    public function logout(): bool
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        return true;
    }

    public function checar($rol): bool
    {
        if ($this->sesionActiva()) {
            $roles = isset($_SESSION['roles']) && is_array($_SESSION['roles']) ? $_SESSION['roles'] : [];
            return in_array($rol, $roles, true);
        }
        return false;
    }

    public function leerRolesUsuario(): array
    {
        return isset($_SESSION['roles']) && is_array($_SESSION['roles']) ? $_SESSION['roles'] : [];
    }

    public function checar_permiso($permiso): bool
    {
        if ($this->sesionActiva()) {
            $permisos = isset($_SESSION['permisos']) && is_array($_SESSION['permisos']) ? $_SESSION['permisos'] : [];
            return in_array($permiso, $permisos, true);
        }
        return false;
    }

    public function requerirRol(string $rol): void
    {
        if (!$this->checar($rol)) {
            header('Location: ' . BASE_PATH . '/admin/views/cadenero.php?msg=forbidden');
            exit();
        }
    }

    public function requerirPermiso(string $permiso): void
    {
        if (!$this->sesionActiva() || !$this->checar_permiso($permiso)) {
            header('Location: ' . BASE_PATH . '/admin/views/cadenero.php?msg=forbidden');
            exit();
        }
    }

    public function requerirCsrf(string $redirectUrl): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !verify_csrf()) {
            header('Location: ' . $redirectUrl . (str_contains($redirectUrl, '?') ? '&' : '?') . 'msg=unauthorized');
            exit();
        }
    }

    public function enviar_correo($destinatario, $asunto, $mensaje): bool
    {
        if (MAILSMTP === '' || MAILUSER === '' || MAILPASSWORD === '') {
            error_log('Configuración SMTP incompleta en .env');
            return false;
        }

        require_once __DIR__ . '/../vendor/autoload.php';
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->CharSet = 'UTF-8';
            $mail->Host = MAILSMTP;
            $mail->Port = MAILPORT;
            $mail->SMTPSecure = MAILENCRYPTION === 'tls'
                ? PHPMailer::ENCRYPTION_STARTTLS
                : PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAuth = true;
            $mail->Username = MAILUSER;
            $mail->Password = MAILPASSWORD;
            $mail->setFrom(MAILUSER, MAILFROMNAME);
            $mail->addAddress($destinatario);
            $mail->Subject = $asunto;
            $mail->msgHTML($mensaje);
            return $mail->send();
        } catch (Exception $e) {
            error_log('Error al enviar correo: ' . $e->getMessage());
            return false;
        }
    }

    public function cambiar_contraseña($correo)
    {
        if ($this->validar_correo($correo)) {
            try {
                $this->conectar();
                $this->conn->beginTransaction();

                $sql = "SELECT correo FROM usuario WHERE correo = :correo LIMIT 1";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                if (isset($resultado['correo'])) {
                    // 128 bits aleatorios (32 caracteres hex) compatibles con columnas VARCHAR(32)+
                    $token = bin2hex(random_bytes(16));
                    $sql = "UPDATE usuario SET token = :token WHERE correo = :correo";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
                    $stmt->execute();

                    $this->conn->commit();
                    return $token;
                }
                $this->conn->rollBack();
                return false;
            } catch (PDOException $e) {
                $this->conn->rollBack();
                error_log('Error en cambiar_contraseña: ' . $e->getMessage());
                return false;
            }
        }
        return false;
    }

    public function validar_token($correo, $token): bool
    {
        if (!is_string($correo) || !is_string($token) || $token === '' || !$this->validar_correo($correo)) {
            return false;
        }

        $this->conectar();
        $sql = "SELECT token FROM usuario WHERE correo = :correo AND token IS NOT NULL LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado && !empty($resultado['token'])) {
            return hash_equals((string) $resultado['token'], $token);
        }
        return false;
    }

    public function restablecer($correo, $contrasenia, $token): bool
    {
        if (!is_string($contrasenia) || trim($contrasenia) === '') {
            return false;
        }

        if ($this->validar_token($correo, $token)) {
            try {
                $this->conectar();
                $this->conn->beginTransaction();
                $hash = $this->hashPassword($contrasenia);
                $sql = "UPDATE usuario SET contrasenia = :contrasenia, token = NULL WHERE correo = :correo";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':contrasenia', $hash, PDO::PARAM_STR);
                $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
                $stmt->execute();
                $this->conn->commit();
                return $stmt->rowCount() > 0;
            } catch (PDOException $e) {
                $this->conn->rollBack();
                error_log('Error en restablecer: ' . $e->getMessage());
                return false;
            }
        }
        return false;
    }

    public function sesionActiva(): bool
    {
        return isset($_SESSION['validado']) && $_SESSION['validado'] === true;
    }
}
