<?php
	session_name("NOMBRE_DE_LA_EMPRESA");

	session_start();

	const IP_MAQUINA="localhost";
	//const IP_MAQUINA="sql108.epizy.com";
	const BASE_DE_DATOS="venta";
	//const BASE_DE_DATOS="epiz_26969083_empresa";

	const USUARIO_ADMINISTRADOR="admin";
	//const USUARIO_ADMINISTRADOR="epiz_26969083";
	const CLAVE_ADMINISTRADOR="admin";
	//const CLAVE_ADMINISTRADOR="zyzR1xAXEEnx3iI";

	const NOMBRE_EMPRESA="Moto Repuestos ELKIN";
	const TELEFONO_EMPRESA="322-225-2727";
	const NIT_EMPRESA="1091 675 123-6";
	const DIRECCION_EMPRESA="Barrio las Llanadas";
	
	const IVA=0.19;
	const DESCUETNO=0;
	const RETEFUENTE=0;

	const NFACTURA_INICIAL=1;

	const USUARIO_LIMITADO="";
	const CLAVE_LIMITADO="";

	header('X-FRAME-OPTIONS: DENY');
?> 