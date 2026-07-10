<?php
require_once "configuraciones.php";
require_once "servidor.php";
require_once "Repositorio.php";
include '../modelos/ventas_MO.php';
include '../modelos/clientes_MO.php';
$id_factura=$_GET['id_factura'];
$conexion=new servidor('A');
$ventas = new ventas_MO($conexion);
$facturas=$ventas->seleccionar("id_factura",$id_factura);
$articulos=$ventas->seleccionarArticulo($id_factura);

$id_cliente=$facturas[0]->nit_cliente;
$fecha=$facturas[0]->fecha_creacion;

$clientes=new clientes_MO($conexion);
$arreglo_cliente=$clientes->seleccionar("nit",$id_cliente);
$nom_cliente=$arreglo_cliente[0]->nombre;
$ciudad_cliente=$arreglo_cliente[0]->ciudad;
$direccion_cliente=$arreglo_cliente[0]->direccion;
$correo_cliente=$arreglo_cliente[0]->correo;
$telefono_cliente=$arreglo_cliente[0]->telefono;



$subtotal=0;

$output = '';
$output .= '
	<table width="100%"  border="1" cellpadding="5" cellspacing="0">
		
		<tr>
			<td colspan="2">
				<table width="100%" cellpadding="5">

					<tr>
						<td width="15%" style="text-align: center;" > <img src="../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 80px; height: 80px; ">
						</td>
						<td width="20%" align="center" style="font-size:35px; color:#B9BDC1; font-weight: bold;">FACTURA</td>
						
						<td width="70%"  style="border: black 3px solid;">
							<b>'.NOMBRE_EMPRESA.'</b><br />
							<b>Nit:</b> '.NIT_EMPRESA.'<br />
							<b>Telefono:</b> '.TELEFONO_EMPRESA.'<br />
							<b>Direccion:</b> '.DIRECCION_EMPRESA.'<br />
						</td>
					</tr>
					<tr>
						<td width="65%">
							<b>FACTURAR A:</b><br />
							<b>Nombre:</b> '.$nom_cliente.' <br /> 
							<b>Dirección:</b> '.$ciudad_cliente.', '.$direccion_cliente.'<br />
							<b>Telefono:</b> '.$telefono_cliente.' <br /> 
							<b>Correo:</b> '.$correo_cliente.' <br /> 
						</td>
						<td width="20%"></td>
						<td width="35%">         
							<b>Factura no. :</b> '.$id_factura.'<br />
							<b>Fecha de la factura :</b>  '.$fecha.'<br />
						</td>
					</tr>
				</table>
	<br />
				<table width="100%" border="1" cellpadding="5" cellspacing="0">
					<tr>
						<th align="left">Sr No.</th>
						<th align="left">Código Producto</th>
						<th align="left">Nombre Item</th>
						<th align="left">Cantidad</th>
						<th align="left">Precio</th>
						<th align="left">Cantidad real</th> 
					</tr>';
					$count = 0;
					foreach ($articulos as $articulo) {
						$count++;
						$output .= '
					<tr>
						<td align="left">' . $count . '</td>
						<td align="left">'.$articulo->codigo.'</td>
						<td align="left">'.$articulo->descripcion.'</td>
						<td align="left">'.$articulo->cantidad.'</td>
						<td align="left">'.$articulo->precio_unitario.'</td>
						<td align="left">'.$articulo->precio_total.'</td>   
					</tr>';
					$subtotal=$subtotal+$articulo->precio_total;
					}
$output .= '
					<tr>
						<td align="right" colspan="5"><b>Sub Total</b></td>
						<td align="left"><b>'.$subtotal.'</b></td>
					</tr>
					<tr>
						<td align="right" colspan="5"><b>Porcentaje Impuestos :</b></td>
						<td align="left">'.IVA.' %</td>
					</tr>
					<tr>
						<td align="right" colspan="5">Monto Impuestos: </td>
						<td align="left">'.$subtotal*IVA.'</td>
					</tr>
					<tr>
						<td align="right" colspan="5">Total: </td>
						<td align="left">'.($subtotal+($subtotal*IVA)).'</td>
					</tr>
					<tr>';
$output .= '
				</table>
			</td>
		</tr>
	</table>';


$invoiceFileName = 'Factura ConfiguroWeb-iddelafact.pdf';
require_once '../dist/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml(html_entity_decode($output));
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream($invoiceFileName, array("Attachment" => false));
