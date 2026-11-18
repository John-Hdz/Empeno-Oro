<?php
require_once(__DIR__ . '/../model.php');

class cliente extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT c.*, u.correo
                                        from cliente c join usuario u on c.id_usuario = u.id_usuario;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdcliente($cliente_unico)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT c.*, u.correo
                                        from cliente c join usuario u on c.id_usuario = u.id_usuario 
                                        WHERE cliente_unico = :cliente_unico;");
        $datos->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['curp']))
        {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el curp de el cliente ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM cliente WHERE curp = :curp;");
            $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0)
            {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO cliente (id_localidad, direccion, codigo_postal, 
                                                                        primer_apellido, segundo_apellido,
                                                                    nombre, nacimiento, curp, telefono, id_sucursal)
                                                values (:id_localidad, :direccion, :codigo_postal, 
                                                        :primer_apellido,:segundo_apellido, :nombre,
                                                        :nacimiento, :curp, :telefono, :id_sucursal);");

            $stmt->bindParam(':id_localidad', $data['id_localidad'], PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $data['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(':codigo_postal', $data['codigo_postal'], PDO::PARAM_STR);
            $stmt->bindParam(':primer_apellido', $data['primer_apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':segundo_apellido', $data['segundo_apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':nacimiento', $data['nacimiento'], PDO::PARAM_STR);
            $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $data['telefono'], PDO::PARAM_STR);
            $stmt->bindParam(':id_sucursal', $data['id_sucursal'], PDO::PARAM_INT);

            $stmt->execute();

            $stmt = $this->conn->prepare("SELECT cliente_unico FROM cliente WHERE curp = :curp");
            $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
            $stmt->execute();
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cliente) {
                throw new Exception("No se pudo recuperar cliente_unico");
            }

            $cliente_unico = $cliente['cliente_unico'];

            $rawPassword = (!empty($data['contrasenia']) && is_string($data['contrasenia']))
                ? $data['contrasenia']
                : bin2hex(random_bytes(16));
            $contrasenia = $this->hashPassword($rawPassword);

            $stmt = $this->conn->prepare("INSERT INTO usuario (correo, contrasenia) VALUES (:correo,:contrasenia);");
            $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
            $stmt->bindParam(':contrasenia', $contrasenia, PDO::PARAM_STR);
            $stmt->execute();

            $id_usuario = $this->conn->lastInsertId();

            $stmt = $this->conn->prepare("UPDATE cliente SET id_usuario = :id_usuario WHERE cliente_unico = :cliente_unico;");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear cliente: " . $e->getMessage());
            return false;
        }
    }

    function modificar($cliente_unico, $data)
    {
        if (!isset($cliente_unico) || !isset($data['curp'])) 
        {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el cliente existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM cliente WHERE cliente_unico = :cliente_unico;");
            $stmt->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE cliente SET primer_apellido=:primer_apellido,segundo_apellido=:segundo_apellido,
                                                                nombre=:nombre,nacimiento=:nacimiento,curp=:curp,telefono=:telefono,
                                                                direccion=:direccion, codigo_postal=:codigo_postal, id_localidad=:id_localidad,
                                                                id_sucursal=:id_sucursal
                                        WHERE cliente_unico = :cliente_unico;");

            $stmt->bindParam(':primer_apellido', $data['primer_apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':segundo_apellido', $data['segundo_apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':nacimiento', $data['nacimiento'], PDO::PARAM_STR);
            $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $data['telefono'], PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $data['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(':codigo_postal', $data['codigo_postal'], PDO::PARAM_STR);
            $stmt->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
            $stmt->bindParam(':id_localidad', $data['id_localidad'], PDO::PARAM_INT);
            $stmt->bindParam(':id_sucursal', $data['id_sucursal'], PDO::PARAM_INT);
            $stmt->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar cliente: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($cliente_unico)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el cliente existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM cliente WHERE cliente_unico = :cliente_unico;");
            $stmt->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("SELECT id_usuario FROM cliente WHERE cliente_unico = :cliente_unico;");
            $stmt->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
            $stmt->execute();
            $id_usuario = $stmt->fetchColumn();
            
            $stmt = $this->conn->prepare("DELETE FROM usuario_rol WHERE id_usuario = :id_usuario;");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->conn->prepare("DELETE FROM cliente WHERE cliente_unico = :cliente_unico;");
            $stmt->bindParam(':cliente_unico', $cliente_unico, PDO::PARAM_INT);
            $stmt->execute();
            
            $stmt = $this->conn->prepare("DELETE FROM usuario WHERE id_usuario = :id_usuario;");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar cliente: " . $e->getMessage());
            return false;
        }
    }
}
?>
