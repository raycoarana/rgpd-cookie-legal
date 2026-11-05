# RGPD Cookies y Páginas Legales (fork)

Mantenimiento del plugin abandonado **"Protección de Datos RGPD"**
(slug original `click-datos-lopd`), cerrado en WordPress.org en marzo de 2024
por un problema de seguridad y sin corrección oficial.

Este fork conserva la funcionalidad y aplica las correcciones de seguridad.

## Vulnerabilidad corregida

**CVE-2023-46071** — XSS reflejado (CVSS 7.1). El plugin no saneaba ni escapaba
el parámetro `busqueda` antes de mostrarlo, permitiendo XSS reflejado contra
usuarios con privilegios (admin).

Detalles: https://wpscan.com/vulnerability/9f03b04a-001d-4faf-a424-42f0123c2424/

## Cambios de seguridad

| # | Gravedad | Ubicación | Corrección |
|---|----------|-----------|------------|
| 1 | Alta (CVE) | `admin/class-cdlopd-admin.php` | Escape de salida de `busqueda` (`esc_attr`) |
| 2 | Alta | `admin/class-cdlopd-admin.php` | Inyección SQL corregida con `$wpdb->prepare()` + `esc_like()` |
| 3 | Crítica | `admin/mail-contacto.php` | Endpoint sin autenticación: añadido nonce + capacidad, validación de email, destino al admin del sitio |
| 4 | Alta | `admin/class-cdlopd-admin.php` | Validación de `tab` contra lista blanca; eliminado `reset` sin uso |
| 5 | Alta | `admin/class-cdlopd-admin.php` | Capacidad + nonce al guardar scripts de cabecera/pie |
| 6 | Media | `admin/class-cdlopd-admin.php` | Validación de email rota (`&&` → `||`) |
| 7-11 | Media/Baja | varios | Escapado de salidas (`esc_attr`, `esc_html`, `esc_textarea`) |
| 12-14 | Media | panel completo | Comprobaciones de capacidad y nonces (CSRF) |

## Instalación

1. Copia la carpeta a `/wp-content/plugins/rgpd-cookie-legal/`.
2. Actívalo desde *Plugins* en el panel de WordPress.
3. Si usas caché, límpiala tras activar.

## Licencia

GPLv2 o posterior. Ver `LICENSE` y `NOTICE.md`.

Es un fork de software libre: se conservan los avisos de copyright originales,
se señalan los cambios (GPLv2 §2a) y la obra derivada se distribuye bajo la
misma licencia.

## Marca

No se usa el nombre, marca ni slug originales. Ver `NOTICE.md`.