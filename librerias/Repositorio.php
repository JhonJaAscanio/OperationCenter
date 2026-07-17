<?php
// Clase base para los modelos *_MO.php: centraliza el CRUD generico que hoy
// esta duplicado (casi identico) en cada modelo, y lo ejecuta con parametros
// bindeados (consultaPreparada) en vez de interpolar valores en el SQL.
//
// Los modelos concretos extienden esta clase y conservan sus propios metodos
// publicos (agregar, actualizar, unico, etc.) con la misma firma que ya usan
// los controladores/vistas; internamente delegan en insertar()/modificar()/
// existeValorUnico() en lugar de repetir la construccion del SQL.
abstract class Repositorio
{
    protected $conexion;
    protected $tabla;
    protected $llavePrimaria;

    function __construct($conexion, $tabla, $llavePrimaria)
    {
        $this->conexion = $conexion;
        $this->tabla = $tabla;
        $this->llavePrimaria = $llavePrimaria;
    }

    function seleccionar($atributo = '', $valor = '')
    {
        if ($atributo !== '' && $valor !== '')
        {
            $sql = "SELECT * FROM {$this->tabla} WHERE $atributo = :valor";
            $this->conexion->consultaPreparada($sql, [':valor' => $valor]);
        }
        else
        {
            $sql = "SELECT * FROM {$this->tabla}";
            $this->conexion->consultaPreparada($sql);
        }

        return $this->conexion->extraerRegistro();
    }

    function eliminar($id)
    {
        $sql = "DELETE FROM {$this->tabla} WHERE {$this->llavePrimaria} = :id";

        $filas = $this->conexion->consultaPreparada($sql, [':id' => $id]);

        if ($filas)
        {
            $this->registrarBitacora('ELIMINAR', $id);
        }

        return $filas;
    }

    // $campos: arreglo asociativo columna => valor
    protected function insertar(array $campos)
    {
        $columnas = implode(', ', array_keys($campos));
        $marcadores = implode(', ', array_map(fn($c) => ":$c", array_keys($campos)));

        $sql = "INSERT INTO {$this->tabla} ($columnas) VALUES ($marcadores)";

        $parametros = [];
        foreach ($campos as $columna => $valor)
        {
            $parametros[":$columna"] = $valor;
        }

        $filas = $this->conexion->consultaPreparada($sql, $parametros);

        if ($filas)
        {
            $this->registrarBitacora('AGREGAR', $this->conexion->obtenerUltimoId());
        }

        return $filas;
    }

    // $campos: arreglo asociativo columna => valor
    protected function modificar($id, array $campos)
    {
        $asignaciones = implode(', ', array_map(fn($c) => "$c = :$c", array_keys($campos)));

        $sql = "UPDATE {$this->tabla} SET $asignaciones WHERE {$this->llavePrimaria} = :id_registro";

        $parametros = [];
        foreach ($campos as $columna => $valor)
        {
            $parametros[":$columna"] = $valor;
        }
        $parametros[':id_registro'] = $id;

        $filas = $this->conexion->consultaPreparada($sql, $parametros);

        if ($filas)
        {
            $this->registrarBitacora('ACTUALIZAR', $id);
        }

        return $filas;
    }

    // Registra en la tabla "bitacora" quien hizo que accion sobre que registro.
    // No pasa por insertar() para no auto-registrarse recursivamente.
    private function registrarBitacora($accion, $id_registro)
    {
        $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

        $sql = "INSERT INTO bitacora (id_usuario, tabla, accion, id_registro) VALUES (:id_usuario, :tabla, :accion, :id_registro)";

        $this->conexion->consultaPreparada($sql, [
            ':id_usuario' => $id_usuario,
            ':tabla' => $this->tabla,
            ':accion' => $accion,
            ':id_registro' => $id_registro,
        ]);
    }

    // Busca registros con $campo = $valor, excluyendo opcionalmente $idExcluir
    // (usado para validar unicidad al agregar/actualizar).
    protected function existeValorUnico($campo, $valor, $idExcluir = '')
    {
        if ($idExcluir === '' || $idExcluir === null)
        {
            $sql = "SELECT * FROM {$this->tabla} WHERE $campo = :valor";
            $parametros = [':valor' => $valor];
        }
        else
        {
            $sql = "SELECT * FROM {$this->tabla} WHERE $campo = :valor AND {$this->llavePrimaria} != :id_excluir";
            $parametros = [':valor' => $valor, ':id_excluir' => $idExcluir];
        }

        $this->conexion->consultaPreparada($sql, $parametros);

        return $this->conexion->extraerRegistro();
    }
}
