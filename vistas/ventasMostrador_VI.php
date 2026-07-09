<?php
class ventasMostrador_VI
{
	function __construct()
	{
	}


	function  agregar_ventas_mostrador()
	{
		require_once "modelos/ventas_MO.php";
		require_once "modelos/productos_MO.php";

		$fecha_dia = date('Y-m-d');
		$conexion = new servidor('A');
		$ventas_MO = new ventas_MO($conexion);
		$arreglo_ventas = $ventas_MO->seleccionar_mostrador('DATE(fecha_creacion)', $fecha_dia);


		$productos_MO = new productos_MO($conexion);
		$arreglo_productos = $productos_MO->seleccionar();


?>
		<div class="row justify-content-md-center">
			<div class="col-md-4 m-2">
				<div id="ventas_modulo">
					<div class="card">
						<div class="card-header" style="background-color: #1A6FBE; color: white">
							<h2 class="card-title"><b>Agregar venta</b></h2>
						</div> <!-- /.card-header -->
						<div class="card-body">
							<form id="formulario_agregar_venta" method="post">
								<div class="form-group">
									<div class="input-group">
										<span>
											<select type="submit" class="form-control" onchange="referencia(value)" id="tipo">
												<option value="1">Codigo</option>
												<option value="2">Descripcion</option>
											</select>
										</span>
										<input type="text" id="producto" class="form-control" name="title" placeholder="Buscar " onkeypress="pulsar(event)">
									</div>
								</div>
								<hr>
								<input type="hidden" id="id">
								<div class="row">
									<div class="col-lg-4">Cantidad
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="fas fa-cart-plus"></i>
												</span>
											</div>
											<input type="number" id="cantidad" class="form-control" value="1" style=" text-align:center" onkeypress="visualizarTotal()">
										</div>
										<!-- /input-group -->
									</div>
									<!-- /.col-lg-6 -->
									<div class="col-lg-8"> Precio
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">$</span>
											</div>
											<input id="precio" type="number" class="form-control" style=" text-align:center" value="0" onkeypress="visualizarTotal()">
										</div>
										<!-- /input-group -->
									</div>
									<!-- /.col-lg-6 -->
								</div>
								<!-- /.row -->

								<div class="row mt-4 mx-auto">

									<div class="input-group-prepend mx-auto">
										<span class="input-group-text"><b>Total: $</b></span>
										<input id="total" type="text" class="form-control" style=" text-align:center" value="0" readonly>
									</div>
								</div>
							</form>
						</div><!-- card body -->
						<div class="card-footer">
							<button type="button" class="btn btn-primary float-right btn-lg" onclick="controladorAgregarVenta()"><i class="fas fa-save"></i> Agregar venta</button>
						</div>
					</div><!-- Fin card -->
				</div>
			</div>





			<!-- <div class="row justify-content-md-center">   -->
			<div class="col-md-7 m-2">
				<div class="card">
					<div class="card-header" style="background-color: #7899B6; color: white">
						<h2 class="card-title"><b>Ventas Mostrador - <?php echo $fecha_dia; ?></b></h2>
					</div> <!-- /.card-header -->
					<div class="card-body">
						<table id="listar_ventas" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th class="col-md-1">Codigo</th>
									<th class="col-md-6">Nombre Producto</th>
									<th class="col-md-1">Cant</th>
									<th class="col-md-1">Precio</th>
									<th class="col-md-1">Total</th>
									<th class="col-md-2">Fecha</th>
									<th style="text-align:center;">Eliminar</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if ($arreglo_ventas) {
									foreach ($arreglo_ventas as $objeto_ventas) {

										$id_venta_mostrador = $objeto_ventas->id_venta_mostrador;
										$codigo = $objeto_ventas->codigo;
										$descripcion = $objeto_ventas->descripcion;
										$precio = $objeto_ventas->precio;
										$cantidad = $objeto_ventas->cantidad;
										$total = $objeto_ventas->total;
										$fecha = $objeto_ventas->fecha_creacion;



								?>
										<tr>
											<td><?php echo $codigo; ?></td>
											<td><?php echo $descripcion; ?></td>
											<td><?php echo $cantidad; ?></td>
											<td><?php echo number_format($precio); ?></td>
											<td><?php echo number_format($total); ?></td>
											<td><?php echo $fecha; ?></td>
											<td style="text-align:center;">
												<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" onclick="eliminarVenta('<?php echo $id_venta_mostrador; ?>')" title="Eliminar "></i>

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
			</div>
			<!-- </div>  -->



			<script>
				$("#producto").blur(function() {
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
						controladorAgregarVenta();
					}
				});

				$('#precio').keyup(function(e) {
					var t = ($('#precio').val()) * ($('#cantidad').val());
					var total = new Intl.NumberFormat('es-ES').format(t);
					$('#total').val(total);
				});


				function visualizarTotal() {
					var t = ($('#precio').val()) * ($('#cantidad').val());
					var total = new Intl.NumberFormat('es-ES').format(t);
					$('#total').val(total);
				}

				// Funcion para llenar el campo input de buscar con los productos
				$(function() {
					var array_producto = new Array();
					var producto = <?= json_encode($arreglo_productos) ?>;
					producto.forEach(function(word) {
						array_producto.push(word.codigo);
					});

					$("#producto").autocomplete({
						source: array_producto
					});


				});
				//--------------------------------------

				function pulsar(e) {

					if (e.keyCode === 13 && !e.shiftKey || e == 13) {
						/// e.preventDefault();
						var producto = <?= json_encode($arreglo_productos) ?>;
						var tipo;

						producto.forEach(function(word) {

							if ($('#tipo').val() == 2) {
								if (word.descripcion == $('#producto').val()) {
									$('#id').val(word.id_producto);
									$('#precio').val(word.precio_general);
								}
							} else {
								if (word.codigo == $('#producto').val()) {
									$('#id').val(word.id_producto);
									$('#precio').val(word.precio_general);
								}
							}
						});


						var t = ($('#precio').val()) * ($('#cantidad').val());
						var total = new Intl.NumberFormat('es-ES').format(t);
						$('#total').val(total);
					}
				}


				function referencia(val) {
					if (val == 1) {
						$(function() {
							var array_producto = new Array();
							var producto = <?= json_encode($arreglo_productos) ?>;
							producto.forEach(function(word) {
								array_producto.push(word.codigo);
							});

							$("#producto").autocomplete({
								source: array_producto
							});
						});
					} else {
						$(function() {
							var array_producto = new Array();
							var producto = <?= json_encode($arreglo_productos) ?>;
							producto.forEach(function(word) {
								array_producto.push(word.descripcion);
							});

							$("#producto").autocomplete({
								source: array_producto
							});
						});
					}
				}



				function controladorAgregarVenta() {
					var tipo = $('#formulario_agregar_venta')[0].elements.tipo.value;

					if ($('#formulario_agregar_venta')[0].elements.producto.value == "") {
						alert("Debe ingresar el producto a la venta");
					} else if ($('#formulario_agregar_venta')[0].elements.cantidad.value <= 0) {
						alert("Debe ingresar cantidad aceptable");
					} else if ($('#formulario_agregar_venta')[0].elements.precio.value == 0 || $('#formulario_agregar_venta')[0].elements.precio.value == "") {
						alert("Debe ingresar el precio a la venta");
					} else {
						var parametro = {
							"id": $('#formulario_agregar_venta')[0].elements.id.value,
							"cantidad": $('#formulario_agregar_venta')[0].elements.cantidad.value,
							"precio": $('#formulario_agregar_venta')[0].elements.precio.value,
							"producto": $('#formulario_agregar_venta')[0].elements.producto.value,
							"tipo": $('#formulario_agregar_venta')[0].elements.tipo.value,
							"ruta": "ventas_CO/agregarVenta"
						};

						$.post('index.php', parametro, function(respuesta) {
							var objeto_respuesta = JSON.parse(respuesta);

							if (objeto_respuesta.estado == "EXITO") {
								exito(objeto_respuesta.mensaje);
								$('#formulario_agregar_venta')[0].reset();
								$.post('index.php', {
									"ruta": "ventasMostrador_VI/agregar_ventas_mostrador"
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

				}


				function eliminarVenta(id_venta_mostrador) {

					var parametro = {
						"id_venta_mostrador": id_venta_mostrador,
						"ruta": "ventas_CO/eliminarVenta"
					};


					$.post('index.php', parametro, function(respuesta) {
						var objeto_respuesta = JSON.parse(respuesta);

						if (objeto_respuesta.estado == "EXITO") {
							exito(objeto_respuesta.mensaje);
							// $('#formulario_agregar_factura')[0].reset();

							$.post('index.php', {
								"ruta": "ventasMostrador_VI/agregar_ventas_mostrador"
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
				} // Fin funcion eliminar articulo
			</script>
		<?php
	} //Fin funcion agregar_ventas_mostrador


	function  listar()
	{
		require_once "modelos/ventas_MO.php";
		require_once "modelos/productos_MO.php";

		$fecha_dia = date('Y-m-d');
		if (isset($_POST['fecha_inicial'])) {
			$fecha_inicial = $_POST['fecha_inicial'];
			$fecha_final = $_POST['fecha_final'];
		} else {
			$fecha_inicial = date('Y-m-d');
			$fecha_final = date('Y-m-d');
		}
		$conexion = new servidor('A');
		$ventas_MO = new ventas_MO($conexion);
		$arreglo_ventas = $ventas_MO->seleccionar_fecha($fecha_inicial, $fecha_final);


		$productos_MO = new productos_MO($conexion);
		$arreglo_productos = $productos_MO->seleccionar();


		?>
			<div class="row justify-content-md-center">
				<div class="col mr-4 ml-4 mt-4">
					<div class="card">
						<div class="card-header" style="background-color: #7899B6; color: white">
							<h2 class="card-title"><b>Ventas Mostrador por fechas</b></h2>
						</div> <!-- /.card-header -->
						<div class="card-body">
							<form id="formulario_buscar_venta" method="post">
								<div class="row">
									<div class="col-lg-4">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i>DE:</i></span>
											</div>
											<input type="date" id="fecha_inicial" class="form-control" value="<?php echo date("Y-m-d"); ?>" style=" text-align:center">
										</div><!-- /input-group -->
									</div><!-- /.col-lg-4 -->
									<div class="col-lg-4">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i>A:</i></span>
											</div>
											<input id="fecha_final" type="date" class="form-control" value="<?php echo date("Y-m-d"); ?>" style=" text-align:center">
										</div><!-- /input-group -->
									</div><!-- /.col-lg-6 -->
									<div class="col-lg-2">
										<button type="button" id="cargar" class="btn btn-primary form-control" onclick="buscar()"><i class="fas fa-search"></i>Buscar ventas</button>
									</div><!-- /.col-lg-6 -->
								</div><br><!-- /.row -->


								<table id="listar_ventas" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th class="col-md-1">Codigo</th>
											<th class="col-md-6">Nombre Producto</th>
											<th class="col-md-1">Cant</th>
											<th class="col-md-1">Precio</th>
											<th class="col-md-1">Total</th>
											<th class="col-md-2">Fecha</th>
											<th class="col-md-1">Devolucion</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($arreglo_ventas) {
											foreach ($arreglo_ventas as $objeto_ventas) {

												$id_venta_mostrador = $objeto_ventas->id_venta_mostrador;
												$codigo = $objeto_ventas->codigo;
												$descripcion = $objeto_ventas->descripcion;
												$precio = $objeto_ventas->precio;
												$cantidad = $objeto_ventas->cantidad;
												$total = $objeto_ventas->total;
												$fecha = $objeto_ventas->fecha_creacion;
												$devolucion = $objeto_ventas->devolucion;

												if ($devolucion == "NO") {



										?>
													<tr>
														<td><?php echo $codigo; ?></td>
														<td><?php echo $descripcion; ?></td>
														<td><?php echo $cantidad; ?></td>
														<td><?php echo number_format($precio); ?></td>
														<td><?php echo number_format($total); ?></td>
														<td><?php echo $fecha; ?></td>
														<td style="text-align:center;">
															<i class="fas fa-sign-out-alt" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaDevolucionVenta('<?php echo $id_venta_mostrador; ?>')" title="Devolucion "></i>

														</td>
													</tr>
										<?php
												}
											}
										}
										?>
									</tbody>
								</table>
							</form>
						</div> <!-- /.card-body -->
					</div><!-- /.card -->
				</div>
			</div>


			<script>
				function vistaDevolucionVenta(id_venta) {
					var parametros = {
						"ruta": "devoluciones_VI/devolucion",
						"id_venta": id_venta
					};
					$.post('index.php', parametros, function(respuesta) {
						$('#titulo_modal').html('Devolucion Venta');
						$('#contenido_modal').html(respuesta);
					});
				}

				function buscar() {
					var f_i = $('#formulario_buscar_venta')[0].elements.fecha_inicial.value;
					var f_f = $('#formulario_buscar_venta')[0].elements.fecha_final.value;
					var parametro = {
						"fecha_inicial": $('#formulario_buscar_venta')[0].elements.fecha_inicial.value,
						"fecha_final": $('#formulario_buscar_venta')[0].elements.fecha_final.value,
						"ruta": "ventasMostrador_VI/listar"
					};
					$.post('index.php', parametro, function(respuesta) {
						$('#contenido').html(respuesta);
						$('#fecha_inicial').val(f_i);
						$('#fecha_final').val(f_f);
					});
				}
			</script>
	<?php
	} //Fin funcion listar


}

	?>