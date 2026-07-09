<?php
class egresos_VI
{
	function __construct()
	{
	}

	function  listar()
	{
		require_once "modelos/egresos_MO.php";

		$conexion = new servidor('A');
		$egresos_MO = new egresos_MO($conexion);
		$arreglo_egresos = $egresos_MO->seleccionar();
		$item = 0;
?>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Egresos</h3>
				<button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#ventana_modal" onclick="vistaAgregarEgreso()"> <i class="far fa-plus-square"></i> Agregar</button>
			</div> <!-- /.card-header -->
			<div class="card-body">
				<table id="listar_egresos" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Items</th>
							<th class="col-sm-6">Concepto</th>
							<th class="col-sm-2">Valor</th>
							<th class="col-sm-2">Fecha</th>
							<th class="col-sm-2" style="text-align:center;">Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($arreglo_egresos) {
							foreach ($arreglo_egresos as $arreglo_egreso) {
								$item++;
								$id_egreso = $arreglo_egreso->id_egreso;
								$concepto = $arreglo_egreso->concepto;
								$valor = $arreglo_egreso->valor;
								$fecha = $arreglo_egreso->fecha;
								$fecha_creacion = $arreglo_egreso->fecha_creacion;
								$fecha_actualizacion = $arreglo_egreso->fecha_actualizacion;


						?>
								<tr>
									<td scope="row"><?php echo $item; ?></td>
									<td class="col-sm-6"><?php echo $concepto; ?></td>
									<td class="col-sm-2"><?php echo number_format($valor); ?></td>
									<td class="col-sm-2"><?php echo $fecha; ?></td>
									<td class="col-sm-2" style="text-align:center;">
										<i class="fas fa-edit" style="cursor:pointer; margin-right: 10px; color: #0C6766;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaActualizarEgreso('<?php echo $id_egreso; ?>')" title="Actualizar"></i>
										<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaEliminarEgreso('<?php echo $id_egreso; ?>')" title="Eliminar "></i>

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
			function vistaAgregarEgreso() {
				var date = new Date(); //Fecha actual
				var mes = date.getMonth() + 1; //obteniendo mes
				var dia = date.getDate(); //obteniendo dia
				var ano = date.getFullYear(); //obteniendo año
				if (dia < 10)
					dia = '0' + dia; //agrega cero si el menor de 10
				if (mes < 10)
					mes = '0' + mes //agrega cero si el menor de 10  


				var parametros = {
					"ruta": "egresos_VI/agregar"
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Agregar egreso');
					$('#contenido_modal').html(respuesta);
					document.getElementById('fecha').value = ano + "-" + mes + "-" + dia;
				});
			}

			function vistaActualizarEgreso(id_egreso) {
				var parametros = {
					"ruta": "egresos_VI/actualizar",
					"id_egreso": id_egreso
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Actualizar egreso');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaEliminarEgreso(id_egreso) {
				var parametros = {
					"ruta": "egresos_VI/eliminar",
					"id_egreso": id_egreso
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#contenido_modal').html(respuesta);
				});
			}
			data_table_egresos = organizarTabla({
				id: "listar_egresos"
			});
		</script>
	<?php
	} //Fin funcion listar





	function agregar()
	{

	?>
		<!-- Form Element sizes -->
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos Egresos</h3>
			</div>
			<div class="card-body">
				<form id="formulario_agregar_egresos">
					<label style=" font-size: 25px">Fecha:</label>
					<input class="form-control" type="date" id="fecha" name="fecha" placeholder="Fecha" autocomplete="on"> <br>
					<label style=" font-size: 25px">Concepto:</label>
					<input class="form-control" type="text" id="concepto" name="concepto" placeholder="Concepto" autocomplete="on"> <br>
					<label style="font-size: 25px">Valor:</label>
					<input class="form-control" type="number" id="valor" name="valor" placeholder="Valor" autocomplete="on"> <br>
					<button type="button" class="btn btn-primary float-right" onclick="controladorAgregarEgreso()"><i class="fas fa-save"></i> Guardar</button>
				</form>
			</div><!-- /.card-body -->
		</div><!-- Form Element sizes -->


		<script>
			function controladorAgregarEgreso() {
				if ($('#formulario_agregar_egresos')[0].elements.concepto.value == "" || $('#formulario_agregar_egresos')[0].elements.valor.value == "") {
					alert("Debe ingresar todos los datos");
				} else {
					var parametros = {
						"ruta": "egresos_CO/agregar",
						"fecha": $('#formulario_agregar_egresos')[0].elements.fecha.value,
						"concepto": $('#formulario_agregar_egresos')[0].elements.concepto.value,
						"valor": $('#formulario_agregar_egresos')[0].elements.valor.value
					};
					$.post('index.php', parametros, function(respuesta) {
						var objeto_respuesta = JSON.parse(respuesta);
						if (objeto_respuesta.estado == "EXITO") {
							exito(objeto_respuesta.mensaje);
							$('#formulario_agregar_egresos')[0].reset();

							$.post('index.php', {
								"ruta": "egresos_VI/listar"
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
		</script>

	<?php
	} //Fin funcion Agregar



	function actualizar()
	{
		require_once "modelos/egresos_MO.php";
		$conexion = new servidor('A');
		$egresos_MO = new egresos_MO($conexion);
		$id_egreso = $_POST["id_egreso"];
		$arreglo_egresos = $egresos_MO->seleccionar("id_egreso", $id_egreso);
		$id_egreso = $arreglo_egresos[0]->id_egreso;
		$fecha = $arreglo_egresos[0]->fecha;
		$concepto = $arreglo_egresos[0]->concepto;
		$valor = $arreglo_egresos[0]->valor;
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos egresos</h3>
			</div>
			<div class="card-body">
				<form id="formulario_actualizar_egresos" method="post">
					<input type="hidden" id="id_egreso" name="id_egreso" value="<?php echo $id_egreso; ?>">
					<label style=" font-size: 25px">Fecha:</label>
					<input class="form-control" type="text" id="fecha" name="fecha" placeholder="Fecha" value="<?php echo $fecha; ?>" autocomplete="on" readonly><br>

					<label style="font-size: 25px">Concepto:</label>
					<input class="form-control" type="text" id="concepto" name="concepto" placeholder="Fecha" value="<?php echo $concepto; ?>" autocomplete="on"><br>

					<label style=" font-size: 25px">Valor:</label>
					<input class="form-control" type="text" id="valor" name="valor" placeholder="Valor" value="<?php echo $valor; ?>" autocomplete="on"><br>
					<button type="button" class="btn btn-primary float-right" onclick="controladorActualizarEgreso()"><i class="fas fa-save"></i> Guardar</button>
				</form>
			</div><!-- /.card-body -->
		</div>

		<script>
			function controladorActualizarEgreso() {
				var cadena = $('#formulario_actualizar_egresos').serialize();
				var parametro = {
					"ruta": "egresos_CO/actualizar",
					"id_egreso": $('#formulario_actualizar_egresos')[0].elements.id_egreso.value,
					"fecha": $('#formulario_actualizar_egresos')[0].elements.fecha.value,
					"concepto": $('#formulario_actualizar_egresos')[0].elements.concepto.value,
					"valor": $('#formulario_actualizar_egresos')[0].elements.valor.value

				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						//Pendiente en actualizacion de usuario
						$('#formulario_actualizar_egresos').modal('hide');

						$.post('index.php', {
							"ruta": "egresos_VI/listar"
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





	function eliminar()
	{
		require_once "modelos/egresos_MO.php";
		$conexion = new servidor('A');
		$egresos_MO = new egresos_MO($conexion);
		$id_egreso = $_POST["id_egreso"];


	?>
		<div class="card card-danger">
			<div class="card-header">
				<h3 class="card-title">Eliminar Egreso</h3>
			</div>
			<div class="card-body">
				<form id="formulario_eliminar_egresos" method="post">
					<center><label>
							<h1>¿Desea eliminar la egreso?</h1>
						</label></center><br>
					<input type="hidden" id="id_egreso" name="id_egreso" value="<?php echo $id_egreso; ?>">
					<button type="button" class="btn btn-danger btn-lg btn-block" onclick="controladorEliminarEgreso(<?php echo $id_egreso; ?>)">Deseo continuar</button> <br> <button type="button" class="btn btn-success btn-lg btn-block" data-dismiss="modal">Cerrar</button> <br>
				</form>
			</div>
		</div>


		<script>
			function controladorEliminarEgreso(id) {
				//var cadena=$('#formulario_eliminar_egresos').serialize();			        	 		
				var parametro = {
					"id_egreso": id,
					"ruta": "egresos_CO/eliminar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						$.post('index.php', {
							"ruta": "egresos_VI/listar"
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