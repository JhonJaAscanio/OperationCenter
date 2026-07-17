<?php
class servidor
{
    private $conexion;
    private $resultado;

    function __construct($privilegio)
    {
        $ip_maquina=IP_MAQUINA;
        $base_de_datos=BASE_DE_DATOS;

        if($privilegio=="A")
        {
            $usuario=USUARIO_ADMINISTRADOR;
            $clave=CLAVE_ADMINISTRADOR;
        }
        else if($privilegio=="L")
        {
            $usuario=USUARIO_LIMITADO;
            $clave=CLAVE_LIMITADO;
        }
        else 
        {
            $respuesta = [
                "estado" => "ERROR",
                'mensaje' => "ERROR: Usuario no especificado"
            ];
            echo json_encode($respuesta);
            exit;
        }

        try
        {
            $this->conexion = new PDO("mysql:host=$ip_maquina;dbname=$base_de_datos", $usuario, $clave);
        } 
        catch (PDOException $pe) 
        {
            //exit("ERROR: ".$pe->getMessage());
            $respuesta = [
                "estado" => "ERROR",
                'mensaje' => "ERROR: No hay conexi&oacute;n a la base de datos"
            ];
            echo json_encode($respuesta);
            exit;
        }
    }
    // FIN CONSTRUCTOR

    function consulta($sql)
    {
        $this->resultado=$this->conexion->query($sql) or $this->errorQuery();

        return $this->resultado->rowCount(); //Solo para los INSERT, UPDATE Y DELETE

    }

    // Igual que consulta(), pero con parametros bindeados via prepared statement,
    // para evitar concatenar valores directamente en el SQL.
    function consultaPreparada($sql, $parametros = array())
    {
        $sentencia = $this->conexion->prepare($sql) or $this->errorQuery();
        $sentencia->execute($parametros) or $this->errorQuery();

        $this->resultado = $sentencia;

        return $this->resultado->rowCount(); //Solo para los INSERT, UPDATE Y DELETE
    }

    function obtenerUltimoId()
    {
        return $this->conexion->lastInsertId();
    }

    function iniciarTransaccion()
    {
        return $this->conexion->beginTransaction();
    }

    function confirmarTransaccion()
    {
        return $this->conexion->commit();
    }

    function revertirTransaccion()
    {
        if ($this->conexion->inTransaction())
        {
            $this->conexion->rollBack();
        }
    }

    function errorQuery()
    {
        $respuesta = [
            "estado" => "ERROR",
            'mensaje' => "ERROR: Consulta mal estructurada",
        ];
        echo json_encode($respuesta);
        exit;
    }

    function extraerRegistro()
    {
        $arreglo=array();
        
        //PDO::FETCH_BOTH
        //PDO::FETCH_OBJ
        //PDO::FETCH_ASSOC

        if($this->resultado)
        {
            $arreglo=$this->resultado->fetchAll(PDO::FETCH_OBJ);
        }

        return $arreglo;
    }

}
?>