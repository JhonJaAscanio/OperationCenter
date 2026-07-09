<?php
class catalogos_VI
{
    function __construct()
    {
    }

    function  listar()
    {
        require_once "modelos/catalogos_MO.php";

        $conexion = new servidor('A');
        $catalogos_MO = new catalogos_MO($conexion);
        $arreglo_catalogos = $catalogos_MO->seleccionar();
?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Catalogos</h3>

            </div> <!-- /.card-header -->
            <div class="card-body">
                <table id="listar_catalogos" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-2">Marca</th>
                            <th class="col-md-4">Modelo</th>
                            <th class="col-md-1" style="text-align:center;">Consultar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($arreglo_catalogos) {
                            foreach ($arreglo_catalogos as $objeto_catalogos) {
                                $id_categoria = $objeto_catalogos->id_catalogo;
                                $marca = $objeto_catalogos->marca;
                                $modelo = $objeto_catalogos->modelo;


                        ?>
                                <tr>
                                    <td><?php echo $marca; ?></td>
                                    <td><?php echo $modelo; ?></td>
                                    <td style="text-align:center;">
                                        <i class="far fa-eye" style="cursor:pointer; margin-right: 10px;color: #697E0A;" data-toggle="modal" data-target="#ventana_modal" onclick="vistaConsultarCatalogo('<?php echo $id_categoria; ?>')" title="Consultar"></i>

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
            function vistaConsultarCatalogo(id) {
                var parametros = {
                    "ruta": "catalogos_VI/consultar",
                    "id_catalogo": id
                };
                $.post('index.php', parametros, function(respuesta) {
                    $('#titulo_modal').html('Consultar cliente');
                    $('#contenido_modal').html(respuesta);
                });
            }

            data_table_catalogos = organizarTabla({
                id: "listar_catalogos"
            });
        </script>
<?php
    } //Fin funcion listar


    function consultar()
    {
        require_once "modelos/catalogos_MO.php";
        $conexion = new servidor('A');
        $catalogos_MO = new catalogos_MO($conexion);
        $id_catalogo = $_POST["id_catalogo"];
        $arreglo_catalogos = $catalogos_MO->seleccionar("id_catalogo", $id_catalogo);
        $id_catalogo = $arreglo_catalogos[0]->id_catalogo;
        $marca = $arreglo_catalogos[0]->marca;
        $modelo = $arreglo_catalogos[0]->modelo;
    }
}
?>