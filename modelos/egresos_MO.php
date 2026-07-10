<?php
class egresos_MO extends Repositorio
{
	function __construct($conexion)
	{
		parent::__construct($conexion, "egresos", "id_egreso");
	}

	function agregar($fecha, $concepto, $valor)
	{
		return $this->insertar([
			"fecha" => $fecha,
			"concepto" => $concepto,
			"valor" => $valor,
		]);
	}

	function actualizar($id_egreso, $fecha, $concepto, $valor)
	{
		return $this->modificar($id_egreso, [
			"fecha" => $fecha,
			"concepto" => $concepto,
			"valor" => $valor,
		]);
	}

	function seleccionar_mayor_gasto()
	{
		$sql = "SELECT concepto, SUM(valor) AS valor FROM {$this->tabla} GROUP BY concepto ORDER BY valor DESC LIMIT 10";

		$this->conexion->consultaPreparada($sql);

		return $this->conexion->extraerRegistro();
	}
}
