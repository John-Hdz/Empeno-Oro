<?php
require_once(__DIR__ . '/../model.php');

class CotizacionOnline extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM cotizacion;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerCotizacion($opcion)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT c.cotizacion FROM cotizacion c WHERE c.tipo_cotizacion = :tipo_cotizacion;");
        $datos->bindParam(':tipo_cotizacion', $opcion, PDO::PARAM_STR);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }
}
?>
