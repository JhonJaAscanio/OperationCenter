# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

OperationCenter ("Sales Dashboard") is a PHP + MySQL sales/inventory management system (products, purchases, sales, clients, providers, categories, returns/devoluciones, expenses/egresos) for a store ("Moto Repuestos ELKIN"). No build tooling, package manager, or test suite — it's classic procedural-OOP PHP served directly by Apache/XAMPP. The UI is built on the AdminLTE template with vendored front-end libraries checked into `dist/` (Bootstrap, DataTables, SweetAlert2, jQuery, dompdf, etc.).

## Running the project

There is no build/lint/test command. This runs as a standard PHP app under XAMPP:

- Place/keep the repo at `C:\xampp\htdocs\OperationCenter` (already the case) and start Apache + MySQL via the XAMPP control panel.
- Visit `http://localhost/OperationCenter/` — `index.php` is the single entry point.
- The MySQL database name is `venta` (see `librerias/configuraciones.php`); there is no committed schema/migration file in the repo, so the database must already exist locally with the tables referenced by the models (e.g. `productos`, `usuarios`/accesos, `ventas`, `clientes`, `proveedores`, `categorias`, `compras`, `devoluciones`, `egresos`).
- Default admin credentials and DB connection settings live in `librerias/configuraciones.php` as PHP constants (`IP_MAQUINA`, `BASE_DE_DATOS`, `USUARIO_ADMINISTRADOR`, `CLAVE_ADMINISTRADOR`, etc.) — edit these directly to point at a different DB/host (there is a commented-out remote/production config block already in that file showing the alternate values).

## Architecture

This is a hand-rolled MVC framework (not a package like CodeIgniter, but structurally similar) with a single front controller and a naming-convention-based router.

**Request flow:**
1. `index.php` starts the session (via `librerias/configuraciones.php`), and either:
   - dispatches to `controlador::main()` if `$_SESSION["autenticado"] == "SI"`,
   - handles a login POST via `controladores/accesos_CO.php`,
   - or renders the login form via `vistas/accesos_VI.php`.
2. `librerias/controlador.php` (`controlador::main()`) is the router. It reads `$_REQUEST["ruta"]`, e.g. `"productos_CO/agregar"` or `"productos_VI/listar"`, splits on `/` into `[clase, metodo, ...args]`, and:
   - resolves which folder to `require_once` based on the **suffix** of the class name: classes ending in `_VI` live in `vistas/`, classes ending in `_CO` live in `controladores/`.
   - instantiates the class and calls the method, passing any remaining URL segments as an array if present.
   - If no `ruta` is given, it defaults to `menu_VI::verMenu()` (the main dashboard/menu shell).

**Naming convention — every module follows a strict 3-file pattern**, named `<modulo>_<SUFIJO>.php`:
- `vistas/<modulo>_VI.php` — View. Renders HTML (often full pages with embedded `<script>` for DataTables/AJAX) and also exposes read methods like `listar()`/`consultar()` that return JSON for AJAX calls from the front end.
- `controladores/<modulo>_CO.php` — Controller. Handles the mutating actions (`agregar`, `actualizar`, `eliminar`, `activo`, etc.), reads `$_POST`, talks to the model, and `echo json_encode(...)` a `{"estado": "EXITO"|"ADVERTENCIA"|"ERROR", "mensaje": "..."}` style response.
- `modelos/<modulo>_MO.php` — Model. Owns all SQL for that module via a `servidor` connection object passed into its constructor.

Front-end JS (inline in the `_VI.php` files, plus `dist/js/pages/`) calls back into `index.php?ruta=<Clase>/<metodo>` via AJAX (jQuery), matching this router convention — when adding a new AJAX action, add a method to the appropriate `_CO.php`/`_MO.php`/`_VI.php` and reference it from the front end using the same `ruta` string format.

**Data layer (`librerias/servidor.php`):** the `servidor` class wraps a single PDO MySQL connection.
- Constructed with a privilege flag: `new servidor('A')` (admin, uses `USUARIO_ADMINISTRADOR`/`CLAVE_ADMINISTRADOR`) or `'L'` (limited user, currently unset/empty constants) — anything else exits with a JSON error.
- `consulta($sql)` runs a raw SQL string and returns `rowCount()` (used for INSERT/UPDATE/DELETE).
- `extraerRegistro()` returns the last result set as an array of `PDO::FETCH_OBJ` stdClass rows (used after SELECTs).
- **Queries are built via raw string interpolation, not parameterized/prepared statements** — this is a pre-existing pattern throughout every `_MO.php` file. Be aware of this when touching model code; it's the established (if unsafe) convention in this codebase, not something introduced by a specific change.

**Input sanitization (`librerias/funciones.php`):** most `_CO.php` files instantiate `funciones` and call `$f->limpiarMatriz($_POST)` at file scope (before the class definition) to trim/strip tags/strip quotes from all POST fields recursively before use.

**Auth:** session-based. `$_SESSION["autenticado"] == "SI"` and `$_SESSION["id_usuario"]` are set on login in `accesos_CO::verificarInicioSesion()` (checks credentials via `modelos/accesos_MO.php`, plain-text password comparison, no hashing). Logout is `accesos_CO::cerrarSesion()`, which unsets/destroys the session. There is no per-route/per-role permission check beyond "is a session authenticated" — the menu (`vistas/menu_VI.php`) is the main authenticated shell that other views/controllers are loaded into via AJAX.

**PDF invoices:** `librerias/imprimir.php` is a standalone script (not routed through the front controller) that reads `?id_factura=`, pulls the sale + client from the models directly, builds an HTML invoice string, and renders it to PDF using the vendored dompdf library at `dist/dompdf/`.

**Constants/config:** company info, tax rate (`IVA`), starting invoice number, etc. are plain PHP constants in `librerias/configuraciones.php` — read from there when a change involves company branding, tax %, or DB target.
