<?php
class clientes_MO extends Repositorio
{
	function __construct($conexion)
	{
		parent::__construct($conexion, "clientes", "id_cliente");
	}

	function agregar($nit, $nombre, $correo, $ciudad, $direccion, $telefono, $nota_credito)
	{
		return $this->insertar([
			"nit" => $nit,
			"nombre" => $nombre,
			"correo" => $correo,
			"ciudad" => $ciudad,
			"direccion" => $direccion,
			"telefono" => $telefono,
			"nota_credito" => $nota_credito,
		]);
	}

	function actualizar($id_cliente, $nit, $nombre, $correo, $telefono, $nota_credito)
	{
		return $this->modificar($id_cliente, [
			"nombre" => $nombre,
			"correo" => $correo,
			"telefono" => $telefono,
			"nota_credito" => $nota_credito,
		]);
	}

	function actualizar_saldo($id, $nota_credito)
	{
		return $this->modificar($id, ["nota_credito" => $nota_credito]);
	}

	function unico($nit, $id_cliente = '')
	{
		return $this->existeValorUnico("nit", $nit, $id_cliente);
	}
}
