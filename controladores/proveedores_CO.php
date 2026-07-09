<?php
	require_once "modelos/proveedores_MO.php";

	$f=new funciones();	
	$f->limpiarMatriz($_POST);

	class proveedores_CO
	{
		function __construct(){}


		 function agregar()
    	{
	        $nombre=$_POST["nombre"];
	        $telefono=$_POST["telefono"];
	        $direccion=$_POST["direccion"];
	        $ciudad=$_POST["ciudad"];	        
         	$conexion=new servidor('A');
			$proveedores_MO=new proveedores_MO($conexion);
			$arreglo_proveedores=$proveedores_MO->unico($nombre);

     		if($arreglo_proveedores)
        	{
	            $respuesta = [
	               "estado" => "ADVERTENCIA",
	                'mensaje' => "ADVERTENCIA: El Proveedor <b>$nombre</b> ya existe"
	            ];
        	}
	        else
	        {
	            $filas_afectadas=$proveedores_MO->agregar( $nombre,$telefono,$direccion,$ciudad);

	            if($filas_afectadas)
	            {
                	$arreglo_proveedores=$proveedores_MO->seleccionar("nombre",$nombre);
	              	$nombre = $arreglo_proveedores[0]->nombre;
	                $telefono = $arreglo_proveedores[0]->telefono;
	                $direccion=$arreglo_proveedores[0]->direccion;
	                $ciudad=$arreglo_proveedores[0]->ciudad;	                
	                $fecha_creacion=$arreglo_proveedores[0]->fecha_creacion;
	                $fecha_actualizacion = $arreglo_proveedores[0]->fecha_actualizacion;
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
			$id_proveedor=$_POST["id_proveedor"];
			$nombre=$_POST["nombre"];
	        $telefono=$_POST["telefono"];
	        $direccion=$_POST["direccion"];
	        $ciudad=$_POST["ciudad"];	        
	        $conexion=new servidor('A');
			$proveedores_MO=new proveedores_MO($conexion);
			//$arreglo_proveedores=$proveedores_MO->unico($codigo, $id_proveedor);



		      //  if($arreglo_proveedores)
		       // {
		      //      $respuesta = [
		       //     "estado" => "ADVERTENCIA",
		       //     'mensaje' => "ADVERTENCIA: El proveedor con codigo <b>$codigo</b> ya existe"
		       ////     ];
		      //  }
		      //  else
		      //  {    
					$filas_afectadas=$proveedores_MO->actualizar($id_proveedor,$nombre,$telefono,$direccion,$ciudad);
	                if($filas_afectadas)
	                {
	                    $arreglo_proveedores=$proveedores_MO->seleccionar("nombre",$nombre);
	                    $fecha_actualizacion=$arreglo_proveedores[0]->fecha_actualizacion;

	                    $respuesta = [
	                        "estado" => "EXITO",
	                        'mensaje' => "EXITO: Registro Guardado",
	                        'id_proveedor' => $id_proveedor,
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
    		$id_proveedor=$_POST["id_proveedor"];
    		$conexion=new servidor('A');
			$proveedores_MO=new proveedores_MO($conexion);
			$arreglo_proveedores=$proveedores_MO->eliminar($id_proveedor);

			if($arreglo_proveedores)
			{
				$respuesta = [
		            "estado" => "EXITO",
		            'mensaje' => "EXITO: El proveedor se elimino correctamente"
		            ];
			}else
			{
				$respuesta = [
		            "estado" => "ADVERTENCIA",
		            'mensaje' => "ADVERTENCIA: El proveedor no se pudo eliminar, intente mas tarde"
		            ];
			}
			echo json_encode($respuesta);

    	}
	}