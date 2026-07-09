<?php 
	require_once "modelos/ventas_MO.php";
	require_once "modelos/productos_MO.php";
	require_once "modelos/clientes_MO.php";
	require_once "dist/dompdf/src/Autoloader.php";
    use Dompdf\Dompdf;
    use Dompdf\Autoloader;
	$f=new funciones();	
	$f->limpiarMatriz($_POST);

	class ventas_CO 
	{
		function __construct(){}

		function agregarVenta()
		{
			$id_producto=$_POST["id"];
			$cantidad=$_POST["cantidad"];
			$precio=$_POST["precio"];
			$producto=$_POST["producto"];
			$tipo=$_POST["tipo"];
			$conexion=new servidor('A');
			$productos_MO = new productos_MO($conexion);
			$ventas_MO = new ventas_MO($conexion);
			$arreglo_producto = $productos_MO->seleccionar('id_producto',$id_producto);
			$total = $precio * $cantidad;
			$codigo= $arreglo_producto[0]->codigo;
			$descripcion=$arreglo_producto[0]->descripcion;
			$cantidad_bodega = $arreglo_producto[0]->cantidad_bodega;			
			$precio_costo = $arreglo_producto[0]->precio_costo;

			if($tipo==1)
			{
				$producto_existente = $productos_MO->seleccionar('codigo',$producto);
			}else
			{
				$producto_existente = $productos_MO->seleccionar('descripcion',$producto);
			}

	    	if($producto_existente)
		    {
		    		if($cantidad_bodega >= $cantidad)
			    	{

			    		if($precio>$precio_costo)
			    		{
			    			$filas_afectadas=$ventas_MO->agregarVenta($codigo,$descripcion,$cantidad,$precio,$total); 
			    		
				    		if($filas_afectadas) 
						    {		  
						    	$cantidad_restante=$cantidad_bodega - $cantidad;
				        		$arreglo_productos=$productos_MO->disminuir_cantidad($id_producto,$cantidad_restante);
						    	$respuesta = [
						                    "estado" => "EXITO",
						                    'mensaje' => "EXITO: Venta agregada"
					                	];
				        	}
				        	else
				        	{
				                $respuesta = [
				                  	"estado" => "ADVERTENCIA",
				                    'mensaje' => "ADVERTENCIA: No se registro la venta, intentelo de nuevo"
				                ];
				            }
			    		}
			    		else
			    		{
			    			$respuesta = [
			                  	"estado" => "ADVERTENCIA",
			                    'mensaje' => "ADVERTENCIA: El precio no puede descender del precio de costo"
		                	];
			    		}
			    		

			    	}else
			    	{
			    		$respuesta = [
			                  	"estado" => "ADVERTENCIA",
			                    'mensaje' => "ADVERTENCIA: Cantidad insuficiente en bodega"
		                ];
			    	}
	    	}
	    	else
	    	{
	    			$respuesta = [
			                  	"estado" => "ADVERTENCIA",
			                    'mensaje' => "ADVERTENCIA: Producto no existente"
		            ];
	    	}

	    	


			

		            echo json_encode($respuesta);
		} //Fin funcion agregarVenta


		function agregar()
    	{
	        $nit=$_POST["nit"];
	        $id_cliente=$_POST["id_cliente"];
	        $id_factura=$_POST["id_factura"];
	        $forma_pago=$_POST["forma_pago"];
	        $total=$_POST["total"];
	        $abono=$_POST["abono"];	
	        $notas=$_POST["notas"];
			$saldo = 0;  	      	
         	$conexion=new servidor('A');
			$ventas_MO=new ventas_MO($conexion);
			$arreglo_ventas=$ventas_MO->unico($id_factura);

			//Actualizar nota credito en el cliente
			$clientes_MO=new clientes_MO($conexion);
			$arreglo_cliente=$clientes_MO->unico($nit);

     		if($arreglo_ventas)
        	{
	            $respuesta = [
	               "estado" => "ADVERTENCIA",
	                'mensaje' => "ADVERTENCIA: La venta ya existe"
	            ];
        	}
	        else
	        {
	        	
	        	if($abono>$total)
	        	{
	        		$respuesta = [
	                  	"estado" => "ADVERTENCIA",
	                    'mensaje' => "ADVERTENCIA: Digito mal el abono"
	                ];
	        	}
		        else
		        {	

		        	if($forma_pago =='Efectivo')
		        	{
		        		$abono=$total;
		        	}
		        	$saldo_t=$total - $abono;
		        	$filas_afectadas_cliente=$clientes_MO->actualizar_saldo($id_cliente,$saldo_t); 
		            $filas_afectadas=$ventas_MO->agregar( $nit,$id_factura,$forma_pago,$total, $abono,$notas, $saldo_t); 
		            

		            if($filas_afectadas)
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
		                    'mensaje' => "ADVERTENCIA: No se guardo el registro"
		                ];
		            }
		        }
        	}

        echo json_encode($respuesta);
    	}

    	 function agregar_articulo()
    	{

    	    $id_factura=$_POST["id_factura"];
	        $cantidad=$_POST["cantidad"];
	        $articulo=$_POST["articulo"];
	        $precio=$_POST["precio"];
         	$conexion=new servidor('A');
			$ventas_MO=new ventas_MO($conexion);

			$productos_MO=new productos_MO($conexion);
			$arreglo_productos=$productos_MO->seleccionar("codigo",$articulo);
			



     		if(!$arreglo_productos)
        	{
	            $respuesta = [
	               "estado" => "ADVERTENCIA",
	                'mensaje' => "ADVERTENCIA: El producto no existe"
	            ];
        	}
	        else
	        {
	        	$id_producto=$arreglo_productos[0]->id_producto;
	        	$cantidad_bodega=$arreglo_productos[0]->cantidad_bodega;
	        	$descripcion = $arreglo_productos[0]->descripcion;

				if($cantidad <= $cantidad_bodega)
				{
					$cantidad_restante=$cantidad_bodega - $cantidad;
	        		$arreglo_productos=$productos_MO->disminuir_cantidad($id_producto,$cantidad_restante);
	        		$precio_total = $precio * $cantidad ;
	
			            $filas_afectadas=$ventas_MO->agregarArticulo( $id_factura,$articulo,$cantidad,$descripcion, $precio,$precio_total);

			            if($filas_afectadas)
			            {
		   	                $respuesta = [
			                    "estado" => "EXITO",
			                    'mensaje' => "EXITO: Articulo añadido",
			                   
		                	];
		            	}
		            	else
		            	{
			                $respuesta = [
			                  	"estado" => "ADVERTENCIA",
			                    'mensaje' => "ADVERTENCIA: No se añadio el articulo"
			                ];
			            }

			    }else{
			    	 $respuesta = [
			                  	"estado" => "ADVERTENCIA",
			                    'mensaje' => "ADVERTENCIA:Cantidad insuficiente en bodega"
			                ];
			    }
        	}

        echo json_encode($respuesta);
    	}


	 	function actualizar()
    	{
			$id_venta=$_POST["id_venta"];
			$nit=$_POST["nit"];
	        $id_factura=$_POST["id_factura"];
	        $forma_pago=$_POST["forma_pago"];
	        $total=$_POST["total"];	
	        $abono=$_POST["abono"]; 
	        $devolucion=$_POST["devolucion"];	
	        $saldo=$_POST["saldo"];          
	        $conexion=new servidor('A');
			$ventas_MO=new ventas_MO($conexion);
			
					$filas_afectadas=$ventas_MO->actualizar($id_venta,$nit,$id_factura,$forma_pago,$total,$abono,$devolucion,$saldo);
	                if($filas_afectadas)
	                {
	                    $arreglo_ventas=$ventas_MO->seleccionar("id_venta",$id_venta);
	                    $fecha_actualizacion=$arreglo_ventas[0]->fecha_actualizacion;

	                    $respuesta = [
	                        "estado" => "EXITO",
	                        'mensaje' => "EXITO: Registro Guardado",
	                        'id_venta' => $id_venta,
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

        	 
         echo json_encode($respuesta);
    	}


    	/*function eliminar()
    	{
    		$cantidad=$_POST["cantidad"];
    		$codigo=$_POST["codigo"];

    		$id_venta=$_POST["id_venta"];
    		$id_factura=$_POST["id_factura"];
    		$conexion=new servidor('A');
			$ventas_MO=new ventas_MO($conexion);
			$arreglo_ventas=$ventas_MO->eliminar($id_venta,$id_factura);

			$productos_MO=new productos_MO($conexion);
			$arreglo_productos=$productos_MO->seleccionar("codigo",$codigo);

			if($arreglo_ventas)
			{
				$respuesta = [
		            "estado" => "EXITO",
		            'mensaje' => "EXITO: El venta se elimino correctamente"
		            ];
			}else
			{
				$respuesta = [
		            "estado" => "ADVERTENCIA",
		            'mensaje' => "ADVERTENCIA: El venta no se pudo eliminar, intente mas tarde"
		            ];
			}
			echo json_encode($respuesta);

    	} // Fin funcion eliminar*/


    	function eliminarArticulo()
    	{
    		$id_articulo=$_POST["id_articulo"];
    		$cantidad=$_POST["cantidad"];
    		$codigo=$_POST["codigo"];
    		$conexion=new servidor('A');
			$ventas_MO=new ventas_MO($conexion);
			$arreglo_articulos=$ventas_MO->eliminarArticulo($id_articulo);

			$productos_MO=new productos_MO($conexion);
			$arreglo_productos=$productos_MO->seleccionar("codigo",$codigo);

			if($arreglo_articulos)
			{
				$id_producto=$arreglo_productos[0]->id_producto;
				$cantidad_bodega=$arreglo_productos[0]->cantidad_bodega;
				$cantidad_total=$cantidad_bodega+$cantidad;
				$arreglo_productos=$productos_MO->sumar_cantidad($id_producto,$cantidad_total);

				$respuesta = [
		            "estado" => "EXITO",
		            'mensaje' => "EXITO: El Articulo se elimino correctamente"
		            ];
			}else
			{
				$respuesta = [
		            "estado" => "ADVERTENCIA",
		            'mensaje' => "ADVERTENCIA: El Articulo no se pudo eliminar, intente mas tarde"
		            ];
			}
			echo json_encode($respuesta);

    	}// Fin funcion eliminar articulo

    		function eliminarVenta()
    	{
    		$id_venta_mostrador=$_POST["id_venta_mostrador"];    	
    		$conexion=new servidor('A');
			$ventas_MO=new ventas_MO($conexion);
			$arreglo_venta_mostrador=$ventas_MO->seleccionar_mostrador('id_venta_mostrador',  $id_venta_mostrador);
			$arreglo_eliminado=$ventas_MO->eliminarVenta($id_venta_mostrador);

			$productos_MO=new productos_MO($conexion);
			$codigo=$arreglo_venta_mostrador[0]->codigo;
			$cantidad=$arreglo_venta_mostrador[0]->cantidad;
			$arreglo_productos=$productos_MO->seleccionar("codigo",$codigo);
			$id_producto= $arreglo_productos[0]->id_producto;
			if($arreglo_eliminado)
			{
				$cantidad_bodega=$arreglo_productos[0]->cantidad_bodega;
				$cantidad_total=$cantidad_bodega+$cantidad;
				$arreglo_productos=$productos_MO->sumar_cantidad($id_producto,$cantidad_total);

				$respuesta = [
		            "estado" => "EXITO",
		            'mensaje' => "EXITO: El Articulo se elimino correctamente"
		            ];
			}else
			{
				$respuesta = [
		            "estado" => "ADVERTENCIA",
		            'mensaje' => "ADVERTENCIA: El Articulo no se pudo eliminar, intente mas tarde"
		            ];
			}
			echo json_encode($respuesta);

    	}// Fin funcion eliminar venta_mostradro


    	function agregarAbono()
    	{
    		$id_factura=$_POST["id_factura"];
	        $fecha=$_POST["fecha"];
	        $valor=$_POST["valor"];
	        $nit=$_POST["nit"];
	        $vendedor=NOMBRE_EMPRESA;

	        $conexion=new servidor('A');
			$ventas_MO=new ventas_MO($conexion);

			$arreglo_venta=$ventas_MO->seleccionar("id_factura",$id_factura);
			$saldo=$arreglo_venta[0]->saldo;

			if($saldo < $valor)
			{
				$respuesta = [
		            "estado" => "ADVERTENCIA",
		            'mensaje' => "ADVERTENCIA: Abonar el saldo correcto"
		        ];	
			}
			else
			{
				$arreglo_abonos=$ventas_MO->agregarAbono($id_factura,$nit,$fecha,$vendedor,$valor);

				if($arreglo_abonos)
				{
					$arreglo_ventas_abonado=$ventas_MO->adiccionarAbonoTotal($id_factura,$valor);
					$respuesta = [
			            "estado" => "EXITO",
			            'mensaje' => "EXITO: Se agrego el abono correctamente"
		           	];
				}
				else
				{
					$respuesta = [
			            "estado" => "ADVERTENCIA",
			            'mensaje' => "ADVERTENCIA: No agregado, intente mas tarde"
			        ];
				}
			}

			
			echo json_encode($respuesta);
    	}//Fin funcion agregar abono


	}