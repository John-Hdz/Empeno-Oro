<?php
require_once(__DIR__ . '/../model.php');

class Estado extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM estado;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdestado($id_estado)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM estado WHERE id_estado = :id_estado;");
        $datos->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['estado'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el nombre del estado ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM estado WHERE estado = :estado;");
            $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO estado (estado) VALUES (:estado);");
            $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear estado: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_estado, $data)
    {
        if (!isset($data['estado'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si la estado existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM estado WHERE id_estado = :id_estado;");
            $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE estado SET estado = :estado WHERE id_estado = :id_estado;");
            $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
            $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar estado: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_estado)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si la estado existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM estado WHERE id_estado = :id_estado;");
            $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM estado WHERE id_estado = :id_estado;");
            $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar estado: " . $e->getMessage());
            return false;
        }
    }
}
?>
