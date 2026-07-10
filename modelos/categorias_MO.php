<?php
class categorias_MO extends Repositorio
{
	function __construct($conexion)
	{
		parent::__construct($conexion, "categorias", "id_categoria");
	}

	function agregar($descripcion)
	{
		return $this->insertar(["descripcion" => $descripcion]);
	}

	function actualizar($id_categoria, $descripcion)
	{
		return $this->modificar($id_categoria, ["descripcion" => $descripcion]);
	}

	function unico($descripcion, $id_categoria = '')
	{
		return $this->existeValorUnico("descripcion", $descripcion, $id_categoria);
	}
}
