<?php
class productos_MO
{
	private $conexion;

	function __construct($conexion)
	{
		$this->conexion = $conexion;
	}


	function agregar($codigo, $referencia, $descripcion, $proveedor, $categoria, $cantidad, $precio_costo, $precio_general, $precio_mayorista, $ubicacion)
	{
		$sql = "INSERT INTO productos ( codigo,referencia,descripcion,proveedor,categoria,precio_costo,precio_general,precio_mayorista, cantidad_bodega,ubicacion) VALUES ('$codigo',    '$referencia','$descripcion','$proveedor','$categoria','$precio_costo','$precio_general','$precio_mayorista','$cantidad','$ubicacion')";

		$filas_afectadas = $this->conexion->consulta($sql);

		return $filas_afectadas;
	}

	function actualizar($id_producto, $referencia, $descripcion, $proveedor, $categoria, $cantidad, $precio_costo, $precio_general, $precio_mayorista, $ubicacion)
	{

		$sql = "UPDATE productos SET referencia='$referencia',descripcion='$descripcion', proveedor='$proveedor',categoria='$categoria', precio_costo='$precio_costo',precio_general='$precio_general',precio_mayorista='$precio_mayorista',cantidad_bodega='$cantidad',ubicacion='$ubicacion' WHERE id_producto='$id_producto'";

		$filas_afectadas = $this->conexion->consulta($sql);

		return $filas_afectadas;
	}


	function seleccionar($atributo = '', $valor = '')
	{
		$condicion = "";

		if ($atributo && $valor) {
			$condicion = " WHERE $atributo='$valor'";
		}

		$sql = "SELECT * FROM productos $condicion";

		$this->conexion->consulta($sql);

		$arreglo_accesos = $this->conexion->extraerRegistro();

		return $arreglo_accesos;
	}


	function unico($codigo, $id_producto = '')
	{
		if (empty($id_productos)) {
			//AGREGAR
			$sql = "SELECT * FROM productos
	                 WHERE codigo='$codigo'";
		} else {
			//ACTUALIAZAR
			$sql = "SELECT * FROM productos
	                 WHERE codigo='$codigo'
	                 AND id_producto!='$id_producto'";
		}

		$this->conexion->consulta($sql);

		$arreglo_accesos = $this->conexion->extraerRegistro();

		return $arreglo_accesos;
	}


	function eliminar($id_producto)
	{
		$sql = "DELETE FROM productos
	        	WHERE id_producto='$id_producto'";

		$filas_afectadas = $this->conexion->consulta($sql);

		return $filas_afectadas;
	}

	function disminuir_cantidad($id, $cantidad)
	{
		$sql = "UPDATE productos SET cantidad_bodega='$cantidad' WHERE id_producto='$id'";

		$filas_afectadas = $this->conexion->consulta($sql);

		return $filas_afectadas;
	}

	function sumar_cantidad($id, $cantidad)
	{
		$sql = "UPDATE productos SET cantidad_bodega='$cantidad' WHERE id_producto='$id'";

		$filas_afectadas = $this->conexion->consulta($sql);

		return $filas_afectadas;
	}
}
