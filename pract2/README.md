# Ejercicio 2 AW / SW

Repositorio para el ejercicio 2 de las asignaturas Aplicaciones Web y Sistemas Web de la Facultad de Informática de la UCM.

## Cambios

- Añadido el trait `MagicProperties` aplicado a las clases `Mensaje` y `Usuario` (tanto en su versión memoria y en su versión BD) para poder aplicar restricciones y control de acceso con métodos getter y setters en las entidades de la aplicación, pero facilitando su uso como si fueran propiedades públicas. Este trait proporciona una implementación genérica para los métodos mágicos `__set()` y `__get()`.

- Añadidas dos clases Usuario y Mensaje que utilizan una base de datos para el almacenamiento de la información. Antes de poder utilizar la aplicación, tienes:
    -  Cue crear una base de datos en XAMPP a través de [phpMyAdmin](http://localhost/phpmyadmin/)
    - Importar los ficheros `includes/mysql/estructura.sql` y `includes/mysql/datos.sql` (*desactivando la opción `Habilite la revisión de las claves foráneas` en este último).
    - Modificar `includes/config.php` para configurar los parámetros de conexión a la BD correspondientes a la BD que acabas de crear.

- Añadida una nueva funcionalidad a la solución implementando un tablón de anuncios simple.

- Reorganizando las carpetas del proyecto. El directorio `includes` contiene todos los archivos y otros directorios que sólo tiene sentido que se puedan utilizar desde PHP pero que no *deben de ser accesibles* por el usuario de la aplicación aunque descubra el nombre de los ficheros.
    - *vistas*. Este directorio contiene los ficheros de apoyo para las diferentes scripts de vista de la aplicación.
    - *src*. Este directorio contiene los scripts de PHP que o bien son clases o traits y que se utilizan en toda la aplicación.  
- Modificando vistas para utilizar plantillas básicas. Las vistas utilizan un sistema de plantilla básico para evitar que la estructura básica de las vistas de la aplicación tenga que copiarse y pegarse.
- La clase Usuario representa esa entidad en el ejemplo. Por ahora el único concepto que gestiona la aplicación es la lógica asociada a un Usuario para el login. La clase Usuario agrupa esta lógica y además simula una BD en memoria.
- Configuración centralizada de la aplicación en el fichero `includes/config.php`. Para facilitar la instalación, configuración y despliegue, este script define los parámetros que son configurables en la aplicación y además la inicializa para que funcione adecuadamente. Normalmente este es el script que **debe** incluirse en todas las vistas que reciben las peticiones del usuario. Para poder utilizar la solución debes al menos modificar dos parámetros:
    - `RUTA_APP`. Esta es la ruta base (prefijo de URL) necesario para poder utilizar la aplicación, por ejemplo, si dejas la carpeta *Ejercicio02* dentro del htdocs de Apache, este prefijo normalmente correspondería a `/Ejercicio02`. Esta ruta es utilizada para poder generar rutas absolutas para los recursos (CSS, JS, IMGs, etc.) que tienes en tu aplicación. Puedes ver como se utiliza en `includes/vistas/layout.php`.
    - `INSTALADA`. Booleano que controla si la aplicación puede recibir peticiones del usuario o muestra una página de error. Mientras estamos instalando la aplicación o configurándola normalmente no queremos recibir peticiones del usuario.