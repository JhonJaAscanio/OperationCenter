<?php
class productos_MO extends Repositorio
{
	function __construct($conexion)
	{
		parent::__construct($conexion, "productos", "id_producto");
	}

	// $datos: arreglo asociativo columna => valor, ya armado por el controlador
	// (con los valores por defecto de los campos opcionales ya resueltos).
	function agregar(array $datos)
	{
		return $this->insertar($datos);
	}

	function actualizar($id_producto, array $datos)
	{
		return $this->modificar($id_producto, $datos);
	}

	function unico($codigo, $id_producto = '')
	{
		return $this->existeValorUnico("codigo", $codigo, $id_producto);
	}

	function activo($id_producto, $estado)
	{
		return $this->modificar($id_producto, ["estado" => $estado]);
	}

	// Trae el producto con los nombres de categoria/proveedor/marca/subcategoria
	// ya resueltos (para listados y formularios), en vez de solo los id de FK.
	function seleccionarConNombres($atributo = '', $valor = '')
	{
		$condicion = "";
		$parametros = [];

		if ($atributo && $valor !== '')
		{
			$condicion = "WHERE p.$atributo = :valor";
			$parametros[':valor'] = $valor;
		}

		$sql = "SELECT p.*, c.descripcion AS nombre_categoria, pr.nombre AS nombre_proveedor,
					m.descripcion AS nombre_marca, s.descripcion AS nombre_subcategoria
				FROM {$this->tabla} p
				JOIN categorias c ON p.id_categoria = c.id_categoria
				JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
				LEFT JOIN marcas m ON p.id_marca = m.id_marca
				LEFT JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria
				$condicion";

		$this->conexion->consultaPreparada($sql, $parametros);

		return $this->conexion->extraerRegistro();
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
