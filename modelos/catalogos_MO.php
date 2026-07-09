 <?php
	class catalogos_MO
	{
		private $conexion;

		function __construct($conexion)
		{
			$this->conexion = $conexion;
		}

		function seleccionar($atributo = '', $valor = '')
		{
			$condicion = "";

			if ($atributo && $valor) {
				$condicion = " WHERE $atributo='$valor'";
			}

			$sql = "SELECT * FROM catalogos $condicion";

			$this->conexion->consulta($sql);

			$arreglo_catalogos = $this->conexion->extraerRegistro();

			return $arreglo_catalogos;
		}
	}
	?>