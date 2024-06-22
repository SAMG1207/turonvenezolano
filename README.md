# TuRon Venezolano

Tu Ron Venezolano es una plataforma web diseñada para la distribución de rones venezolanos en Madrid. Desde el año 2023, se especializa en ofrecer rones de alta calidad, ideales para cualquier tipo de evento, ya sea una boda, graduación, cumpleaños, o simplemente para disfrutar de un buen momento.

# Características Principales

- Autenticación de Usuarios: Permite el inicio de sesión a través de Google, facilitando el acceso seguro y personalizado a la plataforma.
- Catálogo de Productos: Exhibe un amplio catálogo de rones venezolanos, incluyendo detalles como marca, modelo, y precio.
- Gestión de Compras: Los usuarios pueden seleccionar productos, especificar la cantidad deseada y realizar compras.
- Registro de Direcciones: Los usuarios pueden registrar direcciones de envío dentro de España para facilitar el proceso de compra.
- Administración de Inventario: Una sección de administrador permite la gestión de productos, incluyendo la alta de nuevos productos, entrada de mercancías, cambio de precios, y registro de pérdidas.

# Estructura del Proyecto

El proyecto se organiza en varias carpetas y archivos principales:

- public/: Contiene los archivos accesibles al público, incluyendo las páginas PHP principales (index.php, administrador.php, detallado.php, regdirection.php, preventa.php), y los recursos estáticos (CSS, JS, imágenes).
- src/: Incluye los scripts PHP que manejan la lógica del negocio (index.script.php, administrador.script.php, detallado.script.php), y la configuración de la sesión.
- src/includes/: Contiene archivos de configuración y clases de utilidad, como el autoloader y la configuración de la sesión.
- src/scripts/: Scripts PHP específicos para cada página, encargados de procesar la lógica de negocio.
- vendor/: Librerías de terceros, incluyendo el SDK de Google para la autenticación OAuth.

# Instalación

Para instalar y ejecutar Tu Ron Venezolano en tu servidor local, sigue estos pasos:

Clona el repositorio en tu servidor local.
IMPORTANTE: este proyecto esta desarrollado en local, se ha usado XAMPP como servidor de aplicaciones web, sin embargo, puede usarse otro. El proyecto trabaja con un archivo mkcert.exe para simular un certificado SSL. Este certificado puede obtenerlo en https://github.com/FiloSottile/mkcert. Es necesario una vez este certificado haya sido descargado que se modifiquen las carpetas que gestionan estos certificados en su servidor de aplicaciones web. En el caso de XAMPP estos se encuentran en C:\xampp\apache\conf y ahi debe modificar en la carpeta correspondiente (ssl.crt y ssl.key).
Configura una base de datos MySQL e importa el esquema proporcionado.
Configura las credenciales de la base de datos y Google OAuth en los archivos de configuración correspondientes. Este proyecto obtiene las credenciales de otra base de datos. Se recomienda hacer lo mismo y bajo ningún concepto hacer "hardcode".
Accede a la carpeta public/ a través de tu navegador para iniciar la aplicación.
Contribuir
Si estás interesado en contribuir al proyecto Tu Ron Venezolano, puedes empezar por revisar los issues abiertos en el repositorio de GitHub, o bien, proponer nuevas mejoras o características a través de pull requests. Esto será de bastante agradecimiento.

# Licencia

Tu Ron Venezolano es un proyecto de código abierto bajo la licencia MIT. Esto significa que puedes utilizar, copiar, modificar, fusionar, publicar, distribuir, sublicenciar, y/o vender copias del software, siempre y cuando otorgues el crédito correspondiente y no infrinjas los términos de la licencia.

## Tecnologías

- PHP 8.2
- MySQL
- JavaScript 3
- Composer
- [Bootstrap](https://getbootstrap.com/)
- [Stripe](https://stripe.com/es)
- Google OAuth: Para la autenticación de usuarios a través de Google.
