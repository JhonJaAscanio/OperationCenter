<?php
	require_once "modelos/clientes_MO.php";

	$f=new funciones();	
	$f->limpiarMatriz($_POST);

	class clientes_CO
	{
		function __construct(){}


		 function agregar()
    	{
	        $nit=$_POST["nit"];
	        $nombre=$_POST["nombre"];
	        $correo=$_POST["correo"];
	        $departamento=$_POST["departamento"];
	        $ciudad=$_POST["ciudad"];
	        $ubicacion=$ciudad." - ".$departamento; 
	        $direccion=$_POST["direccion"];
	        $telefono=$_POST["telefono"];
	        $nota_credito=$_POST["nota_credito"];		        
         	$conexion=new servidor('A');
			$clientes_MO=new clientes_MO($conexion);
			$arreglo_clientes=$clientes_MO->unico($nit);

     		if($arreglo_clientes)
        	{
	            $respuesta = [
	               "estado" => "ADVERTENCIA",
	                'mensaje' => "ADVERTENCIA: El Cliente <b>$nombre</b> ya existe"
	            ];
        	}
	        else
	        {
	            $filas_afectadas=$clientes_MO->agregar( $nit,$nombre,$correo,$ubicacion,$direccion,$telefono, $nota_credito);

	            if($filas_afectadas)
	            {
                	$arreglo_clientes=$clientes_MO->seleccionar("nit",$nit);
	                $nombre = $arreglo_clientes[0]->nombre;	                               
	                $fecha_creacion=$arreglo_clientes[0]->fecha_creacion;
	                $fecha_actualizacion = $arreglo_clientes[0]->fecha_actualizacion;
   	                $respuesta = [
	                    "estado" => "EXITO",
	                    'mensaje' => "EXITO: Registro Guardado",
	                    'nombre' => $nombre,	                    
	                    'fecha_creacion' => $fecha_creacion,
	                    'fecha_actualizacion' => $fecha_actualizacion
                	];
            	}
            	else
            	{
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
			$id_cliente=$_POST["id_cliente"];
			$nit=$_POST["nit"];
	        $nombre=$_POST["nombre"];
	        $correo=$_POST["correo"];
	        $telefono=$_POST["telefono"];	
	        $nota_credito=$_POST["nota_credito"];        
	        $conexion=new servidor('A');
			$clientes_MO=new clientes_MO($conexion);
			//$arreglo_clientes=$clientes_MO->unico($codigo, $id_cliente);



		      //  if($arreglo_clientes)
		       // {
		      //      $respuesta = [
		       //     "estado" => "ADVERTENCIA",
		       //     'mensaje' => "ADVERTENCIA: El cliente con codigo <b>$codigo</b> ya existe"
		       ////     ];
		      //  }
		      //  else
		      //  {    
					$filas_afectadas=$clientes_MO->actualizar($id_cliente,$nit,$nombre,$correo,$telefono,$nota_credito);
	                if($filas_afectadas)
	                {
	                    $arreglo_clientes=$clientes_MO->seleccionar("nit",$nit);
	                    $fecha_actualizacion=$arreglo_clientes[0]->fecha_actualizacion;

	                    $respuesta = [
	                        "estado" => "EXITO",
	                        'mensaje' => "EXITO: Registro Guardado",
	                        'id_cliente' => $id_cliente,
	                        'fecha_actualizacion' => $fecha_actualizacion
	                    ];
	                }
	                else
	                {
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
    		$id_cliente=$_POST["id_cliente"];
    		$conexion=new servidor('A');
			$clientes_MO=new clientes_MO($conexion);
			$arreglo_clientes=$clientes_MO->eliminar($id_cliente);

			if($arreglo_clientes)
			{
				$respuesta = [
		            "estado" => "EXITO",
		            'mensaje' => "EXITO: El cliente se elimino correctamente"
		            ];
			}else
			{
				$respuesta = [
		            "estado" => "ADVERTENCIA",
		            'mensaje' => "ADVERTENCIA: El cliente no se pudo eliminar, intente mas tarde"
		            ];
			}
			echo json_encode($respuesta);

    	}
	}