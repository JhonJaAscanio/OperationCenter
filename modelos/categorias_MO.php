<?php
class categorias_MO
{
	private $conexion;

	function __construct($conexion)
	{
		$this->conexion = $conexion;
	}

	function agregar($descripcion)
	{
		$sql = "INSERT INTO categorias ( descripcion) VALUES ('$descripcion')";

		$filas_afectadas = $this->conexion->consulta($sql);

		return $filas_afectadas;
	}

	function actualizar($id_categoria, $descripcion)
	{

		$sql = "UPDATE categorias SET descripcion='$descripcion' WHERE id_categoria='$id_categoria'";

		$filas_afectadas = $this->conexion->consulta($sql);

		return $filas_afectadas;
	}


	function seleccionar($atributo = '', $valor = '')
	{
		$condicion = "";

		if ($atributo && $valor) {
			$condicion = " WHERE $atributo='$valor'";
		}

		$sql = "SELECT * FROM categorias $condicion";

		$this->conexion->consulta($sql);

		$arreglo_accesos = $this->conexion->extraerRegistro();

		return $arreglo_accesos;
	}

	function eliminar($id_categoria)
	{
		$sql = "DELETE FROM categorias WHERE id_categoria='$id_categoria'";

		$filas_afectadas = $this->conexion->consulta($sql);

		return $filas_afectadas;
	}

	function unico($descripcion, $id_categoria = '')
	{
		if (empty($id_Proveedores)) {
			//AGREGAR
			$sql = "SELECT * FROM categorias
	                 WHERE descripcion='$descripcion'";
		} else {
			//ACTUALIAZAR
			$sql = "SELECT * FROM categorias
	                 WHERE descripcion='$descripcion'
	                 AND id_categoria!='$id_categoria'";
		}

		$this->conexion->consulta($sql);

		$arreglo_accesos = $this->conexion->extraerRegistro();

		return $arreglo_accesos;
	}
}
