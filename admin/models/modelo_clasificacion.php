<?php
require_once(__DIR__ . '/../model.php');

class Clasificacion extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM clasificacion;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdclasificacion($id_clasificacion)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM clasificacion WHERE id_clasificacion = :id_clasificacion;");
        $datos->bindParam(':id_clasificacion', $id_clasificacion, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['clasificacion'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el nombre de la clasificación ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM clasificacion WHERE clasificacion = :clasificacion;");
            $stmt->bindParam(':clasificacion', $data['clasificacion'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO clasificacion (clasificacion) VALUES (:clasificacion);");
            $stmt->bindParam(':clasificacion', $data['clasificacion'], PDO::PARAM_STR);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear clasificación: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_clasificacion, $data)
    {
        if (!isset($data['clasificacion'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si la clasificación existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM clasificacion WHERE id_clasificacion = :id_clasificacion;");
            $stmt->bindParam(':id_clasificacion', $id_clasificacion, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE clasificacion SET clasificacion = :clasificacion WHERE id_clasificacion = :id_clasificacion;");
            $stmt->bindParam(':clasificacion', $data['clasificacion'], PDO::PARAM_STR);
            $stmt->bindParam(':id_clasificacion', $id_clasificacion, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar clasificación: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_clasificacion)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si la clasificación existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM clasificacion WHERE id_clasificacion = :id_clasificacion;");
            $stmt->bindParam(':id_clasificacion', $id_clasificacion, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM clasificacion WHERE id_clasificacion = :id_clasificacion;");
            $stmt->bindParam(':id_clasificacion', $id_clasificacion, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar clasificación: " . $e->getMessage());
            return false;
        }
    }
}
?>
