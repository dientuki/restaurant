# Sistema de reservas para Restaurant

## Tabla de Contenidos

[Sistema de reservas para Restaurant](#sistema-de-reservas-para-restaurant)
   1. [Requisitos del Sistema](#requisitos-del-sistema)
      - [ABM de Mesas](#abm-de-mesas)
      - [Login/Registro de Usuarios](#loginregistro-de-usuarios)
      - [Solicitud de Reserva](#solicitud-de-reserva)
      - [Gestión de Disponibilidad](#gestión-de-disponibilidad)
      - [Listado de Reservas](#listado-de-reservas)
   2. [Instalación](#instalación)
      - [Sail](#sail)
      - [Migraciones](#migraciones)
        - [Mesas](#mesas)
        - [Usuarios](#usuarios)
      - [Sistema](#sistema)
   3. [Tooling](#tooling)
      - [PHP Linters](#php-linters)
      - [Testing](#testing)


**Objetivo**: Desarrollar un minisistema de reservas para un restaurante que permita gestionar mesas, usuarios y reservas de manera eficiente.

**Requisitos del Sistema**:

1. ABM de Mesas (No es necesario, solo el modelo de datos y tabla en la base):
    - **Ubicación:** [A, B, C, D]
    - **Número de mesa**
    - **Cantidad de personas**
2. Login/Registro de Usuarios (No es necesario, solo el modelo de datos y tabla en la base):
    - Sistema de autenticación para usuarios registrados.
    - Registro de nuevos usuarios con validación de datos.
    - Asegurar la seguridad en el manejo de datos de usuarios y reservas.
3. Solicitud de Reserva (No es necesario, solamente el modelo de datos, tabla y lo necesario para la Gestión de Disponibilidad):
    - **Usuario**
    - **Fecha y Hora:**
        - Lunes a Viernes: 10:00 a 24:00
        - Sábado: 22:00 a 02:00 (del día siguiente)
        - Domingo: 12:00 a 16:00
    - **Cantidad de personas**
    - **Duración:** Por defecto de 1:45 horas, con 15 minutos adicionales para la preparación de las mesas (excepto al inicio del día). La duración se ingresa en fracciones de 30 minutos.
4. Gestión de Disponibilidad (Necesario):
    - Implementar validaciones para evitar conflictos de reservas.
    - Uso de caché en memoria para la disponibilidad por ubicación, optimizando su rendimiento.
    - La ubicación de la mesa la define el sistema por orden (cuando ya no puede gestionarse en A, pasa a B, etc.).
    - Posibilidad de unir mesas en la misma ubicación, con un máximo de 3 mesas por reserva.
    - Reservas permitidas hasta 15 minutos antes del horario deseado.
5. Listado de Reservas:
    - Generar un listado por fecha que muestre las reservas por ubicación y sección, incluyendo las mesas en una sola consulta SQL.

**Tecnologías a Utilizar**:

-   **Base de Datos:** MySQL
-   **Backend:** PHP
-   **Framework Opcional:** Laravel

\-------------

Objetivo de la prueba, Gestión de Disponibilidad y Listado de Reservas

## Instalacion

Antes de instalar Sail conviene hacer un `cp .env.example .env`, el archivo de ejemplo tiene una configuracion de base de datos (al usar sail y docker, podemos usar la configuracion sin temor a sobreescribir alguna otra base de datos).

### Sail

Usando docker podemos hacer todo con sail:

`docker run --rm
    -u "$(id -u):$(id -g)"
    -v "$(pwd):/var/www/html"
    -w /var/www/html
    laravelsail/php83-composer:latest
    composer install --ignore-platform-reqs`

Lo mejor es un poner un alias en el sistema:

`alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'`

Y luego levantar todo con `sail up -d`

### Migraciones

Siguiendo la estructura de laravel, en `databases/migrations` estan las migraciones para crear la base de datos y en `databases/seeders` estan los seedes para cargar datos demo.

Usando sail podemos ejecutar `sail artisan migrate:fresh --seed` para ejecutar la migracion y los seeders correspondientes.

#### Mesas
El seed de las meses creara entre 3 y 9 mesas por ubicacion con una capacidad por mesa de 2, 4 o 6 comensales.

#### Usuarios
El sistema tiene 2 usuarios, admin1@gmail.com y admin2@gmail.com ambos con la clave 1234.

### Sistema

Para entrar al sistema debemos ir a http://localhost/dashboard

### Tooling

#### PHP Linters

Comando: `sail composer linter`

Hay instalados varios linters de PHP para revisar el codigo y encontrar bugs antes de que puedan suceder o ayudar a mejorar la legibilidad y mantencion del codigo.

- **[PHP Mess Detector](https://phpmd.org/):** Ayuda a encontrar posibles bugs (mas orientado a sintaxis), codigo a mejorar, expresiones complicadas y codigo/variables sin uso.
- **[PHP CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer):** Ayuda mejorar la legibilidad del codigo.
- **[PHP Stan](https://phpstan.org/):** Ayuda a encontrar posibles que PHP Mess Detector puede no encontrar (mas orientado a logica).
- **[PHP Magic Number Detector](https://github.com/povils/phpmnd):** Evita que se usen numeros (o string) "al azar" en el codigo forzando a que sean constantes o variables, por ejemplo, en lugar de hacer `$http_status === 400` deberiamos hacer `$http_status === CodeNotFound`. Ayuda a mejorar la legibilidad.

#### Testing

Comando: `sail composer test`

Corre los test en php que hay en el sistema.