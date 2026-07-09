<?php
class categorias_VI
{
	function __construct()
	{
	}

	function  listar()
	{
		require_once "modelos/categorias_MO.php";

		$conexion = new servidor('A');
		$categorias_MO = new categorias_MO($conexion);
		$arreglo_categorias = $categorias_MO->seleccionar();
?>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Categorias</h3>
				<button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#ventana_modal" onclick="vistaAgregarCategoria()"> <i class="far fa-plus-square"></i> Agregar</button>
			</div> <!-- /.card-header -->
			<div class="card-body">
				<table id="listar_categorias" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Categoria</th>
							<th style="text-align:center;">Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($arreglo_categorias) {
							foreach ($arreglo_categorias as $objeto_categorias) {
								$id_categoria = $objeto_categorias->id_categoria;
								$descripcion = $objeto_categorias->descripcion;
								$fecha_creacion = $objeto_categorias->fecha_creacion;
								$fecha_actualizacion = $objeto_categorias->fecha_actualizacion;


						?>
								<tr>
									<td><?php echo $descripcion; ?></td>
									<td style="text-align:center;">
										<i class="fas fa-edit" style="cursor:pointer; margin-right: 10px; color: #0C6766;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaActualizarCategoria('<?php echo $id_categoria; ?>')" title="Actualizar"></i>
										<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaEliminarCategoria('<?php echo $id_categoria; ?>')" title="Eliminar "></i>

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
			function vistaAgregarCategoria() {
				var parametros = {
					"ruta": "categorias_VI/agregar"
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Agregar categoria');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaActualizarCategoria(id_categoria) {
				var parametros = {
					"ruta": "categorias_VI/actualizar",
					"id_categoria": id_categoria
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Actualizar categoria');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaEliminarCategoria(id_categoria) {
				var parametros = {
					"ruta": "categorias_VI/eliminar",
					"id_categoria": id_categoria
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#contenido_modal').html(respuesta);
				});
			}
			data_table_categorias = organizarTabla({
				id: "listar_categorias"
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
				<h3 class="card-title">Datos Categorias</h3>
			</div>
			<div class="card-body">
				<form id="formulario_agregar_categorias">
					<input class="form-control form-control-lg" type="text" id="descripcion" name="descripcion" placeholder="Descripcion categoria" autocomplete="on"> <br>
					<button type="button" class="btn btn-primary float-right" onclick="controladorAgregarCategoria()"><i class="fas fa-save"></i> Guardar</button>
				</form>
			</div><!-- /.card-body -->
		</div><!-- Form Element sizes -->


		<script>
			function controladorAgregarCategoria() {
				if ($('#formulario_agregar_categorias')[0].elements.descripcion.value == "") {
					alert("Debe ingresar la categoria");
				} else {
					var parametros = {
						"ruta": "categorias_CO/agregar",
						"descripcion": $('#formulario_agregar_categorias')[0].elements.descripcion.value
					};
					$.post('index.php', parametros, function(respuesta) {
						var objeto_respuesta = JSON.parse(respuesta);
						if (objeto_respuesta.estado == "EXITO") {
							exito(objeto_respuesta.mensaje);
							$('#formulario_agregar_categorias')[0].reset();

							$.post('index.php', {
								"ruta": "categorias_VI/listar"
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
		require_once "modelos/categorias_MO.php";
		$conexion = new servidor('A');
		$categorias_MO = new categorias_MO($conexion);
		$id_categoria = $_POST["id_categoria"];
		$arreglo_categorias = $categorias_MO->seleccionar("id_categoria", $id_categoria);
		$id_categoria = $arreglo_categorias[0]->id_categoria;
		$descripcion = $arreglo_categorias[0]->descripcion;
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos categorias</h3>
			</div>
			<div class="card-body">
				<form id="formulario_actualizar_categorias" method="post">
					<input type="hidden" id="id_categoria" name="id_categoria" value="<?php echo $id_categoria; ?>">
					<input class="form-control form-control-lg" type="text" id="descripcion" name="descripcion" placeholder="Descripcion" value="<?php echo $descripcion; ?>" autocomplete="on"><br>

					<button type="button" class="btn btn-primary float-right" onclick="controladorActualizarCategoria()"><i class="fas fa-save"></i> Guardar</button>
				</form>
			</div><!-- /.card-body -->
		</div>

		<script>
			function controladorActualizarCategoria() {
				var cadena = $('#formulario_actualizar_categorias').serialize();
				var parametro = {
					"ruta": "categorias_CO/actualizar",
					"id_categoria": $('#formulario_actualizar_categorias')[0].elements.id_categoria.value,
					"descripcion": $('#formulario_actualizar_categorias')[0].elements.descripcion.value

				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						//Pendiente en actualizacion de usuario
						$('#formulario_actualizar_categorias').modal('hide');

						$.post('index.php', {
							"ruta": "categorias_VI/listar"
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
		require_once "modelos/categorias_MO.php";
		$conexion = new servidor('A');
		$categorias_MO = new categorias_MO($conexion);
		$id_categoria = $_POST["id_categoria"];


	?>
		<div class="card card-danger">
			<div class="card-header">
				<h3 class="card-title">Eliminar Categoria</h3>
			</div>
			<div class="card-body">
				<form id="formulario_eliminar_categorias" method="post">
					<center><label>
							<h1>¿Desea eliminar la categoria?</h1>
						</label></center><br>
					<input type="hidden" id="id_categoria" name="id_categoria" value="<?php echo $id_categoria; ?>">
					<button type="button" class="btn btn-danger btn-lg btn-block" onclick="controladorEliminarCategoria(<?php echo $id_categoria; ?>)">Deseo continuar</button> <br> <button type="button" class="btn btn-success btn-lg btn-block" data-dismiss="modal">Cerrar</button> <br>
				</form>
			</div>
		</div>


		<script>
			function controladorEliminarCategoria(id) {
				//var cadena=$('#formulario_eliminar_categorias').serialize();			        	 		
				var parametro = {
					"id_categoria": id,
					"ruta": "categorias_CO/eliminar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						$.post('index.php', {
							"ruta": "categorias_VI/listar"
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