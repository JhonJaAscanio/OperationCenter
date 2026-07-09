<?php 
	class devoluciones_MO
	{
    	private $conexion;

    	function __construct($conexion)
    	{
        	$this->conexion=$conexion;
    	}


    	function actulizar_devolucion($id)
    	{
	        $sql= "UPDATE ventas_mostrador SET devolucion='SI' WHERE id_venta_mostrador='$id'" ;

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    
	    }

		function actualizar_venta_mostrador($id_venta,$cantidad_actualizar,$precio)
	    {
	        
	        $sql = "UPDATE ventas_mostrador SET  cantidad='$cantidad_actualizar',total=(precio*'$cantidad_actualizar') WHERE id_venta_mostrador='$id_venta'";

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    }


		function insertar_devolucion($codigo,$descripcion,$cantidad,$precio,$total)
		{
	       	$sql= "INSERT INTO ventas_mostrador (codigo,descripcion,cantidad,precio,total,devolucion) VALUES ('$codigo','$descripcion','$cantidad','$precio','$total','SI')";

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

	        $sql = "SELECT * FROM ventas_mostrador $condicion";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    } 

	
    }
