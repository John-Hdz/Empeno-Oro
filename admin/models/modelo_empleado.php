<?php
require_once(__DIR__ . '/../model.php');

class Empleado extends Model
{
    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT e.*, u.correo
                                        from empleado e join usuario u on e.id_usuario = u.id_usuario;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerPorIdempleado($id_empleado)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT e.*, u.correo
                                        from empleado e join usuario u on e.id_usuario = u.id_usuario 
                                        WHERE id_empleado = :id_empleado;");
        $datos->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }

    function crear($data)
    {
        if (!isset($data['rfc']))
        {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el rfc de el empleado ya existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM empleado WHERE rfc = :rfc;");
            $stmt->bindParam(':rfc', $data['rfc'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0)
            {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO empleado (primer_apellido, segundo_apellido, nombre,
                                                        nacimiento, rfc, curp, id_sucursal, id_localidad, direccion, codigo_postal, telefono)
                                            VALUES (:primer_apellido,:segundo_apellido,:nombre,:nacimiento,
                                                    :rfc,:curp,:id_sucursal, :id_localidad, :direccion, :codigo_postal,:telefono);");
            $stmt->bindParam(':primer_apellido', $data['primer_apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':segundo_apellido', $data['segundo_apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':nacimiento', $data['nacimiento'], PDO::PARAM_STR);
            $stmt->bindParam(':rfc', $data['rfc'], PDO::PARAM_STR);
            $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
            $stmt->bindParam(':id_sucursal', $data['id_sucursal'], PDO::PARAM_INT);
            $stmt->bindParam(':id_localidad', $data['id_localidad'], PDO::PARAM_INT);
            $stmt->bindParam(':direccion', $data['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(':codigo_postal', $data['codigo_postal'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $data['telefono'], PDO::PARAM_STR);
            $stmt->execute();

            $id_empleado = $this->conn->lastInsertId();
            $rawPassword = (!empty($data['contrasenia']) && is_string($data['contrasenia']))
                ? $data['contrasenia']
                : bin2hex(random_bytes(16));
            $contrasenia = $this->hashPassword($rawPassword);

            $stmt = $this->conn->prepare("INSERT INTO usuario (correo, contrasenia) VALUES (:correo,:contrasenia);");
            $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
            $stmt->bindParam(':contrasenia', $contrasenia, PDO::PARAM_STR);
            $stmt->execute();

            $id_usuario = $this->conn->lastInsertId();

            $stmt = $this->conn->prepare("UPDATE empleado SET id_usuario = :id_usuario WHERE id_empleado = :id_empleado;");
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al crear empleado: " . $e->getMessage());
            return false;
        }
    }

    function modificar($id_empleado, $data)
    {
        if (!isset($id_empleado) || !isset($data['rfc'])) 
        {
            return false;
        }

        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el empleado existe antes de modificar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM empleado WHERE id_empleado = :id_empleado;");
            $stmt->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("UPDATE empleado SET primer_apellido=:primer_apellido,segundo_apellido=:segundo_apellido,
                                                                nombre=:nombre,nacimiento=:nacimiento,rfc=:rfc,curp=:curp,id_sucursal=:id_sucursal,
                                                                id_localidad=:id_localidad,direccion=:direccion,codigo_postal=:codigo_postal,telefono=:telefono
                                        WHERE id_empleado = :id_empleado;");
            $stmt->bindParam(':primer_apellido', $data['primer_apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':segundo_apellido', $data['segundo_apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':nacimiento', $data['nacimiento'], PDO::PARAM_STR);
            $stmt->bindParam(':rfc', $data['rfc'], PDO::PARAM_STR);
            $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
            $stmt->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
            $stmt->bindParam(':id_sucursal', $data['id_sucursal'], PDO::PARAM_INT);
            $stmt->bindParam(':id_localidad', $data['id_localidad'], PDO::PARAM_INT);
            $stmt->bindParam(':direccion', $data['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(':codigo_postal', $data['codigo_postal'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $data['telefono'], PDO::PARAM_STR);
            $stmt->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al modificar empleado: " . $e->getMessage());
            return false;
        }
    }

    function eliminar($id_empleado)
    {
        $this->conectar();
        try {
            $this->conn->beginTransaction();

            // Verificar si el empleado existe antes de eliminar
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM empleado WHERE id_empleado = :id_empleado;");
            $stmt->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $this->conn->rollBack();
                return false;
            }

            $stmt = $this->conn->prepare("SELECT id_usuario FROM empleado WHERE id_empleado = :id_empleado;");
            $stmt->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
            $stmt->execute();
            $id_usuario = $stmt->fetchColumn();
            
            $stmt = $this->conn->prepare("DELETE FROM usuario_rol WHERE id_usuario = :id_usuario;");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->conn->prepare("DELETE FROM empleado WHERE id_empleado = :id_empleado;");
            $stmt->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
            $stmt->execute();
            
            $stmt = $this->conn->prepare("DELETE FROM usuario WHERE id_usuario = :id_usuario;");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error al eliminar empleado: " . $e->getMessage());
            return false;
        }
    }
}
?>
