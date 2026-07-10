<?php
// NOTA: este modelo abarca 4 tablas (ventas, ventas_mostrador, articulos, abonos),
// por lo que no encaja en el patron de tabla-unica de Repositorio y se mantiene
// como clase independiente. Se migro a consultas preparadas (consultaPreparada)
// para eliminar la interpolacion de valores en el SQL, preservando exactamente
// la misma logica y los mismos metodos publicos que ya usan los controladores/vistas.
class ventas_MO
{
	private $conexion;

	function __construct($conexion)
	{
		$this->conexion = $conexion;
	}

	function agregar($nit, $id_factura, $forma_pago, $total, $abono, $notas, $saldo)
	{
		$sql = "INSERT INTO ventas (nit_cliente,id_factura,forma_pago,total,abono,comentario,saldo) VALUES (:nit,:id_factura,:forma_pago,:total,:abono,:notas,:saldo)";

		return $this->conexion->consultaPreparada($sql, [
			':nit' => $nit,
			':id_factura' => $id_factura,
			':forma_pago' => $forma_pago,
			':total' => $total,
			':abono' => $abono,
			':notas' => $notas,
			':saldo' => $saldo,
		]);
	}

	function agregarVenta($codigo, $descripcion, $cantidad, $precio, $total)
	{
		$sql = "INSERT INTO ventas_mostrador (codigo,descripcion,cantidad,precio,total) VALUES (:codigo,:descripcion,:cantidad,:precio,:total)";

		return $this->conexion->consultaPreparada($sql, [
			':codigo' => $codigo,
			':descripcion' => $descripcion,
			':cantidad' => $cantidad,
			':precio' => $precio,
			':total' => $total,
		]);
	}

	function agregarArticulo($id_factura, $codigo, $cantidad, $descripcion, $precio_unitario, $precio_total)
	{
		$sql = "INSERT INTO articulos (id_factura,codigo,cantidad,descripcion,precio_unitario,precio_total) VALUES (:id_factura,:codigo,:cantidad,:descripcion,:precio_unitario,:precio_total)";

		return $this->conexion->consultaPreparada($sql, [
			':id_factura' => $id_factura,
			':codigo' => $codigo,
			':cantidad' => $cantidad,
			':descripcion' => $descripcion,
			':precio_unitario' => $precio_unitario,
			':precio_total' => $precio_total,
		]);
	}

	function actualizar($id_venta, $nit, $id_factura, $forma_pago, $total, $abono, $devolucion, $saldo)
	{
		$sql = "UPDATE ventas SET nit_cliente=:nit,id_factura=:id_factura, forma_pago=:forma_pago,total=:total, abono=:abono, devolucion=:devolucion,saldo=:saldo WHERE id_venta=:id_venta";

		return $this->conexion->consultaPreparada($sql, [
			':nit' => $nit,
			':id_factura' => $id_factura,
			':forma_pago' => $forma_pago,
			':total' => $total,
			':abono' => $abono,
			':devolucion' => $devolucion,
			':saldo' => $saldo,
			':id_venta' => $id_venta,
		]);
	}

	function adiccionarAbonoTotal($id, $valor)
	{
		$sql = "UPDATE ventas SET abono=(abono + :valor1),saldo=(saldo - :valor2) WHERE id_factura=:id";

		return $this->conexion->consultaPreparada($sql, [
			':valor1' => $valor,
			':valor2' => $valor,
			':id' => $id,
		]);
	}

	function agregarAbono($id_factura, $nit, $fecha, $vendedor, $valor)
	{
		$sql = "INSERT INTO abonos (id_factura,nit_cliente,fecha,vendedor,valor) VALUES (:id_factura,:nit,:fecha,:vendedor,:valor)";

		return $this->conexion->consultaPreparada($sql, [
			':id_factura' => $id_factura,
			':nit' => $nit,
			':fecha' => $fecha,
			':vendedor' => $vendedor,
			':valor' => $valor,
		]);
	}

	function seleccionar($atributo = '', $valor = '')
	{
		if ($atributo && $valor)
		{
			$sql = "SELECT * FROM ventas WHERE $atributo = :valor";
			$this->conexion->consultaPreparada($sql, [':valor' => $valor]);
		}
		else
		{
			$sql = "SELECT * FROM ventas";
			$this->conexion->consultaPreparada($sql);
		}

		return $this->conexion->extraerRegistro();
	}

	function seleccionar_mostrador($atributo = '', $valor = '')
	{
		if ($atributo && $valor)
		{
			$sql = "SELECT * FROM ventas_mostrador WHERE $atributo = :valor";
			$this->conexion->consultaPreparada($sql, [':valor' => $valor]);
		}
		else
		{
			$sql = "SELECT * FROM ventas_mostrador";
			$this->conexion->consultaPreparada($sql);
		}

		return $this->conexion->extraerRegistro();
	}

	function seleccionar_fecha($inicial = '', $fin = '')
	{
		if ($inicial && $fin)
		{
			$sql = "SELECT * FROM ventas_mostrador WHERE DATE(fecha_creacion) BETWEEN :inicial AND :fin";
			$this->conexion->consultaPreparada($sql, [':inicial' => $inicial, ':fin' => $fin]);
		}
		else
		{
			$sql = "SELECT * FROM ventas_mostrador";
			$this->conexion->consultaPreparada($sql);
		}

		return $this->conexion->extraerRegistro();
	}

	function seleccionarMayor($atributo = '', $valor = '')
	{
		if ($atributo && $valor)
		{
			$sql = "SELECT id_factura FROM ventas WHERE $atributo = :valor";
			$this->conexion->consultaPreparada($sql, [':valor' => $valor]);
		}
		else
		{
			$sql = "SELECT id_factura FROM ventas WHERE id_factura = (SELECT MAX(id_factura) FROM ventas)";
			$this->conexion->consultaPreparada($sql);
		}

		return $this->conexion->extraerRegistro();
	}

	function seleccionarArticulo($id)
	{
		$sql = "SELECT * FROM articulos WHERE id_factura = :id";

		$this->conexion->consultaPreparada($sql, [':id' => $id]);

		return $this->conexion->extraerRegistro();
	}

	function seleccionarArticuloFecha()
	{
		$sql = "SELECT * FROM ventas_mostrador ORDER BY fecha_creacion DESC LIMIT 10";

		$this->conexion->consultaPreparada($sql);

		return $this->conexion->extraerRegistro();
	}

	function seleccionarArticuloCantidad()
	{
		$sql = "SELECT id_venta_mostrador,codigo,descripcion, SUM(cantidad) AS cantidad, total,fecha_creacion FROM ventas_mostrador GROUP BY codigo ORDER BY cantidad DESC LIMIT 10";

		$this->conexion->consultaPreparada($sql);

		return $this->conexion->extraerRegistro();
	}

	function seleccionarAbonos($nit)
	{
		$sql = "SELECT id_factura, fecha, vendedor, valor, fecha_creacion FROM abonos WHERE nit_cliente = :nit";

		$this->conexion->consultaPreparada($sql, [':nit' => $nit]);

		return $this->conexion->extraerRegistro();
	}

	// NOTA: se preserva el comportamiento original (bug preexistente, reportado
	// aparte): la rama "AGREGAR" filtra por id_venta en vez de id_factura, por lo
	// que esta validacion no bloquea duplicados al agregar una venta.
	function unico($id_factura, $id_venta = '')
	{
		if (empty($id_venta))
		{
			$sql = "SELECT * FROM ventas WHERE id_venta = :id_venta";
			$parametros = [':id_venta' => $id_venta];
		}
		else
		{
			$sql = "SELECT * FROM ventas WHERE id_factura = :id_factura AND id_venta != :id_venta";
			$parametros = [':id_factura' => $id_factura, ':id_venta' => $id_venta];
		}

		$this->conexion->consultaPreparada($sql, $parametros);

		return $this->conexion->extraerRegistro();
	}

	// NOTA: eliminar() para la tabla "ventas" no existe (esta comentado en el
	// original, incluyendo la limpieza en cascada de "articulos"). Se preserva
	// la ausencia del metodo -- ventas_CO.php ya tiene una llamada a un metodo
	// inexistente, ver hallazgo reportado aparte.

	function eliminarArticulo($id_articulo)
	{
		$sql = "DELETE FROM articulos WHERE id_articulo = :id";

		return $this->conexion->consultaPreparada($sql, [':id' => $id_articulo]);
	}

	function eliminarVenta($id)
	{
		$sql = "DELETE FROM ventas_mostrador WHERE id_venta_mostrador = :id";

		return $this->conexion->consultaPreparada($sql, [':id' => $id]);
	}
}
