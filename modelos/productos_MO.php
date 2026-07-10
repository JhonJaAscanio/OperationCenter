<?php
class productos_MO extends Repositorio
{
	function __construct($conexion)
	{
		parent::__construct($conexion, "productos", "id_producto");
	}

	function agregar($codigo, $referencia, $descripcion, $proveedor, $categoria, $cantidad, $precio_costo, $precio_general, $precio_mayorista, $ubicacion)
	{
		return $this->insertar([
			"codigo" => $codigo,
			"referencia" => $referencia,
			"descripcion" => $descripcion,
			"proveedor" => $proveedor,
			"categoria" => $categoria,
			"precio_costo" => $precio_costo,
			"precio_general" => $precio_general,
			"precio_mayorista" => $precio_mayorista,
			"cantidad_bodega" => $cantidad,
			"ubicacion" => $ubicacion,
		]);
	}

	function actualizar($id_producto, $referencia, $descripcion, $proveedor, $categoria, $cantidad, $precio_costo, $precio_general, $precio_mayorista, $ubicacion)
	{
		return $this->modificar($id_producto, [
			"referencia" => $referencia,
			"descripcion" => $descripcion,
			"proveedor" => $proveedor,
			"categoria" => $categoria,
			"precio_costo" => $precio_costo,
			"precio_general" => $precio_general,
			"precio_mayorista" => $precio_mayorista,
			"cantidad_bodega" => $cantidad,
			"ubicacion" => $ubicacion,
		]);
	}

	function unico($codigo, $id_producto = '')
	{
		return $this->existeValorUnico("codigo", $codigo, $id_producto);
	}

	function disminuir_cantidad($id, $cantidad)
	{
		return $this->modificar($id, ["cantidad_bodega" => $cantidad]);
	}

	function sumar_cantidad($id, $cantidad)
	{
		return $this->modificar($id, ["cantidad_bodega" => $cantidad]);
	}
}
