<?php
require_once(__DIR__ . '/../model.php');

class Transaccion extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT t.id_transaccion,
                                            (select sucursal from sucursal where id_sucursal = s.id_sucursal) as sucursal,
                                            (select nombre from empleado where id_empleado = p.id_empleado) as empleado,
                                            (select nombre from cliente where cliente_unico = c.cliente_unico) as cliente,
                                            fecha
                                        from transaccion t join sucursal s on t.id_sucursal = s.id_sucursal
                                        join empleado p on t.id_empleado = p.id_empleado
                                        join cliente c on t.cliente_unico = c.cliente_unico;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdtransaccion($id_transaccion)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT t.*, td.* 
                                        from transaccion t join transaccion_detalle td on t.id_transaccion = td.id_transaccion
                                        where t.id_transaccion = :id_transaccion;");
        $datos->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function leerTransaccionDetalle($id_transaccion)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT td.id_transaccion, td.id_boleta, c.nombre as cliente, u.correo,t.fecha, td.monto, td.referencia_motivo
                                        from transaccion_detalle td join transaccion t on td.id_transaccion = t.id_transaccion
                                        join cliente c on t.cliente_unico = c.cliente_unico
                                        join usuario u on c.id_usuario = u.id_usuario
                                        where td.id_transaccion = :id_transaccion;");
        $datos->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['cliente_unico']) || !isset($data['id_empleado']) || !isset($data['id_boleta']) 
            || !isset($data['monto']) || !isset($data['referencia_motivo']))
        {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("INSERT INTO transaccion (id_empleado, id_sucursal, cliente_unico)
                                        VALUES (:id_empleado, (select id_sucursal from boleta where id_boleta = :id_boleta), :cliente_unico);");
            $stmt->bindParam(':id_empleado', $data['id_empleado'], PDO::PARAM_INT);
            $stmt->bindParam(':cliente_unico', $data['cliente_unico'], PDO::PARAM_INT);
            $stmt->bindParam(':id_boleta', $data['id_boleta'], PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->conn->prepare("INSERT INTO transaccion_detalle (id_transaccion, id_boleta, referencia_motivo, monto)
                                            VALUES (LAST_INSERT_ID(), :id_boleta, :referencia_motivo, :monto);");
            $stmt->bindParam(':id_boleta', $data['id_boleta'], PDO::PARAM_INT);
            $stmt->bindParam(':referencia_motivo', $data['referencia_motivo'], PDO::PARAM_STR);
            $stmt->bindParam(':monto', $data['monto'], PDO::PARAM_STR);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear transaccion: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_transaccion, $data)
    {
        if (!isset($id_transaccion) && $data) {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el transaccion existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM transaccion WHERE id_transaccion = :id_transaccion;");
            $stmt->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE transaccion set id_empleado = :id_empleado, cliente_unico = :cliente_unico
                                            where id_transaccion = :id_transaccion;");
            $stmt->bindParam(':id_empleado', $data['id_empleado'], PDO::PARAM_STR);
            $stmt->bindParam(':cliente_unico', $data['cliente_unico'], PDO::PARAM_STR);
            $stmt->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->conn->prepare("UPDATE transaccion_detalle set id_boleta = :id_boleta, referencia_motivo=:referencia_motivo, monto = :monto
                                            where id_transaccion = :id_transaccion;");
            $stmt->bindParam(':id_boleta', $data['id_boleta'], PDO::PARAM_STR);
            $stmt->bindParam(':referencia_motivo', $data['referencia_motivo'], PDO::PARAM_STR);
            $stmt->bindParam(':monto', $data['monto'], PDO::PARAM_STR);
            $stmt->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_INT);
            $stmt->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar transaccion: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_transaccion)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el transaccion existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM transaccion WHERE id_transaccion = :id_transaccion;");
            $stmt->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("DELETE FROM transaccion_detalle WHERE id_transaccion = :id_transaccion;");
            $stmt->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->conn->prepare("DELETE FROM transaccion WHERE id_transaccion = :id_transaccion;");
            $stmt->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_INT);
            $stmt->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar transaccion: " . $e->getMessage());
            return false;
        }
    }
}
?>
