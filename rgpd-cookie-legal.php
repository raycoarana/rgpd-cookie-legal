<?php
/*
Plugin Name: RGPD Cookies y Páginas Legales
Plugin URI: https://github.com/raycoarana/rgpd-cookie-legal
Description: Mantenimiento del plugin abandonado "Protección de Datos RGPD" (RGPD LOPD). Páginas legales, banner de cookies, cláusulas legales. Fork parcheado de seguridad.
Version: 1.0.1
Author: raycoarana
Author URI: https://github.com/raycoarana
Text Domain: click-datos-lopd
Domain Path: /languages
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/*
 * Este es un fork de "Protección de Datos RGPD" (click-datos-lopd) 3.1.0,
 * © ClickDatos / Atalantic Soluciones Digitales, publicado bajo GPLv2 o posterior.
 * Original: https://wordpress.org/plugins/click-datos-lopd/ (cerrado el 2024-03-07 por problema de seguridad)
 *
 * Cambios respecto al original (GPLv2 §2a — cambios señalados):
 *  - Corregido XSS reflejado (CVE-2023-46071) en admin/class-cdlopd-admin.php.
 *  - Corregida inyección SQL en la misma función.
 *  - Eliminado el formulario de soporte por correo.
 *  - Añadidas comprobaciones de capacidad y nonces (CSRF).
 *  - Saneado/escapado de salidas.
 *  - Eliminadas dependencias de servicios externos.
 */
// Añadimos seguridad y definimos constantes.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'CDLOPD_PLUGIN_URL' ) ) {
	define( 'CDLOPD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( is_admin() ) {
	require_once __DIR__ . '/admin/class-cdlopd-admin.php';
	$cdlopd_admin = new CDLOPD_Admin();
	$cdlopd_admin->init();



} else {
	require_once __DIR__ . '/public/class-cdlopd-public.php';
	$cdlopd_public = new CDLOPD_Public();
	$cdlopd_public->init();
}
require_once __DIR__ . '/public/customizer.php';


function cdlopd_load_plugin_textdomain() {
	load_plugin_textdomain( 'click-datos-lopd', false, basename( __DIR__ ) . '/languages/' );
}
add_action( 'plugins_loaded', 'cdlopd_load_plugin_textdomain' );
