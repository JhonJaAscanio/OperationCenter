<?php
class compras_VI
{
	function __construct()
	{
	}

	function  listar()
	{
		require_once "modelos/compras_MO.php";


		$conexion = new servidor('A');
		$compras_MO = new compras_MO($conexion);
		$arreglo_compras = $compras_MO->seleccionar();



?>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Compra</h3>
				<button type="button" class="btn btn-success float-right" onclick="vistaAgregarCompra()"> <i class="far fa-plus-square"></i> Agregar</button>
			</div> <!-- /.card-header -->
			<div class="card-body">
				<table id="listar_compras" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th class="col-md-1"></th>
							<th class="col-md-1">Id Factura</th>
							<th class="col-md-2">Fecha</th>
							<th class="col-md-3">Proveedor</th>
							<th class="col-md-2">Total</th>
							<th class="col-md-2">Abono</th>
							<th class="col-md-2">Saldo</th>
							<th style="text-align:center;">Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($arreglo_compras) {
							foreach ($arreglo_compras as $objeto_compras) {
								$id_compra = $objeto_compras->id_compra;
								$id_factura = $objeto_compras->id_factura;
								$proveedor = $objeto_compras->proveedor;
								$total = $objeto_compras->total;
								$abono = $objeto_compras->abono;
								$saldo = $total - $abono;
								$pago = $objeto_compras->pago;
								$fecha_creacion = $objeto_compras->fecha_creacion;
								$fecha_actualizacion = $objeto_compras->fecha_actualizacion;


						?>
								<tr>
									<td><?php echo $pago; ?></td>
									<td><?php echo $id_factura; ?></td>
									<td><?php echo $fecha_creacion; ?></td>
									<td><?php echo $proveedor; ?></td>
									<td><?php echo number_format($total); ?></td>
									<td><?php echo number_format($abono); ?></td>
									<td><?php echo number_format($saldo); ?></td>
									<td style="text-align:center;">
										<i class="fas fa-edit" style="cursor:pointer; margin-right: 10px; color: #0C6766;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaActualizarCompra('<?php echo $id_compra; ?>')" title="Actualizar"></i>
										<i class="far fa-eye" style="cursor:pointer; margin-right: 10px;color: #697E0A;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaConsultarCompra('<?php echo $id_compra; ?>')" title="Consultar"></i>
										<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaEliminarCompra('<?php echo $id_compra; ?>')" title="Eliminar "></i>

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
			function vistaAgregarCompra() {
				var fecha = new Date(); //Fecha actual
				var mes = fecha.getMonth() + 1; //obteniendo mes
				var dia = fecha.getDate(); //obteniendo dia
				var ano = fecha.getFullYear(); //obteniendo año
				if (dia < 10)
					dia = '0' + dia; //agrega cero si el menor de 10
				if (mes < 10)
					mes = '0' + mes //agrega cero si el menor de 10

				var parametros = {
					"ruta": "compras_VI/agregar"
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#contenido').html(respuesta);
					$('#fecha').val(ano + "-" + mes + "-" + dia);
				});
			}

			function vistaActualizarCompra(id_compra) {
				var parametros = {
					"ruta": "compras_VI/actualizar",
					"id_compra": id_compra
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Actualizar compra');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaConsultarCompra(id_compra) {
				var parametros = {
					"ruta": "compras_VI/consultar",
					"id_compra": id_compra
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Consultar compra');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaEliminarCompra(id_compra) {
				var parametros = {
					"ruta": "compras_VI/eliminar",
					"id_compra": id_compra
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#contenido_modal').html(respuesta);
				});
			}


			data_table_compras = organizarTabla({
				id: "listar_compras"
			});
		</script>
	<?php
	} //Fin funcion listar





	function agregar()
	{

		require_once "modelos/proveedores_MO.php";
		require_once "modelos/compras_MO.php";
		require_once "modelos/productos_MO.php";
		$conexion = new servidor('A');
		$proveedores_MO = new proveedores_MO($conexion);
		$compras_MO = new compras_MO($conexion);
		$arreglo_proveedor = $proveedores_MO->seleccionar();

		$arreglo_compras = $compras_MO->seleccionarMayor();
		if (!$arreglo_compras) {
			$id_factura = 0;
		} else {
			$id_factura = ($arreglo_compras[0]->id_factura) + 1;
		}

		$arreglo_articulos = $compras_MO->seleccionarArticulo($id_factura);

		$productos_MO = new productos_MO($conexion);
		$arreglo_productos = $productos_MO->seleccionar();
		$total = 0;
	?>
		<div class="row offset-sm-1 mt-4 mr-3 ml-3">
			<div class="col-md-12">
				<div class="card card-primary card-tabs">
					<div class="card-header">
						<h3><i class="fas fa-solid fa-bars"></i> Nueva compra</h3>
					</div>
					<div class="card-body">
						<form id="formulario_agregar_compras">
							<div class="row">
								<div class="col">
									<label style=" font-size: 15px">Proveedor</label>
									<select class="form-control" name="proveedor" id="proveedor">
										<option value="0" disabled selected>Proveedor:</option>
										<?php
										if ($arreglo_proveedor) {
											foreach ($arreglo_proveedor as $objeto_proveedor) {
												$id_proveedor = $objeto_proveedor->id_proveedor;
												$nombre = $objeto_proveedor->nombre;
										?>
												<option value="<?php echo  $nombre; ?>"><?php echo $nombre; ?></option>
										<?php
											}
										}
										?>
									</select> <br>
								</div>
								<div class="col">
									<label style=" font-size: 15px">N° Factura Proveedor</label>
									<input class="form-control" type="text" id="id_factura" name="id_factura" placeholder="Id Factura" autocomplete="on"> <br>
								</div>
								<div class="col">
									<label style=" font-size: 15px">Fecha:</label>
									<input class="form-control" type="date" id="fecha" name="fecha" placeholder="Fecha" autocomplete="on"> <br>
								</div>
							</div> <!-- Fin primera fila -->
							<div class="row">
								<div class="col">
									<label style=" font-size: 15px">Forma de pago</label>
									<select class="form-control" id="pago" name="pago">
										<option value="0" disabled selected>Pago:</option>
										<option value="Pagado">Pagado</option>
										<option value="No Pagado">No pagado</option>
									</select><br>
								</div>
								<div class="col">
									<label style=" font-size: 15px">Bodega</label>
									<input class="form-control" type="text" id="bodega" name="bodega" value="PRINCIPAL" readonly autocomplete="on"> <br>
								</div>
								<div class="col">
									<label style=" font-size: 15px">N° Compra</label>
									<input class="form-control" type="text" id="compra" name="compra" style=" text-align:center" value="<?php echo $id_factura ?>" readonly autocomplete="on"> <br>
								</div>
							</div> <!-- Fin Segundad fila -->

							<div class="row">
								<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#ventana_modal" onclick="agregarProducto()"><i class="fas fa-save" tabindex="-1"></i> Nuevo Producto</button>
							</div> <!-- Fin Tercera fila --> <br>
							<hr>

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
											<input type="text" id="stock" class="form-control" placeholder="Stock" readonly="">
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

							</div><br><!-- FIN ROW AGREGAR ARTICULO -->


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
												$id_articulo = $objeto_articulos->id_articulo_compra;
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
														<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" onclick="eliminarArticulo('<?php echo $id_articulo; ?>','<?php echo $cantidad; ?>','<?php echo $codigo; ?>')" title="Eliminar "></i>

													</td>
												</tr>
										<?php
											}
										}
										?>
									</tbody>
								</table>

							</div> <!-- Fin Tabla -->


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


						</form>
					</div> <!--Fin card body-->
				</div>

			</div>
		</div>

		<script>
			function agregarProducto() {
				var parametros = {
					"ruta": "productos_VI/agregar"
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Agregar producto');
					$('#contenido_modal').html(respuesta);
				});
			}

			var array_producto = new Array();
			var articulo = <?= json_encode($arreglo_productos) ?>;
			articulo.forEach(function(word) {
				array_producto.push(word.codigo);
			});

			$("#articulo").autocomplete({
				source: array_producto
			});

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
								$('#stock').val(word.cantidad_bodega + "   Productos en Bodega");
							}
						} else {
							if (word.codigo == $('#articulo').val()) {
								$('#id').val(word.id_producto);
								$('#precio').val(word.precio_general);
								$('#stock').val(word.cantidad_bodega + "   Productos en Bodega");
							}
						}
					});
					visualizarTotal();
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
		</script>


		<script>
			function controladorAgregarArticulo() {
				/*	var fecha = new Date(); //Fecha actual
				    var mes = fecha.getMonth()+1; //obteniendo mes
				    var dia = fecha.getDate(); //obteniendo dia
				    var ano = fecha.getFullYear(); //obteniendo año
				    if(dia<10)
				    	dia='0'+dia; //agrega cero si el menor de 10
				    if(mes<10)
				    	mes='0'+mes; //agrega cero si el menor de 10  
				   
				    var forma_pago = $('#forma_pago').val(); //Mantener valor de forma de pago
				    var abono = $('#abono').val(); //Mantener valor de abono agregado
				    var cliente = $('#nit').val(); //Mantener valor del cliente seleccionado
				    var ciudad = $('#ciudad').val();
				    var direccion = $('#direccion').val();
				    var telefono = $('#telefono').val();
				    var correo = $('#correo').val();
				    var nit=$('#nit_client').val();
				    var id_cliente=$('#id_cliente').val();*/

				//No se puede realizar la añadidura de un articulo si se manda los input en blanco
				if ($('#formulario_agregar_compras')[0].elements.cantidad.value == "" || $('#formulario_agregar_compras')[0].elements.articulo.value == "") {
					alert("Debe ingresar el articulo y la cantidad");
				} else {
					var cadena = $('#formulario_agregar_compras').serialize();
					var parametros = {
						"ruta": "compras_CO/agregar_articulo",
						"id_factura": $('#formulario_agregar_compras')[0].elements.compra.value,
						"cantidad": $('#formulario_agregar_compras')[0].elements.cantidad.value,
						"precio": $('#formulario_agregar_compras')[0].elements.precio.value,
						"tipo": $('#formulario_agregar_compras')[0].elements.tipo.value,
						"articulo": $('#formulario_agregar_compras')[0].elements.articulo.value
					};

					$.post('index.php', parametros, function(respuesta) {
						var objeto_respuesta = JSON.parse(respuesta);
						if (objeto_respuesta.estado == "EXITO") {

							exito(objeto_respuesta.mensaje);
							$('#formulario_agregar_compras')[0].reset();
							$.post('index.php', {
								"ruta": "compras_VI/agregar"
							}, function(res) {
								$('#contenido').html(res);
								/*	$('#date').html(ano+"-"+mes+"-"+dia);
						                $('#forma_pago').val(forma_pago);  
				                    	$('#nit').val(cliente); 
				                    	$('#ciudad').val(ciudad);
				                    	$('#direccion').val(direccion);
				                    	$('#telefono').val(telefono);
				                    	$('#correo').val(correo);
				                    	$('#nit_client').val(nit);
				                    	$('#id_cliente').val(id_cliente);

				                    	if(forma_pago == "Credito" )
				                    	{
				                    		$('#abono').css("display","block");
				                    		$('#abono').val(abono); 
				                    	}*/
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


			function eliminarArticulo(id_articulo, cantidad, codigo) {

				var parametro = {
					"id_articulo": id_articulo,
					"cantidad": cantidad,
					"codigo": codigo,
					"ruta": "compras_CO/eliminarArticulo"
				};


				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {

						exito(objeto_respuesta.mensaje);
						$('#formulario_agregar_compras')[0].reset();
						$.post('index.php', {
							"ruta": "compras_VI/agregar"
						}, function(res) {
							$('#contenido').html(res);
							/*	$('#date').html(ano+"-"+mes+"-"+dia);
				                $('#forma_pago').val(forma_pago);  
		                    	$('#nit').val(cliente); 
		                    	$('#ciudad').val(ciudad);
		                    	$('#direccion').val(direccion);
		                    	$('#telefono').val(telefono);
		                    	$('#correo').val(correo);
		                    	$('#nit_client').val(nit);
		                    	$('#id_cliente').val(id_cliente);

		                    	if(forma_pago == "Credito" )
		                    	{
		                    		$('#abono').css("display","block");
		                    		$('#abono').val(abono); 
		                    	}*/
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
		</script>

	<?php
	} //Fin funcion Agregar


	function actualizar()
	{
		require_once "modelos/compras_MO.php";
		$conexion = new servidor('A');
		$compras_MO = new compras_MO($conexion);
		$id_compra = $_POST["id_compra"];
		$arreglo_compras = $compras_MO->seleccionar("id_compra", $id_compra);
		$id_compra = $arreglo_compras[0]->id_compra;
		$id_factura = $arreglo_compras[0]->id_factura;
		$proveedor = $arreglo_compras[0]->proveedor;
		$total = $arreglo_compras[0]->total;
		$abono = $arreglo_compras[0]->abono;
		$pago = $arreglo_compras[0]->pago;


		require_once "modelos/proveedores_MO.php";
		$proveedores_MO = new proveedores_MO($conexion);
		$arreglo_proveedor = $proveedores_MO->seleccionar();
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos compras</h3>
			</div>
			<div class="card-body">
				<form id="formulario_actualizar_compras" method="post">
					<div class="row">
						<div class="col-6">
							<input type="hidden" id="id_compra" name="id_compra" value="<?php echo $id_compra; ?>">
							<input class="form-control form-control-lg" type="text" id="id_factura" name="id_factura" placeholder="ID Factura" value="<?php echo $id_factura; ?>" autocomplete="on"><br>
							<select class="form-control form-control-lg" name="proveedor" id="proveedor">
								<option value="0" disabled selected>Proveedor:</option>

								<?php
								if ($arreglo_proveedor) {
									foreach ($arreglo_proveedor as $objeto_proveedor) {
										$id_proveedor = $objeto_proveedor->id_proveedor;
										$nombre = $objeto_proveedor->nombre;
										if ($nombre == $proveedor) {
											echo "<option value='" . $proveedor . "' selected>" . $proveedor . "</option>";
										} else {
											echo "<option value='" . $nombre . "'>" . $nombre . "</option>";
										}
									}
								}
								?>
							</select> <br>

							<select class="form-control form-control-lg" id="pago" name="pago">
								<option value="0" disabled selected>Pago:</option>
								<?php
								if ($pago == "Pagado") {
									echo "<option value='" . $pago . "' selected>" . 'Pagado' . "</option>";
								} else {
									echo "<option value='" . $pago . "' selected>" . 'No pagado' . "</option>";
								}
								echo "<option value='" . 'No pagado' . "'>" . 'No pagado' . "</option>";
								echo "<option value='" . 'Pagado' . "'>" . 'Pagado' . "</option>";

								?>
							</select><br>
						</div>
						<div class="col-6">
							<input class="form-control form-control-lg" type="text" id="total" name="total" placeholder="Total" value="<?php echo $total; ?>" autocomplete="on"><br>
							<input class="form-control form-control-lg" type="text" id="abono" name="abono" placeholder="Abono" value="<?php echo $abono; ?>" autocomplete="on"><br>
							<button type="button" class="btn btn-primary float-right" onclick="controladorActualizarCompra()"><i class="fas fa-save"></i> Guardar</button>
						</div>
					</div>
				</form>
			</div><!-- /.card-body -->
		</div>

		<script>
			function controladorActualizarCompra() {
				var cadena = $('#formulario_actualizar_compras').serialize();
				var parametro = {
					"id_compra": $('#formulario_actualizar_compras')[0].elements.id_compra.value,
					"id_factura": $('#formulario_actualizar_compras')[0].elements.id_factura.value,
					"proveedor": $('#formulario_actualizar_compras')[0].elements.proveedor.value,
					"total": $('#formulario_actualizar_compras')[0].elements.total.value,
					"abono": $('#formulario_actualizar_compras')[0].elements.abono.value,
					"pago": $('#formulario_actualizar_compras')[0].elements.pago.value,
					"ruta": "compras_CO/actualizar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						//Pendiente en actualizacion de usuario
						$('#formulario_actualizar_compras').modal('hide');

						$.post('index.php', {
							"ruta": "compras_VI/listar"
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
		require_once "modelos/compras_MO.php";
		$conexion = new servidor('A');
		$compras_MO = new compras_MO($conexion);
		$id_compra = $_POST["id_compra"];
		$arreglo_compras = $compras_MO->seleccionar("id_compra", $id_compra);
		$id_compra = $arreglo_compras[0]->id_compra;
		$id_factura = $arreglo_compras[0]->id_factura;
		$proveedor = $arreglo_compras[0]->proveedor;
		$total = $arreglo_compras[0]->total;
		$abono = $arreglo_compras[0]->abono;
		$pago = $arreglo_compras[0]->pago;

	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos compras</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-6">

						<input type="hidden" id="id_compra" name="id_compra" value="<?php echo $id_compra; ?>">
						<label class="col-lg-3 control-label">Id Compra</label>
						<input class="form-control form-control-lg" type="text" id="id_compra" name="id_compra" value="<?php echo $id_compra; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-3 control-label">ID Factura</label>
						<input class="form-control form-control-lg" type="text" id="id_factura" name="id_factura" value="<?php echo $id_factura; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-3 control-label">Proveedor</label>
						<input class="form-control form-control-lg" type="text" id="proveedor" name="proveedor" value="<?php echo $proveedor; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-3 control-label">Total</label>
						<input class="form-control form-control-lg" type="text" id="total" name="total" value="<?php echo $total; ?>" autocomplete="on" readonly><br>
					</div>
					<div class="col-6">
						<label class="col-lg-3 control-label">Abono</label>
						<input class="form-control form-control-lg" type="text" id="abono" name="abono" value="<?php echo $abono; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-3 control-label">Pago</label>
						<input class="form-control form-control-lg" type="text" id="pago" name="pago" value="<?php echo $pago; ?>" autocomplete="on" readonly><br>
					</div>
				</div>
			</div><!-- /.card-body -->
		</div>


	<?php
	} //FIN FUNCION CONSULTAR


	function eliminar()
	{
		require_once "modelos/compras_MO.php";
		$conexion = new servidor('A');
		$compras_MO = new compras_MO($conexion);
		$id_compra = $_POST["id_compra"];


	?>
		<div class="card card-danger">
			<div class="card-header">
				<h3 class="card-title">Eliminar compra</h3>
			</div>
			<div class="card-body">
				<form id="formulario_eliminar_compras" method="post">
					<center><label>
							<h1>¿Desea eliminar el compra?</h1>
						</label></center><br>
					<input type="hidden" id="id_compra" name="id_compra" value="<?php echo $id_compra; ?>">
					<button type="button" class="btn btn-danger btn-lg btn-block" onclick="controladorEliminarCompra(<?php echo $id_compra; ?>)">Deseo continuar</button> <br> <button type="button" class="btn btn-success btn-lg btn-block" data-dismiss="modal">Cerrar</button> <br>
				</form>
			</div>
		</div>


		<script>
			function controladorEliminarCompra($id) {
				//var cadena=$('#formulario_eliminar_compras').serialize();			        	 		
				var parametro = {
					"id_compra": $id,
					"ruta": "compras_CO/eliminar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);
						$.post('index.php', {
							"ruta": "compras_VI/listar"
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
	} //FIN FUNCION ELIMINAR



}

?>