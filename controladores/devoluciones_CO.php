<?php 
	require_once "modelos/devoluciones_MO.php";

	$f=new funciones();	
	$f->limpiarMatriz($_POST);

	class devoluciones_CO
	{
		function __construct(){} 


		 function devolucion_total()
    	{
	        $id_venta=$_POST["id_venta"];
         	$conexion=new servidor('A');
			$devoluciones_MO=new devoluciones_MO($conexion);
			$arreglo_ventas=$devoluciones_MO->actulizar_devolucion($id_venta);

     		if($arreglo_ventas)
        	{
	            $respuesta = [
	              "estado" => "EXITO",
	                'mensaje' => "EXITO: Registro Actualizado",
	            ];
        	}
	        else
	        {
	           	 $respuesta = [
	               "estado" => "ADVERTENCIA",
	                'mensaje' => "ADVERTENCIA: Vuelva a intentar"
	            	];
        	}

        echo json_encode($respuesta);
    	}


	 	function devolucion_cantidad()
    	{
    		$id_venta=$_POST["id_venta"];
			$cantidad_actualizar=$_POST["cantidad_actualizar"];			
	        $cantidad_devolver=$_POST["cantidad_devolver"];	  
	        $precio=$_POST["precio"];	     
	        $conexion=new servidor('A');
			$devoluciones_MO=new devoluciones_MO($conexion);
			$arreglo_ventas_mostrador=$devoluciones_MO->actualizar_venta_mostrador($id_venta,$cantidad_actualizar,$precio);


				if($arreglo_ventas_mostrador)
				{  
					$arreglo=$devoluciones_MO->seleccionar("id_venta_mostrador",$id_venta);
					$codigo=$arreglo[0]->codigo;
					$descripcion=$arreglo[0]->descripcion;
					$precio=$arreglo[0]->precio;
					$total=$cantidad_devolver*$precio; 

					$arreglo_ventas_mostrador=$devoluciones_MO->insertar_devolucion($codigo,$descripcion,$cantidad_devolver,$precio,$total);

					if($arreglo_ventas_mostrador)
					{
						  $respuesta = [
	                        "estado" => "EXITO",
	                        'mensaje' => "EXITO: Registro Guardado"
	                    ];
					}
					else
					{
						 $respuesta = [
	                        "estado" => "ADVERTENCIA",
	                        'mensaje' => "ADVERTENCIA: No ocurrieron cambios"
	                    ];
					}
					
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


	}