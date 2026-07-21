<?php
class marcas_MO extends Repositorio
{
	function __construct($conexion)
	{
		parent::__construct($conexion, "marcas", "id_marca");
	}

	function agregar($descripcion)
	{
		return $this->insertar(["descripcion" => $descripcion]);
	}

	function actualizar($id_marca, $descripcion)
	{
		return $this->modificar($id_marca, ["descripcion" => $descripcion]);
	}

	function unico($descripcion, $id_marca = '')
	{
		return $this->existeValorUnico("descripcion", $descripcion, $id_marca);
	}
}
