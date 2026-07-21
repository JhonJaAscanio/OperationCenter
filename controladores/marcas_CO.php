<?php
require_once "modelos/marcas_MO.php";

$f = new funciones();
$f->limpiarMatriz($_POST);

class marcas_CO
{
	function __construct()
	{
	}


	function agregar()
	{
		$descripcion = $_POST["descripcion"];
		$conexion = new servidor('A');
		$marcas_MO = new marcas_MO($conexion);
		$arreglo_marcas = $marcas_MO->unico($descripcion);

		if ($arreglo_marcas) {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: La marca <b>$descripcion</b> ya existe"
			];
		} else {
			$filas_afectadas = $marcas_MO->agregar($descripcion);

			if ($filas_afectadas) {
				$arreglo_marcas = $marcas_MO->seleccionar("descripcion", $descripcion);
				$fecha_creacion = $arreglo_marcas[0]->fecha_creacion;
				$fecha_actualizacion = $arreglo_marcas[0]->fecha_actualizacion;
				$respuesta = [
					"estado" => "EXITO",
					'mensaje' => "EXITO: Registro Guardado",
					'descripcion' => $descripcion,
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
		$id_marca = $_POST["id_marca"];
		$descripcion = $_POST["descripcion"];
		$conexion = new servidor('A');
		$marcas_MO = new marcas_MO($conexion);
		$arreglo_marcas = $marcas_MO->unico($descripcion, $id_marca);

		if ($arreglo_marcas) {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: La marca <b>$descripcion</b> ya existe"
			];
		} else {
			$filas_afectadas = $marcas_MO->actualizar($id_marca, $descripcion);
			if ($filas_afectadas) {
				$arreglo_marcas = $marcas_MO->seleccionar("id_marca", $id_marca);
				$fecha_actualizacion = $arreglo_marcas[0]->fecha_actualizacion;

				$respuesta = [
					"estado" => "EXITO",
					'mensaje' => "EXITO: Registro Guardado",
					'id_marca' => $id_marca,
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
		$id_marca = $_POST["id_marca"];
		$conexion = new servidor('A');
		$marcas_MO = new marcas_MO($conexion);
		$arreglo_marcas = $marcas_MO->eliminar($id_marca);

		if ($arreglo_marcas) {
			$respuesta = [
				"estado" => "EXITO",
				'mensaje' => "EXITO: La marca se elimino correctamente"
			];
		} else {
			$respuesta = [
				"estado" => "ADVERTENCIA",
				'mensaje' => "ADVERTENCIA: La marca no se pudo eliminar, intente mas tarde"
			];
		}
		echo json_encode($respuesta);
	}
}
