<?php
	class ventas_MO
	{
    	private $conexion;

    	function __construct($conexion)
    	{
        	$this->conexion=$conexion;
    	}


    	function agregar($nit, $id_factura, $forma_pago,$total,$abono,$notas ,$saldo)
    	{
	        $sql= "INSERT INTO ventas (nit_cliente,id_factura,forma_pago,total,abono,comentario,saldo) VALUES ('$nit','$id_factura','$forma_pago','$total','$abono','$notas', '$saldo')";

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    
	    }

	    function agregarVenta($codigo, $descripcion, $cantidad,$precio ,$total)
    	{
	        $sql= "INSERT INTO ventas_mostrador (codigo,descripcion,cantidad,precio,total) VALUES ('$codigo','$descripcion','$cantidad','$precio','$total')";

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    
	    }

	    function agregarArticulo( $id_factura,$codigo,$cantidad,$descripcion,$precio_unitario, $precio_total)
    	{
	        $sql= "INSERT INTO articulos (id_factura,codigo,cantidad,descripcion,precio_unitario,precio_total) VALUES ('$id_factura','$codigo','$cantidad','$descripcion', '$precio_unitario','$precio_total')";

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    
	    }

		function actualizar($id_venta,$nit, $id_factura, $forma_pago,$total,$abono,$devolucion, $saldo)
	    {
	        
	        $sql = "UPDATE ventas SET nit_cliente='$nit',id_factura='$id_factura', forma_pago='$forma_pago',total='$total', abono='$abono', devolucion='$devolucion',saldo='$saldo' WHERE id_venta='$id_venta'";

	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    }

	    function adiccionarAbonoTotal($id,$valor)
	    {
	    	$sql="UPDATE ventas SET abono=(abono+'$valor'),saldo=(saldo-'$valor') WHERE id_factura='$id'";

	    	$filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    }

	    function agregarAbono($id_factura,$nit,$fecha,$vendedor,$valor)
	    {	
	    	 $sql= "INSERT INTO abonos (id_factura,nit_cliente,fecha,vendedor,valor) VALUES ('$id_factura','$nit','$fecha','$vendedor','$valor')";

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

	        $sql = "SELECT * FROM ventas $condicion";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    } 

	    function seleccionar_mostrador($atributo='',$valor='')
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

	    function seleccionar_fecha($inicial='',$fin='')
		{
	        $condicion="";
	        
	        if($inicial && $fin)
	        {
	            $condicion = " WHERE DATE(fecha_creacion) BETWEEN '$inicial' AND '$fin'";
	        }

	        $sql = "SELECT * FROM ventas_mostrador $condicion";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    }

		function seleccionarMayor($atributo='',$valor='')
		{
	        $condicion="WHERE id_factura = (SELECT MAX(id_factura) FROM ventas) ";
	        
	        if($atributo && $valor)
	        {
	            $condicion = " WHERE $atributo='$valor'";
	        }

	        $sql = "SELECT id_factura FROM ventas $condicion";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    }

	    function seleccionarArticulo($id)
		{
	        	        
	        $sql = "SELECT * FROM articulos WHERE id_factura='$id'";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    }

	     function seleccionarArticuloFecha()
		{
	        $sql = "SELECT * FROM ventas_mostrador ORDER BY fecha_creacion DESC LIMIT 10";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    }
	    /*"SELECT codigo,descripcion, SUM(cantidad) AS Cant FROM ventas_mostrador  GROUP BY codigo ORDER BY cantidad";*/

	     function seleccionarArticuloCantidad()
		{       
	        $sql = "SELECT id_venta_mostrador,codigo,descripcion, SUM(cantidad) AS cantidad, total,fecha_creacion FROM ventas_mostrador  GROUP BY codigo ORDER BY cantidad DESC LIMIT 10";
	        
	        $this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    }

	    function seleccionarAbonos($nit)
	    {
	    	$sql= "SELECT id_factura, fecha, vendedor, valor, fecha_creacion FROM abonos WHERE nit_cliente='$nit'";

	    	$this->conexion->consulta($sql);

	        $arreglo_accesos=$this->conexion->extraerRegistro();

	        return $arreglo_accesos;
	    }

	    function unico($id_factura, $id_venta='')
		{
	    	if (empty($id_Proveedores)) 
	    	{
	        //AGREGAR
	        $sql = "SELECT * FROM ventas
	                 WHERE id_venta='$id_venta'";
	    	}
	    	else
	    	{
	         //ACTUALIAZAR
	        $sql="SELECT * FROM ventas
	                 WHERE id_factura='$id_factura' 
	                 AND id_venta!='$id_venta'";
	    	}

		    $this->conexion->consulta($sql);

		    $arreglo_accesos=$this->conexion->extraerRegistro();

		    return $arreglo_accesos;
		}


		/*function eliminar($id_venta, $id_factura)
		{
	        $sql = "DELETE FROM ventas WHERE id_venta='$id_venta'";
	        
	        $filas_afectadas=$this->conexion->consulta($sql);

	        if($filas_afectadas){
	        	$sql = "DELETE FROM articulos WHERE id_factura='$id_factura'";

	          	$filas_afectadas=$this->conexion->consulta($sql);
	        }

	        return $filas_afectadas;
	    } */

	    function eliminarArticulo($id_articulo)
		{
	        $sql = "DELETE FROM articulos WHERE id_articulo='$id_articulo'";
	        
	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    } 

	    function eliminarVenta($id)
		{
	        $sql = "DELETE FROM ventas_mostrador WHERE id_venta_mostrador='$id'";
	        
	        $filas_afectadas=$this->conexion->consulta($sql);

	        return $filas_afectadas;
	    } 



    }
