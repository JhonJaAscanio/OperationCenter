function organizarTabla(arreglo_configuracion)
{
    var id=arreglo_configuracion['id'];
    var orden=arreglo_configuracion['orden'] || 'desc';
    var ordenar_columna=arreglo_configuracion['ordenar_columna'] || 0;

    var data_table_global=$('#'+id).DataTable({
    "pageLength": 10,
    "order": [[ ordenar_columna, orden ]],
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "language": 
    {
    "processing":     "Procesando...",
    "lengthMenu":     "Mostrar _MENU_ registros",
    "zeroRecords":    "No se encontraron resultados",
    "info":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "emptyTable":     "Ningún dato disponible en esta tabla",
    "infoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "infoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "search":         "Buscar:",
    "thousands":  ",",
    "searchPlaceholder": "¿Qué buscas?",
    "loadingRecords": "Cargando...",
    "paginate": {
    "first":      "Primero",
    "last":       "Último",
    "next":       "Siguiente",
    "previous":   "Anterior"
    },
    "oAria": {
        "sortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sortDescending": ": Activar para ordenar la columna de manera descendente"
    },
    "buttons": {
        "copy": "Copiar",
        "colvis": "Visibilidad"
    }
    }
    });

    return data_table_global;
}

function exito(mensaje)
{
  toastr.success(mensaje);
}
function error(mensaje)
{
  toastr.error(mensaje);
}
function advertencia(mensaje)
{
  toastr.warning(mensaje);
}