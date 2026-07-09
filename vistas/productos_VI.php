<?php
class productos_VI
{
	function __construct()
	{
	}

	function  listar()
	{
		require_once "modelos/productos_MO.php";


		$conexion = new servidor('A');
		$productos_MO = new productos_MO($conexion);
		$arreglo_productos = $productos_MO->seleccionar();



?>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Productos</h3>
				<button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#ventana_modal" onclick="vistaAgregarProducto()"> <i class="far fa-plus-square"></i> Agregar</button>
			</div> <!-- /.card-header -->
			<div class="card-body">
				<table id="listar_productos" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th class="col-md-1">C&oacute;digo</th>
							<th class="col-md-1">Referencia</th>
							<th class="col-md-4">Descripci&oacute;n</th>
							<th class="col-md-1">Cantidad</th>
							<th class="col-md-1">Precio</th>
							<th class="col-md-1">Ubicaci&oacute;n</th>
							<th class="col-md-1" style="text-align:center;">Acci&oacute;n</th>
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
								$cantidad = $objeto_productos->cantidad_bodega;
								$precio_costo = $objeto_productos->precio_costo;
								$precio_general = $objeto_productos->precio_general;
								$precio_mayorista = $objeto_productos->precio_mayorista;
								$ubicacion = $objeto_productos->ubicacion;
								$fecha_creacion = $objeto_productos->fecha_creacion;
								$fecha_actualizacion = $objeto_productos->fecha_actualizacion;



						?>
								<tr>
									<td><?php echo $codigo; ?></td>
									<td><?php echo $referencia; ?></td>
									<td><?php echo $descripcion; ?></td>
									<td><?php echo $cantidad; ?></td>
									<td><?php echo number_format($precio_general); ?></td>
									<td><?php echo $ubicacion; ?></td>
									<td style="text-align:center;">
										<i class="fas fa-edit" style="cursor:pointer; margin-right: 10px; color: #0C6766;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaActualizarProducto('<?php echo $id_producto; ?>')" title="Actualizar"></i>
										<i class="far fa-eye" style="cursor:pointer; margin-right: 10px;color: #697E0A;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaConsultarProducto('<?php echo $id_producto; ?>')" title="Consultar"></i>
										<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaEliminarProducto('<?php echo $id_producto; ?>')" title="Eliminar "></i>

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
			function vistaAgregarProducto() {
				var parametros = {
					"ruta": "productos_VI/agregar"
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Agregar producto');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaActualizarProducto(id_producto) {
				var parametros = {
					"ruta": "productos_VI/actualizar",
					"id_producto": id_producto
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Actualizar producto');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaConsultarProducto(id_producto) {
				var parametros = {
					"ruta": "productos_VI/consultar",
					"id_producto": id_producto
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Consultar producto');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaEliminarProducto(id_producto) {
				var parametros = {
					"ruta": "productos_VI/eliminar",
					"id_producto": id_producto
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#contenido_modal').html(respuesta);
				});
			}
			data_table_productos = organizarTabla({
				id: "listar_productos"
			});
		</script>
	<?php
	} //Fin funcion listar





	function agregar()
	{
		require_once "modelos/categorias_MO.php";
		require_once "modelos/proveedores_MO.php";
		$conexion = new servidor('A');
		$categorias_MO = new categorias_MO($conexion);
		$arreglo_categorias = $categorias_MO->seleccionar();

		$proveedores_MO = new proveedores_MO($conexion);
		$arreglo_proveedores = $proveedores_MO->seleccionar();
	?>
		<!-- Form Element sizes -->
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos Productos</h3>
			</div>
			<div class="card-body">
				<form id="formulario_agregar_productos">
					<div class="row">
						<div class="col-6">
							<label class="control-label">Código</label>
							<input class="form-control form-control-lg" type="text" id="codigo" name="codigo" placeholder="Codigo" autocomplete="on">
							<br>
							<label class="control-label">Referencia (Opcional)</label>
							<input class="form-control form-control-lg" type="text" id="referencia" name="referencia" placeholder="Referencia" autocomplete="on"><br>
							<label class="control-label">Descripción</label>
							<input class="form-control form-control-lg" type="text" id="descripcion" name="descripcion" placeholder="Descripcion" autocomplete="on"><br>
							<label class="control-label">Proveedor</label>
							<select class="form-control form-control-lg" name="proveedor" id="proveedor">
								<option value="0" disabled selected>Proveedor:</option>

								<?php

								if ($arreglo_proveedores) {
									foreach ($arreglo_proveedores as $objeto_proveedores) {
										$id_proveedor = $objeto_proveedores->id_proveedor;
										$nombre = $objeto_proveedores->nombre;
								?>
										<option value="<?php echo $nombre; ?>"><?php echo $nombre; ?></option>
								<?php
									}
								}
								?>
							</select> <br>
							<label class="control-label">Categoria</label>
							<select class="form-control form-control-lg" name="categoria" id="categoria">
								<option value="0" disabled selected>Categoria:</option>

								<?php

								if ($arreglo_categorias) {
									foreach ($arreglo_categorias as $objeto_categoria) {
										$id_cat = $objeto_categoria->id_categoria;
										$des = $objeto_categoria->descripcion;
								?>
										<option value="<?php echo $des; ?>"><?php echo $des; ?></option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-6">
							<label class="control-label">Precio costo</label>
							<input class="form-control form-control-lg" type="text" id="precio_costo" name="precio_costo" placeholder="Precio Costo" autocomplete="on"> <br>
							<label class="control-label">Precio al público</label>
							<input class="form-control form-control-lg" type="text" id="precio_general" name="precio_general" placeholder="Precio General" autocomplete="on"> <br>
							<label class="control-label">Precio Mayorista</label>
							<input class="form-control form-control-lg" type="text" id="precio_mayorista" name="precio_mayorista" placeholder="Precio Mayorista" autocomplete="on"> <br>
							<label class="control-label">Ubicacion</label>
							<select class="form-control form-control-lg" name="ubicacion" id="ubicacion">
								<option value="0" disabled selected>Sección:</option>
								<option value="Vitrina A">Vitrina A</option>
								<option value="Vitrina B">Vitrina B</option>
								<option value="Estante A1-0">Estante A1-0</option>
								<option value="Estante A1-1">Estante A1-1</option>
								<option value="Estante A1-2">Estante A1-2</option>
								<option value="Estante A1-3">Estante A1-3</option>
								<option value="Estante A1-4">Estante A1-4</option>
								<option value="Estante A1-5">Estante A1-5</option>
								<option value="Estante A1-6">Estante A1-6</option>
								<option value="Estante A2-0">Estante A2-0</option>
								<option value="Estante A2-1">Estante A2-1</option>
								<option value="Estante A2-2">Estante A2-2</option>
								<option value="Estante A2-3">Estante A2-3</option>
								<option value="Estante A2-4">Estante A2-4</option>
								<option value="Estante A2-5">Estante A2-5</option>
								<option value="Estante A2-6">Estante A2-6</option>
								<option value="Estante B1-0">Estante B1-0</option>
								<option value="Estante B1-1">Estante B1-1</option>
								<option value="Estante B1-2">Estante B1-2</option>
								<option value="Estante B1-3">Estante B1-3</option>
								<option value="Estante B1-4">Estante B1-4</option>
								<option value="Estante B1-5">Estante B1-5</option>
								<option value="Estante B1-6">Estante B1-6</option>
								<option value="Estante B2-0">Estante B2-0</option>
								<option value="Estante B2-1">Estante B2-1</option>
								<option value="Estante B2-2">Estante B2-2</option>
								<option value="Estante B2-3">Estante B2-3</option>
								<option value="Estante B2-4">Estante B2-4</option>
								<option value="Estante B2-5">Estante B2-5</option>
								<option value="Estante B2-6">Estante B2-6</option>
								<option value="Estante B3-0">Estante B3-0</option>
								<option value="Estante B3-1">Estante B3-1</option>
								<option value="Estante B3-2">Estante B3-2</option>
								<option value="Estante B3-3">Estante B3-3</option>
								<option value="Estante B3-4">Estante B3-4</option>
								<option value="Estante B3-5">Estante B3-5</option>
								<option value="Estante B3-6">Estante B3-6</option>
								<option value="Estante C1-0">Estante C1-0</option>
								<option value="Estante C1-1">Estante C1-1</option>
								<option value="Estante C1-2">Estante C1-2</option>
								<option value="Estante C1-3">Estante C1-3</option>
								<option value="Estante C1-4">Estante C1-4</option>
								<option value="Estante C1-5">Estante C1-5</option>
								<option value="Estante C1-6">Estante C1-6</option>
								<option value="Estante C2-0">Estante C2-0</option>
								<option value="Estante C2-1">Estante C2-1</option>
								<option value="Estante C2-2">Estante C2-2</option>
								<option value="Estante C2-3">Estante C2-3</option>
								<option value="Estante C2-4">Estante C2-4</option>
								<option value="Estante C2-5">Estante C2-5</option>
								<option value="Estante C2-6">Estante C2-6</option>
								<option value="Estante C3-0">Estante C3-0</option>
								<option value="Estante C3-1">Estante C3-1</option>
								<option value="Estante C3-2">Estante C3-2</option>
								<option value="Estante C3-3">Estante C3-3</option>
								<option value="Estante C3-4">Estante C3-4</option>
								<option value="Estante C3-5">Estante C3-5</option>
								<option value="Estante C3-6">Estante C3-6</option>
								<option value="Estante D1-0">Estante D1-0</option>
								<option value="Estante D1-1">Estante D1-1</option>
								<option value="Estante D1-2">Estante D1-2</option>
								<option value="Estante D1-3">Estante D1-3</option>
								<option value="Estante D1-4">Estante D1-4</option>
								<option value="Estante D1-5">Estante D1-5</option>
								<option value="Estante D1-6">Estante D1-6</option>
								<option value="Estante D2-0">Estante D2-0</option>
								<option value="Estante D2-1">Estante D2-1</option>
								<option value="Estante D2-2">Estante D2-2</option>
								<option value="Estante D2-3">Estante D2-3</option>
								<option value="Estante D2-4">Estante D2-4</option>
								<option value="Estante D2-5">Estante D2-5</option>
								<option value="Estante D2-6">Estante D2-6</option>
								<option value="Estante D3-0">Estante D3-0</option>
								<option value="Estante D3-1">Estante D3-1</option>
								<option value="Estante D3-2">Estante D3-2</option>
								<option value="Estante D3-3">Estante D3-3</option>
								<option value="Estante D3-4">Estante D3-4</option>
								<option value="Estante D3-5">Estante D3-5</option>
								<option value="Estante D3-6">Estante D3-6</option>
								<option value="Gabeteros Pequeños">Gabeteros Pequeños</option>
								<option value="Gabeteros Grandes">Gabeteros Grandes</option>
								<option value="Gabeteros Tornilleria">Gabeteros Tornilleria</option>
								<option value="Tornilleria">Tornilleria</option>
							</select> <br>
							<div class="row">
								<div class="col">
									<label class="control-label">Cantidad</label>
									<input class="form-control form-control-lg" type="text" id="cantidad" name="cantidad" placeholder="Cantidad" autocomplete="on"> <br>
								</div>
								<div class="col">
									<button type="button" class="btn btn-primary float-right btn-lg mt-4" onclick="controladorAgregarProductos()"><i class="fas fa-save"></i> Guardar</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div><!-- /.card-body -->
		</div><!-- Form Element sizes -->


		<script>
			function controladorAgregarProductos() {
				if ($('#formulario_agregar_productos')[0].elements.codigo.value == "" || $('#formulario_agregar_productos')[0].elements.descripcion.value == "" || $('#formulario_agregar_productos')[0].elements.cantidad.value == "" || $('#formulario_agregar_productos')[0].elements.precio_costo.value == "" || $('#formulario_agregar_productos')[0].elements.precio_general.value == "" || $('#formulario_agregar_productos')[0].elements.precio_mayorista.value == "") {
					alert("Debe ingresar toda la información");
				} else {
					var cadena = $('#formulario_agregar_productos').serialize();
					var parametros = {
						"ruta": "productos_CO/agregar",
						"codigo": $('#formulario_agregar_productos')[0].elements.codigo.value,
						"referencia": $('#formulario_agregar_productos')[0].elements.referencia.value,
						"descripcion": $('#formulario_agregar_productos')[0].elements.descripcion.value,
						"proveedor": $('#formulario_agregar_productos')[0].elements.proveedor.value,
						"categoria": $('#formulario_agregar_productos')[0].elements.categoria.value,
						"cantidad": $('#formulario_agregar_productos')[0].elements.cantidad.value,
						"precio_costo": $('#formulario_agregar_productos')[0].elements.precio_costo.value,
						"precio_general": $('#formulario_agregar_productos')[0].elements.precio_general.value,
						"precio_mayorista": $('#formulario_agregar_productos')[0].elements.precio_mayorista.value,
						"ubicacion": $('#formulario_agregar_productos')[0].elements.ubicacion.value
					};
					$.post('index.php', parametros, function(respuesta) {
						var objeto_respuesta = JSON.parse(respuesta);
						if (objeto_respuesta.estado == "EXITO") {
							exito(objeto_respuesta.mensaje);
							$('#formulario_agregar_productos')[0].reset();

							$.post('index.php', {
								"ruta": "productos_VI/listar"
							}, function(res) {
								$('#contenido').html(res);
							});

							//let boton='<div style="text-align:center;"><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ventana_modal" onclick=vistaActualizarProducto("'+objeto_respuesta.id_producto+'") title="Actualizar"><i class="far fa-edit"></i></button></div>';

							// data_table_productos.row.add([objeto_respuesta.codigo,objeto_respuesta.referencia,objeto_respuesta.descripcion,objeto_respuesta.cantidad,objeto_respuesta.precio,boton]).draw();
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
		require_once "modelos/productos_MO.php";
		require_once "modelos/categorias_MO.php";
		require_once "modelos/proveedores_MO.php";
		$conexion = new servidor('A');
		$productos_MO = new productos_MO($conexion);
		$id_producto = $_POST["id_producto"];
		$arreglo_productos = $productos_MO->seleccionar("id_producto", $id_producto);
		$id_producto = $arreglo_productos[0]->id_producto;
		$codigo = $arreglo_productos[0]->codigo;
		$referencia = $arreglo_productos[0]->referencia;
		$descripcion = $arreglo_productos[0]->descripcion;
		$proveedor = $arreglo_productos[0]->proveedor;
		$categoria = $arreglo_productos[0]->categoria;
		$cantidad = $arreglo_productos[0]->cantidad_bodega;
		$precio_costo = $arreglo_productos[0]->precio_costo;
		$precio_general = $arreglo_productos[0]->precio_general;
		$precio_mayorista = $arreglo_productos[0]->precio_mayorista;
		$ubicacion = $arreglo_productos[0]->ubicacion;
		$fecha = $arreglo_productos[0]->fecha_actualizacion;

		$categorias_MO = new categorias_MO($conexion);
		$arreglo_categorias = $categorias_MO->seleccionar();

		$proveedores_MO = new proveedores_MO($conexion);
		$arreglo_proveedores = $proveedores_MO->seleccionar();
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos productos</h3>
			</div>
			<div class="card-body">
				<form id="formulario_actualizar_productos" method="post">
					<div class="row">
						<div class="col-6">
							<input type="hidden" id="id_producto" name="id_producto" value="<?php echo $id_producto; ?>">
							<label class="control-label">Código</label>
							<input class="form-control form-control-lg" type="text" id="codigo" name="codigo" placeholder="Codgio" value="<?php echo $codigo; ?>" autocomplete="on" readonly><br>
							<label class="control-label">Referencia (Opcional)</label>
							<input class="form-control form-control-lg" type="text" id="referencia" name="referencia" placeholder="Referencia" value="<?php echo $referencia; ?>" autocomplete="on"><br>
							<label class="control-label">Descripción</label>
							<input class="form-control form-control-lg" type="text" id="descripcion" name="descripcion" placeholder="Descripcion" value="<?php echo $descripcion; ?>" autocomplete="on"><br>
							<label class="control-label">Proveedor</label>
							<select class="form-control form-control-lg" name="proveedor" id="proveedor">
								<option value="0" disabled selected>Proveedores:</option>

								<?php

								if ($arreglo_proveedores) {
									foreach ($arreglo_proveedores as $objeto_proveedores) {
										$id_proveedor = $objeto_proveedores->id_proveedor;
										$nombre = $objeto_proveedores->nombre;
										if ($nombre == $proveedor) {
											echo "<option value='" . $proveedor . "' selected>" . $proveedor . "</option>";
										} else {
											echo "<option value='" . $nombre . "'>" . $nombre . "</option>";
										}
									}
								}
								?>
							</select> <br>
							<label class="control-label">Categoría</label>
							<select class="form-control form-control-lg" name="categoria" id="categoria">
								<option value="0" disabled selected>Categoria:</option>

								<?php

								if ($arreglo_categorias) {
									foreach ($arreglo_categorias as $objeto_categoria) {
										$id_cat = $objeto_categoria->id_categoria;
										$des = $objeto_categoria->descripcion;
										if ($des == $categoria) {
											echo "<option value='" . $categoria . "' selected>" . $categoria . "</option>";
										} else {
											echo "<option value='" . $des . "'>" . $des . "</option>";
										}
									}
								}
								?>
							</select>
						</div>
						<div class="col-6">
							<label class="control-label">Precio Costo</label>
							<input class="form-control form-control-lg" type="text" id="precio_costo" name="precio_costo" placeholder="Precio costo" value="<?php echo $precio_costo; ?>" autocomplete="on"><br>
							<label class="control-label">Precio al público</label>
							<input class="form-control form-control-lg" type="text" id="precio_general" name="precio_general" placeholder="Precio general" value="<?php echo $precio_general; ?>" autocomplete="on"><br>
							<label class="control-label">Precio Mayorista</label>
							<input class="form-control form-control-lg" type="text" id="precio_mayorista" name="precio_mayorista" placeholder="Precio mayorista" value="<?php echo $precio_mayorista; ?>" autocomplete="on"><br>
							<label class="control-label">Ubicacion</label>
							<select class="form-control form-control-lg" name="ubicacion" id="ubicacion">
								<option value="0" disabled selected>Sección:</option>
								<option value="Vitrina A">Vitrina A</option>
								<option value="Vitrina B">Vitrina B</option>
								<option value="Estante A1-0">Estante A1-0</option>
								<option value="Estante A1-1">Estante A1-1</option>
								<option value="Estante A1-2">Estante A1-2</option>
								<option value="Estante A1-3">Estante A1-3</option>
								<option value="Estante A1-4">Estante A1-4</option>
								<option value="Estante A1-5">Estante A1-5</option>
								<option value="Estante A1-6">Estante A1-6</option>
								<option value="Estante A2-0">Estante A2-0</option>
								<option value="Estante A2-1">Estante A2-1</option>
								<option value="Estante A2-2">Estante A2-2</option>
								<option value="Estante A2-3">Estante A2-3</option>
								<option value="Estante A2-4">Estante A2-4</option>
								<option value="Estante A2-5">Estante A2-5</option>
								<option value="Estante A2-6">Estante A2-6</option>
								<option value="Estante B1-0">Estante B1-0</option>
								<option value="Estante B1-1">Estante B1-1</option>
								<option value="Estante B1-2">Estante B1-2</option>
								<option value="Estante B1-3">Estante B1-3</option>
								<option value="Estante B1-4">Estante B1-4</option>
								<option value="Estante B1-5">Estante B1-5</option>
								<option value="Estante B1-6">Estante B1-6</option>
								<option value="Estante B2-0">Estante B2-0</option>
								<option value="Estante B2-1">Estante B2-1</option>
								<option value="Estante B2-2">Estante B2-2</option>
								<option value="Estante B2-3">Estante B2-3</option>
								<option value="Estante B2-4">Estante B2-4</option>
								<option value="Estante B2-5">Estante B2-5</option>
								<option value="Estante B2-6">Estante B2-6</option>
								<option value="Estante B3-0">Estante B3-0</option>
								<option value="Estante B3-1">Estante B3-1</option>
								<option value="Estante B3-2">Estante B3-2</option>
								<option value="Estante B3-3">Estante B3-3</option>
								<option value="Estante B3-4">Estante B3-4</option>
								<option value="Estante B3-5">Estante B3-5</option>
								<option value="Estante B3-6">Estante B3-6</option>
								<option value="Estante C1-0">Estante C1-0</option>
								<option value="Estante C1-1">Estante C1-1</option>
								<option value="Estante C1-2">Estante C1-2</option>
								<option value="Estante C1-3">Estante C1-3</option>
								<option value="Estante C1-4">Estante C1-4</option>
								<option value="Estante C1-5">Estante C1-5</option>
								<option value="Estante C1-6">Estante C1-6</option>
								<option value="Estante C2-0">Estante C2-0</option>
								<option value="Estante C2-1">Estante C2-1</option>
								<option value="Estante C2-2">Estante C2-2</option>
								<option value="Estante C2-3">Estante C2-3</option>
								<option value="Estante C2-4">Estante C2-4</option>
								<option value="Estante C2-5">Estante C2-5</option>
								<option value="Estante C2-6">Estante C2-6</option>
								<option value="Estante C3-0">Estante C3-0</option>
								<option value="Estante C3-1">Estante C3-1</option>
								<option value="Estante C3-2">Estante C3-2</option>
								<option value="Estante C3-3">Estante C3-3</option>
								<option value="Estante C3-4">Estante C3-4</option>
								<option value="Estante C3-5">Estante C3-5</option>
								<option value="Estante C3-6">Estante C3-6</option>
								<option value="Estante D1-0">Estante D1-0</option>
								<option value="Estante D1-1">Estante D1-1</option>
								<option value="Estante D1-2">Estante D1-2</option>
								<option value="Estante D1-3">Estante D1-3</option>
								<option value="Estante D1-4">Estante D1-4</option>
								<option value="Estante D1-5">Estante D1-5</option>
								<option value="Estante D1-6">Estante D1-6</option>
								<option value="Estante D2-0">Estante D2-0</option>
								<option value="Estante D2-1">Estante D2-1</option>
								<option value="Estante D2-2">Estante D2-2</option>
								<option value="Estante D2-3">Estante D2-3</option>
								<option value="Estante D2-4">Estante D2-4</option>
								<option value="Estante D2-5">Estante D2-5</option>
								<option value="Estante D2-6">Estante D2-6</option>
								<option value="Estante D3-0">Estante D3-0</option>
								<option value="Estante D3-1">Estante D3-1</option>
								<option value="Estante D3-2">Estante D3-2</option>
								<option value="Estante D3-3">Estante D3-3</option>
								<option value="Estante D3-4">Estante D3-4</option>
								<option value="Estante D3-5">Estante D3-5</option>
								<option value="Estante D3-6">Estante D3-6</option>
								<option value="Gabeteros Pequeños">Gabeteros Pequeños</option>
								<option value="Gabeteros Grandes">Gabeteros Grandes</option>
								<option value="Gabeteros Tornilleria">Gabeteros Tornilleria</option>
								<option value="Tornilleria">Tornilleria</option>
							</select> <br>
							<div class="row">
								<div class="col">
									<label class="control-label">Cantidad</label>
									<input class="form-control form-control-lg" type="text" id="cantidad" name="cantidad" placeholder="Cantidad" value="<?php echo $cantidad; ?>" autocomplete="on"><br>
								</div>
								<div class="col">
									<button type="button" class="btn btn-primary float-center btn-lg" onclick="controladorActualizarProducto()"><i class="fas fa-save"></i> Guardar</button>
								</div>
							</div>
						</div>
				</form>
			</div><!-- /.card-body -->
		</div>

		<script>
			function controladorActualizarProducto() {
				var cadena = $('#formulario_actualizar_productos').serialize();
				var parametro = {
					"id_producto": $('#formulario_actualizar_productos')[0].elements.id_producto.value,
					"codigo": $('#formulario_actualizar_productos')[0].elements.codigo.value,
					"referencia": $('#formulario_actualizar_productos')[0].elements.referencia.value,
					"descripcion": $('#formulario_actualizar_productos')[0].elements.descripcion.value,
					"proveedor": $('#formulario_actualizar_productos')[0].elements.proveedor.value,
					"categoria": $('#formulario_actualizar_productos')[0].elements.categoria.value,
					"cantidad": $('#formulario_actualizar_productos')[0].elements.cantidad.value,
					"precio_costo": $('#formulario_actualizar_productos')[0].elements.precio_costo.value,
					"precio_general": $('#formulario_actualizar_productos')[0].elements.precio_general.value,
					"precio_mayorista": $('#formulario_actualizar_productos')[0].elements.precio_mayorista.value,
					"ubicacion": $('#formulario_actualizar_productos')[0].elements.ubicacion.value,
					"ruta": "productos_CO/actualizar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						//Pendiente en actualizacion de usuario
						$('#formulario_actualizar_productos').modal('hide');

						$.post('index.php', {
							"ruta": "productos_VI/listar"
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
		require_once "modelos/productos_MO.php";
		$conexion = new servidor('A');
		$productos_MO = new productos_MO($conexion);
		$id_producto = $_POST["id_producto"];
		$arreglo_productos = $productos_MO->seleccionar("id_producto", $id_producto);
		$id_producto = $arreglo_productos[0]->id_producto;
		$codigo = $arreglo_productos[0]->codigo;
		$referencia = $arreglo_productos[0]->referencia;
		$descripcion = $arreglo_productos[0]->descripcion;
		$proveedor = $arreglo_productos[0]->proveedor;
		$categoria = $arreglo_productos[0]->categoria;
		$cantidad = $arreglo_productos[0]->cantidad_bodega;
		$precio_costo = $arreglo_productos[0]->precio_costo;
		$precio_general = $arreglo_productos[0]->precio_general;
		$precio_mayorista = $arreglo_productos[0]->precio_mayorista;
		$ubicacion = $arreglo_productos[0]->ubicacion;
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos productos</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-6">

						<input type="hidden" id="id_producto" name="id_producto" value="<?php echo $id_producto; ?>">
						<label class="col-lg-3 control-label">Código</label>
						<input class="form-control form-control-lg" type="text" id="codigo" name="codigo" value="<?php echo $codigo; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-3 control-label">Referencia</label>
						<input class="form-control form-control-lg" type="text" id="referencia" name="referencia" placeholder="Referencia" value="<?php echo $referencia; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-3 control-label">Descripción</label>
						<input class="form-control form-control-lg" type="text" id="descripcion" name="descripcion" placeholder="Descripcion" value="<?php echo $descripcion; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-3 control-label">Proveedor</label>
						<input class="form-control form-control-lg" type="text" id="proveedor" name="proveedor" placeholder="Proveedor" value="<?php echo $proveedor; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-3 control-label">Categoria</label>
						<input class="form-control form-control-lg" type="text" id="categoria" name="categoria" placeholder="Categoria" value="<?php echo $categoria; ?>" autocomplete="on" readonly><br>
					</div>
					<div class="col-6">
						<label class="col-lg-3 control-label">Cantidad</label>
						<input class="form-control form-control-lg" type="text" id="cantidad" name="cantidad" placeholder="Cantidad" value="<?php echo $cantidad; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-4 control-label">Precio costo</label>
						<input class="form-control form-control-lg" type="text" id="precio_costo" name="precio_costo" placeholder="Precio costo" value="<?php echo $precio_costo; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-4 control-label">Precio general</label>
						<input class="form-control form-control-lg" type="text" id="precio_general" name="precio_general" placeholder="Precio general" value="<?php echo $precio_general; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-4 control-label">Precio mayorista</label>
						<input class="form-control form-control-lg" type="text" id="precio_mayorista" name="precio_mayorista" placeholder="Precio mayorista" value="<?php echo $precio_mayorista; ?>" autocomplete="on" readonly><br>
						<label class="col-lg-4 control-label">Ubicacion</label>
						<input class="form-control form-control-lg" type="text" id="ubicacion" name="ubicacion" placeholder="Ubicacion" value="<?php echo $ubicacion; ?>" autocomplete="on" readonly><br>
					</div>

				</div>

			</div><!-- /.card-body -->
		</div>


	<?php
	} //FIN FUNCION CONSULTAR


	function eliminar()
	{
		require_once "modelos/productos_MO.php";
		$conexion = new servidor('A');
		$productos_MO = new productos_MO($conexion);
		$id_producto = $_POST["id_producto"];


	?>
		<div class="card card-danger text-center">
			<div class="card-header">
				<h5 class="center">Confirmar eliminación</h5>
			</div>
			<div class="card-body">
				¿Estás seguro de que deseas eliminar este elemento?
			</div>
			<div class="card-footer">
				<form id="formulario_eliminar_productos" method="post">
					<input type="hidden" id="id_producto" name="id_producto" value="<?php echo $id_producto; ?>">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-danger">Eliminar</button>
				</form>
			</div>
		</div>



		<script>
			function controladorEliminarProducto($id) {
				//var cadena=$('#formulario_eliminar_productos').serialize();			        	 		
				var parametro = {
					"id_producto": $id,
					"ruta": "productos_CO/eliminar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);
						$.post('index.php', {
							"ruta": "productos_VI/listar"
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