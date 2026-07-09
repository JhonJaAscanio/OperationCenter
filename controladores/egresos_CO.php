<?php 
	require_once "modelos/egresos_MO.php";

	$f=new funciones();	
	$f->limpiarMatriz($_POST);

	class egresos_CO
	{
		function __construct(){}


		 function agregar()
    	{
	        $fecha=$_POST["fecha"];
	        $concepto=$_POST["concepto"];
	        $valor=$_POST["valor"];
         	$conexion=new servidor('A');
			$egresos_MO=new egresos_MO($conexion);
			
	            $filas_afectadas=$egresos_MO->agregar( $fecha,$concepto,$valor);

	            if($filas_afectadas)
	            {
                	
   	                $respuesta = [
	                    "estado" => "EXITO",
	                    'mensaje' => "EXITO: Gasto Guardado"	                  
                	];
            	}
            	else
            	{
	                $respuesta = [
	                  	"estado" => "ADVERTENCIA",
	                    'mensaje' => "ADVERTENCIA: No se guardo el registro"
	                ];
	            }
        	
        echo json_encode($respuesta);
    	}


	 	function actualizar()
    	{
			$id_egreso=$_POST["id_egreso"];			
	        $fecha=$_POST["fecha"];
	        $concepto=$_POST["concepto"];
	        $valor=$_POST["valor"];	       
	        $conexion=new servidor('A');
			$egresos_MO=new egresos_MO($conexion);
			 
					$filas_afectadas=$egresos_MO->actualizar($id_egreso,$fecha,$concepto,$valor);
	                if($filas_afectadas)
	                {
	                   
	                    $respuesta = [
	                        "estado" => "EXITO",
	                        'mensaje' => "EXITO: Gasto Actualizado"
	                    ];
	                }
	                else
	                {
                    $respuesta = [
                        "estado" => "ADVERTENCIA",
                        'mensaje' => "ADVERTENCIA: No ocurrieron cambios"
                    ];
                	}

        		
         echo json_encode($respuesta);
    	}


    	function eliminar()
    	{
    		$id_egreso=$_POST["id_egreso"];
    		$conexion=new servidor('A');
			$egresos_MO=new egresos_MO($conexion);
			$arreglo_egresos=$egresos_MO->eliminar($id_egreso);

			if($arreglo_egresos)
			{
				$respuesta = [
		            "estado" => "EXITO",
		            'mensaje' => "EXITO: La egreso se elimino correctamente"
		            ];
			}else
			{
				$respuesta = [
		            "estado" => "ADVERTENCIA",
		            'mensaje' => "ADVERTENCIA: La egreso no se pudo eliminar, intente mas tarde"
		            ];
			}
			echo json_encode($respuesta);

    	}
	}