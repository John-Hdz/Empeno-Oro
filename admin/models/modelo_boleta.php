<?php
require_once(__DIR__ . '/../model.php');

class Boleta extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM vw_boleta_cliente");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorCliente_unico($cliente_unico)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM vw_boleta_cliente WHERE cliente_unico = :cliente_unico");
        $datos->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorCliente($cliente)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * from vw_boleta_cliente where 
                                    concat(nombre,' ',primer_apellido,' ',segundo_apellido)
                                    LIKE :cliente");
        $cliente = "%{$cliente}%"; 
        $datos->bindParam(':cliente', $cliente, PDO::PARAM_STR);
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }
    function leerPorBoleta($id_boleta)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * from vw_boleta_cliente where 
                                    id_boleta = :id_boleta");
        $datos->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorID($id_boleta)
    {
        try {
            $this->conectar();
            $datos = $this->conn->prepare("SELECT * from vw_boleta_cliente where 
                                    id_boleta = :id_boleta");
            $datos->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
            $datos->execute();
            return $datos->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en leerPorID: " . $e->getMessage());
            return [];
        }
    }

    function leerEmpenio($id_empenio)
    {
        $this->conectar();
        try{
            $datos = $this->conn->prepare("SELECT * from empenio where 
                                    id_empenio = :id_empenio");
            $datos->bindParam(':id_empenio', $id_empenio, PDO::PARAM_INT);
            $datos->execute();
            return $datos->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            error_log("Error en leerEmpenio: " . $e->getMessage());
            return [];
        }
    }

    function leerEmpenioBoleta($id_boleta)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT e.id_empenio, id_articulo, id_clasificacion, id_kilataje, id_cotizacion, id_empleado,
        caracteristicas_prenda, peso_prenda, avaluo_prenda, id_boleta, cliente_unico,
        id_sucursal, plazo_credito, monto_credito, pago_total, total_intereses,
        limite_pago_proximo, deuda_restante, estado_empeño
from empenio e join boleta b on e.id_empenio = b.id_empenio
where id_boleta = :id_boleta;");
        $datos->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crearEmpenio($data)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            $sql = "SELECT cotizacion FROM cotizacion WHERE id_cotizacion = :id_cotizacion";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_cotizacion', $data['id_cotizacion'], PDO::PARAM_INT);
            $stmt->execute();
            $cotizacion = $stmt->fetchColumn();
            if (!isset($cotizacion)) {
                $this->conn->rollBack();
                return false;
            }

            $total_intereses = $data['monto_credito'] * $cotizacion / 100;

            $sql = "INSERT INTO empenio (id_empleado, id_articulo, id_clasificacion,id_kilataje,
                                            id_cotizacion, caracteristicas_prenda, peso_prenda,avaluo_prenda)
                    VALUES (:id_empleado, :id_articulo, :id_clasificacion,
                            :id_kilataje, :id_cotizacion, :carac_prenda, :peso_prenda, :avaluo_prenda)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_empleado", $data['id_empleado'], PDO::PARAM_INT);
            $stmt->bindParam(":id_articulo", $data['id_articulo'], PDO::PARAM_INT);
            $stmt->bindParam(":id_clasificacion", $data['id_clasificacion'], PDO::PARAM_INT);
            $stmt->bindParam(':id_kilataje', $data['id_kilataje'], PDO::PARAM_INT);
            $stmt->bindParam(':id_cotizacion', $data['id_cotizacion'], PDO::PARAM_INT);
            $stmt->bindParam(':carac_prenda', $data['carac_prenda'], PDO::PARAM_STR);
            $stmt->bindParam(':peso_prenda', $data['peso_prenda'], PDO::PARAM_STR);
            $stmt->bindParam(':avaluo_prenda', $data['avaluo_prenda'], PDO::PARAM_STR);
            $stmt->execute();

            $id_empenio = $this->conn->lastInsertId();

            $sql = "INSERT INTO boleta (id_empenio, cliente_unico, id_sucursal, plazo_credito, monto_credito, total_intereses)
                    values (:id_empenio,:cliente_unico,:id_sucursal,:plazo_credito,:monto_credito,:total_intereses);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_empenio", $id_empenio, PDO::PARAM_INT);
            $stmt->bindParam(":cliente_unico", $data["cliente_unico"], PDO::PARAM_INT);
            $stmt->bindParam(":id_sucursal", $data["id_sucursal"], PDO::PARAM_INT);
            $stmt->bindParam(":plazo_credito", $data["plazo_credito"], PDO::PARAM_INT);
            $stmt->bindParam(":monto_credito", $data["monto_credito"], PDO::PARAM_STR);
            $stmt->bindParam(":total_intereses", $total_intereses, PDO::PARAM_STR);
            $stmt->execute();
            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al crear empeño: " . $e->getMessage());
            return false;
        }
    }

    function modificar($data)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();
            $sql = "UPDATE empenio set id_articulo = :id_articulo, id_clasificacion = :id_clasificacion, id_empleado = :id_empleado,
                    caracteristicas_prenda=:caracteristicas_prenda
                    where id_empenio = :id_empenio;";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_articulo", $data["id_articulo"], PDO::PARAM_INT);
            $stmt->bindParam(":id_clasificacion", $data["id_clasificacion"], PDO::PARAM_INT);
            $stmt->bindParam(":id_empleado", $data["id_empleado"], PDO::PARAM_INT);
            $stmt->bindParam(":caracteristicas_prenda", $data["caracteristicas_prenda"], PDO::PARAM_STR);
            $stmt->bindParam(":id_empenio", $data["id_empenio"], PDO::PARAM_INT);
            $stmt->execute();

            $sql = "UPDATE boleta set cliente_unico = :cliente_unico, id_sucursal=:id_sucursal, plazo_credito=:plazo_credito,
                    limite_pago_proximo=:limite_pago_proximo, estado_empeño=:estado_empenio
                    where id_boleta = :id_boleta;";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":cliente_unico", $data["cliente_unico"], PDO::PARAM_INT);
            $stmt->bindParam(":id_sucursal", $data["id_sucursal"], PDO::PARAM_INT);
            $stmt->bindParam(":plazo_credito", $data["plazo_credito"], PDO::PARAM_INT);
            $stmt->bindParam(":limite_pago_proximo", $data["limite_pago_proximo"], PDO::PARAM_STR);
            $stmt->bindParam(":estado_empenio", $data["estado_empeño"], PDO::PARAM_STR);
            $stmt->bindParam(":id_boleta", $data["id_boleta"], PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al modificar boleta: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_boleta)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            $sql = "SELECT id_empenio from vw_boleta_cliente where id_boleta = :id_boleta";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_boleta", $id_boleta, PDO::PARAM_INT);
            $stmt->execute();
            $id_empenio = $stmt->fetchColumn();
            if (!$id_empenio) {
                $this->conn->rollBack();
                return false; // No se encontró el empeño asociado a la boleta
            }

            $sql = "DELETE FROM boleta WHERE id_boleta = :id_boleta;";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_boleta', $id_boleta, PDO::PARAM_INT);
            $stmt->execute();

            $sql = "DELETE FROM empenio WHERE id_empenio = :id_empenio;";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_empenio', $id_empenio, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar boleta: " . $e->getMessage());
            return false;
        }
    }

    function leerReporteTransacciones()
    {
        $this->conectar();
        try {
            $sql = "SELECT * from vw_reporteTransaccion;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en leerReporteTransacciones: " . $e->getMessage());
            return [];
        }
    }
    function leerReporteClienteMoroso()
    {
        $this->conectar();
        try {
            $sql = "SELECT * FROM vw_clienteMoroso;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en leerReporteClienteMoroso: " . $e->getMessage());
            return [];
        }
    }
    function leerClientesPorSucursal()
    {
        $this->conectar();
        try {
            $sql = "SELECT * from vw_clienteSucursal;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en leerClientesPorSucursal: " . $e->getMessage());
            return [];
        }
    }
    function leerClientesEstado()
    {
        $this->conectar();
        try {
            $sql = "SELECT * from vw_estadoEmpenios";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en leerClientesEstado: " . $e->getMessage());
            return [];
        }
    }

    function leerReporteBoletaPorEmpleado()
    {
        $this->conectar();
        try {
            $sql = "SELECT * FROM vw_empenioPorEmpleado;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en leerReporteBoletaPorEmpleado: " . $e->getMessage());
            return [];
        }
    }

    function leerPorNumeroCredito($numero_credito)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * from vw_boleta_cliente where numero_credito = :numero_credito;");
        $datos->bindParam(':numero_credito', $numero_credito, PDO::PARAM_STR);
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorClienteUnico($cliente_unico)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * from vw_boleta_cliente where cliente_unico = :cliente_unico;");
        $datos->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
