<?php
class funciones
{
	function __construct(){}

	function limpiarMatriz(&$matriz)
	{
		foreach($matriz as $key=>&$value)
		{
			if (is_array($value))
			{
				$this->limpiarMatriz($value);
			}
			else
			{
			   $value=$this->limpiaCampo($value);
			}
		}
	}
	
	function limpiaCampo($texto)
	{
	  //Se quitan los espacios en blanco al comienzo y al final
	  $texto=trim($texto);
	  
	  // quita los espacios, si hay mas de uno, en medio de una cadena
	  $valor=preg_replace("([  ]+)"," ",$texto);	  
	  
	  // se transforman todas las etiquetas html
	  @$texto = htmlentities($texto,ENT_QUOTES);

	  // se quitan las comillas dobles y simples
	  $texto=str_replace('"', '', $texto);
	  $texto=str_replace("'", "", $texto);

	  return $texto;	
    }

	// Corta la ejecucion con un JSON de error si el token CSRF de la peticion
	// no coincide con el de la sesion. Usar en rutas que modifican datos.
	function validarCSRF()
	{
		$token_recibido = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
		$token_sesion = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';

		if (!$token_sesion || !hash_equals($token_sesion, $token_recibido))
		{
			$respuesta = [
				"estado" => "ERROR",
				'mensaje' => "ERROR: Token de seguridad invalido o expirado, recargue la pagina"
			];
			echo json_encode($respuesta);
			exit;
		}
	}

    function obtenerIp()
	{
		$ip="";
		if(isset($_SERVER))
		{
			if(!empty($_SERVER['HTTP_CLIENT_IP'])) 
			{
				$ip=$_SERVER['HTTP_CLIENT_IP'];
			}
			elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			else
			{
				$ip=$_SERVER['REMOTE_ADDR'];
			}
		}
		else
		{
			if(getenv('HTTP_CLIENT_IP'))
			{
				$ip=getenv('HTTP_CLIENT_IP');
			} 
			elseif(getenv( 'HTTP_X_FORWARDED_FOR'))
			{
				$ip=getenv('HTTP_X_FORWARDED_FOR');
			}
			else
			{
				$ip=getenv('REMOTE_ADDR');
			}
		} 
		
		if(strstr($ip,','))
		{
			$ip=array_shift(explode(',',$ip));
		}
		return $ip;
	}
}
?>