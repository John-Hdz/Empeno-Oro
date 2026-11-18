<?php
require_once(__DIR__ . '/../model.php');

class Kilataje extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM kilataje;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdkilataje($id_kilataje)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM kilataje WHERE id_kilataje = :id_kilataje;");
        $datos->bindParam(':id_kilataje', $id_kilataje, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }
    function leerPorKilataje($kilataje)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM kilataje WHERE kilataje = :kilataje;");
        $datos->bindParam(':kilataje', $kilataje, PDO::PARAM_STR);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['kilataje'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el nombre de el kilataje ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM kilataje WHERE kilataje = :kilataje;");
            $stmt->bindParam(':kilataje', $data['kilataje'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO kilataje (kilataje, precio_gramo) VALUES (:kilataje, :precio_gramo);");
            $stmt->bindParam(':kilataje', $data['kilataje'], PDO::PARAM_STR);
            $stmt->bindParam(':precio_gramo', $data['precio_gramo'], PDO::PARAM_STR);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear kilataje: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_kilataje, $data)
    {
        if (!isset($data['kilataje']) || !isset($data['precio_gramo'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el kilataje existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM kilataje WHERE id_kilataje = :id_kilataje;");
            $stmt->bindParam(':id_kilataje', $id_kilataje, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE kilataje SET kilataje = :kilataje, precio_gramo = :precio_gramo WHERE id_kilataje = :id_kilataje;");
            $stmt->bindParam(':kilataje', $data['kilataje'], PDO::PARAM_STR);
            $stmt->bindParam(':precio_gramo', $data['precio_gramo'], PDO::PARAM_STR);
            $stmt->bindParam(':id_kilataje', $id_kilataje, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar kilataje: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_kilataje)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el kilataje existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM kilataje WHERE id_kilataje = :id_kilataje;");
            $stmt->bindParam(':id_kilataje', $id_kilataje, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM kilataje WHERE id_kilataje = :id_kilataje;");
            $stmt->bindParam(':id_kilataje', $id_kilataje, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar kilataje: " . $e->getMessage());
            return false;
        }
    }
}
?>
