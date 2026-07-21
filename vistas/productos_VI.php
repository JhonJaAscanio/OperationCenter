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
		$arreglo_productos = $productos_MO->seleccionarConNombres();



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
							<th class="col-md-3">Descripci&oacute;n</th>
							<th class="col-md-1">Marca</th>
							<th class="col-md-1">Cantidad</th>
							<th class="col-md-1">Precio</th>
							<th class="col-md-1">Ubicaci&oacute;n</th>
							<th class="col-md-1" style="text-align:center;">Estado</th>
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
								$nombre_marca = $objeto_productos->nombre_marca;
								$cantidad = $objeto_productos->cantidad_bodega;
								$stock_minimo = $objeto_productos->stock_minimo;
								$precio_general = $objeto_productos->precio_general;
								$ubicacion = $objeto_productos->ubicacion;
								$estado = $objeto_productos->estado;

								$fila_baja_existencia = ($cantidad <= $stock_minimo) ? 'table-danger' : '';

								if ($estado == "SI") {
									$icono_estado = "fas fa-toggle-on";
									$titulo_estado = "Desactivar";
									$nuevo_estado = "NO";
									$color_estado = "color:green;";
								} else {
									$icono_estado = "fas fa-toggle-off";
									$titulo_estado = "Activar";
									$nuevo_estado = "SI";
									$color_estado = "color:#870B0B;";
								}
						?>
								<tr class="<?php echo $fila_baja_existencia; ?>">
									<td><?php echo htmlspecialchars($codigo, ENT_QUOTES); ?></td>
									<td><?php echo htmlspecialchars($referencia, ENT_QUOTES); ?></td>
									<td><?php echo htmlspecialchars($descripcion, ENT_QUOTES); ?></td>
									<td><?php echo htmlspecialchars($nombre_marca ?? '', ENT_QUOTES); ?></td>
									<td><?php echo (int) $cantidad; ?><?php if ($fila_baja_existencia) : ?> <i class="fas fa-exclamation-triangle" title="Stock en o por debajo del minimo (<?php echo (int) $stock_minimo; ?>)"></i><?php endif; ?></td>
									<td><?php echo number_format($precio_general); ?></td>
									<td><?php echo htmlspecialchars($ubicacion, ENT_QUOTES); ?></td>
									<td style="text-align:center;">
										<i class="<?php echo $icono_estado; ?>" style="cursor:pointer; <?php echo $color_estado; ?>" title="<?php echo $titulo_estado; ?>" onclick="activoProducto('<?php echo (int) $id_producto; ?>','<?php echo $nuevo_estado; ?>')"></i>
									</td>
									<td style="text-align:center;">
										<i class="fas fa-edit" style="cursor:pointer; margin-right: 10px; color: #0C6766;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaActualizarProducto('<?php echo (int) $id_producto; ?>')" title="Actualizar"></i>
										<i class="far fa-eye" style="cursor:pointer; margin-right: 10px;color: #697E0A;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaConsultarProducto('<?php echo (int) $id_producto; ?>')" title="Consultar"></i>
										<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaEliminarProducto('<?php echo (int) $id_producto; ?>')" title="Eliminar "></i>

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

			function activoProducto(id_producto, estado) {
				var parametros = {
					"ruta": "productos_CO/activo",
					"id_producto": id_producto,
					"estado": estado
				};
				$.post('index.php', parametros, function(respuesta) {
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
					}
				});
			}
			data_table_productos = organizarTabla({
				id: "listar_productos"
			});
		</script>
	<?php
	} //Fin funcion listar


	// HTML de las <option> de unidad de medida, compartido entre agregar/actualizar.
	private function opcionesUnidadMedida($seleccionada = 'UND')
	{
		$unidades = [
			'UND' => 'Unidad',
			'KG' => 'Kilogramo',
			'LT' => 'Litro',
			'M' => 'Metro',
			'PAR' => 'Par',
			'CAJA' => 'Caja',
			'JGO' => 'Juego',
		];
		$html = '';
		foreach ($unidades as $valor => $etiqueta) {
			$sel = ($valor == $seleccionada) ? 'selected' : '';
			$html .= "<option value='" . $valor . "' $sel>" . $etiqueta . "</option>";
		}
		return $html;
	}

	private function opcionesUbicacion($seleccionada = '')
	{
		$ubicaciones = [
			'Vitrina A',
			'Vitrina B',
			'Estante A1-0',
			'Estante A1-1',
			'Estante A1-2',
			'Estante A1-3',
			'Estante A1-4',
			'Estante A1-5',
			'Estante A1-6',
			'Estante A2-0',
			'Estante A2-1',
			'Estante A2-2',
			'Estante A2-3',
			'Estante A2-4',
			'Estante A2-5',
			'Estante A2-6',
			'Estante B1-0',
			'Estante B1-1',
			'Estante B1-2',
			'Estante B1-3',
			'Estante B1-4',
			'Estante B1-5',
			'Estante B1-6',
			'Estante B2-0',
			'Estante B2-1',
			'Estante B2-2',
			'Estante B2-3',
			'Estante B2-4',
			'Estante B2-5',
			'Estante B2-6',
			'Estante B3-0',
			'Estante B3-1',
			'Estante B3-2',
			'Estante B3-3',
			'Estante B3-4',
			'Estante B3-5',
			'Estante B3-6',
			'Estante C1-0',
			'Estante C1-1',
			'Estante C1-2',
			'Estante C1-3',
			'Estante C1-4',
			'Estante C1-5',
			'Estante C1-6',
			'Estante C2-0',
			'Estante C2-1',
			'Estante C2-2',
			'Estante C2-3',
			'Estante C2-4',
			'Estante C2-5',
			'Estante C2-6',
			'Estante C3-0',
			'Estante C3-1',
			'Estante C3-2',
			'Estante C3-3',
			'Estante C3-4',
			'Estante C3-5',
			'Estante C3-6',
			'Estante D1-0',
			'Estante D1-1',
			'Estante D1-2',
			'Estante D1-3',
			'Estante D1-4',
			'Estante D1-5',
			'Estante D1-6',
			'Estante D2-0',
			'Estante D2-1',
			'Estante D2-2',
			'Estante D2-3',
			'Estante D2-4',
			'Estante D2-5',
			'Estante D2-6',
			'Estante D3-0',
			'Estante D3-1',
			'Estante D3-2',
			'Estante D3-3',
			'Estante D3-4',
			'Estante D3-5',
			'Estante D3-6',
			'Gabeteros Pequeños',
			'Gabeteros Grandes',
			'Gabeteros Tornilleria',
			'Tornilleria',
		];
		$html = '<option value="0" ' . ($seleccionada === '' ? 'disabled selected' : '') . '>Sección:</option>';
		foreach ($ubicaciones as $u) {
			$sel = ($u == $seleccionada) ? 'selected' : '';
			$html .= "<option value='" . $u . "' $sel>" . $u . "</option>";
		}
		return $html;
	}


	function agregar()
	{
		require_once "modelos/categorias_MO.php";
		require_once "modelos/proveedores_MO.php";
		require_once "modelos/marcas_MO.php";
		$conexion = new servidor('A');
		$categorias_MO = new categorias_MO($conexion);
		$arreglo_categorias = $categorias_MO->seleccionar();

		$proveedores_MO = new proveedores_MO($conexion);
		$arreglo_proveedores = $proveedores_MO->seleccionar();

		$marcas_MO = new marcas_MO($conexion);
		$arreglo_marcas = $marcas_MO->seleccionar();

		$impuesto_defecto = IVA * 100;
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
							<label class="control-label">Código (SKU)</label>
							<input class="form-control form-control-lg" type="text" id="codigo" name="codigo" placeholder="Codigo" autocomplete="on">
							<br>
							<label class="control-label">Código de barras (Opcional)</label>
							<input class="form-control form-control-lg" type="text" id="codigo_barras" name="codigo_barras" placeholder="Codigo de barras" autocomplete="on"><br>
							<label class="control-label">Referencia (Opcional)</label>
							<input class="form-control form-control-lg" type="text" id="referencia" name="referencia" placeholder="Referencia" autocomplete="on"><br>
							<label class="control-label">Descripción</label>
							<input class="form-control form-control-lg" type="text" id="descripcion" name="descripcion" placeholder="Descripcion" autocomplete="on"><br>
							<label class="control-label">Proveedor</label>
							<select class="form-control form-control-lg" name="id_proveedor" id="id_proveedor">
								<option value="" disabled selected>Proveedor:</option>

								<?php

								if ($arreglo_proveedores) {
									foreach ($arreglo_proveedores as $objeto_proveedores) {
								?>
										<option value="<?php echo (int) $objeto_proveedores->id_proveedor; ?>"><?php echo htmlspecialchars($objeto_proveedores->nombre, ENT_QUOTES); ?></option>
								<?php
									}
								}
								?>
							</select> <br>
							<label class="control-label">Marca (Opcional)</label>
							<select class="form-control form-control-lg" name="id_marca" id="id_marca">
								<option value="">Sin marca</option>
								<?php
								if ($arreglo_marcas) {
									foreach ($arreglo_marcas as $objeto_marca) {
								?>
										<option value="<?php echo (int) $objeto_marca->id_marca; ?>"><?php echo htmlspecialchars($objeto_marca->descripcion, ENT_QUOTES); ?></option>
								<?php
									}
								}
								?>
							</select> <br>
							<label class="control-label">Categoria</label>
							<select class="form-control form-control-lg" name="id_categoria" id="id_categoria" onchange="cargarSubcategorias(this.value, 'agregar')">
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
							</select> <br>
							<label class="control-label">Subcategoria (Opcional)</label>
							<select class="form-control form-control-lg" name="id_subcategoria" id="id_subcategoria_agregar">
								<option value="">Seleccione primero la categoria</option>
							</select>
						</div>
						<div class="col-6">
							<label class="control-label">Precio costo</label>
							<input class="form-control form-control-lg" type="text" id="precio_costo" name="precio_costo" placeholder="Precio Costo" autocomplete="on"> <br>
							<label class="control-label">Precio al público</label>
							<input class="form-control form-control-lg" type="text" id="precio_general" name="precio_general" placeholder="Precio General" autocomplete="on"> <br>
							<label class="control-label">Precio Mayorista</label>
							<input class="form-control form-control-lg" type="text" id="precio_mayorista" name="precio_mayorista" placeholder="Precio Mayorista" autocomplete="on"> <br>
							<label class="control-label">Precio Promocional (Opcional)</label>
							<input class="form-control form-control-lg" type="text" id="precio_promocional" name="precio_promocional" placeholder="Precio Promocional" autocomplete="on"> <br>
							<label class="control-label">Impuesto %</label>
							<input class="form-control form-control-lg" type="text" id="impuesto" name="impuesto" value="<?php echo htmlspecialchars($impuesto_defecto, ENT_QUOTES); ?>" autocomplete="on"> <br>
							<label class="control-label">Ubicacion</label>
							<select class="form-control form-control-lg" name="ubicacion" id="ubicacion">
								<?php echo $this->opcionesUbicacion(); ?>
							</select> <br>
							<div class="row">
								<div class="col">
									<label class="control-label">Cantidad inicial</label>
									<input class="form-control form-control-lg" type="text" id="cantidad" name="cantidad" placeholder="Cantidad" autocomplete="on"> <br>
								</div>
								<div class="col">
									<label class="control-label">Unidad de medida</label>
									<select class="form-control form-control-lg" name="unidad_medida" id="unidad_medida">
										<?php echo $this->opcionesUnidadMedida(); ?>
									</select><br>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<label class="control-label">Stock mínimo</label>
									<input class="form-control form-control-lg" type="text" id="stock_minimo" name="stock_minimo" placeholder="0" autocomplete="on"> <br>
								</div>
								<div class="col">
									<label class="control-label">Stock máximo (Opcional)</label>
									<input class="form-control form-control-lg" type="text" id="stock_maximo" name="stock_maximo" placeholder="Sin limite" autocomplete="on"> <br>
								</div>
								<div class="col">
									<label class="control-label">Peso (Opcional)</label>
									<input class="form-control form-control-lg" type="text" id="peso" name="peso" placeholder="Kg" autocomplete="on"> <br>
								</div>
							</div>
							<button type="button" class="btn btn-primary float-right btn-lg mt-2" onclick="controladorAgregarProductos()"><i class="fas fa-save"></i> Guardar</button>
						</div>
					</div>
				</form>
			</div><!-- /.card-body -->
		</div><!-- Form Element sizes -->


		<script>
			function cargarSubcategorias(id_categoria, contexto) {
				var selectId = (contexto == 'agregar') ? '#id_subcategoria_agregar' : '#id_subcategoria_actualizar';
				if (!id_categoria) {
					$(selectId).html('<option value="">Seleccione primero la categoria</option>');
					return;
				}
				$.post('index.php', {
					"ruta": "subcategorias_VI/opciones",
					"id_categoria": id_categoria
				}, function(respuesta) {
					$(selectId).html('<option value="">Sin subcategoria</option>' + respuesta);
				});
			}

			function controladorAgregarProductos() {
				var f = $('#formulario_agregar_productos')[0].elements;
				if (f.codigo.value == "" || f.descripcion.value == "" || f.cantidad.value == "" || f.precio_costo.value == "" || f.precio_general.value == "" || f.precio_mayorista.value == "" || f.id_proveedor.value == "" || f.id_categoria.value == "") {
					alert("Debe ingresar toda la información obligatoria");
				} else {
					var parametros = {
						"ruta": "productos_CO/agregar",
						"codigo": f.codigo.value,
						"codigo_barras": f.codigo_barras.value,
						"referencia": f.referencia.value,
						"descripcion": f.descripcion.value,
						"id_proveedor": f.id_proveedor.value,
						"id_marca": f.id_marca.value,
						"id_categoria": f.id_categoria.value,
						"id_subcategoria": f.id_subcategoria.value,
						"cantidad": f.cantidad.value,
						"unidad_medida": f.unidad_medida.value,
						"precio_costo": f.precio_costo.value,
						"precio_general": f.precio_general.value,
						"precio_mayorista": f.precio_mayorista.value,
						"precio_promocional": f.precio_promocional.value,
						"impuesto": f.impuesto.value,
						"stock_minimo": f.stock_minimo.value,
						"stock_maximo": f.stock_maximo.value,
						"peso": f.peso.value,
						"ubicacion": f.ubicacion.value
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
		require_once "modelos/marcas_MO.php";
		require_once "modelos/subcategorias_MO.php";
		$conexion = new servidor('A');
		$productos_MO = new productos_MO($conexion);
		$id_producto = $_POST["id_producto"];
		$arreglo_productos = $productos_MO->seleccionar("id_producto", $id_producto);
		$id_producto = $arreglo_productos[0]->id_producto;
		$codigo = $arreglo_productos[0]->codigo;
		$codigo_barras = $arreglo_productos[0]->codigo_barras;
		$referencia = $arreglo_productos[0]->referencia;
		$descripcion = $arreglo_productos[0]->descripcion;
		$id_proveedor = $arreglo_productos[0]->id_proveedor;
		$id_categoria = $arreglo_productos[0]->id_categoria;
		$id_marca = $arreglo_productos[0]->id_marca;
		$id_subcategoria = $arreglo_productos[0]->id_subcategoria;
		$cantidad = $arreglo_productos[0]->cantidad_bodega;
		$unidad_medida = $arreglo_productos[0]->unidad_medida;
		$precio_costo = $arreglo_productos[0]->precio_costo;
		$precio_general = $arreglo_productos[0]->precio_general;
		$precio_mayorista = $arreglo_productos[0]->precio_mayorista;
		$precio_promocional = $arreglo_productos[0]->precio_promocional;
		$impuesto = $arreglo_productos[0]->impuesto;
		$stock_minimo = $arreglo_productos[0]->stock_minimo;
		$stock_maximo = $arreglo_productos[0]->stock_maximo;
		$peso = $arreglo_productos[0]->peso;
		$ubicacion = $arreglo_productos[0]->ubicacion;

		$categorias_MO = new categorias_MO($conexion);
		$arreglo_categorias = $categorias_MO->seleccionar();

		$proveedores_MO = new proveedores_MO($conexion);
		$arreglo_proveedores = $proveedores_MO->seleccionar();

		$marcas_MO = new marcas_MO($conexion);
		$arreglo_marcas = $marcas_MO->seleccionar();

		$subcategorias_MO = new subcategorias_MO($conexion);
		$arreglo_subcategorias = $id_categoria ? $subcategorias_MO->seleccionarPorCategoria($id_categoria) : [];
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos productos</h3>
			</div>
			<div class="card-body">
				<form id="formulario_actualizar_productos" method="post">
					<div class="row">
						<div class="col-6">
							<input type="hidden" id="id_producto" name="id_producto" value="<?php echo (int) $id_producto; ?>">
							<label class="control-label">Código (SKU)</label>
							<input class="form-control form-control-lg" type="text" id="codigo" name="codigo" placeholder="Codgio" value="<?php echo htmlspecialchars($codigo, ENT_QUOTES); ?>" autocomplete="on" readonly><br>
							<label class="control-label">Código de barras (Opcional)</label>
							<input class="form-control form-control-lg" type="text" id="codigo_barras" name="codigo_barras" value="<?php echo htmlspecialchars($codigo_barras ?? '', ENT_QUOTES); ?>" autocomplete="on"><br>
							<label class="control-label">Referencia (Opcional)</label>
							<input class="form-control form-control-lg" type="text" id="referencia" name="referencia" placeholder="Referencia" value="<?php echo htmlspecialchars($referencia, ENT_QUOTES); ?>" autocomplete="on"><br>
							<label class="control-label">Descripción</label>
							<input class="form-control form-control-lg" type="text" id="descripcion" name="descripcion" placeholder="Descripcion" value="<?php echo htmlspecialchars($descripcion, ENT_QUOTES); ?>" autocomplete="on"><br>
							<label class="control-label">Proveedor</label>
							<select class="form-control form-control-lg" name="id_proveedor" id="id_proveedor">
								<?php
								if ($arreglo_proveedores) {
									foreach ($arreglo_proveedores as $objeto_proveedores) {
										$sel = ($objeto_proveedores->id_proveedor == $id_proveedor) ? 'selected' : '';
								?>
										<option value="<?php echo (int) $objeto_proveedores->id_proveedor; ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($objeto_proveedores->nombre, ENT_QUOTES); ?></option>
								<?php
									}
								}
								?>
							</select> <br>
							<label class="control-label">Marca (Opcional)</label>
							<select class="form-control form-control-lg" name="id_marca" id="id_marca">
								<option value="">Sin marca</option>
								<?php
								if ($arreglo_marcas) {
									foreach ($arreglo_marcas as $objeto_marca) {
										$sel = ($objeto_marca->id_marca == $id_marca) ? 'selected' : '';
								?>
										<option value="<?php echo (int) $objeto_marca->id_marca; ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($objeto_marca->descripcion, ENT_QUOTES); ?></option>
								<?php
									}
								}
								?>
							</select> <br>
							<label class="control-label">Categoría</label>
							<select class="form-control form-control-lg" name="id_categoria" id="id_categoria" onchange="cargarSubcategorias(this.value, 'actualizar')">
								<?php
								if ($arreglo_categorias) {
									foreach ($arreglo_categorias as $objeto_categoria) {
										$sel = ($objeto_categoria->id_categoria == $id_categoria) ? 'selected' : '';
								?>
										<option value="<?php echo (int) $objeto_categoria->id_categoria; ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($objeto_categoria->descripcion, ENT_QUOTES); ?></option>
								<?php
									}
								}
								?>
							</select>
							<br>
							<label class="control-label">Subcategoria (Opcional)</label>
							<select class="form-control form-control-lg" name="id_subcategoria" id="id_subcategoria_actualizar">
								<option value="">Sin subcategoria</option>
								<?php
								if ($arreglo_subcategorias) {
									foreach ($arreglo_subcategorias as $objeto_subcategoria) {
										$sel = ($objeto_subcategoria->id_subcategoria == $id_subcategoria) ? 'selected' : '';
								?>
										<option value="<?php echo (int) $objeto_subcategoria->id_subcategoria; ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($objeto_subcategoria->descripcion, ENT_QUOTES); ?></option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-6">
							<label class="control-label">Precio Costo</label>
							<input class="form-control form-control-lg" type="text" id="precio_costo" name="precio_costo" placeholder="Precio costo" value="<?php echo htmlspecialchars($precio_costo, ENT_QUOTES); ?>" autocomplete="on"><br>
							<label class="control-label">Precio al público</label>
							<input class="form-control form-control-lg" type="text" id="precio_general" name="precio_general" placeholder="Precio general" value="<?php echo htmlspecialchars($precio_general, ENT_QUOTES); ?>" autocomplete="on"><br>
							<label class="control-label">Precio Mayorista</label>
							<input class="form-control form-control-lg" type="text" id="precio_mayorista" name="precio_mayorista" placeholder="Precio mayorista" value="<?php echo htmlspecialchars($precio_mayorista, ENT_QUOTES); ?>" autocomplete="on"><br>
							<label class="control-label">Precio Promocional (Opcional)</label>
							<input class="form-control form-control-lg" type="text" id="precio_promocional" name="precio_promocional" value="<?php echo htmlspecialchars($precio_promocional ?? '', ENT_QUOTES); ?>" autocomplete="on"><br>
							<label class="control-label">Impuesto %</label>
							<input class="form-control form-control-lg" type="text" id="impuesto" name="impuesto" value="<?php echo htmlspecialchars($impuesto, ENT_QUOTES); ?>" autocomplete="on"><br>
							<label class="control-label">Ubicacion</label>
							<select class="form-control form-control-lg" name="ubicacion" id="ubicacion">
								<?php echo $this->opcionesUbicacion($ubicacion); ?>
							</select> <br>
							<div class="row">
								<div class="col">
									<label class="control-label">Cantidad</label>
									<input class="form-control form-control-lg" type="text" id="cantidad" name="cantidad" placeholder="Cantidad" value="<?php echo htmlspecialchars($cantidad, ENT_QUOTES); ?>" autocomplete="on"><br>
								</div>
								<div class="col">
									<label class="control-label">Unidad de medida</label>
									<select class="form-control form-control-lg" name="unidad_medida" id="unidad_medida">
										<?php echo $this->opcionesUnidadMedida($unidad_medida); ?>
									</select><br>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<label class="control-label">Stock mínimo</label>
									<input class="form-control form-control-lg" type="text" id="stock_minimo" name="stock_minimo" value="<?php echo htmlspecialchars($stock_minimo, ENT_QUOTES); ?>" autocomplete="on"><br>
								</div>
								<div class="col">
									<label class="control-label">Stock máximo (Opcional)</label>
									<input class="form-control form-control-lg" type="text" id="stock_maximo" name="stock_maximo" value="<?php echo htmlspecialchars($stock_maximo ?? '', ENT_QUOTES); ?>" autocomplete="on"><br>
								</div>
								<div class="col">
									<label class="control-label">Peso (Opcional)</label>
									<input class="form-control form-control-lg" type="text" id="peso" name="peso" value="<?php echo htmlspecialchars($peso ?? '', ENT_QUOTES); ?>" autocomplete="on"><br>
								</div>
							</div>
							<button type="button" class="btn btn-primary float-right btn-lg mt-2" onclick="controladorActualizarProducto()"><i class="fas fa-save"></i> Guardar</button>
						</div>
					</div>
				</form>
			</div><!-- /.card-body -->
		</div>

		<script>
			function controladorActualizarProducto() {
				var f = $('#formulario_actualizar_productos')[0].elements;
				var parametro = {
					"ruta": "productos_CO/actualizar",
					"id_producto": f.id_producto.value,
					"codigo": f.codigo.value,
					"codigo_barras": f.codigo_barras.value,
					"referencia": f.referencia.value,
					"descripcion": f.descripcion.value,
					"id_proveedor": f.id_proveedor.value,
					"id_marca": f.id_marca.value,
					"id_categoria": f.id_categoria.value,
					"id_subcategoria": f.id_subcategoria.value,
					"cantidad": f.cantidad.value,
					"unidad_medida": f.unidad_medida.value,
					"precio_costo": f.precio_costo.value,
					"precio_general": f.precio_general.value,
					"precio_mayorista": f.precio_mayorista.value,
					"precio_promocional": f.precio_promocional.value,
					"impuesto": f.impuesto.value,
					"stock_minimo": f.stock_minimo.value,
					"stock_maximo": f.stock_maximo.value,
					"peso": f.peso.value,
					"ubicacion": f.ubicacion.value
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
		$arreglo_productos = $productos_MO->seleccionarConNombres("id_producto", $id_producto);
		$p = $arreglo_productos[0];
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos productos</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-6">

						<input type="hidden" id="id_producto" name="id_producto" value="<?php echo (int) $p->id_producto; ?>">
						<label class="col-lg-3 control-label">Código</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->codigo, ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-3 control-label">Código de barras</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->codigo_barras ?? '', ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-3 control-label">Referencia</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->referencia, ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-3 control-label">Descripción</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->descripcion, ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-3 control-label">Proveedor</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->nombre_proveedor, ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-3 control-label">Marca</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->nombre_marca ?? 'Sin marca', ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-3 control-label">Categoria</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->nombre_categoria, ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-3 control-label">Subcategoria</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->nombre_subcategoria ?? 'Sin subcategoria', ENT_QUOTES); ?>" readonly><br>
					</div>
					<div class="col-6">
						<label class="col-lg-3 control-label">Cantidad</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->cantidad_bodega, ENT_QUOTES); ?> <?php echo htmlspecialchars($p->unidad_medida, ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-3 control-label">Stock mínimo / máximo</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->stock_minimo, ENT_QUOTES); ?> / <?php echo htmlspecialchars($p->stock_maximo ?? 'Sin limite', ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-4 control-label">Precio costo</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->precio_costo, ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-4 control-label">Precio general</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->precio_general, ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-4 control-label">Precio mayorista</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->precio_mayorista, ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-4 control-label">Precio promocional</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->precio_promocional ?? 'N/A', ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-4 control-label">Impuesto</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->impuesto, ENT_QUOTES); ?> %" readonly><br>
						<label class="col-lg-4 control-label">Peso</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->peso ?? 'N/A', ENT_QUOTES); ?>" readonly><br>
						<label class="col-lg-4 control-label">Ubicacion</label>
						<input class="form-control form-control-lg" type="text" value="<?php echo htmlspecialchars($p->ubicacion, ENT_QUOTES); ?>" readonly><br>
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
					<input type="hidden" id="id_producto" name="id_producto" value="<?php echo (int) $id_producto; ?>">
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
