# Proyecto: VideoTrend

**Descripción:**

Aplicación web de consultas de películas y creación de biblioteca con sus películas de preferencia para usuarios registrados previamente. Permite informarse de las películas más recomendadas, como los comentarios con más puntaje (más concuerdan los usuarios), su género, clasificación, y rating/puntuación otorgado a la película.

![Alt text](image-2.png)

## Características 

- Registro y autenticación de usuarios.
- Validación y autenticación de email de registro.
- Actualización y control de datos de usuario.
- Alta y baja de películas en biblioteca personal.
- Validación y control de datos de entrada.
- Encriptación de activos (password).
- Conexión a servicios de terceros (API trakt - SMTP Miltrap)

## Tecnologías Utilizadas
- **PHP y CodeIgnater 4**
- **Composer**
- **Servidor Apache**
- **Base de datos: PostgreSQL** 
- **Boostrap 5**

## Arquitectura: 
- Utilización de arquitectura **MVC**
- Utilización de arquitectura **REST** para el manejo de las rutas
 
## Instalación y configuración
- Instalar XAMPP (Con php y apache) : https://www.apachefriends.org/es/index.html
- Instalar Composer : https://getcomposer.org/
- Instalar PostgreSQL: https://www.postgresql.org/download/
- En WAMPP en la fila del servicio de apache > botón config > PHP(php.init)
    - Quitar los `;` delante de las siguientes extensiones en el archivo para  habilitarlos: `extension=pgsql`, `extension=pdo_pgsql` y `extension=intl`.
- En la base de datos PostgreSQL crear las tablas e insertar los datos para poder probar según lo indica el archivo `Instrucciones_DB.sql` del proyecto.
- Posicionarse con git en la carpeta htdocs de XAMPP y luego clonar proyecto en esa carpeta.
- Clonar proyecto del repositiorio: `git clone https://github.com/AxelK1999/VideoTrends.git`
- Estando posicionado en la carpeta del proyecto previamente clonado, en la terminal:
    1. Instalación de dependencias: `composer install` de surgir algún problema `composer update`. 
- Crearse una cuenta y loguearse en https://mailtrap.io/signin y acceder a `Home` > `email testing` > `SMTP Settings` > `Show Credentials` > definir los datos SMTP que se especifican en la clase `app/Helpers/Email.php` > variable `$emailConfig`. Permitirá ver los emails de validación de cuenta, de lo contrario al registrar no tendrá accesos.

## Uso
- Correr el proyecto: abrir panel de control de XAMPP > iniciar(start) el servicio de apache y acceder a `http://localhost/VideoTrends/public/api/1.0/views/login`.


