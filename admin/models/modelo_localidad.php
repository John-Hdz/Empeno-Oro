<?php
require_once(__DIR__ . '/../model.php');

class Localidad extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT l.*, c.*
                                from localidad l join ciudad c on l.id_ciudad = c.id_ciudad;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdlocalidad($id_localidad)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM localidad WHERE id_localidad = :id_localidad;");
        $datos->bindParam(':id_localidad', $id_localidad, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['localidad'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el nombre de el localidad ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM localidad WHERE localidad = :localidad;");
            $stmt->bindParam(':localidad', $data['localidad'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO localidad (localidad, id_ciudad) VALUES (:localidad, :id_ciudad);");
            $stmt->bindParam(':localidad', $data['localidad'], PDO::PARAM_STR);
            $stmt->bindParam(':id_ciudad', $data['id_ciudad'], PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear localidad: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_localidad, $data)
    {
        if (!isset($data['localidad']) || !isset($data['id_ciudad'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el localidad existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM localidad WHERE id_localidad = :id_localidad;");
            $stmt->bindParam(':id_localidad', $id_localidad, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE localidad SET localidad = :localidad, id_ciudad = :id_ciudad WHERE id_localidad = :id_localidad;");
            $stmt->bindParam(':localidad', $data['localidad'], PDO::PARAM_STR);
            $stmt->bindParam(':id_ciudad', $data['id_ciudad'], PDO::PARAM_INT);
            $stmt->bindParam(':id_localidad', $id_localidad, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar localidad: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_localidad)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el localidad existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM localidad WHERE id_localidad = :id_localidad;");
            $stmt->bindParam(':id_localidad', $id_localidad, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM localidad WHERE id_localidad = :id_localidad;");
            $stmt->bindParam(':id_localidad', $id_localidad, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar localidad: " . $e->getMessage());
            return false;
        }
    }
}
?>
