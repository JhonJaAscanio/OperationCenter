<?php 
require_once "modelos/accesos_MO.php";

$f=new funciones();	
$f->limpiarMatriz($_POST);

class accesos_CO
{
    function __construct(){}

    function verificarInicioSesion()
    {
     $ip= new funciones();   //capturar IP
     $nip=$ip->obtenerIp();  //Capturar IP

        $usuario=$_POST["usuario"];
        $clave=$_POST["clave"];

        $conexion=new servidor('A');

        $accesos_MO=new accesos_MO($conexion);

        $arreglo_accesos=$accesos_MO->verificarInicioSesion($usuario,$clave);

        $arreglo_ip=$accesos_MO->capturarIP($nip,$usuario);   // Capturar IP

        if($arreglo_accesos)
        {
            $_SESSION["id_usuario"]=$arreglo_accesos[0]->id_usuario;
            $_SESSION["autenticado"]="SI";
            header("Location: index.php");
        }
        else
        {
            header("Location: index.php?error=ERROR: Usuario $usuario No Registrado&usuario=$usuario");
        }

    }


 



 
    function agregar()
    {
     //   $id_estudiantes=$_POST["id_usuario"];
        $usuario=$_POST["usuario"];
        $clave=$_POST["clave"];
        

        $conexion=new servidor('A');

        $accesos_MO=new accesos_MO($conexion);

        $arreglo_accesos=$accesos_MO->unico($usuario);

     if($arreglo_accesos)
        {
            $respuesta = [
               "estado" => "ADVERTENCIA",
                'mensaje' => "ADVERTENCIA: El Usuario <b>$usuario</b> ya existe"
            ];
        }
        else
        {
            $filas_afectadas=$accesos_MO->agregar( $usuario, $clave);

            if($filas_afectadas)
            {
                $arreglo_accesos=$accesos_MO->seleccionar("usuario",$usuario);
              
      
                $usuario = $arreglo_accesos[0]->usuario;
                $clave = $arreglo_accesos[0]->clave;
                $fecha_creacion=$arreglo_accesos[0]->fecha_creacion;
                $fecha_actualizacion=$arreglo_accesos[0]->fecha_actualizacion;
                
                $respuesta = [
                    "estado" => "EXITO",
                    'mensaje' => "EXITO: Registro Guardado",
                    'usuario' => $usuario,
                    'clave' => $clave,
                    'fecha_creacion' => $fecha_creacion,
                    'fecha_actualizacion' => $fecha_actualizacion
                ];
            }
            else
            {
                $respuesta = [
                  "estado" => "ADVERTENCIA",
                    'mensaje' => "ADVERTENCIA: No se guardo el registro"
                ];
            }
        }

        echo json_encode($respuesta);
    }

//-----------------------------------------------------------------------

    function actualizar()
    {

        $id_accesos = $_POST["id_accesos"];
        $usuario=$_POST["usuario"];
        $clave=$_POST["clave"];

        $conexion=new servidor('A');

        $accesos_MO=new accesos_MO($conexion);


       $arreglo_accesos=$accesos_MO->unico($usuario, $id_accesos);

         if($arreglo_accesos)
         {
             $respuesta = [
             "estado" => "ADVERTENCIA",
             'mensaje' => "ADVERTENCIA: El Usuario con usuario <b>$usuario</b> ya existe"
             ];
         }
        else
         {    

                $filas_afectadas=$accesos_MO->actualizar($id_accesos,$usuario, $clave);
                
                if($filas_afectadas)
                {
                    $arreglo_accesos=$accesos_MO->seleccionar("id_usuario",$id_accesos);
                    $fecha_actualizacion=$arreglo_accesos[0]->fecha_actualizacion;

                    $respuesta = [
                        "estado" => "EXITO",
                        'mensaje' => "EXITO: Registro Guardado",
                        'id_usuario' => $id_accesos,
                        'fecha_actualizacion' => $fecha_actualizacion
                    ];
                }
                else
                {
                    $respuesta = [
                        "estado" => "ADVERTENCIA",
                        'mensaje' => "ADVERTENCIA: No ocurrieron cambios"
                    ];
                }

        }  
         echo json_encode($respuesta);
    }



function cerrarSesion($arreglo_url)
{
    session_unset();  //Marcar variable de sessión
    //Destruir todas las variables de sesion.
    $_SESSION = array(); //SE LE ASIGNA ARREGLO VACION PARA DESTRUIRLA

    //Si se destruye la sesión completamente, borre también la cookie de sesión.
    //Nota: ¡Esto destruirá la sesión, y no la información de la sesión!
    if (ini_get("session.use_cookies"))
     {
       $params = session_get_cookie_params();
       setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    //Finalmente, destruir la sesión.
    session_destroy();

    $usuario="<b>".$arreglo_url[0]."</b> Que tenga un buen d&iacute;a";

    $respuesta = [
         "estado" => "EXITO",
         'mensaje' => $usuario
    ];
    echo json_encode($respuesta);
}



function activo()
{    

     $conexion=new servidor('A');
     $accesos_MO=new accesos_MO($conexion);

     $id_accesos=$_POST["id_accesos"];
     $activo=$_POST["activo"];

     $filas_afectadas=$accesos_MO->activo($id_accesos, $activo);

     if($filas_afectadas)
     {
        $arreglo_accesos=$accesos_MO->seleccionar("id_usuario", $id_accesos);
        $fecha_actualizacion=$arreglo_accesos[0]->fecha_actualizacion;
        $activo=$arreglo_accesos[0]->activo;

        $respuesta = [
            "estado" => "EXITO",
            'mensaje'=> "EXITO: Registro Guardado",
            'id_usuario'=>$id_accesos,
            'activo'=>$activo,
            'fecha_actualizacion'=>$fecha_actualizacion
        ];
     }
     else
     {
        $respuesta = [
            "estado"=> "ADVERTENCIA",
            'mensaje'=> "ADVERTENCIA: No ocurrieron cambios"
        ];
     }
     echo json_encode($respuesta);
}






}
