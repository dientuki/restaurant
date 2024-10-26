<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Sistema de reservas para Restaurant

**Objetivo**: Desarrollar un minisistema de reservas para un restaurante que permita gestionar mesas, usuarios y reservas de manera eficiente.

**Requisitos del Sistema**:

1. ABM de Mesas (No es necesario, solo el modelo de datos y tabla en la base):
   * **Ubicación:** [A, B, C, D]
   * **Número de mesa**
   * **Cantidad de personas**
2. Login/Registro de Usuarios (No es necesario, solo el modelo de datos y tabla en la base):
   * Sistema de autenticación para usuarios registrados.
   * Registro de nuevos usuarios con validación de datos.
   * Asegurar la seguridad en el manejo de datos de usuarios y reservas.
3. Solicitud de Reserva (No es necesario, solamente el modelo de datos, tabla y lo necesario para la Gestión de Disponibilidad):
   * **Usuario**
   * **Fecha y Hora:**
     * Lunes a Viernes: 10:00 a 24:00
     * Sábado: 22:00 a 02:00 (del día siguiente)
     * Domingo: 12:00 a 16:00
   * **Cantidad de personas**
   * **Duración:** Por defecto de 1:45 horas, con 15 minutos adicionales para la preparación de las mesas (excepto al inicio del día). La duración se ingresa en fracciones de 30 minutos.
4. Gestión de Disponibilidad (Necesario):
   * Implementar validaciones para evitar conflictos de reservas.
   * Uso de caché en memoria para la disponibilidad por ubicación, optimizando su rendimiento.
   * La ubicación de la mesa la define el sistema por orden (cuando ya no puede gestionarse en A, pasa a B, etc.).
   * Posibilidad de unir mesas en la misma ubicación, con un máximo de 3 mesas por reserva.
   * Reservas permitidas hasta 15 minutos antes del horario deseado.
5. Listado de Reservas:
   * Generar un listado por fecha que muestre las reservas por ubicación y sección, incluyendo las mesas en una sola consulta SQL.

**Tecnologías a Utilizar**:

* **Base de Datos:** MySQL
* **Backend:** PHP
* **Framework Opcional:** Laravel

\-------------

Objetivo de la prueba, Gestión de Disponibilidad y Listado de Reservas