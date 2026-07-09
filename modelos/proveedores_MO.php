<?php
class proveedores_MO
{
	private $conexion;

	function __construct($conexion)
	{
		$this->conexion = $conexion;
	}


	function agregar($nombre, $telefono, $direccion, $ciudad)
	{
		$sql = "INSERT INTO proveedores ( nombre,telefono,direccion,ciudad) VALUES ('$nombre','$telefono','$direccion','$ciudad')";

		$filas_afectadas = $this->conexion->consulta($sql);

		return $filas_afectadas;
	}

	function actualizar($id_proveedor, $nombre, $telefono, $direccion, $ciudad)
	{

		$sql = "UPDATE proveedores SET nombre='$nombre',telefono='$telefono', direccion='$direccion',ciudad='$ciudad' WHERE id_proveedor='$id_proveedor'";

		$filas_afectadas = $this->conexion->consulta($sql);

		return $filas_afectadas;
	}


	function seleccionar($atributo = '', $valor = '')
	{
		$condicion = "";

		if ($atributo && $valor) {
			$condicion = " WHERE $atributo='$valor'";
		}

		$sql = "SELECT * FROM proveedores $condicion";

		$this->conexion->consulta($sql);

		$arreglo_accesos = $this->conexion->extraerRegistro();

		return $arreglo_accesos;
	}



	function unico($nombre, $id_proveedor = '')
	{
		if (empty($id_Proveedores)) {
			//AGREGAR
			$sql = "SELECT * FROM proveedores
	                 WHERE nombre='$nombre'";
		} else {
			//ACTUALIAZAR
			$sql = "SELECT * FROM proveedores
	                 WHERE nombre='$nombre'
	                 AND id_proveedor!='$id_proveedor'";
		}

		$this->conexion->consulta($sql);

		$arreglo_accesos = $this->conexion->extraerRegistro();

		return $arreglo_accesos;
	}


	function eliminar($id_proveedor)
	{
		$sql = "DELETE FROM proveedores WHERE id_proveedor='$id_proveedor'";

		$filas_afectadas = $this->conexion->consulta($sql);

		return $filas_afectadas;
	}
}
