<?php
class compras_MO extends Repositorio
{
	function __construct($conexion)
	{
		parent::__construct($conexion, "compras", "id_compra");
	}

	function agregar($id_factura, $proveedor, $total, $abono, $pago)
	{
		return $this->insertar([
			"id_factura" => $id_factura,
			"proveedor" => $proveedor,
			"total" => $total,
			"abono" => $abono,
			"pago" => $pago,
		]);
	}

	function agregarArticulo($id_factura, $codigo, $cantidad, $descripcion, $precio_unitario, $precio_total)
	{
		$sql = "INSERT INTO articulos_compra (id_factura,codigo,cantidad,descripcion,precio_unitario,precio_total) VALUES (:id_factura,:codigo,:cantidad,:descripcion,:precio_unitario,:precio_total)";

		return $this->conexion->consultaPreparada($sql, [
			':id_factura' => $id_factura,
			':codigo' => $codigo,
			':cantidad' => $cantidad,
			':descripcion' => $descripcion,
			':precio_unitario' => $precio_unitario,
			':precio_total' => $precio_total,
		]);
	}

	function actualizar($id_compra, $id_factura, $proveedor, $total, $abono, $pago)
	{
		return $this->modificar($id_compra, [
			"id_factura" => $id_factura,
			"proveedor" => $proveedor,
			"total" => $total,
			"abono" => $abono,
			"pago" => $pago,
		]);
	}

	function seleccionarArticulo($id)
	{
		$sql = "SELECT * FROM articulos_compra WHERE id_factura = :id";

		$this->conexion->consultaPreparada($sql, [':id' => $id]);

		return $this->conexion->extraerRegistro();
	}

	function seleccionarMayor($atributo = '', $valor = '')
	{
		if ($atributo && $valor)
		{
			$sql = "SELECT id_factura FROM {$this->tabla} WHERE $atributo = :valor";
			$this->conexion->consultaPreparada($sql, [':valor' => $valor]);
		}
		else
		{
			$sql = "SELECT id_factura FROM {$this->tabla} WHERE id_factura = (SELECT MAX(id_factura) FROM {$this->tabla})";
			$this->conexion->consultaPreparada($sql);
		}

		return $this->conexion->extraerRegistro();
	}

	// NOTA: se preserva el comportamiento original (bug preexistente reportado
	// aparte): la rama "AGREGAR" filtra por id_compra en vez de id_factura, por lo
	// que esta validacion no bloquea duplicados al agregar una compra. No se
	// corrige en esta migracion para no alterar comportamiento sin confirmacion.
	function unico($id_factura, $id_compra = '')
	{
		if (empty($id_compra))
		{
			$sql = "SELECT * FROM {$this->tabla} WHERE {$this->llavePrimaria} = :id_compra";
			$parametros = [':id_compra' => $id_compra];
		}
		else
		{
			$sql = "SELECT * FROM {$this->tabla} WHERE id_factura = :id_factura AND {$this->llavePrimaria} != :id_compra";
			$parametros = [':id_factura' => $id_factura, ':id_compra' => $id_compra];
		}

		$this->conexion->consultaPreparada($sql, $parametros);

		return $this->conexion->extraerRegistro();
	}

	function eliminarArticulo($id_articulo)
	{
		$sql = "DELETE FROM articulos_compra WHERE id_articulo_compra = :id";

		return $this->conexion->consultaPreparada($sql, [':id' => $id_articulo]);
	}
}
