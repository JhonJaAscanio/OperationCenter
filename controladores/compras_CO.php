<?php
require_once "modelos/compras_MO.php";
require_once "modelos/productos_MO.php";

$f = new funciones();
$f->limpiarMatriz($_POST);
$f->validarCSRF();

class compras_CO
{
	function __construct() {}


	function agregar()
	{
		$id_factura = $_POST["id_factura"];
		$proveedor = $_POST["proveedor"];
		$total = $_POST["total"];
		$abono = $_POST["abono"];
		$pago = $_POST["pago"];
		$conexion = new servidor('A');
		$compras_MO = new compras_MO($conexion);
		$arreglo_compras = $compras_MO->unico($id_factura);

		if ($arreglo_compras) {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: La compra ya existe"
			];
		} else {
			$filas_afectadas = $compras_MO->agregar($id_factura, $proveedor, $total, $abono, $pago);

			if ($filas_afectadas) {
				$respuesta = [
					"estado" => "EXITO",
					'mensaje' => "EXITO: Registro Guardado"
				];
			} else {
				$respuesta = [
					"estado" => "ADVERTENCIA",
					'mensaje' => "ADVERTENCIA: No se guardo el registro"
				];
			}
		}

		echo json_encode($respuesta);
	}


	function agregar_articulo()
	{

		$id_factura = $_POST["id_factura"];
		$cantidad = $_POST["cantidad"];
		$articulo = $_POST["articulo"];
		$precio = $_POST["precio"];
		$tipo = $_POST["tipo"];
		$conexion = new servidor('A');
		$compras_MO = new compras_MO($conexion);

		$productos_MO = new productos_MO($conexion);

		if ($tipo == 1) {
			$arreglo_productos = $productos_MO->seleccionar('codigo', $articulo);
		} else {
			$arreglo_productos = $productos_MO->seleccionar('descripcion', $articulo);
		}



		if (!$arreglo_productos) {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: El producto no existe"
			];
		} else {
			$id_producto = $arreglo_productos[0]->id_producto;
			$codigo = $arreglo_productos[0]->codigo;
			$descripcion = $arreglo_productos[0]->descripcion;
			$precio_total = $precio * $cantidad;

			$conexion->iniciarTransaccion();
			$productos_MO->sumar_cantidad($id_producto, $cantidad);  //Sumar cantidad al stock del producto
			$filas_afectadas = $compras_MO->agregarArticulo($id_factura, $codigo, $cantidad, $descripcion, $precio, $precio_total);

			if ($filas_afectadas) {
				$conexion->confirmarTransaccion();
				$respuesta = [
					"estado" => "EXITO",
					'mensaje' => "EXITO: Articulo añadido",

				];
			} else {
				$conexion->revertirTransaccion();
				$respuesta = [
					"estado" => "ADVERTENCIA",
					'mensaje' => "ADVERTENCIA: No se añadio el articulo"
				];
			}
		}

		echo json_encode($respuesta);
	}



	function actualizar()
	{
		$id_compra = $_POST["id_compra"];
		$id_factura = $_POST["id_factura"];
		$proveedor = $_POST["proveedor"];
		$total = $_POST["total"];
		$abono = $_POST["abono"];
		$pago = $_POST["pago"];
		$conexion = new servidor('A');
		$compras_MO = new compras_MO($conexion);
		//$arreglo_compras=$compras_MO->unico($codigo, $id_compra);



		//  if($arreglo_compras)
		// {
		//      $respuesta = [
		//     "estado" => "ADVERTENCIA",
		//     'mensaje' => "ADVERTENCIA: El compra con codigo <b>$codigo</b> ya existe"
		////     ];
		//  }
		//  else
		//  {    
		$filas_afectadas = $compras_MO->actualizar($id_compra, $id_factura, $proveedor, $total, $abono, $pago);
		if ($filas_afectadas) {

			$respuesta = [
				"estado" => "EXITO",
				'mensaje' => "EXITO: Registro Guardado"
			];
		} else {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: No ocurrieron cambios"
			];
		}

		//}  
		echo json_encode($respuesta);
	}


	function eliminar()
	{
		$id_compra = $_POST["id_compra"];
		$conexion = new servidor('A');
		$compras_MO = new compras_MO($conexion);
		$arreglo_compras = $compras_MO->eliminar($id_compra);

		if ($arreglo_compras) {
			$respuesta = [
				"estado" => "EXITO",
				'mensaje' => "EXITO: La compra se elimino correctamente"
			];
		} else {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: La compra no se pudo eliminar, intente mas tarde"
			];
		}
		echo json_encode($respuesta);
	}

	function eliminarArticulo()
	{
		$id_articulo = $_POST["id_articulo"];
		$cantidad = $_POST["cantidad"];
		$codigo = $_POST["codigo"];
		$conexion = new servidor('A');
		$compras_MO = new compras_MO($conexion);
		$productos_MO = new productos_MO($conexion);
		$arreglo_productos = $productos_MO->seleccionar("codigo", $codigo);

		$conexion->iniciarTransaccion();
		$arreglo_articulos = $compras_MO->eliminarArticulo($id_articulo);

		if ($arreglo_articulos) {
			$id_producto = $arreglo_productos[0]->id_producto;
			$filas_stock = $productos_MO->disminuir_cantidad($id_producto, $cantidad);

			if ($filas_stock) {
				$conexion->confirmarTransaccion();
				$respuesta = [
					"estado" => "EXITO",
					'mensaje' => "EXITO: El Articulo se elimino correctamente"
				];
			} else {
				$conexion->revertirTransaccion();
				$respuesta = [
					"estado" => "ADVERTENCIA",
					'mensaje' => "ADVERTENCIA: No hay suficiente stock para revertir esta compra"
				];
			}
		} else {
			$conexion->revertirTransaccion();
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: El Articulo no se pudo eliminar, intente mas tarde"
			];
		}
		echo json_encode($respuesta);
	} // Fin funcion eliminar articulo
}
