<?php
class accesos_MO
{
    private $conexion;

    function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    function verificarInicioSesion($usuario, $clave)
    {
        $arreglo_accesos = array();
        $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND activo='SI'";
        $this->conexion->consulta($sql);

        if ($arreglo = $this->conexion->extraerRegistro()) {
            if (password_verify($clave, $arreglo[0]->clave))    // Verificar contraseña encryptada
                $arreglo_accesos = $arreglo;
        }

        return $arreglo_accesos;
    }

    function agregar($usuario, $clave)
    {
        $clave_cifrada = password_hash($clave, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios ( usuario,clave) VALUES ('$usuario','$clave_cifrada')";

        $filas_afectadas = $this->conexion->consulta($sql);

        return $filas_afectadas;
    }

    function actualizar($id_accesos, $usuario, $clave)
    {
        $clave_cifrada = password_hash($clave, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios SET usuario='$usuario', clave='$clave_cifrada' WHERE id_usuario='$id_accesos'";

        $filas_afectadas = $this->conexion->consulta($sql);

        return $filas_afectadas;
    }

    function seleccionar($atributo = '', $valor = '')
    {
        $condicion = "";

        if ($atributo && $valor) {
            $condicion = " WHERE $atributo='$valor'";
        }

        $sql = "SELECT * FROM usuarios $condicion";

        $this->conexion->consulta($sql);

        $arreglo_accesos = $this->conexion->extraerRegistro();

        return $arreglo_accesos;
    }


    function seleccionarUsuario($atributo = '', $valor = '')
    {
        $condicion = "";

        if ($atributo && $valor) {
            $condicion = " WHERE $atributo='$valor'";
        }

        $sql = "SELECT * FROM usuarios $condicion";

        $this->conexion->consulta($sql);

        $arreglo_accesos = $this->conexion->extraerRegistro();

        return $arreglo_accesos;
    }

    function unico($usuario, $id_accesos = '')
    {
        if (empty($id_accesos)) {

            //AGREGAR
            $sql = "SELECT * FROM usuarios
                 WHERE usuario='$usuario'";
        } else {
            //ACTUALIAZAR
            $sql = "SELECT * FROM usuarios
                 WHERE usuario='$usuario'
                 AND id_usuario!='$id_accesos'";
        }

        $this->conexion->consulta($sql);

        $arreglo_accesos = $this->conexion->extraerRegistro();

        return $arreglo_accesos;
    }




    function activo($id_accesos, $activo)
    {
        $sql = "UPDATE usuarios SET activo='$activo' WHERE id_usuario='$id_accesos'";

        $filas_afectadas = $this->conexion->consulta($sql);

        return $filas_afectadas;
    }





    function capturarIP($nip, $usuario)
    {

        $sql = "INSERT INTO entradas (usuario, ip) VALUES ('$usuario','$nip')";

        $filas_afectadas = $this->conexion->consulta($sql);

        return $filas_afectadas;
    }
}
