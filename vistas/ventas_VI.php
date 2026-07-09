<?php
class ventas_VI
{
	function __construct()
	{
	}

	function  listar()
	{
		require_once "modelos/ventas_MO.php";


		$conexion = new servidor('A');
		$ventas_MO = new ventas_MO($conexion);
		$arreglo_ventas = $ventas_MO->seleccionar();



?>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Ventas</h3>
				<button id="modal_factura" type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#ventana_modal" onclick="vistaAgregarFactura()"> <i class="far fa-plus-square"></i> Nueva Factura</button>
				<i class="fas fa-binoculars fa-2x float-right" style="cursor:pointer; margin: auto 25px auto auto; color: black;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaConsultarProducto()" title="Consultar producto"></i>

			</div> <!-- /.card-header -->
			<div class="card-body">
				<table id="listar_ventas" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Estado</th>
							<th>ID Venta</th>
							<th>NIT Cliente</th>
							<th>Id Factura</th>
							<th>Forma de Pago</th>
							<th>Total</th>
							<th>Abono</th>
							<th>Devolucion</th>
							<th>Saldo</th>
							<th style="text-align:center;">Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($arreglo_ventas) {
							foreach ($arreglo_ventas as $objeto_ventas) {
								$id_venta = $objeto_ventas->id_venta;
								$nit = $objeto_ventas->nit_cliente;
								$id_factura = $objeto_ventas->id_factura;
								$forma_pago = $objeto_ventas->forma_pago;
								$total = $objeto_ventas->total;
								$abono = $objeto_ventas->abono;
								$devolucion = $objeto_ventas->devolucion;
								$saldo = $objeto_ventas->saldo;
								$fecha_creacion = $objeto_ventas->fecha_creacion;
								$fecha_actualizacion = $objeto_ventas->fecha_actualizacion;

								if ($saldo == 0) {
									$icono = "fas fa-thumbs-up";
									$color = "color:green;";
									$titulo = "Pagado";
									$estado = "SI";
								} else {
									$icono = "fas fa-thumbs-down";
									$titulo = "No pagado";
									$estado = "NO";
									$color = "color:red;";
								}

						?>
								<tr>
									<td style="text-align:center;">
										<i class="<?php echo $icono; ?>" style="cursor:pointer; <?php echo $color; ?>" title="<?php echo $titulo; ?>"> <?php echo "$titulo"; ?>
										</i>
									</td>
									<td><?php echo $id_venta; ?></td>
									<td><?php echo $nit; ?></td>
									<td><?php echo $id_factura; ?></td>
									<td><?php echo $forma_pago; ?></td>
									<td><?php echo number_format($total); ?></td>
									<td><?php echo number_format($abono); ?></td>
									<td><?php echo number_format($devolucion); ?></td>
									<td><?php echo number_format($saldo); ?></td>
									<td style="text-align:center;">
										<i class="fas fa-edit" style="cursor:pointer; margin-right: 10px; color: #0C6766;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaActualizarVenta('<?php echo $id_venta; ?>')" title="Actualizar"></i>
										<i class="far fa-eye" style="cursor:pointer; margin-right: 10px;color: #697E0A;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaConsultarVenta('<?php echo $id_venta; ?>')" title="Consultar"></i>
										<a class="fas fa-print" href="librerias/imprimir.php?id_factura=<?php echo $id_factura; ?>" style="cursor:pointer; margin-right: 10px; color: blue;" title="Imprimir PDF"></a>
										<!--	<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaEliminarVenta('<?php //echo $id_venta;
																																																					?>','<?php //echo $id_factura;
																																																												?>')" title="Eliminar "></i>  -->

									</td>
								</tr>
						<?php
							}
						}
						?>
					</tbody>
				</table>
			</div> <!-- /.card-body -->
		</div><!-- /.card -->


		<script>
			function vistaAgregarFactura() {
				var fecha = new Date(); //Fecha actual
				var mes = fecha.getMonth() + 1; //obteniendo mes
				var dia = fecha.getDate(); //obteniendo dia
				var ano = fecha.getFullYear(); //obteniendo año
				if (dia < 10)
					dia = '0' + dia; //agrega cero si el menor de 10
				if (mes < 10)
					mes = '0' + mes //agrega cero si el menor de 10  

				var parametros = {
					"ruta": "ventas_VI/agregarFactura"
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Factura de Venta');
					$('#contenido_modal').html(respuesta);
					document.getElementById('date').innerHTML = ano + "-" + mes + "-" + dia;
				});
			}

			function vistaActualizarVenta(id_venta) {
				var parametros = {
					"ruta": "ventas_VI/actualizar",
					"id_venta": id_venta
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Actualizar venta');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaConsultarVenta(id_venta) {
				var parametros = {
					"ruta": "ventas_VI/consultar",
					"id_venta": id_venta
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Consultar Factura');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaConsultarProducto() {
				var parametros = {
					"ruta": "ventas_VI/consultar_producto"
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Consultar');
					$('#contenido_modal').html(respuesta);
				});
			}
			//  data_table_ventas=organizarTabla({id:"listar_ventas"});

			$(document).ready(function() {
				$('#listar_ventas').DataTable({
					"order": [
						[1, "desc"]
					]
				});
			});
		</script>
	<?php
	} //Fin funcion listar


	function agregarFactura()
	{
		require_once "modelos/ventas_MO.php";
		require_once "modelos/clientes_MO.php";
		require_once "modelos/productos_MO.php";
		$conexion = new servidor('A');
		$clientes_MO = new clientes_MO($conexion);
		$arreglo_clientes = $clientes_MO->seleccionar();

		$ventas_MO = new ventas_MO($conexion);
		$arreglo_ventas = $ventas_MO->seleccionarMayor();
		if (!$arreglo_ventas) {
			$id_factura = NFACTURA_INICIAL;
		} else {
			$id_factura = ($arreglo_ventas[0]->id_factura) + 1;
		}


		$arreglo_articulos = $ventas_MO->seleccionarArticulo($id_factura);

		$productos_MO = new productos_MO($conexion);
		$arreglo_productos = $productos_MO->seleccionar();
		$total = 0;

	?>
		<!-- Form Element sizes -->
		<div class="row ">
			<div class="col-5" style="padding-left: 100px; padding-top: 25px;">
				<img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 80px; height: 80px;">
			</div>

			<div class="col-3">
				<a class="font-weight-bold"><?php echo NOMBRE_EMPRESA; ?></a><br>
				<a><?php echo NIT_EMPRESA; ?></a><br>
				<a><?php echo DIRECCION_EMPRESA; ?></a><br>
				<a><?php echo TELEFONO_EMPRESA; ?></a>
			</div>

			<div class="col-3  border border-success">
				<h2 class="font-weight-bold text-center">N° de Factura:</h2>
				<h1 class="text-center"> <?php echo $id_factura; ?></h1>
				<h3 id="date" class="font-weight-bold text-center"></h3>
			</div>
		</div><br><br>
		<form id="formulario_agregar_factura">
			<div class="card card-success">
				<div class="card-header">
					<h3 class="card-title">Datos Cliente</h3>
				</div>
				<div class="card-body">

					<input type="hidden" id="id_factura" value="<?php echo $id_factura; ?>">
					<div class="row">
						<div class="col">
							<label style="display:flex;justify-content: center; font-size: 15px">Cliente</label>
							<select class="form-control form-control-lg" name="nit" id="nit" style=" text-align:center" onchange="camposCliente(this)" tabindex="1">
								<option value="" disabled selected>Cliente:</option>
								<?php
								if ($arreglo_clientes) {
									foreach ($arreglo_clientes as $objeto_cliente) {
										$id_cliente = $objeto_cliente->id_cliente;
										$nit_client = $objeto_cliente->nit;
										$nombre = $objeto_cliente->nombre;
										$correo = $objeto_cliente->correo;
										$telefono = $objeto_cliente->telefono;
										$ciudad = $objeto_cliente->ciudad;
										$direccion = $objeto_cliente->direccion;
								?>
										<option value="<?php echo $id_cliente; ?>,<?php echo $nombre; ?>,<?php echo $correo; ?>,<?php echo $telefono; ?>,<?php echo $ciudad; ?>,<?php echo $direccion; ?>,<?php echo $nit_client; ?>"><?php echo $nombre; ?></option>
								<?php
									}
								}
								?>
							</select> <br>
							<label style="display:flex;justify-content: center; font-size: 15px">Correo</label>
							<input class="form-control form-control-lg" type="text" id="correo" name="correo" placeholder="Correo" autocomplete="on" style=" text-align:center" readonly tabindex="-1"> <br>
						</div>
						<input type="hidden" id="id_cliente" value="<?php echo $id_cliente; ?>" tabindex="-1">
						<div class="col">
							<label style="display:flex;justify-content: center; font-size: 15px">Ciudad</label>
							<input class="form-control form-control-lg" type="text" id="ciudad" name="ciudad" placeholder="Ciudad" autocomplete="on" style=" text-align:center" readonly tabindex="-1"> <br>
							<label style="display:flex;justify-content: center; font-size: 15px">Telefono</label>
							<input class="form-control form-control-lg" type="text" id="telefono" name="telefono" placeholder="Telefono" autocomplete="on" style=" text-align:center" readonly tabindex="-1">

						</div>

						<div class="col">
							<label style="display:flex;justify-content: center; font-size: 15px">Direccion</label>
							<input class="form-control form-control-lg" type="text" id="direccion" name="direccion" placeholder="Direccion" autocomplete="on" style=" text-align:center" readonly tabindex="-1"> <br>
							<label style="display:flex;justify-content: center; font-size: 15px">Nit</label>
							<input class="form-control form-control-lg" type="text" id="nit_client" name="nit_client" placeholder="Nit" autocomplete="on" style=" text-align:center" readonly tabindex="-1">
							< </div>


						</div>
					</div><!-- /.card-body -->
				</div><!-- Form Element sizes -->

				<div class="card card-success">
					<div class="card-header">
						<h3 class="card-title">Datos Factura</h3>
					</div>
					<div class="card-body">


						<div class="row">
							<div class="col-8">
								<div class="form-group">
									<div class="input-group">
										<span>
											<select type="submit" class="form-control" onchange="referencia(value)" id="tipo" tabindex="-1">
												<option value="1">Codigo</option>
												<option value="2">Descripcion</option>
											</select>
										</span>
										<input type="text" id="articulo" class="form-control" placeholder="Buscar " onkeypress="pulsar(event)" tabindex="2">
									</div>
								</div>
							</div>

							<div class="col-4 text-center">
								<button type="button" class="btn btn-primary btn-lg" onclick="controladorAgregarArticulo()"><i class="fas fa-save" tabindex="-1"></i> Agregar articulo</button>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2">Cantidad
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-cart-plus"></i>
										</span>
									</div>
									<input type="number" id="cantidad" class="form-control" value="1" style=" text-align:center" onkeypress="visualizarTotal()" tabindex="3">
								</div> <!-- /input-group -->
							</div> <!-- /.col-lg-6 -->

							<div class="col-lg-4"> Precio
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">$</span>
									</div>
									<input id="precio" type="text" class="form-control" style=" text-align:center" value="0" onkeypress="visualizarTotal()" tabindex="4">
								</div><!-- /input-group -->
							</div><!-- /.col-lg-6 -->

							<div class="col-lg-4"> Total
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">$</span>
									</div>
									<input id="total_articulo" type="text" class="form-control" style=" text-align:center" value="0" readonly tabindex="-1">
								</div><!-- /input-group -->
							</div><!-- /.col-lg-6 -->

						</div><br>

						<div class="row">

							<table id="listar_articulo" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Codigo</th>
										<th>Cantidad</th>
										<th>Descripcion</th>
										<th>Precio Unitario</th>
										<th>Precio Total</th>
										<th style="text-align:center;">Quitar</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if ($arreglo_articulos) {
										foreach ($arreglo_articulos as $objeto_articulos) {
											$id_articulo = $objeto_articulos->id_articulo;
											$id_factura = $objeto_articulos->id_factura;
											$codigo = $objeto_articulos->codigo;
											$cantidad = $objeto_articulos->cantidad;
											$descripcion = $objeto_articulos->descripcion;
											$precio_unitario = $objeto_articulos->precio_unitario;
											$precio_total = $objeto_articulos->precio_total;


									?>
											<tr>
												<td><?php echo $codigo; ?></td>
												<td><?php echo $cantidad; ?></td>
												<td><?php echo $descripcion; ?></td>
												<td><?php echo $precio_unitario; ?></td>
												<td><?php echo $precio_total; ?></td>
												<td style="text-align:center;">
													<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="eliminarArticulo('<?php echo $id_articulo; ?>','<?php echo $cantidad; ?>','<?php echo $codigo; ?>')" title="Eliminar "></i>

												</td>
											</tr>
									<?php
										}
									}
									?>
								</tbody>
							</table>

						</div>
						<br>

						<div class="row ">
							<div class="col-7">
								<h4>Formas de pago</h4>
								<hr>
								<div class="row">
									<div class="col">
										<select class="form-control" id="forma_pago" name="forma_pago" style=" text-align:center;" onchange="forma(value)" tabindex="-1">
											<option value="Efectivo">Efectivo</option>
											<option value="Credito">Credito</option>
										</select>
									</div>
									<div class="col">
										<input class="form-control" type="text" id="abono" name="abono" autocomplete="on" style="display: none; text-align:center" value="0">
									</div>
								</div>
								<h3>Observaciones: </h3>
								<div class="form-group">
									<textarea class="form-control txt" rows="5" name="notas" id="notas" placeholder="Observaciones" tabindex="5"></textarea>
								</div>
								<button type="button" class="btn btn-primary float-none btn-lg" onclick="controladorAgregarVentas()"><i class="fas fa-save" tabindex="6"></i> Guardar Factura</button>
							</div>
							<div class="col" style="padding-right:5%;">
								<div class="form-group">
									<div class="input-group mb-3 justify-content-end">
										<label>Subtotal: &nbsp;</label>
										<span class="input-group-text">$</span>
										<?php
										if ($arreglo_articulos) {
											foreach ($arreglo_articulos as $objeto_articulos) {
												$precio_total = $objeto_articulos->precio_total;
												$total += $precio_total;
											}
											echo '<input id="total" name="total" form-control-lg" style=" text-align:center" readonly value="' . $total . '">';
										} else {
											echo '<input id="total" name="total"  form-control-lg" style=" text-align:center" readonly value="0">';
										}
										?>
									</div>
								</div>

								<div class="form-group">
									<div class="input-group mb-3 justify-content-end">
										<label>Monto impuestos: &nbsp;</label>
										<span class="input-group-text">$</span>
										<input value="<?php echo ($total * IVA)  ?>" readonly name="taxAmount" id="taxAmount" placeholder="Monto impuestos" style=" text-align:center">
									</div>
								</div>
								<div class="form-group">
									<div class="input-group mb-3 justify-content-end">
										<label>Total Neto: &nbsp;</label>
										<span class="input-group-text">$</span>
										<input value="<?php echo ($total + ($total * IVA));  ?>" readonly name="totalAftertax" id="totalAftertax" placeholder="Total" style=" text-align:center">
									</div>
								</div>
							</div>

						</div> <!-- FIN ROW -->
						<hr>

					</div><!-- /.card-body -->
				</div><!-- Form Element sizes -->
		</form>

		<!-- SCRIPT generar articulo en el input y mostrar subtotal con la cantidad -->
		<script>
			// Funcion para llenar el campo input de buscar con los productos
			//	$(function(){
			var array_producto = new Array();
			var articulo = <?= json_encode($arreglo_productos) ?>;
			articulo.forEach(function(word) {
				array_producto.push(word.codigo);
			});

			$("#articulo").autocomplete({
				source: array_producto
			}).css('z-index', 250000);
			//	});
			//--------------------------------------

			function referencia(val) {
				if (val == 1) {
					$(function() {
						var array_producto = new Array();
						var articulo = <?= json_encode($arreglo_productos) ?>;
						articulo.forEach(function(word) {
							array_producto.push(word.codigo);
						});

						$("#articulo").autocomplete({
							source: array_producto
						});
					});
				} else {
					$(function() {
						var array_producto = new Array();
						var articulo = <?= json_encode($arreglo_productos) ?>;
						articulo.forEach(function(word) {
							array_producto.push(word.descripcion);
						});
						console.log(array_producto);
						$("#articulo").autocomplete({
							source: array_producto
						});
					});
				}
			}

			$("#articulo").blur(function() {
				pulsar(13);
			});
			$("#cantidad").blur(function() {
				visualizarTotal();
			});
			$("#precio").blur(function() {
				visualizarTotal();
			});

			$("#precio").keypress(function(e) {
				if (e.which == 13) {
					controladorAgregarArticulo();
				}
			});
			$('#precio').keyup(function(e) {
				var t = ($('#precio').val()) * ($('#cantidad').val());
				var total = new Intl.NumberFormat('es-ES').format(t);
				$('#total_articulo').val(total);
			});




			function visualizarTotal() {
				var t = ($('#precio').val()) * ($('#cantidad').val());
				var total = new Intl.NumberFormat('es-ES').format(t);
				$('#total_articulo').val(total);
			}

			function pulsar(e) {

				if (e.keyCode === 13 && !e.shiftKey || e == 13) {
					/// e.preventDefault();
					var articulo = <?= json_encode($arreglo_productos) ?>;
					var tipo;

					articulo.forEach(function(word) {

						if ($('#tipo').val() == 2) {
							if (word.descripcion == $('#articulo').val()) {
								$('#id').val(word.id_producto);
								$('#precio').val(word.precio_general);
							}
						} else {
							if (word.codigo == $('#articulo').val()) {
								$('#id').val(word.id_producto);
								$('#precio').val(word.precio_general);
							}
						}
					});
					visualizarTotal();
				}
			}
		</script><!-- Fin SCRIPT generar articulo en el input y mostrar subtotal con la cantidad -->
		<script>
			function camposCliente(datos) {
				var data = datos.value.split(',');
				$('#id_cliente').val(data[0]);
				$('#correo').val(data[2]);
				$('#telefono').val(data[3]);
				$('#ciudad').val(data[4]);
				$('#direccion').val(data[5]);
				$('#nit_client').val(data[6]);
			}

			function forma(forma) {

				var sel = $("forma_pago").val();
				if (forma === 'Credito') {
					$("#labono").css("display", "block");
					$("#abono").css("display", "block");
					$("#lsaldo").css("display", "block");
					$("#saldo").css("display", "block");
				} else if (forma == 'Efectivo') {
					$("#labono").css("display", "none");
					$("#abono").css("display", "none");
					$("#lsaldo").css("display", "none");
					$("#saldo").css("display", "none");
				}
			}

			function controladorAgregarArticulo() {
				var fecha = new Date(); //Fecha actual
				var mes = fecha.getMonth() + 1; //obteniendo mes
				var dia = fecha.getDate(); //obteniendo dia
				var ano = fecha.getFullYear(); //obteniendo año
				if (dia < 10)
					dia = '0' + dia; //agrega cero si el menor de 10
				if (mes < 10)
					mes = '0' + mes; //agrega cero si el menor de 10  

				var forma_pago = $('#forma_pago').val(); //Mantener valor de forma de pago
				var abono = $('#abono').val(); //Mantener valor de abono agregado
				var cliente = $('#nit').val(); //Mantener valor del cliente seleccionado
				var ciudad = $('#ciudad').val();
				var direccion = $('#direccion').val();
				var telefono = $('#telefono').val();
				var correo = $('#correo').val();
				var nit = $('#nit_client').val();
				var id_cliente = $('#id_cliente').val();

				//No se puede realizar la añadidura de un articulo si se manda los input en blanco
				if ($('#formulario_agregar_factura')[0].elements.cantidad.value == "" || $('#formulario_agregar_factura')[0].elements.articulo.value == "") {
					alert("Debe ingresar el articulo y la cantidad");
				} else {
					var cadena = $('#formulario_agregar_factura').serialize();
					var parametros = {
						"ruta": "ventas_CO/agregar_articulo",
						"nit": $('#formulario_agregar_factura')[0].elements.nit.value,
						"forma_pago": $('#formulario_agregar_factura')[0].elements.forma_pago.value,
						"id_factura": $('#formulario_agregar_factura')[0].elements.id_factura.value,
						"cantidad": $('#formulario_agregar_factura')[0].elements.cantidad.value,
						"precio": $('#formulario_agregar_factura')[0].elements.precio.value,
						"articulo": $('#formulario_agregar_factura')[0].elements.articulo.value
					};

					$.post('index.php', parametros, function(respuesta) {
						var objeto_respuesta = JSON.parse(respuesta);
						if (objeto_respuesta.estado == "EXITO") {
							exito(objeto_respuesta.mensaje);
							$('#formulario_agregar_factura')[0].reset();

							$.post('index.php', {
								"ruta": "ventas_VI/agregarFactura"
							}, function(res) {
								$('#contenido_modal').html(res);
								$('#date').html(ano + "-" + mes + "-" + dia);
								$('#forma_pago').val(forma_pago);

								$('#nit').val(cliente);
								$('#ciudad').val(ciudad);
								$('#direccion').val(direccion);
								$('#telefono').val(telefono);
								$('#correo').val(correo);
								$('#nit_client').val(nit);
								$('#id_cliente').val(id_cliente);

								if (forma_pago == "Credito") {
									$('#abono').css("display", "block");
									$('#abono').val(abono);
								}
							});

						} else if (objeto_respuesta.estado == "ADVERTENCIA") {
							advertencia(objeto_respuesta.mensaje);
						} else if (objeto_respuesta.estado == "ERROR") {
							error(objeto_respuesta.mensaje);
						} else {
							advertencia('ADVERTENCIA: Falta el atributo estado');
						}
					});

				}


			} // Fin funcion agregar articulo


			function eliminarArticulo(id, cantidad, codigo) {
				var fecha = new Date(); //Fecha actual
				var mes = fecha.getMonth() + 1; //obteniendo mes
				var dia = fecha.getDate(); //obteniendo dia
				var ano = fecha.getFullYear(); //obteniendo año
				if (dia < 10)
					dia = '0' + dia; //agrega cero si el menor de 10
				if (mes < 10)
					mes = '0' + mes; //agrega cero si el menor de 10  

				var forma_pago = $('#forma_pago').val(); //Mantener valor de forma de pago
				var abono = $('#abono').val(); //Mantener valor de abono agregado
				var cliente = $('#nit').val(); //Mantener valor del cliente seleccionado
				var ciudad = $('#ciudad').val();
				var direccion = $('#direccion').val();
				var telefono = $('#telefono').val();
				var correo = $('#correo').val();

				var parametro = {
					"id_articulo": id,
					"cantidad": cantidad,
					"codigo": codigo,
					"ruta": "ventas_CO/eliminarArticulo"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);
						// $('#formulario_agregar_factura')[0].reset();

						$.post('index.php', {
							"ruta": "ventas_VI/agregarFactura"
						}, function(res) {
							$('#contenido_modal').html(res);
							$('#date').html(ano + "-" + mes + "-" + dia);
							$('#forma_pago').val(forma_pago);
							$('#nit').val(cliente);
							$('#ciudad').val(ciudad);
							$('#direccion').val(direccion);
							$('#telefono').val(telefono);
							$('#correo').val(correo);
							$('#nit_client').val(nit);
							if (forma_pago == "Credito") {
								$('#abono').css("display", "block");
								$('#abono').val(abono);
							}
						});

					} else if (objeto_respuesta.estado == "ADVERTENCIA") {
						advertencia(objeto_respuesta.mensaje);
					} else if (objeto_respuesta.estado == "ERROR") {
						error(objeto_respuesta.mensaje);
					} else {
						advertencia('ADVERTENCIA: Falta el atributo estado');
					}
				});
			} // Fin funcion eliminar articulo



			function controladorAgregarVentas() {
				if ($('#formulario_agregar_factura')[0].elements.total.value == 0) {
					alert("Debe ingresar el articulos ala factura");
				} else if ($('#formulario_agregar_factura')[0].elements.nit.value == 0) {
					alert("Debe ingresar el cliente a la factura");
				} else if ($('#formulario_agregar_factura')[0].elements.forma_pago.value == 0) {
					alert("Debe ingresar la forma de pago a la factura");
				} else {
					var cadena = $('#formulario_agregar_ventas').serialize();
					var parametros = {
						"ruta": "ventas_CO/agregar",
						"nit": $('#formulario_agregar_factura')[0].elements.nit_client.value,
						"id_cliente": $('#formulario_agregar_factura')[0].elements.id_cliente.value,
						"id_factura": $('#formulario_agregar_factura')[0].elements.id_factura.value,
						"forma_pago": $('#formulario_agregar_factura')[0].elements.forma_pago.value,
						"total": $('#formulario_agregar_factura')[0].elements.total.value,
						"abono": $('#formulario_agregar_factura')[0].elements.abono.value,
						"notas": $('#formulario_agregar_factura')[0].elements.notas.value
					};

					$.post('index.php', parametros, function(respuesta) {
						var objeto_respuesta = JSON.parse(respuesta);
						if (objeto_respuesta.estado == "EXITO") {
							exito(objeto_respuesta.mensaje);
							$('#formulario_agregar_factura')[0].reset();

							$.post('index.php', {
								"ruta": "ventas_VI/listar"
							}, function(res) {
								$('#contenido').html(res);
								location.reload();
							});
						} else if (objeto_respuesta.estado == "ADVERTENCIA") {
							advertencia(objeto_respuesta.mensaje);
						} else if (objeto_respuesta.estado == "ERROR") {
							error(objeto_respuesta.mensaje);
						} else {
							advertencia('ADVERTENCIA: Falta el atributo estado');
						}
					});
				}
			}
		</script>

	<?php
	} //Fin funcion Agregar Factura



	function actualizar()
	{
		require_once "modelos/ventas_MO.php";
		require_once "modelos/clientes_MO.php";
		$conexion = new servidor('A');
		$ventas_MO = new ventas_MO($conexion);
		$id_venta = $_POST["id_venta"];
		$arreglo_ventas = $ventas_MO->seleccionar("id_venta", $id_venta);
		$id_venta = $arreglo_ventas[0]->id_venta;
		$nit = $arreglo_ventas[0]->nit_cliente;
		$id_factura = $arreglo_ventas[0]->id_factura;
		$forma_pago = $arreglo_ventas[0]->forma_pago;
		$total = $arreglo_ventas[0]->total;
		$abono = $arreglo_ventas[0]->abono;
		$devolucion = $arreglo_ventas[0]->devolucion;
		$saldo = $arreglo_ventas[0]->saldo;



		$clientes_MO = new clientes_MO($conexion);
		$arreglo_clientes = $clientes_MO->seleccionar();
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos ventas</h3>
			</div>
			<div class="card-body">
				<form id="formulario_actualizar_ventas" method="post">
					<div class="row">
						<div class="col-6">
							<input type="hidden" id="id_venta" name="id_venta" value="<?php echo $id_venta; ?>">
							<select class="form-control form-control-lg" name="nit" id="nit">
								<option value="0" disabled selected>Clientes:</option>

								<?php

								if ($arreglo_clientes) {
									foreach ($arreglo_clientes as $objeto_clientes) {
										$id_cliente = $objeto_clientes->id_cliente;
										$nit_c = $objeto_clientes->nit;
										$nombre = $objeto_clientes->nombre;
										if ($nit == $id_cliente) {
											echo "<option value='" . $nit . "' selected>" . $nombre . "</option>";
										} else {
											echo "<option value='" . $id_cliente . "'>" . $nombre . "</option>";
										}
									}
								}
								?>
							</select> <br>

							<input class="form-control form-control-lg" type="text" id="id_factura" name="id_factura" placeholder="ID Factura" value="<?php echo $id_factura; ?>" autocomplete="on"><br>
							<select class="form-control form-control-lg" id="forma_pago" name="forma_pago">
								<option value="0" disabled selected>Forma de Pago:</option>
								<?php
								if ($forma_pago == "Efectivo") {
									echo "<option value='" . $forma_pago . "' selected>" . $forma_pago . "</option>";
								} else {
									echo "<option value='" . $forma_pago . "' selected>" . $forma_pago . "</option>";
								}
								?>
							</select><br>
							<input class="form-control form-control-lg" type="text" id="total" name="total" placeholder="Total" value="<?php echo $total; ?>" autocomplete="on"><br>
						</div>
						<div class="col-6">
							<input class="form-control form-control-lg" type="text" id="abono" name="abono" placeholder="Abono" value="<?php echo $abono; ?>" autocomplete="on"><br>
							<input class="form-control form-control-lg" type="text" id="devolucion" name="devolucion" placeholder="Devolucion" value="<?php echo $devolucion; ?>" autocomplete="on"><br>
							<input class="form-control form-control-lg" type="text" id="saldo" name="saldo" placeholder="Saldo" value="<?php echo $saldo; ?>" autocomplete="on"><br>
							<button type="button" class="btn btn-primary float-center btn-lg" onclick="controladorActualizarVenta()"><i class="fas fa-save"></i> Guardar</button>
						</div>

				</form>
			</div><!-- /.card-body -->
		</div>

		<script>
			function controladorActualizarVenta() {
				var cadena = $('#formulario_actualizar_ventas').serialize();
				var parametro = {
					"id_venta": $('#formulario_actualizar_ventas')[0].elements.id_venta.value,
					"nit": $('#formulario_actualizar_ventas')[0].elements.nit.value,
					"id_factura": $('#formulario_actualizar_ventas')[0].elements.id_factura.value,
					"forma_pago": $('#formulario_actualizar_ventas')[0].elements.forma_pago.value,
					"total": $('#formulario_actualizar_ventas')[0].elements.total.value,
					"abono": $('#formulario_actualizar_ventas')[0].elements.abono.value,
					"devolucion": $('#formulario_actualizar_ventas')[0].elements.devolucion.value,
					"saldo": $('#formulario_actualizar_ventas')[0].elements.saldo.value,
					"ruta": "ventas_CO/actualizar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						//Pendiente en actualizacion de usuario
						$('#formulario_actualizar_ventas').modal('hide');

						$.post('index.php', {
							"ruta": "ventas_VI/listar"
						}, function(res) {
							$('#contenido').html(res);
						});
					} else if (objeto_respuesta.estado == "ADVERTENCIA") {
						advertencia(objeto_respuesta.mensaje);
					} else if (objeto_respuesta.estado == "ERROR") {
						error(objeto_respuesta.mensaje);
					} else {
						advertencia('ADVERTENCIA: Falta el atributo estado');
					}
				});
			}
		</script>
	<?php
	} //FIN FUNCION ACTUALIZAR


	function consultar()
	{
		require_once "modelos/ventas_MO.php";
		require_once "modelos/clientes_MO.php";
		$conexion = new servidor('A');
		$ventas_MO = new ventas_MO($conexion);
		$id_venta = $_POST["id_venta"];
		$arreglo_ventas = $ventas_MO->seleccionar("id_venta", $id_venta);
		$id_factura = $arreglo_ventas[0]->id_factura;
		$articulos = $ventas_MO->seleccionarArticulo($id_factura);
		$clientes = new clientes_MO($conexion);
		$nit = $arreglo_ventas[0]->nit_cliente;
		$arreglo_cliente = $clientes->seleccionar("nit", $nit);
		$id_venta = $arreglo_ventas[0]->id_venta;
		$forma_pago = $arreglo_ventas[0]->forma_pago;
		$total = $arreglo_ventas[0]->total;
		$abono = $arreglo_ventas[0]->abono;
		$devolucion = $arreglo_ventas[0]->devolucion;
		$saldo = $arreglo_ventas[0]->saldo;
		$subtotal = 0;
		$fecha_creacion = $arreglo_ventas[0]->fecha_creacion;

	?>
		<table width="100%" border="1" cellpadding="5" cellspacing="0">

			<tr>
				<td colspan="2">
					<table width="100%" cellpadding="5">

						<tr>
							<td width="30%" style="text-align: center;"> <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 80px; height: 80px; ">
							</td>

							<td width="70%" style="border: black 3px solid;">
								<b><?php echo NOMBRE_EMPRESA; ?></b><br />
								<b>Nit : </b> <?php echo NIT_EMPRESA ?><br />
								<b>Telefono : </b> <?php echo TELEFONO_EMPRESA ?><br />
								<b>Direccion : </b><?php echo DIRECCION_EMPRESA ?><br />
							</td>
						</tr>
						<tr>
							<td width="65%">
								<b>FACTURAR A : </b><br />
								<b>Nombre : </b><?php echo $arreglo_cliente[0]->nombre ?> <br />
								<b>Dirección : </b><?php echo  $arreglo_cliente[0]->ciudad ?>, <?php echo  $arreglo_cliente[0]->direccion ?><br />
								<b>Telefono : </b><?php echo  $arreglo_cliente[0]->telefono ?> <br />
								<b>Correo : </b><?php echo  $arreglo_cliente[0]->correo ?> <br />
							</td>
							<td width="35%">
								<b>Factura no. : </b><?php echo $id_factura ?><br />
								<b>Fecha de la factura : </b><?php echo $fecha_creacion ?><br />
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
						</tr><?php
								$count = 0;
								foreach ($articulos as $articulo) {
									$count++;  ?>
							<tr>
								<td align="left"><?php echo $count ?></td>
								<td align="left"><?php echo $articulo->codigo ?></td>
								<td align="left"><?php echo $articulo->descripcion ?></td>
								<td align="left"><?php echo $articulo->cantidad ?></td>
								<td align="left"><?php echo $articulo->precio_unitario ?></td>
								<td align="left"><?php echo $articulo->precio_total ?></td>
							</tr> <?php
									$subtotal = $subtotal + $articulo->precio_total;
								}  ?>
						<tr>
							<td align="right" colspan="5"><b>Sub Total</b></td>
							<td align="left"><b><?php echo $subtotal ?></b></td>
						</tr>
						<tr>
							<td align="right" colspan="5"><b>Porcentaje Impuestos :</b></td>
							<td align="left"><?php echo IVA ?> %</td>
						</tr>
						<tr>
							<td align="right" colspan="5">Monto Impuestos: </td>
							<td align="left"><?php echo ($subtotal * IVA) ?></td>
						</tr>
						<tr>
							<td align="right" colspan="5">Total: </td>
							<td align="left"><?php echo ($subtotal + ($subtotal * IVA)) ?></td>
						</tr>
						<tr>
					</table>
				</td>
			</tr>
		</table>


	<?php
	} //FIN FUNCION CONSULTAR



	function consultar_producto()
	{
		require_once "modelos/productos_MO.php";
		$conexion = new servidor('A');
		$productos_MO = new productos_MO($conexion);
		$arreglo_productos = $productos_MO->seleccionar();

	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos Productos</h3>
			</div>
			<div class="card-body">
				<table id="listar_producto" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Codigo</th>
							<th>Referencia</th>
							<th>Descripcion</th>
							<th>Proveedor</th>
							<th>Categoria</th>
							<th>Cantidad</th>
							<th>Precio General</th>
						</tr>
					</thead>
					<tbody>
						<?php

						if ($arreglo_productos) {
							foreach ($arreglo_productos as $objeto_productos) {
								$id_producto = $objeto_productos->id_producto;
								$codigo = $objeto_productos->codigo;
								$referencia = $objeto_productos->referencia;
								$descripcion = $objeto_productos->descripcion;
								$Proveedor = $objeto_productos->proveedor;
								$cantidad = $objeto_productos->cantidad_bodega;
								$categoria = $objeto_productos->categoria;
								$precio_general = $objeto_productos->precio_general;


						?>
								<tr>
									<td><?php echo $codigo; ?></td>
									<td><?php echo $referencia; ?></td>
									<td><?php echo $descripcion; ?></td>
									<td><?php echo $Proveedor; ?></td>
									<td><?php echo $categoria; ?></td>
									<td><?php echo $cantidad; ?></td>
									<td><?php echo $precio_general; ?></td>
								</tr>
						<?php
							}
						}
						?>
					</tbody>
				</table>



			</div><!-- /.card-body -->
		</div>

		<script>
			data_table_productos = organizarTabla({
				id: "listar_producto"
			});
		</script>


<?php
	} //FIN FUNCION CONSULTAR PRODUCTOS


	/*function eliminar()
    	{
		    require_once "modelos/ventas_MO.php";
			$conexion=new servidor('A');
		    $ventas_MO=new ventas_MO($conexion);
			$id_venta=$_POST["id_venta"];
			$id_factura=$_POST["id_factura"];


		     ?>
		       <div class="card card-danger">
		            <div class="card-header">
		                <h3 class="card-title">Eliminar venta</h3>
		            </div>
	              	<div class="card-body">
	              		<form  id="formulario_eliminar_ventas" method="post">
							<center><label><h1>¿Desea eliminar el venta?</h1></label></center><br>
							<input type="hidden" id="id_venta" name="id_venta"  value="<?php echo $id_venta;?>" >
     						<button type="button" class="btn btn-danger btn-lg btn-block" onclick="controladorEliminarVenta(<?php echo $id_venta;?>,<?php echo $id_factura;?>)">Deseo continuar</button> <br>	<button type="button" class="btn btn-success btn-lg btn-block" data-dismiss="modal">Cerrar</button> <br>	
                		</form>
       				</div>
          		</div>  


       	 		<script>
			        function controladorEliminarVenta($id,$id_factura)
			        {		        	 		
				        var parametro = {"id_venta":$id,"id_factura": $id_factura, "ruta":"ventas_CO/eliminar"};

			            $.post('index.php',parametro,function(respuesta)
			            {
			              	var objeto_respuesta=JSON.parse(respuesta);
			                
			              	if(objeto_respuesta.estado=="EXITO")
			              	{
			                	exito(objeto_respuesta.mensaje);
			                	$.post('index.php',{"ruta": "ventas_VI/listar"},function(res){
			                    $('#contenido').html(res);
			                	});
			              	}
			              	else if(objeto_respuesta.estado=="ADVERTENCIA")
			              	{
			                	advertencia(objeto_respuesta.mensaje);
			              	}
			              	else if(objeto_respuesta.estado=="ERROR")
			              	{ 
			                  	error(objeto_respuesta.mensaje);
			              	}
			              	else
			              	{
			                  	advertencia('ADVERTENCIA: Falta el atributo estado');
			              	}
			            });
			        } 
		   		</script>

       
<?php
   		} //FIN FUNCION ELIMINAR*/
}

?>