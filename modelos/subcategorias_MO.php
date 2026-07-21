<?php
class subcategorias_MO extends Repositorio
{
	function __construct($conexion)
	{
		parent::__construct($conexion, "subcategorias", "id_subcategoria");
	}

	function agregar($id_categoria, $descripcion)
	{
		return $this->insertar([
			"id_categoria" => $id_categoria,
			"descripcion" => $descripcion,
		]);
	}

	function actualizar($id_subcategoria, $id_categoria, $descripcion)
	{
		return $this->modificar($id_subcategoria, [
			"id_categoria" => $id_categoria,
			"descripcion" => $descripcion,
		]);
	}

	function seleccionarPorCategoria($id_categoria)
	{
		$sql = "SELECT * FROM {$this->tabla} WHERE id_categoria = :id_categoria";

		$this->conexion->consultaPreparada($sql, [':id_categoria' => $id_categoria]);

		return $this->conexion->extraerRegistro();
	}

	// La unicidad de subcategoria es por (id_categoria, descripcion), no solo por
	// descripcion -- la misma subcategoria puede repetirse bajo categorias distintas.
	function unico($id_categoria, $descripcion, $id_subcategoria = '')
	{
		if ($id_subcategoria === '' || $id_subcategoria === null)
		{
			$sql = "SELECT * FROM {$this->tabla} WHERE id_categoria = :id_categoria AND descripcion = :descripcion";
			$parametros = [':id_categoria' => $id_categoria, ':descripcion' => $descripcion];
		}
		else
		{
			$sql = "SELECT * FROM {$this->tabla} WHERE id_categoria = :id_categoria AND descripcion = :descripcion AND {$this->llavePrimaria} != :id_excluir";
			$parametros = [':id_categoria' => $id_categoria, ':descripcion' => $descripcion, ':id_excluir' => $id_subcategoria];
		}

		$this->conexion->consultaPreparada($sql, $parametros);

		return $this->conexion->extraerRegistro();
	}
}
