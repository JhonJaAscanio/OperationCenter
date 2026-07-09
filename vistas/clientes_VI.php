<?php
class clientes_VI
{
	function __construct()
	{
	}

	function  listar()
	{
		require_once "modelos/clientes_MO.php";

		$conexion = new servidor('A');
		$clientes_MO = new clientes_MO($conexion);
		$arreglo_clientes = $clientes_MO->seleccionar();

?>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Cliente</h3>
				<button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#ventana_modal" onclick="vistaAgregarCliente()"> <i class="far fa-plus-square"></i> Agregar</button>
			</div> <!-- /.card-header -->
			<div class="card-body">
				<table id="listar_clientes" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Nit</th>
							<th>Nombre</th>
							<th>Correo</th>
							<th>Ciudad</th>
							<th>Direccion</th>
							<th>telefono</th>
							<th>Nota Credito</th>
							<th style="text-align:center;">Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($arreglo_clientes) {
							foreach ($arreglo_clientes as $objeto_clientes) {
								$id_cliente = $objeto_clientes->id_cliente;
								$nit = $objeto_clientes->nit;
								$nombre = $objeto_clientes->nombre;
								$correo = $objeto_clientes->correo;
								$ciudad = $objeto_clientes->ciudad;
								$direccion = $objeto_clientes->direccion;
								$telefono = $objeto_clientes->telefono;
								$nota_credito = $objeto_clientes->nota_credito;
								$fecha_creacion = $objeto_clientes->fecha_creacion;
								$fecha_actualizacion = $objeto_clientes->fecha_actualizacion;


						?>
								<tr>
									<td><?php echo $nit; ?></td>
									<td><?php echo $nombre; ?></td>
									<td><?php echo $correo; ?></td>
									<td><?php echo $ciudad; ?></td>
									<td><?php echo $direccion; ?></td>
									<td><?php echo $telefono; ?></td>
									<td><?php echo $nota_credito; ?></td>
									<td style="text-align:center;">
										<i class="fas fa-file-alt" style="cursor:pointer; margin-right: 10px; color: #0C6766;" onclick="vistaHistorialCliente('<?php echo $nit; ?>')" title="Historial"></i>
										<i class="fas fa-edit" style="cursor:pointer; margin-right: 10px; color: #0C6766;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaActualizarCliente('<?php echo $id_cliente; ?>')" title="Actualizar"></i>
										<i class="far fa-eye" style="cursor:pointer; margin-right: 10px;color: #697E0A;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaConsultarCliente('<?php echo $id_cliente; ?>')" title="Consultar"></i>
										<!--	<i class="fas fa-trash" style="cursor:pointer; margin-right: 10px;color: #870B0B;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaEliminarCliente('<?php echo $id_cliente; ?>')" title="Eliminar "></i>   -->

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
			function vistaHistorialCliente(nit) {
				var parametros = {
					"ruta": "clientes_VI/historial",
					"nit": nit
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#contenido').html(respuesta);
				});
			}

			function vistaAgregarCliente() {
				var parametros = {
					"ruta": "clientes_VI/agregar"
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Agregar cliente');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaActualizarCliente(id_cliente) {
				var parametros = {
					"ruta": "clientes_VI/actualizar",
					"id_cliente": id_cliente
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Actualizar cliente');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaConsultarCliente(id_cliente) {
				var parametros = {
					"ruta": "clientes_VI/consultar",
					"id_cliente": id_cliente
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#titulo_modal').html('Consultar cliente');
					$('#contenido_modal').html(respuesta);
				});
			}

			function vistaEliminarCliente(id_cliente) {
				var parametros = {
					"ruta": "clientes_VI/eliminar",
					"id_cliente": id_cliente
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#contenido_modal').html(respuesta);
				});
			}
			data_table_clientes = organizarTabla({
				id: "listar_clientes"
			});
		</script>
	<?php
	} //Fin funcion listar


	function historial()
	{
		require_once "modelos/clientes_MO.php";
		require_once "modelos/ventas_MO.php";
		$conexion = new servidor('A');
		$clientes_MO = new clientes_MO($conexion);
		$nit = $_POST["nit"];
		$arreglo_clientes = $clientes_MO->seleccionar("nit", $nit);
		$nombre = $arreglo_clientes[0]->nombre;

		$ventas_MO = new ventas_MO($conexion);
		$arreglo_ventas = $ventas_MO->seleccionar("nit_cliente", $nit);

		$arreglo_abono = $ventas_MO->seleccionarAbonos($nit);



	?>

		<div class="row offset-sm-1 mt-4 mr-3 ml-3">
			<div class="col-md-12">
				<div class="card card-primary card-tabs">
					<div class="card-header">
						<h3><i class="fas fa-solid fa-bars"></i> Historial -> <i class="nav-icon fas fa-user-tie"></i> <?php echo $nombre; ?></h3>
					</div>
					<div class="card-header p-0 pt-1">
						<ul class="nav nav-tabs" id="custom-tabs-five-tab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="custom-tabs-five-overlay-tab" data-toggle="pill" href="#custom-tabs-five-overlay" role="tab" aria-controls="custom-tabs-five-overlay" aria-selected="true">Facturas Pagadas</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="factura_pendiente_tab" data-toggle="pill" href="#factura_pendiente" role="tab" aria-controls="custom-tabs-five-normal" aria-selected="false">Facturas Pendientes</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="custom-tabs-five-overlay-dark-tab" data-toggle="pill" href="#custom-tabs-five-overlay-dark" role="tab" aria-controls="custom-tabs-five-overlay-dark" aria-selected="false">Abonos</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="custom-tabs-five-normal-tab" data-toggle="pill" href="#custom-tabs-five-normal" role="tab" aria-controls="custom-tabs-five-normal" aria-selected="false">Devolución</a>
							</li>
						</ul>
					</div>
					<div class="card-body">
						<div class="tab-content" id="custom-tabs-five-tabContent">
							<div class="tab-pane fade show active" id="custom-tabs-five-overlay" role="tabpanel" aria-labelledby="custom-tabs-five-overlay-tab">
								<div class="card-body">
									<table id="listar_clientes" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>ID Factura</th>
												<th>Fecha</th>
												<th>Neto</th>
												<th>Descuento</th>
												<th>Total</th>
												<th style="text-align:center;">Imprimir</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($arreglo_ventas) {
												foreach ($arreglo_ventas as $objeto_ventas) {
													$id_factura = $objeto_ventas->id_factura;
													$fecha = $objeto_ventas->fecha_creacion;
													$neto = $objeto_ventas->total;
													$abono = $objeto_ventas->abono;
													$descuento = $objeto_ventas->descuento;
													$total = $neto - $descuento;

													if ($total == $abono) {

											?>
														<tr>
															<td><?php echo $id_factura; ?></td>
															<td><?php echo $fecha; ?></td>
															<td><?php echo number_format($neto); ?></td>
															<td><?php echo number_format($descuento); ?></td>
															<td><?php echo number_format($total); ?></td>
															<td style="text-align:center;">
																<a class="fas fa-print" href="librerias/imprimir.php?id_factura=<?php echo $id_factura; ?>" style="cursor:pointer; margin-right: 10px; color: blue;" title="Imprimir PDF"></a>

															</td>
														</tr>
											<?php
													}
												}
											}
											?>
										</tbody>
									</table>
								</div> <!-- /.card-body -->
							</div>

							<div class="tab-pane fade" id="factura_pendiente" role="tabpanel" aria-labelledby="factura_pendiente_tab">
								<div class="card-body">
									<table id="listar_clientes" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>ID Factura</th>
												<th>Fecha</th>
												<th>Total</th>
												<th>Abono</th>
												<th>Saldo</th>
												<th style="text-align:center;">Abonar</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($arreglo_ventas) {
												foreach ($arreglo_ventas as $objeto_ventas) {
													$id_factura = $objeto_ventas->id_factura;
													$fecha = $objeto_ventas->fecha_creacion;
													$neto = $objeto_ventas->total;
													$abono = $objeto_ventas->abono;
													$saldo = $objeto_ventas->saldo;
													$descuento = $objeto_ventas->descuento;
													$total = $neto - $descuento;

													if ($total != $abono) {

											?>
														<tr>
															<td><?php echo $id_factura; ?></td>
															<td><?php echo $fecha; ?></td>
															<td><?php echo number_format($total); ?></td>
															<td><?php echo number_format($abono); ?></td>
															<td><?php echo number_format($saldo); ?></td>
															<td style="text-align:center;">
																<a class="fas fa-hand-holding-usd" style="cursor:pointer; margin-right: 10px; color: blue;" title="Abonar" onclick="vistaAgregarAbono('<?php echo $id_factura; ?>', '<?php echo $nit; ?>')" data-toggle="modal" data-target="#ventana_modal"></a>

															</td>
														</tr>
											<?php
													}
												}
											}
											?>
										</tbody>
									</table>
								</div> <!-- /.card-body -->
							</div>


							<div class="tab-pane fade" id="custom-tabs-five-overlay-dark" role="tabpanel" aria-labelledby="custom-tabs-five-overlay-dark-tab">
								<div class="overlay-wrapper">
									<table id="listar_clientes" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>ID Factura</th>
												<th>Fecha</th>
												<th>Vendedor</th>
												<th>Valor</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($arreglo_abono) {
												foreach ($arreglo_abono as $objeto_ventas) {
													$id_factura = $objeto_ventas->id_factura;
													$fecha = $objeto_ventas->fecha_creacion;
													$vendedor = NOMBRE_EMPRESA;
													$valor = $objeto_ventas->valor;



											?>
													<tr>
														<td><?php echo $id_factura; ?></td>
														<td><?php echo $fecha; ?></td>
														<td><?php echo $vendedor; ?></td>
														<td><?php echo number_format($valor); ?></td>
													</tr>
											<?php

												}
											}
											?>
										</tbody>
									</table>
								</div>
							</div>


						</div>
					</div> <!-- /.card -->
				</div>
			</div>
		</div><!-- /.row -->

		<script>
			function vistaAgregarAbono(id, nit) {
				var date = new Date(); //Fecha actual
				var mes = date.getMonth() + 1; //obteniendo mes
				var dia = date.getDate(); //obteniendo dia
				var ano = date.getFullYear(); //obteniendo año
				if (dia < 10)
					dia = '0' + dia; //agrega cero si el menor de 10
				if (mes < 10)
					mes = '0' + mes //agrega cero si el menor de 10  
				var parametros = {
					"ruta": "clientes_VI/agregarAbono",
					"id_factura": id,
					"nit": nit
				};
				$.post('index.php', parametros, function(respuesta) {
					$('#contenido_modal').html(respuesta);
					document.getElementById('fecha').value = ano + "-" + mes + "-" + dia;
				});
			}
		</script>
	<?php
	} //FIN FUNCION HISTORIAL


	function agregarAbono()
	{
		$id_factura = $_POST["id_factura"];
		$nit = $_POST["nit"];
	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Adicionar Abono a la Factura</h3>
			</div>
			<div class="card-body">
				<form id="formulario_agregar_abonos">
					<input type="hidden" name="nit" id="nit" value="<?php echo $nit; ?>">
					<label style=" font-size: 25px">Fecha:</label>
					<input class="form-control" type="date" id="fecha" name="fecha" placeholder="Fecha" autocomplete="on"> <br>
					<label style=" font-size: 25px">Factura N°:</label>
					<input class="form-control" type="text" id="id_factura" name="id_factura" autocomplete="on" value="<?php echo $id_factura; ?>" readonly> <br>
					<label style="font-size: 25px">Valor:</label>
					<input class="form-control" type="number" id="valor" name="valor" placeholder="Valor" autocomplete="on"> <br>
					<button type="button" class="btn btn-primary float-right" onclick="controladorAgregarAbono()"><i class="fas fa-save"></i> Guardar</button>
				</form>
			</div><!-- /.card-body -->
		</div><!-- Form Element sizes -->

		<script>
			function controladorAgregarAbono() {
				if ($('#formulario_agregar_abonos')[0].elements.valor.value == "" || $('#formulario_agregar_abonos')[0].elements.valor.value < 0) {
					alert("Debe ingresar el monto abonar");
				} else {
					var parametros = {
						"ruta": "ventas_CO/agregarAbono",
						"nit": $('#formulario_agregar_abonos')[0].elements.nit.value,
						"fecha": $('#formulario_agregar_abonos')[0].elements.fecha.value,
						"id_factura": $('#formulario_agregar_abonos')[0].elements.id_factura.value,
						"valor": $('#formulario_agregar_abonos')[0].elements.valor.value
					};
					$nit = $('#formulario_agregar_abonos')[0].elements.nit.value;
					$.post('index.php', parametros, function(respuesta) {
						var objeto_respuesta = JSON.parse(respuesta);
						if (objeto_respuesta.estado == "EXITO") {
							exito(objeto_respuesta.mensaje);
							$('#formulario_agregar_abonos')[0].reset();

							$.post('index.php', {
								"ruta": "clientes_VI/historial",
								"nit": $nit
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
	}


	function agregar()
	{
	?>
		<!-- Form Element sizes -->
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos Clientees</h3>
			</div>
			<div class="card-body">
				<form id="formulario_agregar_clientes">
					<div class="row">
						<div class="col-6">
							<label class="col-lg-3 control-label">Cedula</label>
							<input class="form-control form-control-lg" type="text" id="nit" name="nit" placeholder="CC" autocomplete="on">
							<br>
							<label class="col-lg-3 control-label">Nombre</label>
							<input class="form-control form-control-lg" type="text" id="nombre" name="nombre" placeholder="Nombre" autocomplete="on">
							<br>
							<label class="col-lg-3 control-label">Telefono</label>
							<input class="form-control form-control-lg" type="text" id="telefono" name="telefono" placeholder="Telefono" autocomplete="on"><br>
							<label class="col-lg-3 control-label">Correo</label>
							<input class="form-control form-control-lg" type="text" id="correo" name="correo" placeholder="Correo" autocomplete="on"><br>

						</div>
						<div class="col-6">
							<label class="col-lg-3 control-label">Departamento</label>
							<select class="form-control" id="departamento" name="departamento" required onchange="camposCiudad(value)">
								<option disabled selected>Departamento</option>
								<option value="Amazonas">Amazonas</option>
								<option value="Antioquia">Antioquia</option>
								<option value="Arauca">Arauca</option>
								<option value="Atlántico">Atlántico</option>
								<option value="Bolívar">Bolívar</option>
								<option value="Boyacá">Boyacá</option>
								<option value="Caldas">Caldas</option>
								<option value="Caquetá">Caquetá</option>
								<option value="Casanare">Casanare</option>
								<option value="Cauca">Cauca</option>
								<option value="Cesar">Cesar</option>
								<option value="Chocó">Chocó</option>
								<option value="Córdoba">Córdoba</option>
								<option value="Cundinamarca">Cundinamarca</option>
								<option value="Guainía">Guainía</option>
								<option value="Guaviare">Guaviare</option>
								<option value="Huila">Huila</option>
								<option value="La Guajira">La Guajira</option>
								<option value="Magdalena">Magdalena</option>
								<option value="Meta">Meta</option>
								<option value="Nariño">Nariño</option>
								<option value="Norte de Santander">Norte de Santander</option>
								<option value="Putumayo">Putumayo</option>
								<option value="Quindío">Quindío</option>
								<option value="Risaralda">Risaralda</option>
								<option value="San Andrés y Providencia">San Andrés y Providencia</option>
								<option value="Santander">Santander</option>
								<option value="Sucre">Sucre</option>
								<option value="Tolima">Tolima</option>
								<option value="Valle del Cauca">Valle del Cauca</option>
								<option value="Vaupés">Vaupés</option>
								<option value="Vichada">Vichada</option>
							</select><br>
							<label class="col-lg-3 control-label">Ciudad</label>
							<select class="form-control" id="ciudad" name="ciudad" required>
								<option disabled selected>Ciudad</option>
							</select><br>
							<label class="col-lg-3 control-label">Direccion</label>
							<input class="form-control form-control-lg" type="text" id="direccion" name="direccion" placeholder="Direccion" autocomplete="on"><br>
							<input class="form-control form-control-lg" type="hidden" id="nota_credito" name="nota_credito">
							<button type="button" class="btn btn-primary float-center btn-lg" onclick="controladorAgregarClientees()"><i class="fas fa-save"></i> Guardar</button>
						</div>

					</div>
				</form>
			</div><!-- /.card-body -->
		</div><!-- Form Element sizes -->


		<script>
			function camposCiudad(value) {
				$('#ciudad').empty();
				if (value == "Amazonas") {
					var array = ["El Encanto", "La Chorrera", "La Pedrera", "La Victoria", "Leticia", "Mirití-Paraná", "Puerto Alegría", "Puerto Arica", "Puerto Nariño", "Puerto Santander", "Tarapacá"];
				} else if (value == "Antioquia") {
					var array = ["Abriaquí", "Altamira", "Amalfi", "Angostura", "Anorí", "Anzá", "Apartadó", "Aragón", "Arboletes", "Bajirá", "Bellavista", "Belmira", "Berlín", "Betulia", "Briceño", "Builópolis", "Buriticá", "Caicedo", "Campamento", "Carepa", "Carolina", "Caucasia", "Cañasgordas", "Cedeño", "Cestillal", "Chamuscados", "Chigorodó", "Concordia", "Copacabana", "Currulao", "Cáceres", "Córdoba", "Cordova-municipio", "Dabeiba", "Don Matías", "Ebéjico", "El Aro", "El Bagre", "El Brasil", "El Carmen", "El Cedro", "El Oro", "El Salto", "El Tres", "Entrerrios", "Frontino", "Giraldo", "Guadalupe", "Guasabra", "Gómez Plata", "Güintar", "Horizontes", "Ituango", "Jaiperá", "Jardín", "Juntas de Uramita", "La Encarnación", "La Granja", "La Merced", "La Placita", "La Playa", "Labores", "Liborina", "Llanadas", "Llanos de Cuivá", "Manguruma", "Marinilla", "Medellín", "Murri", "Mutatá", "Nariño (municipio)", "Necoclí", "Nutibara", "Ochalí", "Olaya", "Palmitas", "Pavarandocito", "Peque", "Pueblo Nuevo", "Puerto Antioquia", "Puerto Berrío", "Puerto Valdivia", "Quebrada Seca", "Quebradona", "Remedios", "Rioverde", "Sabanalarga", "Saiza", "San Andrés", "San Jerónimo", "San José", "San Juan de Urabá", "San Nicolás de Titumate", "San Pablo", "San Pedro", "San Pedro de Urabá", "Santa Ana", "Santa Fé de Antioquia", "Santa Rita", "Santa Rosa de Osos", "Sevilla", "Sopetrán", "Sucre", "Tabacal", "Tarazá", "Toledo", "Tonusco Arriba", "Turbo", "Urama", "Uramagrande", "Uramita", "Urrao", "Valdivia", "Vigía del Fuerte", "Yalí", "Yarumal"];
				} else if (value == "Arauca") {
					var array = ["Arauca", "Arauquita", "Cravo Norte", "Fortul", "Puerto Rondón", "Saravena", "Tame"];
				} else if (value == "Atlántico") {
					var array = ["Barranquilla", "Baranoa", "Campo de la Cruz", "Candelaria", "Galapa", "Juan de Acosta", "Luruaco", "Malambo", "Manatí", "Palmar de Varela", "Piojó", "Polonuevo", "Ponedera", "Puerto Colombia", "Repelon", "Sabanagrande", "Sabanalarga", "Santa Lucía", "Santo Tomás", "Soledad", "Suan", "Suan", "Tubará", "Usiacurí"];
				} else if (value == "Bolívar") {
					var array = ["Achí", "Altos del Rosario", "Arenal del Sur", "Arjona", "Barranco de Loba", "Calamar", "Cantagallo", "Cartagena de Indias", "Cicuco", "Clemencia", "Córdoba", "El Carmen", "El Guamo", "El Peñón", "Hatillo de Loba", "Magangué", "Mahates", "Margarita", "Maria La Baja", "Montecristo", "Morales", "Pinillos", "Regidor", "Río Viejo", "San Cristóbal", "San Estanislao", "San Fernando", "San Jacinto", "San Jacinto del Cauca", "San Juan Nepomuceno", "San Martín de Loba", "San Pablo", "Santa Catalina", "Santa Cruz de Mompox", "Santa Rosa", "Santa Rosa del Sur", "Simití", "Soplaviento", "Talaigua Nuevo", "Tiquisio", "Turbaco", "Turbana", "Villanueva", "Zambrano"];
				} else if (value == "Boyacá") {
					var array = ["Centro", "Distrito Fronterizo", "Gutiérrez", "La Libertad", "Lengupá", "Márquez", "Neira", "Norte", "Occidente", "Oriente", "Ricaurte", "Sugamuxi", "Tundama", "Tundama", "Valderrama", "Zona de Manejo Especial"];
				} else if (value == "Caldas") {
					var array = ["Aguadas", "Anserma", "Aranzazu", "Belalcázar", "Chinchiná", "Filadelfia", "La Dorada", "La Merced", "Manizales", "Manzanares", "Marmato", "Marquetalia", "Marulanda", "Neira", "Norcasia", "Pácora", "Palestina", "Pensilvania", "Riosucio", "Risaralda", "Salamina", "Samaná", "San José", "Supía", "Victoria", "Villamaría", "Viterbo"];
				} else if (value == "Caquetá") {
					var array = ["Albania", "Belen de los andaquites", "Cartagena del Chairá", "Curillo", "El Doncello", "El Paujil", "Florencia", "	La Montañita", "Morelia", "Puerto Milán", "Puerto Rico", "San José del Fragua", "San Vicente del Caguán", "Solano", "Solita", "Valparaíso"];
				} else if (value == "Casanare") {
					var array = ["Aguazul", "Chámeza", "Hato Corozal", "La Salina", "Maní", "Monterrey", "Nunchía", "Orocué", "Paz de Ariporo", "Pore", "Recetor", "Sabanalarga", "Sácama", "San Luis de Palenque", "Támara", "Tauramena"];
				} else if (value == "Cauca") {
					var array = ["Almaguer", "Argelia", "Balboa", "Bolívar", "Buenos Aires", "Cajibío", "Caldono", "Caloto", "Corinto", "El Tambo", "Florencia", "Guachené", "Guapí", "Inzá", "Jambaló", "La Sierra", "La Vega", "López de Micay", "Mercaderes", "Miranda", "Morales", "Padilla", "Páez", "Patía", "Piamonte", "Piendamó", "Popayán", "Puerto Tejada", "Puracé", "Rosas", "San Sebastián", "Santa Rosa", "Santander de Quilichao", "Silvia", "Sotará", "Suarez", "Sucre", "Timbiquí", "Timbío", "Toribío", "Totoró", "Villa Rica"];
				} else if (value == "Cesar") {
					var array = ["Aguachica", "Agustín Codazzi", "Astrea", "Becerril", "Bosconia", "Chimichagua", "Chiriguaná", "Curumaní", "El Copey", "El Paso", "Gamarra", "González", "La Gloria", "La Jagua de Ibirico", "La Paz", "Manaure Balcón del Cesar", "Pailitas", "Pelaya", "Pueblo Bello", "Río de Oro", "San Alberto", "San Diego", "San Martín", "Tamalameque", "Valledupar"];
				} else if (value == "Chocó") {
					var array = ["Acandí", "Alto Baudó", "Atrato", "Bagadó", "Bahía Solano", "Bajo Baudó", "Bojayá", "Cértegui", "Condoto", "El Cantón de San Pablo", "El Carmen de Atrato", "El Carmen del Darién", "El Litoral de San Juan", "Istmina", "Juradó", "Lloró", "Medio Atrato", "Medio Baudó", "Medio San Juan", "Nóvita", "Nuquí", "Quibdó", "Río Iró", "Río Quito", "Riosucio", "San José del Palmar", "Sipí", "Tadó", "Unguía", "Unión Panamericana"];
				} else if (value == "Córdoba") {
					var array = ["Ayapel", "Buenavista", "Canalete", "Cereté", "Chimá", "Chinú", "Ciénaga de Oro", "Cotorra", "La Apartada", "Los Córdobas", "Momil", "Moñitos", "Montelíbano", "Montería", "Planeta Rica", "Pueblo Nuevo", "Puerto Escondido", "Puerto Libertador", "Purísima", "Sahagún", "San Andrés de Sotavento", "San Antero", "San Bernardo del Viento", "San Carlos", "San José de Uré", "San Pelayo", "Santa Cruz de Lorica", "Tierralta", "Tuchín", "Valencia"];
				} else if (value == "Cundinamarca") {
					var array = ["Almeidas", "Alto Magdalena", "Bajo Magdalena", "Bogota", "Gualivá", "Guavio", "Magdalena Centro", "Medina", "Oriente", "Rionegro", "Sabana Centro", "Sabana Occidente", "Soacha", "Sumapaz", "Tequendama", "Ubaté"];
				} else if (value == "Guainía") {
					var array = ["Barrancominas", "Cacahual", "Inírida", "La Guadalupe", "Morichal Nuevo", "Pana Pana", "Puerto Colombia", "San Felipe"];
				} else if (value == "Guaviare") {
					var array = ["Calamar", "El Retorno", "Miraflores", "San José del Guaviare"];
				} else if (value == "Huila") {
					var array = ["Centro", "Norte", "Occidente", "Sur"];
				} else if (value == "La Guajira") {
					var array = ["Albania", "Barrancas", "Dibulla", "Distracción", "El Molino", "Fonseca", "Hatonuevo", "La Jagua del Pilar", "Maicao", "Manaure", "Riohacha", "San Juan del Cesar", "Uribia", "Urumita", "Villanueva"];
				} else if (value == "Magdalena") {
					var array = ["Algarrobo", "Aracataca", "Ariguaní", "Cerro de San Antonio", "Chibolo", "Ciénaga", "Concordia", "El Banco", "El Piñón", "El Retén", "Fundación", "Guamal", "Nueva Granada", "Pedraza", "Pijiño del Carmen", "Pivijay", "Plato", "Pueblo Viejo", "Remolino", "Sabanas de San Ángel", "Salamina", "San Sebastián de Buenavista", "San Zenón", "Santa Ana", "Santa Bárbara de Pinto", "Santa Marta", "Sitionuevo", "Tenerife", "Zapayán", "Zona Bananera"];
				} else if (value == "Meta") {
					var array = ["Acacías", "Barranca de Upía", "Cabuyaro", "Castilla La Nueva", "Cubarral", "Cumaral", "El Calvario", "El Castillo", "El Dorado", "Fuente de Oro", "Granada", "Guamal", "La Macarena", "La Uribe", "Lejanías", "Mapiripán", "Mesetas", "Puerto Concordia", "Puerto Gaitán", "Puerto Lleras", "Puerto López", "Puerto Rico", "Restrepo", "San Carlos de Guaroa", "San Juan de Arama", "San Juanito", "San Martín", "Villavicencio", "Vista Hermosa"];
				} else if (value == "Nariño") {
					var array = ["Juanambú", "Obando", "Pasto", "Tumaco-Barbacoas", "Túquerres"];
				} else if (value == "Norte de Santander") {
					var array = ["Ábrego", "Arboledas", "Bochalema", "Bucarasica", "Cáchira", "Cácota", "Chinácota", "Chitagá", "Convención", "Cúcuta", "Cucutilla", "Durania", "El Carmen", "El Tarra", "El Zulia", "Gramalote", "Hacarí", "Herrán", "La Esperanza", "La Playa de Belén", "Labateca", "Los Patios", "Lourdes", "Mutiscua", "Ocaña", "Pamplona", "Pamplonita", "Puerto Santander", "Ragonvalia", "Salazar de Las Palmas", "San Calixto", "San cayetano", "Santiago", "Santo Domingo de Silos", "Sardinata", "Teorama", "Tibú", "Toledo", "Villa Caro", "Villa del Rosario"];
				} else if (value == "Putumayo") {
					var array = ["Colón", "Mocoa", "Orito", "Puerto Asís", "Puerto Caicedo", "Puerto Guzmán", "Puerto Leguízamo", "San Francisco", "San Miguel", "Santiago", "Sibundoy", "Valle del Guamuez", "Villagarzón"];
				} else if (value == "Quindío") {
					var array = ["Armenia", "Buenavista", "Calarcá", "Circasia", "Córdoba", "Filandia", "Génova", "La Tebaida", "Montenegro", "Pijao", "Quimbaya", "Salento"];
				} else if (value == "Risaralda") {
					var array = ["Apía", "Balboa", "Belén de Umbría", "Dosquebradas", "Guática", "La Celia", "La Virginia", "Marsella", "Mistrató", "Pereira", "Pueblo Rico", "Quinchía", "Santa Rosa de Cabal", "Santuario"];
				} else if (value == "San Andrés y Providencia") {
					var array = ["Providencia y Santa Catalina Islas", "San Andrés"];
				} else if (value == "Santander") {
					var array = ["Barrancabermeja", "Bucaramanga", "Cimitarra", "Floridablanca", "Girón", "Lebrija", "Piedecuesta", "Puerto Wilches", "San Gil", "San Vicente de Chucurí"];
				} else if (value == "Sucre") {
					var array = ["Buenavista", "Caimito", "Chalán", "Colosó", "Corozal", "Coveñas", "El Roble", "Galeras", "Guaranda", "La Unión", "Los Palmitos", "Majagual", "Morroa", "Ovejas", "Sampués", "San Antonio de Palmito", "San Benito Abad", "San Juan de Betulia", "San Marcos", "San Onofre", "San Pedro", "Santiago de Tolú", "Sincé", "Sincelejo", "Sucre", "Toluviejo"];
				} else if (value == "Tolima") {
					var array = ["Ibagué", "Nevados", "Norte", "Oriente", "Sur", "Suroriente"];
				} else if (value == "Valle del Cauca") {
					var array = ["Centro", "Norte", "Occidente", "Oriente", "Sur"];
				} else if (value == "Vaupés") {
					var array = ["Carurú", "Mitú", "Pacoa", "Papunaua", "Taraira", "Yavaraté"];
				} else if (value == "Vichada") {
					var array = ["Cumaribo", "La Primavera", "Puerto Carreño", "Santa Rosalía"];
				}


				for (valor in array) {
					$('#ciudad').append($("<option>", {
						value: array[valor],
						text: array[valor]
					}));

				}

			}


			function controladorAgregarClientees() {
				var cadena = $('#formulario_agregar_clientes').serialize();
				var parametros = {
					"ruta": "clientes_CO/agregar",
					"nit": $('#formulario_agregar_clientes')[0].elements.nit.value,
					"nombre": $('#formulario_agregar_clientes')[0].elements.nombre.value,
					"correo": $('#formulario_agregar_clientes')[0].elements.correo.value,
					"departamento": $('#formulario_agregar_clientes')[0].elements.departamento.value,
					"ciudad": $('#formulario_agregar_clientes')[0].elements.ciudad.value,
					"direccion": $('#formulario_agregar_clientes')[0].elements.direccion.value,
					"telefono": $('#formulario_agregar_clientes')[0].elements.telefono.value,
					"nota_credito": $('#formulario_agregar_clientes')[0].elements.nota_credito.value
				};
				$.post('index.php', parametros, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);
					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);
						$('#formulario_agregar_clientes')[0].reset();

						$.post('index.php', {
							"ruta": "clientes_VI/listar"
						}, function(res) {
							$('#contenido').html(res);
						});

						//let boton='<div style="text-align:center;"><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ventana_modal" onclick=vistaActualizarCliente("'+objeto_respuesta.id_cliente+'") title="Actualizar"><i class="far fa-edit"></i></button></div>';

						// data_table_clientes.row.add([objeto_respuesta.codigo,objeto_respuesta.referencia,objeto_respuesta.descripcion,objeto_respuesta.cantidad,objeto_respuesta.precio,boton]).draw();
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
		require_once "modelos/clientes_MO.php";
		$conexion = new servidor('A');
		$clientes_MO = new clientes_MO($conexion);
		$id_cliente = $_POST["id_cliente"];
		$arreglo_clientes = $clientes_MO->seleccionar("id_cliente", $id_cliente);
		$id_cliente = $arreglo_clientes[0]->id_cliente;
		$nit = $arreglo_clientes[0]->nit;
		$nombre = $arreglo_clientes[0]->nombre;
		$correo = $arreglo_clientes[0]->correo;
		$telefono = $arreglo_clientes[0]->telefono;
		$nota_credito = $arreglo_clientes[0]->nota_credito;

		$dato_direccion = explode("-", $arreglo_clientes[0]->ciudad);
		$departamento = $dato_direccion[1];
		$ciudad = $dato_direccion[0];
		$direccion = $arreglo_clientes[0]->direccion;

	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos Clientees</h3>
			</div>
			<div class="card-body">
				<form id="formulario_agregar_clientes">
					<div class="row">
						<div class="col-6">
							<input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $id_cliente; ?>">
							<label class="col-lg-3 control-label">Cedula</label>
							<input class="form-control form-control-lg" type="text" id="nit" name="nit" value="<?php echo $nit; ?>" placeholder="CC" autocomplete="on"> <br>
							<label class="col-lg-3 control-label">Nombre</label>
							<input class="form-control form-control-lg" type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" placeholder="Nombre" autocomplete="on"> <br>
							<label class="col-lg-3 control-label">Telefono</label>
							<input class="form-control form-control-lg" type="text" id="telefono" name="telefono" value="<?php echo $telefono; ?>" placeholder="Telefono" autocomplete="on"><br>
							<label class="col-lg-3 control-label">Correo</label>
							<input class="form-control form-control-lg" type="text" id="correo" name="correo" value="<?php echo $correo; ?>" placeholder="Correo" autocomplete="on"><br>

						</div>
						<div class="col-6">
							<label class="col-lg-3 control-label">Departamento</label>
							<select class="form-control" id="departamento" name="departamento" required onchange="camposCiudad(value)">
								<option disabled selected><?php echo $departamento ?></option>
								<option value="Amazonas">Amazonas</option>
								<option value="Antioquia">Antioquia</option>
								<option value="Arauca">Arauca</option>
								<option value="Atlántico">Atlántico</option>
								<option value="Bolívar">Bolívar</option>
								<option value="Boyacá">Boyacá</option>
								<option value="Caldas">Caldas</option>
								<option value="Caquetá">Caquetá</option>
								<option value="Casanare">Casanare</option>
								<option value="Cauca">Cauca</option>
								<option value="Cesar">Cesar</option>
								<option value="Chocó">Chocó</option>
								<option value="Córdoba">Córdoba</option>
								<option value="Cundinamarca">Cundinamarca</option>
								<option value="Guainía">Guainía</option>
								<option value="Guaviare">Guaviare</option>
								<option value="Huila">Huila</option>
								<option value="La Guajira">La Guajira</option>
								<option value="Magdalena">Magdalena</option>
								<option value="Meta">Meta</option>
								<option value="Nariño">Nariño</option>
								<option value="Norte de Santander">Norte de Santander</option>
								<option value="Putumayo">Putumayo</option>
								<option value="Quindío">Quindío</option>
								<option value="Risaralda">Risaralda</option>
								<option value="San Andrés y Providencia">San Andrés y Providencia</option>
								<option value="Santander">Santander</option>
								<option value="Sucre">Sucre</option>
								<option value="Tolima">Tolima</option>
								<option value="Valle del Cauca">Valle del Cauca</option>
								<option value="Vaupés">Vaupés</option>
								<option value="Vichada">Vichada</option>
							</select><br>
							<label class="col-lg-3 control-label">Ciudad</label>
							<select class="form-control" id="ciudad" name="ciudad" required>
								<option disabled selected><?php echo $ciudad; ?></option>
							</select><br>
							<label class="col-lg-3 control-label">Direccion</label>
							<input class="form-control form-control-lg" type="text" id="direccion" name="direccion" value="<?php echo $direccion ?>" placeholder="Direccion" autocomplete="on"><br>
							<input class="form-control form-control-lg" type="hidden" id="nota_credito" name="nota_credito" value="<?php echo $nota_credito; ?>">
							<button type="button" class="btn btn-primary float-center btn-lg" onclick="controladorActualizarCliente()"><i class="fas fa-save"></i> Guardar</button>
						</div>

					</div>
				</form>
			</div><!-- /.card-body -->
		</div><!-- Form Element sizes -->


		<script>
			function camposCiudad(value) {
				$('#ciudad').empty();
				if (value == "Amazonas") {
					var array = ["El Encanto", "La Chorrera", "La Pedrera", "La Victoria", "Leticia", "Mirití-Paraná", "Puerto Alegría", "Puerto Arica", "Puerto Nariño", "Puerto Santander", "Tarapacá"];
				} else if (value == "Antioquia") {
					var array = ["Abriaquí", "Altamira", "Amalfi", "Angostura", "Anorí", "Anzá", "Apartadó", "Aragón", "Arboletes", "Bajirá", "Bellavista", "Belmira", "Berlín", "Betulia", "Briceño", "Builópolis", "Buriticá", "Caicedo", "Campamento", "Carepa", "Carolina", "Caucasia", "Cañasgordas", "Cedeño", "Cestillal", "Chamuscados", "Chigorodó", "Concordia", "Copacabana", "Currulao", "Cáceres", "Córdoba", "Cordova-municipio", "Dabeiba", "Don Matías", "Ebéjico", "El Aro", "El Bagre", "El Brasil", "El Carmen", "El Cedro", "El Oro", "El Salto", "El Tres", "Entrerrios", "Frontino", "Giraldo", "Guadalupe", "Guasabra", "Gómez Plata", "Güintar", "Horizontes", "Ituango", "Jaiperá", "Jardín", "Juntas de Uramita", "La Encarnación", "La Granja", "La Merced", "La Placita", "La Playa", "Labores", "Liborina", "Llanadas", "Llanos de Cuivá", "Manguruma", "Marinilla", "Medellín", "Murri", "Mutatá", "Nariño (municipio)", "Necoclí", "Nutibara", "Ochalí", "Olaya", "Palmitas", "Pavarandocito", "Peque", "Pueblo Nuevo", "Puerto Antioquia", "Puerto Berrío", "Puerto Valdivia", "Quebrada Seca", "Quebradona", "Remedios", "Rioverde", "Sabanalarga", "Saiza", "San Andrés", "San Jerónimo", "San José", "San Juan de Urabá", "San Nicolás de Titumate", "San Pablo", "San Pedro", "San Pedro de Urabá", "Santa Ana", "Santa Fé de Antioquia", "Santa Rita", "Santa Rosa de Osos", "Sevilla", "Sopetrán", "Sucre", "Tabacal", "Tarazá", "Toledo", "Tonusco Arriba", "Turbo", "Urama", "Uramagrande", "Uramita", "Urrao", "Valdivia", "Vigía del Fuerte", "Yalí", "Yarumal"];
				} else if (value == "Arauca") {
					var array = ["Arauca", "Arauquita", "Cravo Norte", "Fortul", "Puerto Rondón", "Saravena", "Tame"];
				} else if (value == "Atlántico") {
					var array = ["Barranquilla", "Baranoa", "Campo de la Cruz", "Candelaria", "Galapa", "Juan de Acosta", "Luruaco", "Malambo", "Manatí", "Palmar de Varela", "Piojó", "Polonuevo", "Ponedera", "Puerto Colombia", "Repelon", "Sabanagrande", "Sabanalarga", "Santa Lucía", "Santo Tomás", "Soledad", "Suan", "Suan", "Tubará", "Usiacurí"];
				} else if (value == "Bolívar") {
					var array = ["Achí", "Altos del Rosario", "Arenal del Sur", "Arjona", "Barranco de Loba", "Calamar", "Cantagallo", "Cartagena de Indias", "Cicuco", "Clemencia", "Córdoba", "El Carmen", "El Guamo", "El Peñón", "Hatillo de Loba", "Magangué", "Mahates", "Margarita", "Maria La Baja", "Montecristo", "Morales", "Pinillos", "Regidor", "Río Viejo", "San Cristóbal", "San Estanislao", "San Fernando", "San Jacinto", "San Jacinto del Cauca", "San Juan Nepomuceno", "San Martín de Loba", "San Pablo", "Santa Catalina", "Santa Cruz de Mompox", "Santa Rosa", "Santa Rosa del Sur", "Simití", "Soplaviento", "Talaigua Nuevo", "Tiquisio", "Turbaco", "Turbana", "Villanueva", "Zambrano"];
				} else if (value == "Boyacá") {
					var array = ["Centro", "Distrito Fronterizo", "Gutiérrez", "La Libertad", "Lengupá", "Márquez", "Neira", "Norte", "Occidente", "Oriente", "Ricaurte", "Sugamuxi", "Tundama", "Tundama", "Valderrama", "Zona de Manejo Especial"];
				} else if (value == "Caldas") {
					var array = ["Aguadas", "Anserma", "Aranzazu", "Belalcázar", "Chinchiná", "Filadelfia", "La Dorada", "La Merced", "Manizales", "Manzanares", "Marmato", "Marquetalia", "Marulanda", "Neira", "Norcasia", "Pácora", "Palestina", "Pensilvania", "Riosucio", "Risaralda", "Salamina", "Samaná", "San José", "Supía", "Victoria", "Villamaría", "Viterbo"];
				} else if (value == "Caquetá") {
					var array = ["Albania", "Belen de los andaquites", "Cartagena del Chairá", "Curillo", "El Doncello", "El Paujil", "Florencia", "	La Montañita", "Morelia", "Puerto Milán", "Puerto Rico", "San José del Fragua", "San Vicente del Caguán", "Solano", "Solita", "Valparaíso"];
				} else if (value == "Casanare") {
					var array = ["Aguazul", "Chámeza", "Hato Corozal", "La Salina", "Maní", "Monterrey", "Nunchía", "Orocué", "Paz de Ariporo", "Pore", "Recetor", "Sabanalarga", "Sácama", "San Luis de Palenque", "Támara", "Tauramena"];
				} else if (value == "Cauca") {
					var array = ["Almaguer", "Argelia", "Balboa", "Bolívar", "Buenos Aires", "Cajibío", "Caldono", "Caloto", "Corinto", "El Tambo", "Florencia", "Guachené", "Guapí", "Inzá", "Jambaló", "La Sierra", "La Vega", "López de Micay", "Mercaderes", "Miranda", "Morales", "Padilla", "Páez", "Patía", "Piamonte", "Piendamó", "Popayán", "Puerto Tejada", "Puracé", "Rosas", "San Sebastián", "Santa Rosa", "Santander de Quilichao", "Silvia", "Sotará", "Suarez", "Sucre", "Timbiquí", "Timbío", "Toribío", "Totoró", "Villa Rica"];
				} else if (value == "Cesar") {
					var array = ["Aguachica", "Agustín Codazzi", "Astrea", "Becerril", "Bosconia", "Chimichagua", "Chiriguaná", "Curumaní", "El Copey", "El Paso", "Gamarra", "González", "La Gloria", "La Jagua de Ibirico", "La Paz", "Manaure Balcón del Cesar", "Pailitas", "Pelaya", "Pueblo Bello", "Río de Oro", "San Alberto", "San Diego", "San Martín", "Tamalameque", "Valledupar"];
				} else if (value == "Chocó") {
					var array = ["Acandí", "Alto Baudó", "Atrato", "Bagadó", "Bahía Solano", "Bajo Baudó", "Bojayá", "Cértegui", "Condoto", "El Cantón de San Pablo", "El Carmen de Atrato", "El Carmen del Darién", "El Litoral de San Juan", "Istmina", "Juradó", "Lloró", "Medio Atrato", "Medio Baudó", "Medio San Juan", "Nóvita", "Nuquí", "Quibdó", "Río Iró", "Río Quito", "Riosucio", "San José del Palmar", "Sipí", "Tadó", "Unguía", "Unión Panamericana"];
				} else if (value == "Córdoba") {
					var array = ["Ayapel", "Buenavista", "Canalete", "Cereté", "Chimá", "Chinú", "Ciénaga de Oro", "Cotorra", "La Apartada", "Los Córdobas", "Momil", "Moñitos", "Montelíbano", "Montería", "Planeta Rica", "Pueblo Nuevo", "Puerto Escondido", "Puerto Libertador", "Purísima", "Sahagún", "San Andrés de Sotavento", "San Antero", "San Bernardo del Viento", "San Carlos", "San José de Uré", "San Pelayo", "Santa Cruz de Lorica", "Tierralta", "Tuchín", "Valencia"];
				} else if (value == "Cundinamarca") {
					var array = ["Almeidas", "Alto Magdalena", "Bajo Magdalena", "Bogota", "Gualivá", "Guavio", "Magdalena Centro", "Medina", "Oriente", "Rionegro", "Sabana Centro", "Sabana Occidente", "Soacha", "Sumapaz", "Tequendama", "Ubaté"];
				} else if (value == "Guainía") {
					var array = ["Barrancominas", "Cacahual", "Inírida", "La Guadalupe", "Morichal Nuevo", "Pana Pana", "Puerto Colombia", "San Felipe"];
				} else if (value == "Guaviare") {
					var array = ["Calamar", "El Retorno", "Miraflores", "San José del Guaviare"];
				} else if (value == "Huila") {
					var array = ["Centro", "Norte", "Occidente", "Sur"];
				} else if (value == "La Guajira") {
					var array = ["Albania", "Barrancas", "Dibulla", "Distracción", "El Molino", "Fonseca", "Hatonuevo", "La Jagua del Pilar", "Maicao", "Manaure", "Riohacha", "San Juan del Cesar", "Uribia", "Urumita", "Villanueva"];
				} else if (value == "Magdalena") {
					var array = ["Algarrobo", "Aracataca", "Ariguaní", "Cerro de San Antonio", "Chibolo", "Ciénaga", "Concordia", "El Banco", "El Piñón", "El Retén", "Fundación", "Guamal", "Nueva Granada", "Pedraza", "Pijiño del Carmen", "Pivijay", "Plato", "Pueblo Viejo", "Remolino", "Sabanas de San Ángel", "Salamina", "San Sebastián de Buenavista", "San Zenón", "Santa Ana", "Santa Bárbara de Pinto", "Santa Marta", "Sitionuevo", "Tenerife", "Zapayán", "Zona Bananera"];
				} else if (value == "Meta") {
					var array = ["Acacías", "Barranca de Upía", "Cabuyaro", "Castilla La Nueva", "Cubarral", "Cumaral", "El Calvario", "El Castillo", "El Dorado", "Fuente de Oro", "Granada", "Guamal", "La Macarena", "La Uribe", "Lejanías", "Mapiripán", "Mesetas", "Puerto Concordia", "Puerto Gaitán", "Puerto Lleras", "Puerto López", "Puerto Rico", "Restrepo", "San Carlos de Guaroa", "San Juan de Arama", "San Juanito", "San Martín", "Villavicencio", "Vista Hermosa"];
				} else if (value == "Nariño") {
					var array = ["Juanambú", "Obando", "Pasto", "Tumaco-Barbacoas", "Túquerres"];
				} else if (value == "Norte de Santander") {
					var array = ["Ábrego", "Arboledas", "Bochalema", "Bucarasica", "Cáchira", "Cácota", "Chinácota", "Chitagá", "Convención", "Cúcuta", "Cucutilla", "Durania", "El Carmen", "El Tarra", "El Zulia", "Gramalote", "Hacarí", "Herrán", "La Esperanza", "La Playa de Belén", "Labateca", "Los Patios", "Lourdes", "Mutiscua", "Ocaña", "Pamplona", "Pamplonita", "Puerto Santander", "Ragonvalia", "Salazar de Las Palmas", "San Calixto", "San cayetano", "Santiago", "Santo Domingo de Silos", "Sardinata", "Teorama", "Tibú", "Toledo", "Villa Caro", "Villa del Rosario"];
				} else if (value == "Putumayo") {
					var array = ["Colón", "Mocoa", "Orito", "Puerto Asís", "Puerto Caicedo", "Puerto Guzmán", "Puerto Leguízamo", "San Francisco", "San Miguel", "Santiago", "Sibundoy", "Valle del Guamuez", "Villagarzón"];
				} else if (value == "Quindío") {
					var array = ["Armenia", "Buenavista", "Calarcá", "Circasia", "Córdoba", "Filandia", "Génova", "La Tebaida", "Montenegro", "Pijao", "Quimbaya", "Salento"];
				} else if (value == "Risaralda") {
					var array = ["Apía", "Balboa", "Belén de Umbría", "Dosquebradas", "Guática", "La Celia", "La Virginia", "Marsella", "Mistrató", "Pereira", "Pueblo Rico", "Quinchía", "Santa Rosa de Cabal", "Santuario"];
				} else if (value == "San Andrés y Providencia") {
					var array = ["Providencia y Santa Catalina Islas", "San Andrés"];
				} else if (value == "Santander") {
					var array = ["Barrancabermeja", "Bucaramanga", "Cimitarra", "Floridablanca", "Girón", "Lebrija", "Piedecuesta", "Puerto Wilches", "San Gil", "San Vicente de Chucurí"];
				} else if (value == "Sucre") {
					var array = ["Buenavista", "Caimito", "Chalán", "Colosó", "Corozal", "Coveñas", "El Roble", "Galeras", "Guaranda", "La Unión", "Los Palmitos", "Majagual", "Morroa", "Ovejas", "Sampués", "San Antonio de Palmito", "San Benito Abad", "San Juan de Betulia", "San Marcos", "San Onofre", "San Pedro", "Santiago de Tolú", "Sincé", "Sincelejo", "Sucre", "Toluviejo"];
				} else if (value == "Tolima") {
					var array = ["Ibagué", "Nevados", "Norte", "Oriente", "Sur", "Suroriente"];
				} else if (value == "Valle del Cauca") {
					var array = ["Centro", "Norte", "Occidente", "Oriente", "Sur"];
				} else if (value == "Vaupés") {
					var array = ["Carurú", "Mitú", "Pacoa", "Papunaua", "Taraira", "Yavaraté"];
				} else if (value == "Vichada") {
					var array = ["Cumaribo", "La Primavera", "Puerto Carreño", "Santa Rosalía"];
				}


				for (valor in array) {
					$('#ciudad').append($("<option>", {
						value: array[valor],
						text: array[valor]
					}));

				}

			}

			function controladorActualizarCliente() {
				var cadena = $('#formulario_actualizar_clientes').serialize();
				var parametro = {
					"id_cliente": $('#formulario_actualizar_clientes')[0].elements.id_cliente.value,
					"nit": $('#formulario_actualizar_clientes')[0].elements.nit.value,
					"nombre": $('#formulario_actualizar_clientes')[0].elements.nombre.value,
					"correo": $('#formulario_actualizar_clientes')[0].elements.correo.value,
					"telefono": $('#formulario_actualizar_clientes')[0].elements.telefono.value,
					"nota_credito": $('#formulario_actualizar_clientes')[0].elements.nota_credito.value,
					"ruta": "clientes_CO/actualizar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						//Pendiente en actualizacion de usuario
						$('#formulario_actualizar_clientes').modal('hide');

						$.post('index.php', {
							"ruta": "clientes_VI/listar"
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
		require_once "modelos/clientes_MO.php";
		$conexion = new servidor('A');
		$clientes_MO = new clientes_MO($conexion);
		$id_cliente = $_POST["id_cliente"];
		$arreglo_clientes = $clientes_MO->seleccionar("id_cliente", $id_cliente);
		$id_cliente = $arreglo_clientes[0]->id_cliente;
		$nit = $arreglo_clientes[0]->nit;
		$nombre = $arreglo_clientes[0]->nombre;
		$correo = $arreglo_clientes[0]->correo;
		$telefono = $arreglo_clientes[0]->telefono;
		$nota_credito = $arreglo_clientes[0]->nota_credito;

		$dato_direccion = explode("-", $arreglo_clientes[0]->ciudad);
		$departamento = $dato_direccion[1];
		$ciudad = $dato_direccion[0];
		$direccion = $arreglo_clientes[0]->direccion;

	?>
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Datos Clientees</h3>
			</div>
			<div class="card-body">
				<form id="formulario_agregar_clientes">
					<div class="row">
						<div class="col-6">
							<input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $id_cliente; ?>">
							<label class="col-lg-3 control-label">Cedula</label>
							<input class="form-control form-control-lg" type="text" id="nit" name="nit" value="<?php echo $nit; ?>" readonly autocomplete="on"> <br>
							<label class="col-lg-3 control-label">Nombre</label>
							<input class="form-control form-control-lg" type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" readonly autocomplete="on"> <br>
							<label class="col-lg-3 control-label">Telefono</label>
							<input class="form-control form-control-lg" type="text" id="telefono" name="telefono" value="<?php echo $telefono; ?>" readonly autocomplete="on"><br>
							<label class="col-lg-3 control-label">Correo</label>
							<input class="form-control form-control-lg" type="text" id="correo" name="correo" value="<?php echo $correo; ?>" readonly autocomplete="on"><br>

						</div>
						<div class="col-6">
							<label class="col-lg-3 control-label">Departamento</label>
							<input class="form-control form-control-lg" type="text" id="departamento" name="departamento" value="<?php echo $departamento; ?>" readonly autocomplete="on"><br>
							<label class="col-lg-3 control-label">Ciudad</label>
							<input class="form-control form-control-lg" type="text" id="ciudad" name="ciudad" value="<?php echo $ciudad; ?>" autocomplete="on" readonly><br>
							<label class="col-lg-3 control-label">Direccion</label>
							<input class="form-control form-control-lg" type="text" id="direccion" name="direccion" value="<?php echo $direccion ?>" readonly autocomplete="on"><br>
							<input class="form-control form-control-lg" type="hidden" id="nota_credito" name="nota_credito" value="<?php echo $nota_credito; ?>">
							<button type="button" class="btn btn-primary float-center btn-lg" onclick="controladorActualizarCliente()"><i class="fas fa-save"></i> Guardar</button>
						</div>

					</div>
				</form>
			</div><!-- /.card-body -->
		</div><!-- Form Element sizes -->

	<?php
	} //FIN FUNCION CONSULTAR


	function eliminar()
	{
		require_once "modelos/clientes_MO.php";
		$conexion = new servidor('A');
		$clientes_MO = new clientes_MO($conexion);
		$id_cliente = $_POST["id_cliente"];


	?>
		<div class="card card-danger text-center">
			<div class="card-header">
				<h5 class="center">Confirmar eliminación</h5>
			</div>
			<div class="card-body">
				¿Estás seguro de que deseas eliminar este elemento?
			</div>
			<div class="card-footer">
				<form id="formulario_eliminar_clientes" method="post">
					<input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $id_cliente; ?>">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-danger" onclick="controladorEliminarCliente(<?php echo $id_cliente; ?>)">Eliminar</button>
				</form>
			</div>
		</div>

		<script>
			function controladorEliminarCliente($id) {
				//var cadena=$('#formulario_eliminar_clientes').serialize();			        	 		
				var parametro = {
					"id_cliente": $id,
					"ruta": "clientes_CO/eliminar"
				};

				$.post('index.php', parametro, function(respuesta) {
					var objeto_respuesta = JSON.parse(respuesta);

					if (objeto_respuesta.estado == "EXITO") {
						exito(objeto_respuesta.mensaje);

						$.post('index.php', {
							"ruta": "clientes_VI/listar"
						}, function(res) {
							$('#contenido').html(res);
							$('#ventana_modal').modal('hide');
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