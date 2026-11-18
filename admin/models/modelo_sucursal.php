<?php
require_once(__DIR__ . '/../model.php');

class Sucursal extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT s.*, l.*
                                                from sucursal s join localidad l on 
                                                s.id_localidad = l.id_localidad;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdSucursal($id_sucursal)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM sucursal WHERE id_sucursal = :id_sucursal;");
        $datos->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['sucursal'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el nombre de el sucursal ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM sucursal WHERE sucursal = :sucursal;");
            $stmt->bindParam(':sucursal', $data['sucursal'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO sucursal (sucursal, id_localidad) VALUES (:sucursal, :id_localidad);");
            $stmt->bindParam(':sucursal', $data['sucursal'], PDO::PARAM_STR);
            $stmt->bindParam(':id_localidad', $data['id_localidad'], PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear sucursal: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_sucursal, $data)
    {
        if (!isset($data['sucursal']) || !isset($data['precio_gramo'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el sucursal existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM sucursal WHERE id_sucursal = :id_sucursal;");
            $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE sucursal SET sucursal = :sucursal, id_localidad = :id_localidad WHERE id_sucursal = :id_sucursal;");
            $stmt->bindParam(':sucursal', $data['sucursal'], PDO::PARAM_STR);
            $stmt->bindParam(':id_localidad', $data['id_localidad'], PDO::PARAM_STR);
            $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar sucursal: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_sucursal)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el sucursal existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM sucursal WHERE id_sucursal = :id_sucursal;");
            $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM sucursal WHERE id_sucursal = :id_sucursal;");
            $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar sucursal: " . $e->getMessage());
            return false;
        }
    }
}
?>
