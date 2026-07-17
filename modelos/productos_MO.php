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

	// Resta $cantidad de forma atomica (no un SET absoluto), con guarda de stock
	// suficiente en la misma sentencia para evitar condiciones de carrera entre
	// ventas/compras concurrentes. Devuelve 0 filas afectadas si no hay stock.
	function disminuir_cantidad($id, $cantidad)
	{
		$sql = "UPDATE {$this->tabla} SET cantidad_bodega = cantidad_bodega - :cantidad WHERE {$this->llavePrimaria} = :id AND cantidad_bodega >= :cantidad_disponible";

		return $this->conexion->consultaPreparada($sql, [
			':cantidad' => $cantidad,
			':cantidad_disponible' => $cantidad,
			':id' => $id,
		]);
	}

	// Suma $cantidad de forma atomica (no un SET absoluto).
	function sumar_cantidad($id, $cantidad)
	{
		$sql = "UPDATE {$this->tabla} SET cantidad_bodega = cantidad_bodega + :cantidad WHERE {$this->llavePrimaria} = :id";

		return $this->conexion->consultaPreparada($sql, [
			':cantidad' => $cantidad,
			':id' => $id,
		]);
	}
}
