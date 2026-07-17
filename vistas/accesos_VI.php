<?php
class accesos_VI
{
  function __construct() {}

  //FORMULARIO INICIO DE SESION--------------------------------
  function formularioInicioSesion()
  {
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Empresa</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="dist/css/estiloLogin.css">
      <link rel="stylesheet" href="dist/css/estilos.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>

    <body style="background: #23242a;">

      <div class="box">
        <div class="form">
          <h2>Sign in</h2>
          <form role="form" id="quickForm" method="post" autocomplete="off">
            <div class="inputBox">
              <input type="text" name="usuario" value="<?php if (isset($usuario)) {
                                                          echo $usuario;
                                                        } ?>">
              <span>Username</span>
              <i></i>
            </div>
            <div class="inputBox">
              <input type="password" name="clave">
              <span>Password</span>
              <i></i>
            </div>

            <div class="links">
              <a href="#">Forgot Password</a>
              <a href="#">Singup</a>
            </div>
            <input type="submit" value="Login">
          </form>
          <?php
          if (isset($_GET["error"])) {
            $error = $_GET["error"];
            $usuario = $_GET["usuario"];
            echo "<span style='color:red;'>" . $error . "</span><br><br>";
          }
          ?>
        </div>
      </div>


      <!--  FOOTER   -->
      <div class="footer">
        <div class="container">
          <div class="row">
            <div id="footer-copyright" class="col-md-5">
              2022-22 JHONS.COM.
            </div> <!-- /span6 -->
            <div id="footer-copyright" class="col-md-6">
              Desarrollado por <a href="#" target="_blank">JHONS.com</a>
            </div> <!-- /.span6 -->
            <div id="footer-terms" class="col-md-1">
              <p>V 1.0</p>
            </div> <!-- /.span6 -->
          </div> <!-- /row -->
        </div> <!-- /container -->
      </div>

      <!-- FIN FOOTER   -->
      <script src="dist/bootstrap/bootstrap-5.3.0-alpha1-dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>
  <?php
  } // FIN...... FORMULARIO INICIO DE SESION--------------------------------



  function listar()
  {
    require_once "modelos/accesos_MO.php";

    $conexion = new servidor('A');
    $accesos_MO = new accesos_MO($conexion);

    $arreglo_accesos = $accesos_MO->seleccionar();
  ?>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Personas con Acceso al Sistema</h3>
        <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#ventana_modal" onclick="vistaAgregarAccesos()"><i class="far fa-plus-square"></i> Agregar</button>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="listar_accesos" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Usuario</th>
              <th class="col-sm-2">Fecha Creaci&oacute;n</th>
              <th class="col-sm-2">Fecha Actualizaci&oacute;n</th>
              <th class="col-sm-2" style="text-align:center;">Acci&oacute;n</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($arreglo_accesos) {
              foreach ($arreglo_accesos as $objeto_accesos) {
                $id_accesos = $objeto_accesos->id_usuario;
                $usuario = $objeto_accesos->usuario;
                $clave = $objeto_accesos->clave;
                $activo = $objeto_accesos->activo;
                $fecha_creacion = $objeto_accesos->fecha_creacion;
                $fecha_actualizacion = $objeto_accesos->fecha_actualizacion;

                if ($activo == "SI") {
                  $icono = "fas fa-thumbs-up";
                  $titulo = "Desactivar";
                  $activo = "NO";
                  $color = "color:green;";
                } else if ($activo == "NO") {
                  $icono = "fas fa-thumbs-down";
                  $titulo = "Activar";
                  $activo = "SI";
                  $color = "color:red;";
                }


            ?>
                <tr>
                  <td><?php echo $usuario; ?></td>
                  <td class="col-sm-2"><?php echo $fecha_creacion; ?></td>
                  <td class="col-sm-2"><?php echo $fecha_actualizacion; ?></td>
                  <td class="col-sm-2" style="text-align:center;">
                    <i class="fas fa-edit" style="cursor:pointer; margin-right: 10px;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaActualizarAccesos('<?php echo       $id_accesos; ?>')" title="Actualizar"></i>


                    <i class="<?php echo $icono; ?>" style="cursor:pointer; <?php echo  $color; ?>" title="<?php echo   $titulo; ?>" onclick="activoAccesos('<?php echo $id_accesos; ?>','<?php echo $activo; ?>')"></i>

                  </td>
                </tr>
            <?php
              }
            }
            ?>

          </tbody>
          <tfoot>
            <tr>
              <th>Usuario</th>
              <th>Fecha Creaci&oacute;n</th>
              <th>Fecha Actualizaci&oacute;n</th>
              <th style="text-align:center;">Acci&oacute;n</th>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <script>
      function vistaAgregarAccesos() {
        var parametros = {
          "ruta": "accesos_VI/agregar"
        };
        $.post('index.php', parametros, function(respuesta) {
          $('#titulo_modal').html('Agregar Accesos a Personas');
          $('#contenido_modal').html(respuesta);
        });
      }

      function vistaActualizarAccesos(id_accesos) {
        var parametros = {
          "ruta": "accesos_VI/actualizar",
          "id_accesos": id_accesos
        };
        $.post('index.php', parametros, function(respuesta) {
          $('#titulo_modal').html('Actualizar Accesos a Personas');
          $('#contenido_modal').html(respuesta);
        });
      }



      function activoAccesos(id_accesos, activo) {
        // var cadena='id_accesos='+id_accesos+'&activo='+activo+'ruta='+ruta;
        var cadena = {
          "id_accesos": id_accesos,
          "activo": activo,
          "ruta": "accesos_CO/activo"
        };
        $.post('index.php', cadena, function(respuesta) {
          var objeto_respuesta = JSON.parse(respuesta);

          if (objeto_respuesta.estado == "EXITO") {
            var parametro = {
              "ruta": "accesos_VI/listar"
            };
            $.post('index.php', parametro, function(res) {
              $('#contenido').html(res);
            });
          } else if (objeto_respuesta.estado == "ADVERTENCIA") {
            advertencia(objeto_respuesta.mensaje);
          } else if (objeto_respuesta.estado == "ERROR") {
            error(objeto_respuesta.mensaje);
          }
        });
      }


      data_table_accesos = organizarTabla({
        id: "listar_accesos"
      });
    </script>
  <?php
  }




  //FUNCION VISTA DE INGRESAR ESTUDIANTE

  function agregar()
  {

  ?>

    <!-- Form Element sizes -->
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Datos Estudiantes</h3>
      </div>
      <div class="card-body">
        <form id="formulario_agregar_accesos">
          <input class="form-control form-control-lg" type="text" id="usuario" name="usuario" placeholder="Usuario" autocomplete="on">
          <br>
          <input class="form-control form-control-lg" type="password" id="clave" name="clave" placeholder="Clave" autocomplete="on">

          <br><br>
          <label style="font-size: 25px">Foto o avatar</label><br>
          <input type="file" id="filechooser">
          <br>

          <button type="button" class="btn btn-primary float-right" onclick="controladorAgregarAccesos()"><i class="fas fa-save"></i> Guardar</button>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- Form Element sizes -->


    <script>
      function controladorAgregarAccesos() {
        var cadena = $('#formulario_agregar_accesos').serialize();

        var parametros = {
          "ruta": "accesos_CO/agregar",
          "usuario": $('#formulario_agregar_accesos')[0].elements.usuario.value,
          "clave": $('#formulario_agregar_accesos')[0].elements.clave.value
        };

        $.post('index.php', parametros, function(respuesta) {
          var objeto_respuesta = JSON.parse(respuesta);

          if (objeto_respuesta.estado == "EXITO") {
            exito(objeto_respuesta.mensaje);
            $('#formulario_agregar_accesos')[0].reset();

            let boton = '<div style="text-align:center;"><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ventana_modal" onclick=vistaActualizarAccesos("' + objeto_respuesta.id_accesos + '") title="Actualizar"><i class="far fa-edit"></i></button></div>';

            data_table_accesos.row.add([objeto_respuesta.usuario, objeto_respuesta.fecha_creacion, objeto_respuesta.fecha_actualizacion, boton]).draw();
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
  }


  function actualizar()
  {
    require_once "modelos/accesos_MO.php";

    $conexion = new servidor('A');
    $accesos_MO = new accesos_MO($conexion);

    $id_accesos = $_POST["id_accesos"];
    $arreglo_accesos = $accesos_MO->seleccionar("id_usuario", $id_accesos);
    $id_accesos = $arreglo_accesos[0]->id_usuario;
    $usuario = $arreglo_accesos[0]->usuario;
    $clave = $arreglo_accesos[0]->clave;

  ?>
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Datos Usuarios con Acceso</h3>
      </div>
      <div class="card-body">
        <form id="formulario_actualizar_accesos" method="post">

          <input type="hidden" id="id_accesos" name="id_accesos" value="<?php echo htmlspecialchars($id_accesos, ENT_QUOTES); ?>">
          <input class="form-control form-control-lg" type="text" id="usuario" name="usuario" placeholder="Usuario" value="<?php echo htmlspecialchars($usuario, ENT_QUOTES); ?>" autocomplete="on">
          <br>
          <input class="form-control form-control-lg" type="password" id="clave" name="clave" placeholder="Clave" autocomplete="new-password">

          <br><br>


          <button type="button" class="btn btn-primary float-right" onclick="controladorActualizarAccesos()"><i class="fas fa-save"></i> Guardar</button>
        </form>
      </div>
      <!-- /.card-body -->
    </div>

    <script>
      function controladorActualizarAccesos() {
        var cadena = $('#formulario_actualizar_accesos').serialize();
        var parametro = {
          "id_accesos": $('#formulario_actualizar_accesos')[0].elements.id_accesos.value,
          "usuario": $('#formulario_actualizar_accesos')[0].elements.usuario.value,
          "clave": $('#formulario_actualizar_accesos')[0].elements.clave.value,
          "ruta": "accesos_CO/actualizar"
        }

        $.post('index.php', parametro, function(respuesta) {
          var objeto_respuesta = JSON.parse(respuesta);

          if (objeto_respuesta.estado == "EXITO") {
            exito(objeto_respuesta.mensaje);

            //Pendiente en actualizacion de usuario
            $('#formulario_actualizar_accesos')[0].reset();

            $.post('index.php', {
              "ruta": "accesos_VI/listar"
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
  }
}
?>