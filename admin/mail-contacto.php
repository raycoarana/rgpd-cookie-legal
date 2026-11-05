<?php

require_once("../../../../wp-load.php");

// Endpoint de contacto del plugin (soporte).
// Original: enviaba los datos a plugin@clickdatos.es, un tercero.
// Fork: no se envían datos a terceros; el correo va al administrador del sitio.
// Endurecido: sesión + capacidad + nonce + saneado. No es un relay abierto.

if (!current_user_can('manage_options')) {
    status_header(403);
    echo "No autorizado.";
    exit;
}

check_ajax_referer('cdlopd_mail_contacto', 'nonce');

$nombreu = isset($_POST['nombreu']) ? sanitize_text_field(wp_unslash($_POST['nombreu'])) : '';
$nombrec = isset($_POST['nombrec']) ? sanitize_text_field(wp_unslash($_POST['nombrec'])) : '';
$email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
$url = isset($_POST['url']) ? esc_url_raw(wp_unslash($_POST['url'])) : '';
$consulta = isset($_POST['mensaje']) ? sanitize_textarea_field(wp_unslash($_POST['mensaje'])) : '';
$fecha = gmdate("d-m-Y H:i:s");

$ipaddress = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'UNKNOWN';

if ($email === '' || !is_email($email)) {
    status_header(400);
    echo "El email no tiene un formato válido.";
    exit;
}

// El título no lleva datos del usuario: evita inyección de cabeceras.
$titulo = '[Contacto plugin RGPD] Nueva solicitud';

$mensaje = '<p>El usuario ' . esc_html($nombreu) . ', con nombre completo ' . esc_html($nombrec) . ', ha solicitado una evaluación de su web.</p>
<p>La dirección web de su portal es ' . esc_html($url) . ' </p>
<p>Su email es ' . esc_html($email) . '</p>';
if ($consulta !== "") {
    $mensaje .= '<p>Mensaje adjunto: ' . esc_html($consulta) . '</p>';
}
$mensaje .= '<p>La dirección IP del cliente es: ' . esc_html($ipaddress) . '</p>';
$mensaje .= '<p>Enviado el ' . esc_html($fecha) . '</p>';
$mensaje .= '<br><br><p>Acepta Política de Privacidad: SI</p>';

$header = array('Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $email);

// Destino: el administrador del sitio, nunca un tercero.
$resultado = wp_mail(get_option('admin_email'), $titulo, $mensaje, $header);

if ($resultado) {
    echo "Tu correo ha sido enviado correctamente.";
} else {
    echo "Hubo un problema al enviar el email, inténtalo de nuevo.";
}