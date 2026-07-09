<?php
require_once "modelos/categorias_MO.php";

$f = new funciones();
$f->limpiarMatriz($_POST);

class categorias_CO
{
	function __construct()
	{
	}


	function agregar()
	{
		$descripcion = $_POST["descripcion"];
		$conexion = new servidor('A');
		$categorias_MO = new categorias_MO($conexion);
		$arreglo_categorias = $categorias_MO->unico($descripcion);

		if ($arreglo_categorias) {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: La categoria <b>$descripcion</b> ya existe"
			];
		} else {
			$filas_afectadas = $categorias_MO->agregar($descripcion);

			if ($filas_afectadas) {
				$arreglo_categorias = $categorias_MO->seleccionar("descripcion", $descripcion);
				$descripcion = $arreglo_categorias[0]->descripcion;
				$fecha_creacion = $arreglo_categorias[0]->fecha_creacion;
				$fecha_actualizacion = $arreglo_categorias[0]->fecha_actualizacion;
				$respuesta = [
					"estado" => "EXITO",
					'mensaje' => "EXITO: Registro Guardado",
					'codigo' => $descripcion,
					'fecha_creacion' => $fecha_creacion,
					'fecha_actualizacion' => $fecha_actualizacion
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


	function actualizar()
	{
		$id_categoria = $_POST["id_categoria"];
		$descripcion = $_POST["descripcion"];
		$conexion = new servidor('A');
		$categorias_MO = new categorias_MO($conexion);
		$arreglo_categorias = $categorias_MO->unico($descripcion);


		if ($arreglo_categorias) {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: La categoria <b>$descripcion</b> ya existe"
			];
		} else {
			$filas_afectadas = $categorias_MO->actualizar($id_categoria, $descripcion);
			if ($filas_afectadas) {
				$arreglo_categorias = $categorias_MO->seleccionar("descripcion", $descripcion);
				$fecha_actualizacion = $arreglo_categorias[0]->fecha_actualizacion;

				$respuesta = [
					"estado" => "EXITO",
					'mensaje' => "EXITO: Registro Guardado",
					'id_producto' => $id_categoria,
					'fecha_actualizacion' => $fecha_actualizacion
				];
			} else {
				$respuesta = [
					"estado" => "ADVERTENCIA",
					'mensaje' => "ADVERTENCIA: No ocurrieron cambios"
				];
			}
		}

		echo json_encode($respuesta);
	}


	function eliminar()
	{
		$id_categoria = $_POST["id_categoria"];
		$conexion = new servidor('A');
		$categorias_MO = new categorias_MO($conexion);
		$arreglo_categorias = $categorias_MO->eliminar($id_categoria);

		if ($arreglo_categorias) {
			$respuesta = [
				"estado" => "EXITO",
				'mensaje' => "EXITO: La categoria se elimino correctamente"
			];
		} else {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: La categoria no se pudo eliminar, intente mas tarde"
			];
		}
		echo json_encode($respuesta);
	}
}
