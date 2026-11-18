<?php
require_once(__DIR__ . '/../model.php');

class Cotizacion extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM cotizacion;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdCotizacion($id_cotizacion)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM cotizacion WHERE id_cotizacion = :id_cotizacion;");
        $datos->bindParam(':id_cotizacion', $id_cotizacion, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['cotizacion']) || !isset($data['tipo_cotizacion'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el nombre de la cotización ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM cotizacion WHERE cotizacion = :cotizacion;");
            $stmt->bindParam(':cotizacion', $data['cotizacion'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO cotizacion (cotizacion, tipo_cotizacion) VALUES (:cotizacion, :tipo_cotizacion);");
            $stmt->bindParam(':cotizacion', $data['cotizacion'], PDO::PARAM_STR);
            $stmt->bindParam(':tipo_cotizacion', $data['tipo_cotizacion'], PDO::PARAM_STR);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear cotización: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_cotizacion, $data)
    {
        if (!isset($data['cotizacion']) || !isset($data['tipo_cotizacion'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si la cotización existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM cotizacion WHERE id_cotizacion = :id_cotizacion;");
            $stmt->bindParam(':id_cotizacion', $id_cotizacion, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE cotizacion SET cotizacion = :cotizacion, tipo_cotizacion = :tipo_cotizacion WHERE id_cotizacion = :id_cotizacion;");
            $stmt->bindParam(':cotizacion', $data['cotizacion'], PDO::PARAM_STR);
            $stmt->bindParam(':tipo_cotizacion', $data['tipo_cotizacion'], PDO::PARAM_STR);
            $stmt->bindParam(':id_cotizacion', $id_cotizacion, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar cotización: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_cotizacion)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si la cotización existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM cotizacion WHERE id_cotizacion = :id_cotizacion;");
            $stmt->bindParam(':id_cotizacion', $id_cotizacion, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM cotizacion WHERE id_cotizacion = :id_cotizacion;");
            $stmt->bindParam(':id_cotizacion', $id_cotizacion, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar cotización: " . $e->getMessage());
            return false;
        }
    }
}
?>
