<?php
	class clientes_MO
	{
    	private $conexion;

    	function __construct($conexion)
    	{
        	$this->conexion=$conexion;
    	}


    	function agregar($nit, $nombre,$correo,$ciudad,$direccion,$telefono,$nota_credito)
    	{
	        $sql= "INSERT INTO clientes (nit,nombre,correo,ciudad,direccion,telefono,nota_credito) VALUES ('$nit','$nombre','$correo','$ciudad','$direccion','$telefono','$nota_credito')";

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    
	    }

		function actualizar($id_cliente,$nit,$nombre,$correo,$telefono,$nota_credito)
	    {
	        
	        $sql = "UPDATE clientes SET nombre='$nombre',correo='$correo', telefono='$telefono',nota_credito='$nota_credito' WHERE id_cliente='$id_cliente'";

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    }

		function actualizar_saldo($id,$nota_credito)
		    {
		        
		        $sql = "UPDATE clientes SET nota_credito='$nota_credito' WHERE id_cliente='$id'";

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

	        $sql = "SELECT * FROM clientes $condicion";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    } 



	    function unico($nit, $id_cliente='')
		{
	    	if (empty($id_Proveedores)) 
	    	{
	        //AGREGAR
	        $sql = "SELECT * FROM clientes
	                 WHERE nit='$nit'";
	    	}
	    	else
	    	{
	         //ACTUALIAZAR
	        $sql="SELECT * FROM clientes
	                 WHERE nit='$nit'
	                 AND id_cliente!='$id_cliente'";
	    	}

		    $this->conexion->consulta($sql);

		    $arreglo_accesos=$this->conexion->extraerRegistro();

		    return $arreglo_accesos;
		}


		function eliminar($id_cliente)
		{
	        $sql = "DELETE FROM clientes WHERE id_cliente='$id_cliente'";
	        
	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    } 




    }
