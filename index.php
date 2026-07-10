<?php
require_once "librerias/configuraciones.php";
require_once "librerias/servidor.php";
require_once "librerias/controlador.php";
require_once "librerias/funciones.php";
require_once "librerias/Repositorio.php";
date_default_timezone_set('America/Bogota');

if (isset($_SESSION["autenticado"]) && $_SESSION["autenticado"] == "SI") {
    controlador::main();
} else if (isset($_POST["usuario"]) && isset($_POST["clave"])) {
    require_once "controladores/accesos_CO.php";
    $accesos_CO = new accesos_CO();
    $accesos_CO->verificarInicioSesion();
} else {
    require_once "vistas/accesos_VI.php";
    $accesos_VI = new accesos_VI();
    $accesos_VI->formularioInicioSesion();
}
