<?php
class proveedores_MO extends Repositorio
{
	function __construct($conexion)
	{
		parent::__construct($conexion, "proveedores", "id_proveedor");
	}

	function agregar($nombre, $telefono, $direccion, $ciudad)
	{
		return $this->insertar([
			"nombre" => $nombre,
			"telefono" => $telefono,
			"direccion" => $direccion,
			"ciudad" => $ciudad,
		]);
	}

	function actualizar($id_proveedor, $nombre, $telefono, $direccion, $ciudad)
	{
		return $this->modificar($id_proveedor, [
			"nombre" => $nombre,
			"telefono" => $telefono,
			"direccion" => $direccion,
			"ciudad" => $ciudad,
		]);
	}

	function unico($nombre, $id_proveedor = '')
	{
		return $this->existeValorUnico("nombre", $nombre, $id_proveedor);
	}
}
