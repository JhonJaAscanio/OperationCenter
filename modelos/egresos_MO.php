<?php 
	class egresos_MO
	{
    	private $conexion;

    	function __construct($conexion)
    	{
        	$this->conexion=$conexion;
    	}


    	function agregar($fecha,$concepto,$valor)
    	{
	        $sql= "INSERT INTO egresos ( fecha,concepto,valor) VALUES ('$fecha','$concepto','$valor')";

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    
	    }

		function actualizar($id_egreso,$fecha,$concepto,$valor)
	    {
	        
	        $sql = "UPDATE egresos SET fecha='$fecha',concepto='$concepto',valor='$valor' WHERE id_egreso='$id_egreso'";

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

	        $sql = "SELECT * FROM egresos $condicion";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    } 

	    function seleccionar_mayor_gasto()
		{
	        $sql = "SELECT concepto, SUM(valor) AS valor FROM egresos  GROUP BY concepto ORDER BY valor DESC LIMIT 10";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    } 


		function eliminar($id_egreso)
		{
	        $sql = "DELETE FROM egresos WHERE id_egreso='$id_egreso'";
	        
	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    } 




    }
