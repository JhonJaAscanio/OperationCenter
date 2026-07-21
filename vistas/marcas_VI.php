<?php
class marcas_VI
{
	function __construct()
	{
	}

	function  listar()
	{
		require_once "modelos/marcas_MO.php";

		$conexion = new servidor('A');
		$marcas_MO = new marcas_MO($conexion);
		$arreglo_marcas = $marcas_MO->seleccionar();
	?>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Marcas</h3>
				<button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#ventana_modal" onclick="vistaAgregarMarca()"> <i class="far fa-plus-square"></i> Agregar</button>
			</div> <!-- /.card-header -->
			<div class="card-body">
				<table id="listar_marcas" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Marca</th>
							<th style="text-align:center;">Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($arreglo_marcas) {
							foreach ($arreglo_marcas as $objeto_marcas) {
								$id_marca = $objeto_marcas->id_marca;
								$descripcion = $objeto_marcas->descripcion;
						?>
								<tr>
									<td><?php echo htmlspecialchars($descripcion, ENT_QUOTES); ?></td>
									<td style="text-align:center;">
										<i class="fas fa-edit" style="cursor:pointer; margin-right: 10px; color: #0C6766;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaActualizarMarca('<?php echo (int) $id_marca; ?>')" title="Actualizar"></i>
										<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaEliminarMarca('<?php echo (int) $id_marca; ?>')" title="Eliminar "></i>
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
			function vistaAgregarMarca() {
				var parametros = {
					"ruta": "marcas_VI/agregar"
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Agregar marca');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaActualizarMarca(id_marca) {
				var parametros = {
					"ruta": "marcas_VI/actualizar",
					"id_marca": id_marca
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Actualizar marca');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaEliminarMarca(id_marca) {
				var parametros = {
					"ruta": "marcas_VI/eliminar",
					"id_marca": id_marca
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#contenido_modal').html(respuesta);
				});
			}
			data_table_marcas = organizarTabla({
				id: "listar_marcas"
			});
		</script>
	<?php
	} //Fin funcion listar


	function agregar()
	{
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos Marca</h3>
			</div>
			<div class="card-body">
				<form id="formulario_agregar_marcas">
					<input class="form-control form-control-lg" type="text" id="descripcion" name="descripcion" placeholder="Descripcion marca" autocomplete="on"> <br>
					<button type="button" class="btn btn-primary float-right" onclick="controladorAgregarMarca()"><i class="fas fa-save"></i> Guardar</button>
				</form>
			</div><!-- /.card-body -->
		</div>

		<script>
			function controladorAgregarMarca() {
				if ($('#formulario_agregar_marcas')[0].elements.descripcion.value == "") {
					alert("Debe ingresar la marca");
				} else {
					var parametros = {
						"ruta": "marcas_CO/agregar",
						"descripcion": $('#formulario_agregar_marcas')[0].elements.descripcion.value
					};
					$.post('index.php', parametros, function(respuesta) {
						var objeto_respuesta = JSON.parse(respuesta);
						if (objeto_respuesta.estado == "EXITO") {
							exito(objeto_respuesta.mensaje);
							$('#formulario_agregar_marcas')[0].reset();

							$.post('index.php', {
								"ruta": "marcas_VI/listar"
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
		require_once "modelos/marcas_MO.php";
		$conexion = new servidor('A');
		$marcas_MO = new marcas_MO($conexion);
		$id_marca = $_POST["id_marca"];
		$arreglo_marcas = $marcas_MO->seleccionar("id_marca", $id_marca);
		$id_marca = $arreglo_marcas[0]->id_marca;
		$descripcion = $arreglo_marcas[0]->descripcion;
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos marca</h3>
			</div>
			<div class="card-body">
				<form id="formulario_actualizar_marcas" method="post">
					<input type="hidden" id="id_marca" name="id_marca" value="<?php echo (int) $id_marca; ?>">
					<input class="form-control form-control-lg" type="text" id="descripcion" name="descripcion" placeholder="Descripcion" value="<?php echo htmlspecialchars($descripcion, ENT_QUOTES); ?>" autocomplete="on"><br>

					<button type="button" class="btn btn-primary float-right" onclick="controladorActualizarMarca()"><i class="fas fa-save"></i> Guardar</button>
				</form>
			</div><!-- /.card-body -->
		</div>

		<script>
			function controladorActualizarMarca() {
				var parametro = {
					"ruta": "marcas_CO/actualizar",
					"id_marca": $('#formulario_actualizar_marcas')[0].elements.id_marca.value,
					"descripcion": $('#formulario_actualizar_marcas')[0].elements.descripcion.value
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);
						$('#formulario_actualizar_marcas').modal('hide');

						$.post('index.php', {
							"ruta": "marcas_VI/listar"
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
		require_once "modelos/marcas_MO.php";
		$conexion = new servidor('A');
		$marcas_MO = new marcas_MO($conexion);
		$id_marca = $_POST["id_marca"];
	?>
		<div class="card card-danger">
			<div class="card-header">
				<h3 class="card-title">Eliminar Marca</h3>
			</div>
			<div class="card-body">
				<form id="formulario_eliminar_marcas" method="post">
					<center><label>
							<h1>¿Desea eliminar la marca?</h1>
						</label></center><br>
					<input type="hidden" id="id_marca" name="id_marca" value="<?php echo (int) $id_marca; ?>">
					<button type="button" class="btn btn-danger btn-lg btn-block" onclick="controladorEliminarMarca(<?php echo (int) $id_marca; ?>)">Deseo continuar</button> <br> <button type="button" class="btn btn-success btn-lg btn-block" data-dismiss="modal">Cerrar</button> <br>
				</form>
			</div>
		</div>

		<script>
			function controladorEliminarMarca(id) {
				var parametro = {
					"id_marca": id,
					"ruta": "marcas_CO/eliminar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						$.post('index.php', {
							"ruta": "marcas_VI/listar"
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
