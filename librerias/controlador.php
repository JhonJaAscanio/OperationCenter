<?php 
class controlador
{
	
	public static function main() 
	{
		if(isset($_REQUEST["ruta"]))
			{
				$ruta=$_REQUEST["ruta"];
			}
		else
			{
				$ruta='';
		    }

		$arreglo_url=array();
		
		if($ruta)
		{

			$arreglo_url=explode("/",$ruta);
			$clase=$arreglo_url[0];
			$metodo=$arreglo_url[1];
			
			array_shift($arreglo_url);
			array_shift($arreglo_url);
			
			$sufijo=substr($clase,-2);
			
			if($sufijo=='VI')
			{
				
				$carpeta="vistas";
			}
			else if($sufijo=='CO')
			{
				$carpeta="controladores";
			}
			else
			{
                $respuesta = [
                    "estado" => "ERROR",
                    'mensaje' => "ERROR: El sufijo no fue encontrado"
				];
				echo json_encode($respuesta);
				exit;
			}		
		}
		else
		{


			$carpeta="vistas";
			$clase="menu_VI";
			$metodo="verMenu";
		}





		
		if(!is_file($carpeta."/".$clase.".php"))
		{
			$respuesta = [
				"estado" => "ERROR",
				'mensaje' => "ERROR: La clase no fue encontrada"
			];
			echo json_encode($respuesta);
			exit;
		}
		
		require_once $carpeta."/".$clase.".php";
		
		$arreglo_metodos=get_class_methods($clase);

		if(!in_array($metodo,$arreglo_metodos))
		{
			$respuesta = [
				"estado" => "ERROR",
				'mensaje' => "ERROR: El metodo no fue encontrado"
			];
			echo json_encode($respuesta);
			exit;		
		}	
		
		$objeto=new $clase();
		
		if(count($arreglo_url))
		{
			$objeto->$metodo($arreglo_url);
		}
		else
		{
			$objeto->$metodo();
		}
	}
}
?>