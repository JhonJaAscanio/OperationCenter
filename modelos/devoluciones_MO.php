<?php
class devoluciones_MO extends Repositorio
{
	function __construct($conexion)
	{
		parent::__construct($conexion, "ventas_mostrador", "id_venta_mostrador");
	}

	function actulizar_devolucion($id)
	{
		return $this->modificar($id, ["devolucion" => "SI"]);
	}

	// $precio no se usa en el UPDATE original (el total se recalcula a partir
	// de la columna precio ya almacenada) -- se conserva el mismo comportamiento.
	function actualizar_venta_mostrador($id_venta, $cantidad_actualizar, $precio)
	{
		$sql = "UPDATE {$this->tabla} SET cantidad = :cantidad, total = (precio * :cantidad_total) WHERE {$this->llavePrimaria} = :id";

		return $this->conexion->consultaPreparada($sql, [
			':cantidad' => $cantidad_actualizar,
			':cantidad_total' => $cantidad_actualizar,
			':id' => $id_venta,
		]);
	}

	function insertar_devolucion($codigo, $descripcion, $cantidad, $precio, $total)
	{
		return $this->insertar([
			"codigo" => $codigo,
			"descripcion" => $descripcion,
			"cantidad" => $cantidad,
			"precio" => $precio,
			"total" => $total,
			"devolucion" => "SI",
		]);
	}
}
