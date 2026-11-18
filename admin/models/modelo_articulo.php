<?php
require_once(__DIR__ . '/../model.php');

class Articulo extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM articulo;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdArticulo($id_articulo)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM articulo WHERE id_articulo = :id_articulo;");
        $datos->bindParam(':id_articulo', $id_articulo, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['articulo'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el nombre del articulo ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM articulo WHERE articulo = :articulo;");
            $stmt->bindParam(':articulo', $data['articulo'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO articulo (articulo) VALUES (:articulo);");
            $stmt->bindParam(':articulo', $data['articulo'], PDO::PARAM_STR);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear articulo: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_articulo, $data)
    {
        if (!isset($data['articulo'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si la articulo existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM articulo WHERE id_articulo = :id_articulo;");
            $stmt->bindParam(':id_articulo', $id_articulo, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE articulo SET articulo = :articulo WHERE id_articulo = :id_articulo;");
            $stmt->bindParam(':articulo', $data['articulo'], PDO::PARAM_STR);
            $stmt->bindParam(':id_articulo', $id_articulo, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar articulo: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_articulo)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si la articulo existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM articulo WHERE id_articulo = :id_articulo;");
            $stmt->bindParam(':id_articulo', $id_articulo, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM articulo WHERE id_articulo = :id_articulo;");
            $stmt->bindParam(':id_articulo', $id_articulo, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar articulo: " . $e->getMessage());
            return false;
        }
    }
}
?>
