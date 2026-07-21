<?php
	require_once "modelos/productos_MO.php";

	$f=new funciones();
	$f->limpiarMatriz($_POST);

	class productos_CO
	{
		function __construct(){}

		// Los campos opcionales llegan como '' cuando no se diligencian; se
		// normalizan a NULL para que las columnas nullable de la BD queden
		// vacias en vez de guardar un string vacio.
		private function opcional($valor)
		{
			return ($valor === '' || $valor === null) ? null : $valor;
		}

		private function datosDesdePost()
		{
			return [
				"codigo" => $_POST["codigo"],
				"codigo_barras" => $this->opcional($_POST["codigo_barras"] ?? ''),
				"referencia" => $_POST["referencia"],
				"descripcion" => $_POST["descripcion"],
				"id_proveedor" => $_POST["id_proveedor"],
				"id_categoria" => $_POST["id_categoria"],
				"id_marca" => $this->opcional($_POST["id_marca"] ?? ''),
				"id_subcategoria" => $this->opcional($_POST["id_subcategoria"] ?? ''),
				"precio_costo" => $_POST["precio_costo"],
				"precio_general" => $_POST["precio_general"],
				"precio_mayorista" => $_POST["precio_mayorista"],
				"precio_promocional" => $this->opcional($_POST["precio_promocional"] ?? ''),
				"cantidad_bodega" => $_POST["cantidad"],
				"ubicacion" => $_POST["ubicacion"],
				"peso" => $this->opcional($_POST["peso"] ?? ''),
				"unidad_medida" => ($_POST["unidad_medida"] ?? '') !== '' ? $_POST["unidad_medida"] : 'UND',
				"impuesto" => ($_POST["impuesto"] ?? '') !== '' ? $_POST["impuesto"] : 0,
				"stock_minimo" => ($_POST["stock_minimo"] ?? '') !== '' ? $_POST["stock_minimo"] : 0,
				"stock_maximo" => $this->opcional($_POST["stock_maximo"] ?? ''),
			];
		}

		 function agregar()
    	{
	        $codigo=$_POST["codigo"];
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
	            $filas_afectadas=$productos_MO->agregar($this->datosDesdePost());

	            if($filas_afectadas)
	            {
                	$arreglo_productos=$productos_MO->seleccionar("codigo",$codigo);
	              	$codigo = $arreglo_productos[0]->codigo;
	                $descripcion=$arreglo_productos[0]->descripcion;
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
	        $conexion=new servidor('A');
			$productos_MO=new productos_MO($conexion);

			$filas_afectadas=$productos_MO->actualizar($id_producto, $this->datosDesdePost());
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


    	function activo()
    	{
    		$id_producto=$_POST["id_producto"];
    		$estado=$_POST["estado"];
    		$conexion=new servidor('A');
			$productos_MO=new productos_MO($conexion);
			$filas_afectadas=$productos_MO->activo($id_producto, $estado);

			if($filas_afectadas)
			{
				$arreglo_productos=$productos_MO->seleccionar("id_producto", $id_producto);
				$respuesta = [
		            "estado" => "EXITO",
		            'mensaje' => "EXITO: Registro Guardado",
		            'id_producto' => $id_producto,
		            'estado_producto' => $arreglo_productos[0]->estado
		            ];
			}else
			{
				$respuesta = [
		            "estado" => "ADVERTENCIA",
		            'mensaje' => "ADVERTENCIA: No ocurrieron cambios"
		            ];
			}
			echo json_encode($respuesta);
    	}

	}
