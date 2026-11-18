<?php
require_once(__DIR__ . '/../model.php');

class GuardaValores extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("select * from vw_guarda_valores_cliente;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorId($id_empenio, $cliente_unico)
    {
        $this->conectar();
        $datos = $this->conn->prepare("select * from vw_guarda_valores_cliente
                                        WHERE id_empenio = :id_empenio AND cliente_unico = :cliente_unico;");
        $datos->bindParam(':id_empenio', $id_empenio, PDO::PARAM_INT);
        $datos->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function leerPorCliente($cliente)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM vw_guarda_valores_cliente 
                                        WHERE cliente LIKE :cliente");
        $cliente = "%{$cliente}%";
        $datos->bindParam(':cliente', $cliente, PDO::PARAM_STR);
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }


    function crear($data)
    {
        if (!isset($data['id_empenio']) || !isset($data['cliente_unico'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                INSERT INTO guarda_valores (id_empenio, cliente_unico, columna, fila, deposito) 
                VALUES (:id_empenio, :cliente_unico, :columna, :fila, :deposito);
            ");
            $stmt->bindParam(':id_empenio', $data['id_empenio'], PDO::PARAM_INT);
            $stmt->bindParam(':cliente_unico', $data['cliente_unico'], PDO::PARAM_INT);
            $stmt->bindParam(':columna', $data['columna'], PDO::PARAM_STR);
            $stmt->bindParam(':fila', $data['fila'], PDO::PARAM_STR);
            $stmt->bindParam(':deposito', $data['deposito'], PDO::PARAM_STR);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear guarda_valores: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_empenio, $cliente_unico, $data)
    {
        if (!isset($data['columna']) || !isset($data['fila']) || !isset($data['deposito'])) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("UPDATE guarda_valores 
                SET columna = :columna, fila = :fila, deposito = :deposito 
                WHERE id_empenio = :id_empenio AND cliente_unico = :cliente_unico;");
            $stmt->bindParam(':columna', $data['columna'], PDO::PARAM_STR);
            $stmt->bindParam(':fila', $data['fila'], PDO::PARAM_STR);
            $stmt->bindParam(':deposito', $data['deposito'], PDO::PARAM_STR);
            $stmt->bindParam(':id_empenio', $id_empenio, PDO::PARAM_INT);
            $stmt->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar guarda_valores: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_empenio, $cliente_unico)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("DELETE FROM guarda_valores WHERE id_empenio = :id_empenio AND cliente_unico = :cliente_unico;");
            $stmt->bindParam(':id_empenio', $id_empenio, PDO::PARAM_INT);
            $stmt->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar guarda_valores: " . $e->getMessage());
            return false;
        }
    }
}
?>
