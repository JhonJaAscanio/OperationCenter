<?php
class subcategorias_VI
{
	function __construct()
	{
	}

	// Devuelve las <option> de las subcategorias de una categoria (usado por el
	// select en cascada del formulario de productos via AJAX).
	function opciones()
	{
		require_once "modelos/subcategorias_MO.php";

		$conexion = new servidor('A');
		$subcategorias_MO = new subcategorias_MO($conexion);
		$id_categoria = $_POST["id_categoria"];
		$arreglo_subcategorias = $subcategorias_MO->seleccionarPorCategoria($id_categoria);

		if ($arreglo_subcategorias) {
			foreach ($arreglo_subcategorias as $s) {
				echo '<option value="' . (int) $s->id_subcategoria . '">' . htmlspecialchars($s->descripcion, ENT_QUOTES) . '</option>';
			}
		}
	}

	function  listar()
	{
		require_once "modelos/subcategorias_MO.php";
		require_once "modelos/categorias_MO.php";

		$conexion = new servidor('A');
		$subcategorias_MO = new subcategorias_MO($conexion);
		$categorias_MO = new categorias_MO($conexion);
		$arreglo_subcategorias = $subcategorias_MO->seleccionar();
		$arreglo_categorias_indice = [];
		foreach ($categorias_MO->seleccionar() as $c) {
			$arreglo_categorias_indice[$c->id_categoria] = $c->descripcion;
		}
	?>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Subcategorias</h3>
				<button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#ventana_modal" onclick="vistaAgregarSubcategoria()"> <i class="far fa-plus-square"></i> Agregar</button>
			</div> <!-- /.card-header -->
			<div class="card-body">
				<table id="listar_subcategorias" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Categoria</th>
							<th>Subcategoria</th>
							<th style="text-align:center;">Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($arreglo_subcategorias) {
							foreach ($arreglo_subcategorias as $objeto_subcategorias) {
								$id_subcategoria = $objeto_subcategorias->id_subcategoria;
								$id_categoria = $objeto_subcategorias->id_categoria;
								$descripcion = $objeto_subcategorias->descripcion;
								$nombre_categoria = isset($arreglo_categorias_indice[$id_categoria]) ? $arreglo_categorias_indice[$id_categoria] : '';
						?>
								<tr>
									<td><?php echo htmlspecialchars($nombre_categoria, ENT_QUOTES); ?></td>
									<td><?php echo htmlspecialchars($descripcion, ENT_QUOTES); ?></td>
									<td style="text-align:center;">
										<i class="fas fa-edit" style="cursor:pointer; margin-right: 10px; color: #0C6766;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaActualizarSubcategoria('<?php echo (int) $id_subcategoria; ?>')" title="Actualizar"></i>
										<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaEliminarSubcategoria('<?php echo (int) $id_subcategoria; ?>')" title="Eliminar "></i>
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
			function vistaAgregarSubcategoria() {
				var parametros = {
					"ruta": "subcategorias_VI/agregar"
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Agregar subcategoria');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaActualizarSubcategoria(id_subcategoria) {
				var parametros = {
					"ruta": "subcategorias_VI/actualizar",
					"id_subcategoria": id_subcategoria
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Actualizar subcategoria');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaEliminarSubcategoria(id_subcategoria) {
				var parametros = {
					"ruta": "subcategorias_VI/eliminar",
					"id_subcategoria": id_subcategoria
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#contenido_modal').html(respuesta);
				});
			}
			data_table_subcategorias = organizarTabla({
				id: "listar_subcategorias"
			});
		</script>
	<?php
	} //Fin funcion listar


	function agregar()
	{
		require_once "modelos/categorias_MO.php";
		$conexion = new servidor('A');
		$categorias_MO = new categorias_MO($conexion);
		$arreglo_categorias = $categorias_MO->seleccionar();
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos Subcategoria</h3>
			</div>
			<div class="card-body">
				<form id="formulario_agregar_subcategorias">
					<label class="control-label">Categoria</label>
					<select class="form-control form-control-lg" name="id_categoria" id="id_categoria">
						<option value="" disabled selected>Categoria:</option>
						<?php
						if ($arreglo_categorias) {
							foreach ($arreglo_categorias as $objeto_categoria) {
						?>
								<option value="<?php echo (int) $objeto_categoria->id_categoria; ?>"><?php echo htmlspecialchars($objeto_categoria->descripcion, ENT_QUOTES); ?></option>
						<?php
							}
						}
						?>
					</select><br>
					<label class="control-label">Subcategoria</label>
					<input class="form-control form-control-lg" type="text" id="descripcion" name="descripcion" placeholder="Descripcion subcategoria" autocomplete="on"> <br>
					<button type="button" class="btn btn-primary float-right" onclick="controladorAgregarSubcategoria()"><i class="fas fa-save"></i> Guardar</button>
				</form>
			</div><!-- /.card-body -->
		</div>

		<script>
			function controladorAgregarSubcategoria() {
				var id_categoria = $('#formulario_agregar_subcategorias')[0].elements.id_categoria.value;
				var descripcion = $('#formulario_agregar_subcategorias')[0].elements.descripcion.value;
				if (id_categoria == "" || descripcion == "") {
					alert("Debe seleccionar la categoria e ingresar la subcategoria");
				} else {
					var parametros = {
						"ruta": "subcategorias_CO/agregar",
						"id_categoria": id_categoria,
						"descripcion": descripcion
					};
					$.post('index.php', parametros, function(respuesta) {
						var objeto_respuesta = JSON.parse(respuesta);
						if (objeto_respuesta.estado == "EXITO") {
							exito(objeto_respuesta.mensaje);
							$('#formulario_agregar_subcategorias')[0].reset();

							$.post('index.php', {
								"ruta": "subcategorias_VI/listar"
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
		require_once "modelos/subcategorias_MO.php";
		require_once "modelos/categorias_MO.php";
		$conexion = new servidor('A');
		$subcategorias_MO = new subcategorias_MO($conexion);
		$categorias_MO = new categorias_MO($conexion);
		$id_subcategoria = $_POST["id_subcategoria"];
		$arreglo_subcategorias = $subcategorias_MO->seleccionar("id_subcategoria", $id_subcategoria);
		$id_subcategoria = $arreglo_subcategorias[0]->id_subcategoria;
		$id_categoria = $arreglo_subcategorias[0]->id_categoria;
		$descripcion = $arreglo_subcategorias[0]->descripcion;
		$arreglo_categorias = $categorias_MO->seleccionar();
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos subcategoria</h3>
			</div>
			<div class="card-body">
				<form id="formulario_actualizar_subcategorias" method="post">
					<input type="hidden" id="id_subcategoria" name="id_subcategoria" value="<?php echo (int) $id_subcategoria; ?>">
					<label class="control-label">Categoria</label>
					<select class="form-control form-control-lg" name="id_categoria" id="id_categoria">
						<?php
						if ($arreglo_categorias) {
							foreach ($arreglo_categorias as $objeto_categoria) {
								$seleccionada = ($objeto_categoria->id_categoria == $id_categoria) ? 'selected' : '';
						?>
								<option value="<?php echo (int) $objeto_categoria->id_categoria; ?>" <?php echo $seleccionada; ?>><?php echo htmlspecialchars($objeto_categoria->descripcion, ENT_QUOTES); ?></option>
						<?php
							}
						}
						?>
					</select><br>
					<label class="control-label">Subcategoria</label>
					<input class="form-control form-control-lg" type="text" id="descripcion" name="descripcion" placeholder="Descripcion" value="<?php echo htmlspecialchars($descripcion, ENT_QUOTES); ?>" autocomplete="on"><br>

					<button type="button" class="btn btn-primary float-right" onclick="controladorActualizarSubcategoria()"><i class="fas fa-save"></i> Guardar</button>
				</form>
			</div><!-- /.card-body -->
		</div>

		<script>
			function controladorActualizarSubcategoria() {
				var parametro = {
					"ruta": "subcategorias_CO/actualizar",
					"id_subcategoria": $('#formulario_actualizar_subcategorias')[0].elements.id_subcategoria.value,
					"id_categoria": $('#formulario_actualizar_subcategorias')[0].elements.id_categoria.value,
					"descripcion": $('#formulario_actualizar_subcategorias')[0].elements.descripcion.value
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);
						$('#formulario_actualizar_subcategorias').modal('hide');

						$.post('index.php', {
							"ruta": "subcategorias_VI/listar"
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
		require_once "modelos/subcategorias_MO.php";
		$conexion = new servidor('A');
		$subcategorias_MO = new subcategorias_MO($conexion);
		$id_subcategoria = $_POST["id_subcategoria"];
	?>
		<div class="card card-danger">
			<div class="card-header">
				<h3 class="card-title">Eliminar Subcategoria</h3>
			</div>
			<div class="card-body">
				<form id="formulario_eliminar_subcategorias" method="post">
					<center><label>
							<h1>¿Desea eliminar la subcategoria?</h1>
						</label></center><br>
					<input type="hidden" id="id_subcategoria" name="id_subcategoria" value="<?php echo (int) $id_subcategoria; ?>">
					<button type="button" class="btn btn-danger btn-lg btn-block" onclick="controladorEliminarSubcategoria(<?php echo (int) $id_subcategoria; ?>)">Deseo continuar</button> <br> <button type="button" class="btn btn-success btn-lg btn-block" data-dismiss="modal">Cerrar</button> <br>
				</form>
			</div>
		</div>

		<script>
			function controladorEliminarSubcategoria(id) {
				var parametro = {
					"id_subcategoria": id,
					"ruta": "subcategorias_CO/eliminar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						$.post('index.php', {
							"ruta": "subcategorias_VI/listar"
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
