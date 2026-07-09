<?php
	class compras_MO
	{
    	private $conexion;

    	function __construct($conexion)
    	{
        	$this->conexion=$conexion;
    	}


    	function agregar($id_factura, $proveedor,$total,$abono,$pago)
    	{
	        $sql= "INSERT INTO compras (id_factura,proveedor,total,abono,pago) VALUES ('$id_factura','$proveedor','$total','$abono', '$pago')";

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    
	    }

	     function agregarArticulo( $id_factura,$codigo,$cantidad,$descripcion,$precio_unitario, $precio_total)
    	{
	        $sql= "INSERT INTO articulos_compra (id_factura,codigo,cantidad,descripcion,precio_unitario,precio_total) VALUES ('$id_factura','$codigo','$cantidad','$descripcion', '$precio_unitario','$precio_total')";

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    
	    }

		function actualizar($id_compra, $id_factura, $proveedor,$total,$abono,$pago)
	    {
	        
	        $sql = "UPDATE compras SET id_factura='$id_factura', proveedor='$proveedor',total='$total', abono='$abono', pago='$pago' WHERE id_compra='$id_compra'";

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    }


		function seleccionar($atributo='',$valor='')
		{
	        $condicion="";
	        
	        if($atributo && $valor)
	        {
	            $condicion = " WHERE $atributo='$valor'";
	        }

	        $sql = "SELECT * FROM compras $condicion";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    } 

		function seleccionarArticulo($id)
		{
			$sql = "SELECT * FROM articulos_compra WHERE id_factura='$id'";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
		}

		function seleccionarMayor($atributo='',$valor='')
		{
	        $condicion="WHERE id_factura = (SELECT MAX(id_factura) FROM compras) ";
	        
	        if($atributo && $valor)
	        {
	            $condicion = " WHERE $atributo='$valor'";
	        }

	        $sql = "SELECT id_factura FROM compras $condicion";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    }

	    function unico($id_factura, $id_compra='')
		{
	    	if (empty($id_Proveedores)) 
	    	{
	        //AGREGAR
	        $sql = "SELECT * FROM compras
	                 WHERE id_compra='$id_compra'";
	    	}
	    	else
	    	{
	         //ACTUALIAZAR
	        $sql="SELECT * FROM compras
	                 WHERE id_factura='$id_factura' 
	                 AND id_compra!='$id_compra'";
	    	}

		    $this->conexion->consulta($sql);

		    $arreglo_accesos=$this->conexion->extraerRegistro();

		    return $arreglo_accesos;
		}


		function eliminar($id_compra)
		{
	        $sql = "DELETE FROM compras WHERE id_compra='$id_compra'";
	        
	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    } 

	     function eliminarArticulo($id_articulo)
		{
	        $sql = "DELETE FROM articulos_compra WHERE id_articulo_compra='$id_articulo'";
	        
	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    } 




    }
?>