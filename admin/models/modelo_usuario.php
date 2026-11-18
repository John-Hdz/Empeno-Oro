<?php
require_once(__DIR__ . '/../model.php');

class Usuario extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT id_usuario, correo FROM usuario;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdusuario($id_usuario)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT id_usuario, correo FROM usuario WHERE id_usuario = :id_usuario;");
        $datos->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['correo'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el correo de el usuario ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuario WHERE correo = :correo;");
            $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            if(isset($data['contrasenia']) && $data['contrasenia'] !== '' && 
                isset($data['contrasenia2']) && $data['contrasenia2'] !== '' &&
                $data['contrasenia'] === $data['contrasenia2'])
            {
                $hash = $this->hashPassword($data['contrasenia']);
                $stmt = $this->conn->prepare("INSERT INTO usuario (correo,contrasenia)
                                                VALUES (:correo, :contrasenia);");
                $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
                $stmt->bindParam(':contrasenia', $hash, PDO::PARAM_STR);
                $stmt->execute();
            } else {
                $this->conn->rollBack();
                return false;
            }

            // Obtener el ID del usuario recién creado
            $id_usuario = $this->conn->lastInsertId();
            if (isset($data['roles'])) {
                foreach ($data['roles'] as $rol) {
                    $stmt = $this->conn->prepare("INSERT INTO usuario_rol (id_usuario, id_rol)
                                                    VALUES (:id_usuario,(select id_rol from rol where rol=:rol));");
                    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_usuario, $data)
    {
        if (!isset($data['correo']) && !isset($id_usuario))
        {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el usuario existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuario WHERE id_usuario = :id_usuario;");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0)
            {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE usuario SET correo = :correo WHERE id_usuario = :id_usuario;");
            $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            // Actualizar roles del usuario
            if (isset($data['roles'])) {
                $stmt = $this->conn->prepare("DELETE FROM usuario_rol WHERE id_usuario = :id_usuario;");
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();

                foreach ($data['roles'] as $rol)
                {
                    $stmt = $this->conn->prepare("INSERT INTO usuario_rol (id_usuario, id_rol)
                                                    VALUES (:id_usuario,
                                                            (select id_rol from rol where rol=:rol));");
                    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
            else {
                // Si no se proporcionan roles, eliminar los existentes
                $stmt = $this->conn->prepare("DELETE FROM usuario_rol WHERE id_usuario = :id_usuario;");
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar usuario: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_usuario)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el usuario existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuario WHERE id_usuario = :id_usuario;");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            // Eliminar los roles asociados al usuario
            $stmt = $this->conn->prepare("DELETE FROM usuario_rol WHERE id_usuario = :id_usuario;");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            // Eliminar el usuario
            $stmt = $this->conn->prepare("DELETE FROM usuario WHERE id_usuario = :id_usuario;");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }
}
?>
