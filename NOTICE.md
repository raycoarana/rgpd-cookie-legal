# NOTICE — atribución y procedencia

Este repositorio es un **fork de mantenimiento** del plugin de WordPress
**"Protección de Datos RGPD"**, slug original `click-datos-lopd`, versión 3.1.0.

## Autoría original

- Autor: **ClickDatos** / **Atalantic Soluciones Digitales**
- Página original: https://wordpress.org/plugins/click-datos-lopd/
- Estado: cerrado en WordPress.org el **2024-03-07**, motivo *Security Issue*.
  Sin corrección oficial publicada.
- Licencia original: **GPLv2 o posterior** (declarada en `readme.txt` y en la
  cabecera de `click-datos-lopd.php`).

## Licencia de este fork

Distribuido bajo **GPLv2 o posterior**. Ver `LICENSE`.

La GPLv2 permite copiar, modificar y redistribuir el código, siempre que se
conserven los avisos de copyright, se señalen los cambios y la obra derivada
se distribuya bajo la misma licencia. Esto es lo que hace este repositorio.

## Cambios (GPLv2 §2a)

- Corregido **XSS reflejado** en `admin/class-cdlopd-admin.php`
  (CVE-2023-46071, CVSS 7.1).
- Corregida **inyección SQL** en la búsqueda de formularios
  (`$wpdb` sin `prepare()`).
- Endurecido `admin/mail-contacto.php`: antes era un endpoint sin
  autenticación que enviaba datos a un tercero (`plugin@clickdatos.es`).
  Ahora exige sesión + capacidad + nonce, valida el email y envía al
  administrador del sitio.
- Añadidas comprobaciones de capacidad (`current_user_can`) y **nonces**
  (protección CSRF) en el panel de administración.
- Saneado/escapado de salidas (`esc_attr`, `esc_html`, `esc_textarea`,
  `esc_url`, `sanitize_*`).
- Eliminadas las referencias y llamadas al servicio externo `clickdatos.es`.

## Marca

Este fork **no** usa la marca, el nombre ni el slug originales. El nombre
"ClickDatos" y "Protección de Datos RGPD" pertenecen a sus titulares.
El fork se distribuye bajo el nombre **RGPD Cookies y Páginas Legales (fork)**.