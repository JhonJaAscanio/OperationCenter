<?php

class menu_VI
{
  function __construct() {}

  function verMenu()
  {
    require_once "modelos/accesos_MO.php";
    require_once "modelos/ventas_MO.php";
    require_once "modelos/compras_MO.php";
    require_once "modelos/egresos_MO.php";

    $conexion = new servidor('A');
    $accesos_MO = new accesos_MO($conexion);

    $arreglo_accesos = $accesos_MO->seleccionarUsuario("id_usuario", $_SESSION["id_usuario"]);
    $usuario = $arreglo_accesos[0]->usuario;

    $ventas_MO = new ventas_MO($conexion);
    $arreglo_ventas = $ventas_MO->seleccionar();

    $egresos_MO = new egresos_MO($conexion);
    $arreglo_egresos = $egresos_MO->seleccionar();
    $arreglo_egresos_mayor = $egresos_MO->seleccionar_mayor_gasto();

    $compras_MO = new compras_MO($conexion);
    $arreglo_compras = $compras_MO->seleccionar();

    $ventas_mostrador = new ventas_MO($conexion);
    $arreglo_ventas_mostrador = $ventas_mostrador->seleccionar_mostrador();
    $arreglo_articulo_cantidad = $ventas_mostrador->seleccionarArticuloCantidad();
    $arreglo_articulo_fecha = $ventas_mostrador->seleccionarArticuloFecha();

?>
    <!DOCTYPE html>
    <!--
This is a starter template page. Use this page to   your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
    <html lang="es">

    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="x-ua-compatible" content="ie=edge">

      <title><?php echo NOMBRE_EMPRESA; ?></title>

      <!-- Font Awesome Icons -->
      <link rel="stylesheet" href="dist/plugins/fontawesome-free/css/all.min.css">

      <!-- DataTables -->
      <link rel="stylesheet" href="dist/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="dist/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

      <!-- Toastr -->
      <link rel="stylesheet" href="dist/plugins/toastr/toastr.min.css">

      <link rel="stylesheet" href="dist/css/estilos.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="dist/css/adminlte.min.css">
      <!-- Google Font: Source Sans Pro -->

      <!--<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">-->
      <link href="dist/css/fontsGoogleApi.css" rel="stylesheet">

      <!--<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">-->
      <link rel="stylesheet" href="dist/css/ionicons.min.css">

      <link rel="stylesheet" type="text/css" href="dist/css/jquery-ui.css">
    </head>

    <body class="hold-transition sidebar-mini">
      <!-- recarga por ajax -->
      <div id="div_carga"><img id="cargador" src="dist/img/bluespinner.gif" width="90" /></div>
      <!---------------------->
      <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand  navbar-dark">
          <!-- Left navbar links -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
              <a href="index.php" class="nav-link">Inicio</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
              <a href="#" class="nav-link" onclick="salir()">Salir</a>
            </li>

            <!--<li> <a href="" class="nav-link" onclick="redirigir()">Realizar copia de seguridad</a> </li> -->
          </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
          <!-- Brand Logo -->
          <a href="index.php" class="brand-link elevation-4">
            <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light"><?php echo NOMBRE_EMPRESA ?></span>
          </a>

          <!-- Sidebar -->
          <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                <img src="dist/img/avatar.png" class="img-circle elevation-2" alt="User Image">
              </div>
              <div class="info">
                <a href="#" class="d-block"><?php echo $usuario; ?> </a>
              </div>
            </div>

            <!-- Sidebar Menu -->
            <!-- Sidebar Menu -->
            <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">



                <!--   panel de opciones VENTAS en el menu -->
                <li class="nav-item has-treeview menu-open">
                  <a href="#" class="nav-link active" style="background-color: #45f3ff;">
                    <!--<i class="nav-icon fas fa-tachometer-alt"></i>-->
                    <i class="  fas fa-donate"></i>
                    <p> VENTAS
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item ">
                      <a href="#" class="nav-link" onclick="verModulo('ventasMostrador_VI/agregar_ventas_mostrador')">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Ventas</p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="#" class="nav-link" onclick="verModulo('ventasMostrador_VI/listar')">
                        <i class="nav-icon fas fa-archive"></i>
                        <p>Listar ventas</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link" onclick="verModulo('devoluciones_VI/listar')">
                        <i class="nav-icon  fas fa-sign-out-alt"></i>
                        <p>Listar Devoluciones</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link" onclick="verModulo('ventas_VI/listar')">
                        <i class="nav-icon  fas fa-wallet"></i>
                        <p>Facturacion</p>
                      </a>
                    </li>
                  </ul>
                </li><!-- Fin panel de opcion VENTAS  -->

                <!--   panel de opciones INVENTARIO en el menu -->
                <li class="nav-item has-treeview menu-open">
                  <a href="#" class="nav-link active" style="background-color: #45f3ff;">
                    <!--<i class="nav-icon fas fa-tachometer-alt"></i>-->
                    <i class="fas fa-solid fa-bars"></i>
                    <p> INVENTARIO
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">

                    <li class="nav-item">
                      <a href="#" class="nav-link" onclick="verModulo('productos_VI/listar')">
                        <i class="nav-icon fas fa-solid fa-window-restore"></i>
                        <p>Productos</p>
                      </a>
                    </li>

                    <li class="nav-item ">
                      <a href="#" class="nav-link" onclick="verModulo('categorias_VI/listar')">
                        <i class="nav-icon fas fa-solid fa-sitemap"></i>
                        <p>Categorias</p>
                      </a>
                    </li>

                    <li class="nav-item ">
                      <a href="#" class="nav-link" onclick="verModulo('catalogos_VI/listar')">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Catalogos</p>
                      </a>
                    </li>
                  </ul>
                </li><!-- Fin panel de opcion INVENTARIO  -->

                <!--   panel de opciones COMPRAS en el menu -->
                <li class="nav-item has-treeview menu-open">
                  <a href="#" class="nav-link active" style="background-color: #45f3ff;">
                    <!--<i class="nav-icon fas fa-tachometer-alt"></i>-->
                    <i class="fas fa-shopping-cart"></i>
                    <p> COMPRAS
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="#" class="nav-link" onclick="verModulo('compras_VI/listar')">
                        <i class="nav-icon  fas fa-dolly-flatbed"></i>
                        <p>Compras</p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="#" class="nav-link" onclick="verModulo('egresos_VI/listar')">
                        <i class="nav-icon  fas fa-file-alt"></i>
                        <p>Egresos</p>
                      </a>
                    </li>
                  </ul>
                </li><!-- Fin panel de opcion COMPRAS  -->

                <!--   panel de opciones CONTACTOS en el menu -->
                <li class="nav-item has-treeview menu-open">
                  <a href="#" class="nav-link active" style="background-color: #45f3ff;">
                    <!--<i class="nav-icon fas fa-tachometer-alt"></i>-->
                    <i class="fas fas fa-users"></i>
                    <p> CONTACTOS
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="#" class="nav-link" onclick="verModulo('proveedores_VI/listar')">
                        <i class="nav-icon fas fa-id-card-alt"></i>
                        <p>Proveedores</p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="#" class="nav-link" onclick="verModulo('clientes_VI/listar')">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Clientes</p>
                      </a>
                    </li>
                    <li class="nav-item ">
                      <a href="#" class="nav-link" onclick="verModulo('accesos_VI/listar')">
                        <i class="nav-icon fas fa-user-lock"></i>
                        <p>Accesos</p>
                      </a>
                    </li>
                  </ul>
                </li><!-- Fin panel de opcion CONTACTOS  -->

              </ul>
            </nav>
            <!-- /.sidebar-menu -->
          </div>
          <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" id="contenido">

          <!-- Small Box (Stat card) -->
          <div class="row justify-content-md-center">

            <div class="col-md-2 col-6 offset-sm-1 mt-4 mx-auto">
              <!-- small card -->
              <div class="small-box bg-info shadow">
                <div class="inner">
                  <a id="ingresos">
                  </a>

                  <p><b>Ingreso totales</b></p>
                </div>
                <div class="icon">
                  <i class="fas fa-chart-line"></i>
                </div>
                <a onclick="verModulo('compras_VI/listar')" class="small-box-footer">
                  Más info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-md-2 col-6 offset-sm-1 mt-4 mx-auto">
              <!-- small card -->
              <div class="small-box bg-success shadow">
                <div class="inner">
                  <a id="gastos"></a>

                  <p><b>Gastos totales</b></p>
                </div>
                <div class="icon">
                  <i class="fas fa-money-check-alt"></i>
                </div>
                <a href="#" class="small-box-footer">
                  Más info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-md-2 col-6 offset-sm-1 mt-4 mx-auto">
              <!-- small card -->
              <div class="small-box bg-warning shadow">
                <div class="inner">
                  <a id="ingresos_mes"></a>

                  <p><b>Ingresos este mes</b></p>
                </div>
                <div class="icon">
                  <i class="fas fa-chart-line"></i>
                </div>
                <a onclick="verModulo('buscarVentas_VI/listar')" class="small-box-footer">
                  Más info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-md-2 col-6 offset-sm-1 mt-4 mx-auto">
              <!-- small card -->
              <div class="small-box bg-danger shadow">
                <div class="inner">
                  <a id="gastos_mes"></a>

                  <p><b>Gastos este mes</b></p>
                </div>
                <div class="icon">
                  <i class="fas fa-money-bill-alt"></i>
                </div>
                <a href="#" class="small-box-footer">
                  Más info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <!-- ./col -->
          </div> <!-- ROW  -->

          <br><br><br>
          <!-- BAR CHART -->
          <div class="row justify-content-md-center">

            <div class="container mt-4">
              <div class="chart">
                <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div><br><br> <!-- Fin row  -->


          <div class="row justify-content-md-center">
            <div class="col-md-5 m-2">
              <div class="card shadow">
                <div class="card-header" style="background-color: #7899B6; color: white">
                  <h2 class="card-title">Ultimas Ventas</h2>
                </div> <!-- /.card-header -->
                <div class="card-body">
                  <table id="listar_articulos_vendidos" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="col-md-1">Top</th>
                        <th class="col-md-1">Codigo</th>
                        <th class="col-md-6">Nombre Producto</th>
                        <th class="col-md-2">Fecha</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($arreglo_articulo_fecha) {
                        $c = 0;
                        foreach ($arreglo_articulo_fecha as $objeto_ventas) {
                          $c++;
                          $id_venta_mostrador = $objeto_ventas->id_venta_mostrador;
                          $codigo = $objeto_ventas->codigo;
                          $descripcion = $objeto_ventas->descripcion;
                          $cantidad = $objeto_ventas->cantidad;
                          $total = $objeto_ventas->total;
                          $fecha = $objeto_ventas->fecha_creacion;
                          $corte = explode(" ", $fecha);


                      ?>
                          <tr>
                            <td><?php echo $c; ?></td>
                            <td><?php echo $codigo; ?></td>
                            <td><?php echo $descripcion; ?></td>
                            <td><?php echo $corte[0]; ?></td>
                          </tr>
                      <?php
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                </div><!-- card body -->
              </div>
            </div>



            <div class="col-md-5 m-2 ">
              <div class="card shadow">
                <div class="card-header" style="background-color: #7899B6; color: white">
                  <h2 class="card-title"><b>TOP 10</b> Productos mas vendidos</h2>
                </div> <!-- /.card-header -->
                <div class="card-body">
                  <table id="listar_articulos_vendidos" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="col-md-1">Top</th>
                        <th class="col-md-1">Codigo</th>
                        <th class="col-md-6">Nombre Producto</th>
                        <th class="col-md-1">Cant</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($arreglo_articulo_cantidad) {
                        $c = 0;
                        foreach ($arreglo_articulo_cantidad as $objeto_ventas) {
                          $c++;
                          $id_venta_mostrador = $objeto_ventas->id_venta_mostrador;
                          $codigo = $objeto_ventas->codigo;
                          $descripcion = $objeto_ventas->descripcion;
                          $cantidad = $objeto_ventas->cantidad;
                          $total = $objeto_ventas->total;
                          $fecha = $objeto_ventas->fecha_creacion;




                      ?>
                          <tr>
                            <td><?php echo $c; ?></td>
                            <td><?php echo $codigo; ?></td>
                            <td><?php echo $descripcion; ?></td>
                            <td><?php echo $cantidad; ?></td>
                          </tr>
                      <?php
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                </div> <!-- /.card-body -->
              </div><!-- /.card -->
            </div><!-- Col -->



            <div class="col-md-5 m-2 ">
              <div class="card shadow">
                <div class="card-header" style="background-color: #BD0303; color: white">
                  <h2 class="card-title"><b>TOP 10</b> Mayor gasto</h2>
                </div> <!-- /.card-header -->
                <div class="card-body">
                  <table id="listar_articulos_vendidos" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="col-md-1">Top</th>
                        <th class="col-md-6">Concepto</th>
                        <th class="col-md-2">Valor</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($arreglo_egresos_mayor) {
                        $c = 0;
                        foreach ($arreglo_egresos_mayor as $objeto_ventas) {
                          $c++;
                          $concepto = $objeto_ventas->concepto;
                          $valor = $objeto_ventas->valor;




                      ?>
                          <tr>
                            <td><?php echo $c; ?></td>
                            <td><?php echo $concepto; ?></td>
                            <td><?php echo $valor; ?></td>
                          </tr>
                      <?php
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                </div> <!-- /.card-body -->
              </div><!-- /.card -->
            </div><!-- Col -->



          </div>


        </div><!-- /.content-wrapper -->

        <div class="modal fade" id="ventana_modal">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titulo_modal"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="contenido_modal" tabindex="-1"></div>
            </div> <!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div> <!-- /.modal -->
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
          <!-- Control sidebar content goes here -->
          <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
          </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
          <!-- To the right -->

          <div class="float-right d-none d-sm-inline">
            Sistema contable
          </div>
          <!-- Default to the left -->
          <strong>Copyright &copy; 2023-2030 <a href="https://adminlte.io">JhonAscanio.io</a>.</strong> All rights reserved.


        </footer>
      </div>
      <!-- ./wrapper -->

      <!-- REQUIRED SCRIPTS -->

      <!-- jQuery -->

      <!--<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>-->
      <script src="dist/js/jquery-3.6.0.js"></script>

      <script type="text/javascript" src="dist/js/jquery-ui-1.13.2/jquery-ui.js"></script>
      <!-- <script src="dist/plugins/jquery/jquery.min.js"></script>-->
      <!-- <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>  -->

      <!-- Bootstrap 4 -->
      <script src="dist/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- AdminLTE App -->
      <script src="dist/js/adminlte.min.js"></script>

      <!-- DataTables -->
      <script src="dist/plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="dist/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
      <script src="dist/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
      <script src="dist/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>


      <!-- Toastr -->
      <script src="dist/plugins/toastr/toastr.min.js"></script>
      <script src="dist/js/funciones.js"></script>

      <!-- ChartJS -->
      <script src="dist/plugins/chart.js/Chart.min.js"></script>



      <script>
        var total_ingreso = 0;
        total_compras = 0;
        total_egresos = 0;
        total_gastos = 0;
        total_ingreso_mostrador = 0;

        let arreglo_total_mes = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        let arreglo_total_mes_ventas_mostrador = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        let arreglo_total_mes_compras = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        let arreglo_total_mes_egreso = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        var day;

        var arreglos_ven = <?php echo json_encode($arreglo_ventas); ?>;
        var arreglos_ven_mos = <?php echo json_encode($arreglo_ventas_mostrador); ?>;
        var arreglos_com = <?php echo json_encode($arreglo_compras); ?>;
        var arreglos_egre = <?php echo json_encode($arreglo_egresos); ?>;


        //Mostrar en pantalla los ingresos totales, factura
        arreglos_ven.forEach(function(word) {
          total_ingreso = (total_ingreso + parseInt(word.total));
          let fechas = word.fecha_creacion;
          let corte = fechas.split('-');
          day = parseInt(corte[1]);

          arreglo_total_mes[day] = arreglo_total_mes[day] + parseInt(word.total);

        }); //fin facturas

        //ventas de mostrador
        arreglos_ven_mos.forEach(function(word) {
          total_ingreso_mostrador = (total_ingreso_mostrador + parseInt(word.total));
          let fechas = word.fecha_creacion;
          let corte = fechas.split('-');
          day = parseInt(corte[1]);

          arreglo_total_mes_ventas_mostrador[day] = arreglo_total_mes_ventas_mostrador[day] + parseInt(word.total);

        }); // fin ventas mostrador

        //Compras al proveedor
        arreglos_com.forEach(function(word) {
          total_compras = (total_compras + parseInt(word.total));
          let fechas = word.fecha_creacion;
          let corte = fechas.split('-');
          day = parseInt(corte[1]);

          arreglo_total_mes_compras[day] = arreglo_total_mes_compras[day] + parseInt(word.total);
        }); //fin compras a proveedor

        //Compras (egresos) por varios conceptos
        arreglos_egre.forEach(function(word) {
          total_egresos = (total_egresos + parseInt(word.valor));
          let fechas = word.fecha;
          let corte = fechas.split('-');
          day = parseInt(corte[1]);

          arreglo_total_mes_egreso[day] = arreglo_total_mes_egreso[day] + parseInt(word.valor);
        }); //Fin compras (egresos)

        total_gastos = total_compras + total_egresos; //Suma de todos los gastos (compras,egresos)
        total_ingresos_total = total_ingreso_mostrador + total_ingreso; //Suma de todos los ingresos(Facturas,ventas_mostrador)

        $('#ingresos').html(total_ingresos_total.toLocaleString('en-US')); //mandar valor al menu principal
        $('#gastos').html(total_gastos.toLocaleString('en-US')); //mandar valor al menu principal

        const fecha = new Date();
        mes = (fecha.getMonth() + 1);

        for (var i = 1; i <= 12; i++) {
          if (mes == i) {
            $('#ingresos_mes').html((arreglo_total_mes[i] + arreglo_total_mes_ventas_mostrador[i]).toLocaleString('en-US')); //mandar valor al menu principal
            $('#gastos_mes').html((arreglo_total_mes_compras[i] + arreglo_total_mes_egreso[i]).toLocaleString('en-US')); //mandar valor al menu principal
          }
        }



        //------------------------------------------------------

        $(function() {

          var areaChartData = {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets: [{
                label: 'Total ventas',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: [(arreglo_total_mes[1] + arreglo_total_mes_ventas_mostrador[1]), (arreglo_total_mes[2] + arreglo_total_mes_ventas_mostrador[2]), (arreglo_total_mes[3] + arreglo_total_mes_ventas_mostrador[3]), (arreglo_total_mes[4] + arreglo_total_mes_ventas_mostrador[4]), (arreglo_total_mes[5] + arreglo_total_mes_ventas_mostrador[5]), (arreglo_total_mes[6] + arreglo_total_mes_ventas_mostrador[6]), (arreglo_total_mes[7] + arreglo_total_mes_ventas_mostrador[7]), (arreglo_total_mes[8] + arreglo_total_mes_ventas_mostrador[8]), (arreglo_total_mes[9] + arreglo_total_mes_ventas_mostrador[9]), (arreglo_total_mes[10] + arreglo_total_mes_ventas_mostrador[10]), (arreglo_total_mes[11] + arreglo_total_mes_ventas_mostrador[11]), (arreglo_total_mes[12] + arreglo_total_mes_ventas_mostrador[12])]
              },
              {
                label: 'Total gastos',
                backgroundColor: 'rgba(210, 214, 222, 1)',
                borderColor: 'rgba(210, 214, 222, 1)',
                pointRadius: false,
                pointColor: 'rgba(210, 214, 222, 1)',
                pointStrokeColor: '#c1c7d1',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data: [(arreglo_total_mes_compras[1] + arreglo_total_mes_egreso[1]), (arreglo_total_mes_compras[2] + arreglo_total_mes_egreso[2]), (arreglo_total_mes_compras[3] + arreglo_total_mes_egreso[3]), (arreglo_total_mes_compras[4] + arreglo_total_mes_egreso[4]), (arreglo_total_mes_compras[5] + arreglo_total_mes_egreso[5]), (arreglo_total_mes_compras[6] + arreglo_total_mes_egreso[6]), (arreglo_total_mes_compras[7] + arreglo_total_mes_egreso[7]), (arreglo_total_mes_compras[8] + arreglo_total_mes_egreso[8]), (arreglo_total_mes_compras[9] + arreglo_total_mes_egreso[9]), (arreglo_total_mes_compras[10] + arreglo_total_mes_egreso[10]), (arreglo_total_mes_compras[11] + arreglo_total_mes_egreso[11]), (arreglo_total_mes_compras[12] + arreglo_total_mes_egreso[12])]
              },
            ]
          }
          var barChartCanvas = $('#barChart').get(0).getContext('2d')
          var barChartData = $.extend(true, {}, areaChartData)
          var temp0 = areaChartData.datasets[0]
          var temp1 = areaChartData.datasets[1]
          barChartData.datasets[0] = temp1
          barChartData.datasets[1] = temp0

          var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false
          }

          new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
          })

        })

        //--------------------------------------------------------------------------------

        //UNA FORMA DE MANDAR DATOS POR JQUERY
        function verModulo(ruta) {
          var parametros = {
            "ruta": ruta
          };
          $.post('index.php', parametros, function(respuesta) {
            $('#contenido').html(respuesta);
          });
        }


        /* 
//OTRA FORMA DE MANDAR DATOS POR AJAX
     function verModulo(ruta)
      {
         var parametros = {
                  "ruta" : ruta
          };
       $.ajax({
          // aqui va la ubicación de la página PHP
            url: 'index.php',
            type: 'POST',
            dataType: 'html',
            data:parametros,
            success:function(respuesta){
             // imprime "resultado Funcion"
              $('#contenido').html(respuesta);
            }
        });
      }

    */

        function salir() {
          var parametros = {
            "ruta": "accesos_CO/cerrarSesion/<?php echo $usuario; ?>"
          };
          $.post("index.php", parametros, function(respuesta) {
            var objecto_respuesta = JSON.parse(respuesta);

            if (objecto_respuesta.estado == "EXITO") {
              exito(objecto_respuesta.mensaje);

              setTimeout(function() {
                location.href = "index.php";
              }, 2000);
            }
          });
        }

        $(document).hide().ajaxStart(function() {
          $("#div_carga").show();
        }).ajaxStop(function() {
          $("#div_carga").hide();
        });
      </script>


    </body>

    </html>
<?php


  }
}
?>