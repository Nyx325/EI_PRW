# EI

## Prerequisitos

1. Instalar [composer](https://getcomposer.org/download/)
2. Configurar su `php.ini`
    - Si se encuentran en windows y usando `xampp` dirijanse a
    su carpeta de instalación, consideraremos que está en
    `C:\xampp`
    - Ahora deberán buscar su carpeta de php ubicada en
    `C:\xampp\php`

    - Deberán modificar el archivo `php.ini`. Deberán encontrar una linea la cual contiene:
```ini
;extension=zip
```
Deberán cambiarlo a 
```ini
extension=zip
```

## Preparar el proyecto
Una vez ya instalado composer abriremos el
en vscode o en una terminal y ejecutaremos
```bash
composer install
```

Tambien se debe configurar las opciones para conectarse
a la BD en un archivo `config.php` que se debe colocar
dentro de la carpeta raiz del proyecto

```php
<?php

return [
  'host'     => 'localhost',  // Dirección del servidor MySQL
  'dbname'   => 'barbershop',  // Nombre de la base de datos
  'user'     => 'rubenor',  // Usuario con permisos para acceder a la base de datos
  'password' => '',  // Contraseña del usuario
  'charset'  => 'utf8mb4',  // Conjunto de caracteres recomendado (UTF-8 completo)
];
```

## Desplegar el proyecto sin Apache
Si no desean hacer uso de apache de xampp para
ver el proyecto pueden ejecutar

```bash
php -S localhost:8000
```

Si su terminal no encuentra php como comando
coloquen

```bash
C:\xampp\php\php.exe -S localhost:8000
```

Y en su navegador colocar
```arduino
http://localhost:8000
```

Si tienen algun error o excepcion que se lance
puede que les muestre por consola el error
