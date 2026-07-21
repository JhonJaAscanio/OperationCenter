<?php
require_once "modelos/subcategorias_MO.php";

$f = new funciones();
$f->limpiarMatriz($_POST);

class subcategorias_CO
{
	function __construct()
	{
	}


	function agregar()
	{
		$id_categoria = $_POST["id_categoria"];
		$descripcion = $_POST["descripcion"];
		$conexion = new servidor('A');
		$subcategorias_MO = new subcategorias_MO($conexion);
		$arreglo_subcategorias = $subcategorias_MO->unico($id_categoria, $descripcion);

		if ($arreglo_subcategorias) {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: La subcategoria <b>$descripcion</b> ya existe en esa categoria"
			];
		} else {
			$filas_afectadas = $subcategorias_MO->agregar($id_categoria, $descripcion);

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


	function actualizar()
	{
		$id_subcategoria = $_POST["id_subcategoria"];
		$id_categoria = $_POST["id_categoria"];
		$descripcion = $_POST["descripcion"];
		$conexion = new servidor('A');
		$subcategorias_MO = new subcategorias_MO($conexion);
		$arreglo_subcategorias = $subcategorias_MO->unico($id_categoria, $descripcion, $id_subcategoria);

		if ($arreglo_subcategorias) {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: La subcategoria <b>$descripcion</b> ya existe en esa categoria"
			];
		} else {
			$filas_afectadas = $subcategorias_MO->actualizar($id_subcategoria, $id_categoria, $descripcion);
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
		}

		echo json_encode($respuesta);
	}


	function eliminar()
	{
		$id_subcategoria = $_POST["id_subcategoria"];
		$conexion = new servidor('A');
		$subcategorias_MO = new subcategorias_MO($conexion);
		$arreglo_subcategorias = $subcategorias_MO->eliminar($id_subcategoria);

		if ($arreglo_subcategorias) {
			$respuesta = [
				"estado" => "EXITO",
				'mensaje' => "EXITO: La subcategoria se elimino correctamente"
			];
		} else {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: La subcategoria no se pudo eliminar, intente mas tarde"
			];
		}
		echo json_encode($respuesta);
	}
}
