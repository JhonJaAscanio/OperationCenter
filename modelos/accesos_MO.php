<?php
class accesos_MO extends Repositorio
{
	function __construct($conexion)
	{
		parent::__construct($conexion, "usuarios", "id_usuario");
	}

	function verificarInicioSesion($usuario, $clave)
	{
		$arreglo_accesos = array();

		$sql = "SELECT * FROM {$this->tabla} WHERE usuario = :usuario AND activo = 'SI'";
		$this->conexion->consultaPreparada($sql, [':usuario' => $usuario]);

		if ($arreglo = $this->conexion->extraerRegistro())
		{
			if (password_verify($clave, $arreglo[0]->clave))    // Verificar contraseña encryptada
				$arreglo_accesos = $arreglo;
		}

		return $arreglo_accesos;
	}

	function agregar($usuario, $clave)
	{
		$clave_cifrada = password_hash($clave, PASSWORD_DEFAULT);

		return $this->insertar([
			"usuario" => $usuario,
			"clave" => $clave_cifrada,
		]);
	}

	function actualizar($id_accesos, $usuario, $clave)
	{
		$clave_cifrada = password_hash($clave, PASSWORD_DEFAULT);

		return $this->modificar($id_accesos, [
			"usuario" => $usuario,
			"clave" => $clave_cifrada,
		]);
	}

	// Alias historico de seleccionar(), se conserva porque vistas/menu_VI.php lo invoca.
	function seleccionarUsuario($atributo = '', $valor = '')
	{
		return $this->seleccionar($atributo, $valor);
	}

	function unico($usuario, $id_accesos = '')
	{
		return $this->existeValorUnico("usuario", $usuario, $id_accesos);
	}

	function activo($id_accesos, $activo)
	{
		return $this->modificar($id_accesos, ["activo" => $activo]);
	}

	function capturarIP($nip, $usuario)
	{
		$sql = "INSERT INTO entradas (usuario, ip) VALUES (:usuario, :ip)";

		return $this->conexion->consultaPreparada($sql, [
			':usuario' => $usuario,
			':ip' => $nip,
		]);
	}
}
