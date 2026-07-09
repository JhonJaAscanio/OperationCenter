<?php
class proveedores_VI
{
	function __construct()
	{
	}

	function  listar()
	{
		require_once "modelos/proveedores_MO.php";


		$conexion = new servidor('A');
		$proveedores_MO = new proveedores_MO($conexion);
		$arreglo_proveedores = $proveedores_MO->seleccionar();



?>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Proveedor</h3>
				<button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#ventana_modal" onclick="vistaAgregarProveedor()"> <i class="far fa-plus-square"></i> Agregar</button>
			</div> <!-- /.card-header -->
			<div class="card-body">
				<table id="listar_proveedores" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Telefono</th>
							<th>Direcci&oacute;n</th>
							<th>Ciudad</th>
							<th style="text-align:center;">Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($arreglo_proveedores) {
							foreach ($arreglo_proveedores as $objeto_proveedores) {
								$id_proveedor = $objeto_proveedores->id_proveedor;
								$nombre = $objeto_proveedores->nombre;
								$telefono = $objeto_proveedores->telefono;
								$direccion = $objeto_proveedores->direccion;
								$ciudad = $objeto_proveedores->ciudad;
								$fecha_creacion = $objeto_proveedores->fecha_creacion;
								$fecha_actualizacion = $objeto_proveedores->fecha_actualizacion;


						?>
								<tr>
									<td><?php echo $nombre; ?></td>
									<td><?php echo $telefono; ?></td>
									<td><?php echo $direccion; ?></td>
									<td><?php echo $ciudad; ?></td>
									<td style="text-align:center;">
										<i class="fas fa-edit" style="cursor:pointer; margin-right: 10px; color: #0C6766;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaActualizarProveedor('<?php echo $id_proveedor; ?>')" title="Actualizar"></i>
										<i class="far fa-eye" style="cursor:pointer; margin-right: 10px;color: #697E0A;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaConsultarProveedor('<?php echo $id_proveedor; ?>')" title="Consultar"></i>
										<!--	<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaEliminarProveedor('<?php echo $id_proveedor; ?>')" title="Eliminar "></i>  -->

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
			function vistaAgregarProveedor() {
				var parametros = {
					"ruta": "proveedores_VI/agregar"
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Agregar proveedor');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaActualizarProveedor(id_proveedor) {
				var parametros = {
					"ruta": "proveedores_VI/actualizar",
					"id_proveedor": id_proveedor
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Actualizar proveedor');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaConsultarProveedor(id_proveedor) {
				var parametros = {
					"ruta": "proveedores_VI/consultar",
					"id_proveedor": id_proveedor
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Consultar proveedor');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaEliminarProveedor(id_proveedor) {
				var parametros = {
					"ruta": "proveedores_VI/eliminar",
					"id_proveedor": id_proveedor
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#contenido_modal').html(respuesta);
				});
			}
			data_table_proveedores = organizarTabla({
				id: "listar_proveedores"
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
				<h3 class="card-title">Datos Proveedores</h3>
			</div>
			<div class="card-body">
				<form id="formulario_agregar_proveedores">
					<div class="row">
						<div class="col-6">
							<input class="form-control form-control-lg" type="text" id="nombre" name="nombre" placeholder="Nombre" autocomplete="on">
							<br>
							<input class="form-control form-control-lg" type="text" id="telefono" name="telefono" placeholder="Telefono" autocomplete="on"><br>
						</div>
						<div class="col-6">
							<input class="form-control form-control-lg" type="text" id="direccion" name="direccion" placeholder="Direccion" autocomplete="on"><br>
							<input class="form-control form-control-lg" type="text" id="ciudad" name="ciudad" placeholder="Ciudad" autocomplete="on"> <br>
						</div>
						<button type="button" class="btn btn-primary float-center btn-lg" onclick="controladorAgregarProveedores()"><i class="fas fa-save"></i> Guardar</button>

					</div>
				</form>
			</div><!-- /.card-body -->
		</div><!-- Form Element sizes -->


		<script>
			function controladorAgregarProveedores() {
				var cadena = $('#formulario_agregar_proveedores').serialize();
				var parametros = {
					"ruta": "proveedores_CO/agregar",
					"nombre": $('#formulario_agregar_proveedores')[0].elements.nombre.value,
					"telefono": $('#formulario_agregar_proveedores')[0].elements.telefono.value,
					"direccion": $('#formulario_agregar_proveedores')[0].elements.direccion.value,
					"ciudad": $('#formulario_agregar_proveedores')[0].elements.ciudad.value
				};
				$.post('index.php', parametros, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);
					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);
						$('#formulario_agregar_proveedores')[0].reset();

						$.post('index.php', {
							"ruta": "proveedores_VI/listar"
						}, function(res) {
							$('#contenido').html(res);
						});

						//let boton='<div style="text-align:center;"><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ventana_modal" onclick=vistaActualizarProveedor("'+objeto_respuesta.id_proveedor+'") title="Actualizar"><i class="far fa-edit"></i></button></div>';

						// data_table_proveedores.row.add([objeto_respuesta.codigo,objeto_respuesta.referencia,objeto_respuesta.descripcion,objeto_respuesta.cantidad,objeto_respuesta.precio,boton]).draw();
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
	} //Fin funcion Agregar



	function actualizar()
	{
		require_once "modelos/proveedores_MO.php";
		$conexion = new servidor('A');
		$proveedores_MO = new proveedores_MO($conexion);
		$id_proveedor = $_POST["id_proveedor"];
		$arreglo_proveedores = $proveedores_MO->seleccionar("id_proveedor", $id_proveedor);
		$id_proveedor = $arreglo_proveedores[0]->id_proveedor;
		$nombre = $arreglo_proveedores[0]->nombre;
		$telefono = $arreglo_proveedores[0]->telefono;
		$direccion = $arreglo_proveedores[0]->direccion;
		$ciudad = $arreglo_proveedores[0]->ciudad;

	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos proveedores</h3>
			</div>
			<div class="card-body">
				<form id="formulario_actualizar_proveedores" method="post">
					<input type="hidden" id="id_proveedor" name="id_proveedor" value="<?php echo $id_proveedor; ?>">
					<input class="form-control form-control-lg" type="text" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>" autocomplete="on" readonly><br>
					<input class="form-control form-control-lg" type="text" id="telefono" name="telefono" placeholder="Telefono" value="<?php echo $telefono; ?>" autocomplete="on"><br>
					<input class="form-control form-control-lg" type="text" id="direccion" name="direccion" placeholder="Direccion" value="<?php echo $direccion; ?>" autocomplete="on"><br>
					<input class="form-control form-control-lg" type="text" id="ciudad" name="ciudad" placeholder="Ciudad" value="<?php echo $ciudad; ?>" autocomplete="on"><br>

					<button type="button" class="btn btn-primary float-right" onclick="controladorActualizarProveedor()"><i class="fas fa-save"></i> Guardar</button>
				</form>
			</div><!-- /.card-body -->
		</div>

		<script>
			function controladorActualizarProveedor() {
				var cadena = $('#formulario_actualizar_proveedores').serialize();
				var parametro = {
					"id_proveedor": $('#formulario_actualizar_proveedores')[0].elements.id_proveedor.value,
					"nombre": $('#formulario_actualizar_proveedores')[0].elements.nombre.value,
					"telefono": $('#formulario_actualizar_proveedores')[0].elements.telefono.value,
					"direccion": $('#formulario_actualizar_proveedores')[0].elements.direccion.value,
					"ciudad": $('#formulario_actualizar_proveedores')[0].elements.ciudad.value,
					"ruta": "proveedores_CO/actualizar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						//Pendiente en actualizacion de usuario
						$('#formulario_actualizar_proveedores').modal('hide');

						$.post('index.php', {
							"ruta": "proveedores_VI/listar"
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
		require_once "modelos/proveedores_MO.php";
		$conexion = new servidor('A');
		$proveedores_MO = new proveedores_MO($conexion);
		$id_proveedor = $_POST["id_proveedor"];
		$arreglo_proveedores = $proveedores_MO->seleccionar("id_proveedor", $id_proveedor);
		$id_proveedor = $arreglo_proveedores[0]->id_proveedor;
		$nombre = $arreglo_proveedores[0]->nombre;
		$telefono = $arreglo_proveedores[0]->telefono;
		$direccion = $arreglo_proveedores[0]->direccion;
		$ciudad = $arreglo_proveedores[0]->ciudad;

	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos proveedores</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-6">

						<input type="hidden" id="id_proveedor" name="id_proveedor" value="<?php echo $id_proveedor; ?>">
						<label class="col-lg-3 control-label">Nombre</label>
						<input class="form-control form-control-lg" type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-3 control-label">Telefono</label>
						<input class="form-control form-control-lg" type="text" id="telefono" name="telefono" placeholder="Referencia" value="<?php echo $telefono; ?>" autocomplete="on" readonly><br>
					</div>
					<div class="col-6">
						<label class="col-lg-3 control-label">Direccion</label>
						<input class="form-control form-control-lg" type="text" id="direccion" name="direccion" placeholder="Descripcion" value="<?php echo $direccion; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-3 control-label">Ciudad</label>
						<input class="form-control form-control-lg" type="text" id="ciudad" name="ciudad" placeholder="Proveedor" value="<?php echo $ciudad; ?>" autocomplete="on" readonly><br>
					</div>
				</div>
			</div><!-- /.card-body -->
		</div>


	<?php
	} //FIN FUNCION CONSULTAR


	function eliminar()
	{
		require_once "modelos/proveedores_MO.php";
		$conexion = new servidor('A');
		$proveedores_MO = new proveedores_MO($conexion);
		$id_proveedor = $_POST["id_proveedor"];


	?>
		<div class="card card-danger">
			<div class="card-header">
				<h3 class="card-title">Eliminar proveedor</h3>
			</div>
			<div class="card-body">
				<form id="formulario_eliminar_proveedores" method="post">
					<center><label>
							<h1>¿Desea eliminar el proveedor?</h1>
						</label></center><br>
					<input type="hidden" id="id_proveedor" name="id_proveedor" value="<?php echo $id_proveedor; ?>">
					<button type="button" class="btn btn-danger btn-lg btn-block" onclick="controladorEliminarProveedor(<?php echo $id_proveedor; ?>)">Deseo continuar</button> <br> <button type="button" class="btn btn-success btn-lg btn-block" data-dismiss="modal">Cerrar</button> <br>
				</form>
			</div>
		</div>


		<script>
			function controladorEliminarProveedor($id) {
				//var cadena=$('#formulario_eliminar_proveedores').serialize();			        	 		
				var parametro = {
					"id_proveedor": $id,
					"ruta": "proveedores_CO/eliminar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);
						$.post('index.php', {
							"ruta": "proveedores_VI/listar"
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