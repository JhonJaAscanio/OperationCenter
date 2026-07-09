<?php
class devoluciones_VI
{
	function __construct()
	{
	}

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
					<div class="card-header" style="background-color: #D61C13; color: white">
						<h2 class="card-title"><b>Devoluciones Ventas Mostrador</b></h2>
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
									<button type="button" id="cargar" class="btn btn-primary form-control" onclick="buscar()"><i class="fas fa-search"></i>Buscar Devolucion</button>
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
											$fecha = $objeto_ventas->fecha_actualizacion;
											$devolucion = $objeto_ventas->devolucion;

											if ($devolucion == "SI") {


									?>
												<tr>
													<td><?php echo $codigo; ?></td>
													<td><?php echo $descripcion; ?></td>
													<td><?php echo $cantidad; ?></td>
													<td><?php echo number_format($precio); ?></td>
													<td><?php echo number_format($total); ?></td>
													<td><?php echo $fecha; ?></td>
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
			function buscar() {
				var f_i = $('#formulario_buscar_venta')[0].elements.fecha_inicial.value;
				var f_f = $('#formulario_buscar_venta')[0].elements.fecha_final.value;
				var parametro = {
					"fecha_inicial": $('#formulario_buscar_venta')[0].elements.fecha_inicial.value,
					"fecha_final": $('#formulario_buscar_venta')[0].elements.fecha_final.value,
					"ruta": "devoluciones_VI/listar"
				};
				$.post('index.php', parametro, function(respuesta) {
					$('#contenido').html(respuesta);
					$('#fecha_inicial').val(f_i);
					$('#fecha_final').val(f_f);
				});
			}

			$(document).ready(function() {
				$('#listar_ventas').DataTable({
					"order": [
						[5, "desc"]
					]
				});
			});
		</script>
	<?php
	} //Fin funcion listar




	function devolucion()
	{

		require_once "modelos/ventas_MO.php";
		$id_venta = $_POST["id_venta"];
		$conexion = new servidor('A');
		$ventas_MO = new ventas_MO($conexion);
		$arreglo_ventas = $ventas_MO->seleccionar_mostrador("id_venta_mostrador", $id_venta);
		$codigo = $arreglo_ventas[0]->codigo;
		$descripcion = $arreglo_ventas[0]->descripcion;
		$cantidad = $arreglo_ventas[0]->cantidad;
		$precio = $arreglo_ventas[0]->precio;
		$total = $arreglo_ventas[0]->total;
		$fecha = $arreglo_ventas[0]->fecha_creacion;

	?>
		<!-- Form Element sizes -->
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos Venta</h3>
			</div>
			<div class="card-body">
				<form id="formulario_venta">
					<table id="listar_ventas" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th class="col-md-1">Codigo</th>
								<th class="col-md-4">Decripcion</th>
								<th class="col-md-1">Cantidad</th>
								<th class="col-md-1">Precio</th>
								<th class="col-md-1">Total</th>
								<th class="col-md-2">Fecha</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $codigo  ?></td>
								<td><?php echo $descripcion  ?></td>
								<td><?php echo $cantidad  ?></td>
								<td><?php echo $precio  ?></td>
								<td><?php echo $total  ?></td>
								<td><?php echo $fecha  ?></td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="id_venta" id="id_venta" value="<?php echo $id_venta ?>">
					<input type="hidden" name="cantidad_venta" id="cantidad_venta" value="<?php echo $cantidad ?>">
					<input type="hidden" name="precio" id="precio" value="<?php echo $precio ?>">
					<div class="row">
						<div class="col-lg-4"></div>
						<div class="col-lg-4">Cantidad a devolver
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-cart-plus"></i>
									</span>
								</div>
								<input type="number" id="cantidad" class="form-control" value="1" style=" text-align:center">
							</div>
							<!-- /input-group -->
						</div>

						<div class="col">
							<button type="button" class="btn btn-primary float-right mt-4" onclick="controladorDevolucionVenta()"><i class="fas fa-save"></i> Confirmar</button>
						</div>


					</div>

				</form>
			</div><!-- /.card-body -->
		</div><!-- Form Element sizes -->

		<script>
			function controladorDevolucionVenta() {
				var cantidad_venta = $('#formulario_venta')[0].elements.cantidad_venta.value;
				var cantidad_devolver = $('#formulario_venta')[0].elements.cantidad.value;
				var cantidad_actualizada = cantidad_venta - cantidad_devolver;

				if (cantidad_devolver <= cantidad_venta && cantidad_devolver > 0) {

					if (cantidad_venta == cantidad_devolver) // Se actualizar la venta en "devolucion"= SI
					{
						var parametro = {
							"ruta": "devoluciones_CO/devolucion_total",
							"id_venta": $('#formulario_venta')[0].elements.id_venta.value
						}

						$.post('index.php', parametro, function(respuesta) {
							var objeto_respuesta = JSON.parse(respuesta);

							if (objeto_respuesta.estado == "EXITO") {
								exito(objeto_respuesta.mensaje);
								$.post('index.php', {
									"ruta": "devoluciones_VI/listar"
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
					} else // Se actualiza la cantidad a devolver en la venta y se deja en "devolucion"= NO y se crea una nueva venta con la devolucion en "SI"
					{
						var parametro = {
							"ruta": "devoluciones_CO/devolucion_cantidad",
							"cantidad_actualizar": cantidad_actualizada,
							"cantidad_devolver": cantidad_devolver,
							"id_venta": $('#formulario_venta')[0].elements.id_venta.value,
							"precio": $('#formulario_venta')[0].elements.precio.value
						};

						$.post('index.php', parametro, function(respuesta) {
							var objeto_respuesta = JSON.parse(respuesta);

							if (objeto_respuesta.estado == "EXITO") {
								exito(objeto_respuesta.mensaje);
								$.post('index.php', {
									"ruta": "devoluciones_VI/listar"
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


				} else {
					alert("Cantidad incorrecta");
				}
			}
		</script>

<?php
	} //Fin funcion devolucion







}

?>