<?php
require_once(__DIR__ . '/../model.php');

class Ciudad extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT c.*, e.*
                        from ciudad c join estado e on c.id_estado = e.id_estado;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdciudad($id_ciudad)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM ciudad WHERE id_ciudad = :id_ciudad;");
        $datos->bindParam(':id_ciudad', $id_ciudad, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['ciudad'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el nombre de el ciudad ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM ciudad WHERE ciudad = :ciudad;");
            $stmt->bindParam(':ciudad', $data['ciudad'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO ciudad (ciudad, id_estado) VALUES (:ciudad, :id_estado);");
            $stmt->bindParam(':ciudad', $data['ciudad'], PDO::PARAM_STR);
            $stmt->bindParam(':id_estado', $data['id_estado'], PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear ciudad: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_ciudad, $data)
    {
        if (!isset($data['ciudad']) || !isset($data['id_estado'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el ciudad existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM ciudad WHERE id_ciudad = :id_ciudad;");
            $stmt->bindParam(':id_ciudad', $id_ciudad, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE ciudad SET ciudad = :ciudad, id_estado = :id_estado WHERE id_ciudad = :id_ciudad;");
            $stmt->bindParam(':ciudad', $data['ciudad'], PDO::PARAM_STR);
            $stmt->bindParam(':id_estado', $data['id_estado'], PDO::PARAM_INT);
            $stmt->bindParam(':id_ciudad', $id_ciudad, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar ciudad: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_ciudad)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el ciudad existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM ciudad WHERE id_ciudad = :id_ciudad;");
            $stmt->bindParam(':id_ciudad', $id_ciudad, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM ciudad WHERE id_ciudad = :id_ciudad;");
            $stmt->bindParam(':id_ciudad', $id_ciudad, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar ciudad: " . $e->getMessage());
            return false;
        }
    }
}
?>
