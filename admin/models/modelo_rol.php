<?php
require_once(__DIR__ . '/../model.php');

class Rol extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM vw_RolesPermisos;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }
    function leerRoles()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM rol;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPermisosRol($id_rol)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT p.permiso
                                    from permiso p join permiso_rol pr on p.id_permiso = pr.id_permiso
                                    where pr.id_rol = :id_rol;");
        $datos->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdrol($id_rol)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM vw_RolesPermisos WHERE id_rol = :id_rol;");
        $datos->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function leerRolUsuario($id_usuario)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT rol
                                        from usuario u join usuario_rol ur on u.id_usuario = ur.id_usuario
                                        join rol r on ur.id_rol = r.id_rol where u.id_usuario = :id_usuario;");
        $datos->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_COLUMN);
    }

    function crear($data)
    {
        if (!isset($data['rol']))
        {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el nombre de el rol ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM rol WHERE rol = :rol;");
            $stmt->bindParam(':rol', $data['rol'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0)
            {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO rol (rol) VALUES (:rol);");
            $stmt->bindParam(':rol', $data['rol'], PDO::PARAM_STR);
            $stmt->execute();

            // Insertar permisos
            if (isset($data['permisos']))
            {
                $id_rol = $this->conn->lastInsertId();
                foreach ($data['permisos'] as $permiso)
                {
                    $stmt = $this->conn->prepare("INSERT INTO permiso_rol (id_permiso, id_rol)
                                                VALUES ((select id_permiso from permiso where permiso = :permiso), :id_rol);");
                    $stmt->bindParam(':permiso', $permiso, PDO::PARAM_STR);
                    $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear rol: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_rol, $data)
    {
        if (!isset($data['rol']) || !isset($id_rol)) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el rol existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM rol WHERE id_rol = :id_rol;");
            $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE rol SET rol = :rol WHERE id_rol = :id_rol;");
            $stmt->bindParam(':rol', $data['rol'], PDO::PARAM_STR);
            $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
            $stmt->execute();

            // Modificar permisos
            if (isset($data['permisos'])) {
                // Eliminar permisos existentes
                $stmt = $this->conn->prepare("DELETE FROM permiso_rol WHERE id_rol = :id_rol;");
                $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
                $stmt->execute();

                // Insertar nuevos permisos
                foreach ($data['permisos'] as $permiso) {
                    $stmt = $this->conn->prepare("INSERT INTO permiso_rol (id_permiso, id_rol)
                                                VALUES ((select id_permiso from permiso where permiso = :permiso), :id_rol);");
                    $stmt->bindParam(':permiso', $permiso, PDO::PARAM_STR);
                    $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }else {
                // Si no se proporcionan permisos, eliminar todos los permisos del rol
                $stmt = $this->conn->prepare("DELETE FROM permiso_rol WHERE id_rol = :id_rol;");
                $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar rol: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_rol)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el rol existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM rol WHERE id_rol = :id_rol;");
            $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM rol WHERE id_rol = :id_rol;");
            $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar rol: " . $e->getMessage());
            return false;
        }
    }
}
?>
