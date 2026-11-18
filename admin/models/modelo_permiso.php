<?php
require_once(__DIR__ . '/../model.php');

class Permiso extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM permiso;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }
    function leerPermisosRol($id_rol)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM vw_RolesPermisos WHERE id_rol = :id_rol;");
        $datos->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdpermiso($id_permiso)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM permiso WHERE id_permiso = :id_permiso;");
        $datos->bindParam(':id_permiso', $id_permiso, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['permiso'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el nombre de el permiso ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM permiso WHERE permiso = :permiso;");
            $stmt->bindParam(':permiso', $data['permiso'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO permiso (permiso) VALUES (:permiso);");
            $stmt->bindParam(':permiso', $data['permiso'], PDO::PARAM_STR);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear permiso: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_permiso, $data)
    {
        if (!isset($data['permiso']) || !isset($id_permiso)) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el permiso existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM permiso WHERE id_permiso = :id_permiso;");
            $stmt->bindParam(':id_permiso', $id_permiso, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE permiso SET permiso = :permiso WHERE id_permiso = :id_permiso;");
            $stmt->bindParam(':permiso', $data['permiso'], PDO::PARAM_STR);
            $stmt->bindParam(':id_permiso', $id_permiso, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar permiso: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_permiso)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el permiso existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM permiso WHERE id_permiso = :id_permiso;");
            $stmt->bindParam(':id_permiso', $id_permiso, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM permiso WHERE id_permiso = :id_permiso;");
            $stmt->bindParam(':id_permiso', $id_permiso, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar permiso: " . $e->getMessage());
            return false;
        }
    }
}
?>
