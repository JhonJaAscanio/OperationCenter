<?php
	require_once "modelos/productos_MO.php";

	$f=new funciones();	
	$f->limpiarMatriz($_POST);

	class productos_CO
	{
		function __construct(){}


		 function agregar()
    	{
	        $codigo=$_POST["codigo"];
	        $referencia=$_POST["referencia"];
	        $descripcion=$_POST["descripcion"];
	        $proveedor=$_POST["proveedor"];
	        $categoria=$_POST["categoria"];
	        $cantidad=$_POST["cantidad"];	   
	        $precio_costo=$_POST["precio_costo"];	        
	        $precio_general=$_POST["precio_general"];
	        $precio_mayorista=$_POST["precio_mayorista"];
	        $ubicacion=$_POST["ubicacion"];
         	$conexion=new servidor('A');
			$productos_MO=new productos_MO($conexion);
			$arreglo_productos=$productos_MO->unico($codigo);

     		if($arreglo_productos)
        	{
	            $respuesta = [
	               "estado" => "ADVERTENCIA",
	                'mensaje' => "ADVERTENCIA: El Producto <b>$codigo</b> ya existe"
	            ];
        	}
	        else
	        {
	            $filas_afectadas=$productos_MO->agregar( $codigo,$referencia,$descripcion,$proveedor,$categoria,$cantidad,$precio_costo,$precio_general, $precio_mayorista, $ubicacion);

	            if($filas_afectadas)
	            {
                	$arreglo_productos=$productos_MO->seleccionar("codigo",$codigo);
	              	$codigo = $arreglo_productos[0]->codigo;
	                $referencia = $arreglo_productos[0]->referencia;
	                $descripcion=$arreglo_productos[0]->descripcion;
	                $proveedor=$arreglo_productos[0]->proveedor;
	                $categoria = $arreglo_productos[0]->categoria;
	                $cantidad = $arreglo_productos[0]->cantidad_bodega;
	                $precio_costo = $arreglo_productos[0]->precio_costo;
	                $precio_general=$arreglo_productos[0]->precio_general;
	                $precio_mayorista=$arreglo_productos[0]->precio_mayorista;
	                $fecha_creacion=$arreglo_productos[0]->fecha_creacion;
	                $fecha_actualizacion = $arreglo_productos[0]->fecha_actualizacion;
   	                $respuesta = [
	                    "estado" => "EXITO",
	                    'mensaje' => "EXITO: Registro Guardado",
	                    'codigo' => $codigo,
	                    'descripcion' => $descripcion,
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
			$id_producto=$_POST["id_producto"];
			$codigo=$_POST["codigo"];
	        $referencia=$_POST["referencia"];
	        $descripcion=$_POST["descripcion"];
	        $proveedor=$_POST["proveedor"];
	        $categoria=$_POST["categoria"];
	        $cantidad=$_POST["cantidad"];
	        $precio_costo=$_POST["precio_costo"];	        
	        $precio_general=$_POST["precio_general"];
	        $precio_mayorista=$_POST["precio_mayorista"];
	        $ubicacion=$_POST["ubicacion"];
	        $conexion=new servidor('A');
			$productos_MO=new productos_MO($conexion);
			  
					$filas_afectadas=$productos_MO->actualizar($id_producto,$referencia,$descripcion,$proveedor,$categoria,$cantidad,$precio_costo,$precio_general,$precio_mayorista,$ubicacion);
	                if($filas_afectadas)
	                {
	                    $arreglo_productos=$productos_MO->seleccionar("codigo",$codigo);
	                    $fecha_actualizacion=$arreglo_productos[0]->fecha_actualizacion;

	                    $respuesta = [
	                        "estado" => "EXITO",
	                        'mensaje' => "EXITO: Registro Guardado",
	                        'id_producto' => $id_producto,
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


    	function eliminar()
    	{
    		$id_producto=$_POST["id_producto"];
    		$conexion=new servidor('A');
			$productos_MO=new productos_MO($conexion);
			$arreglo_productos=$productos_MO->eliminar($id_producto);

			if($arreglo_productos)
			{
				$respuesta = [
		            "estado" => "EXITO",
		            'mensaje' => "EXITO: El producto se elimino correctamente"
		            ];
			}else
			{
				$respuesta = [
		            "estado" => "ADVERTENCIA",
		            'mensaje' => "ADVERTENCIA: El producto no se pudo eliminar, intente mas tarde"
		            ];
			}
			echo json_encode($respuesta);

    	}


    
	}