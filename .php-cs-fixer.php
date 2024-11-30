<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Ergebnis\PhpCsFixer\Config;

$ruleSet = Config\RuleSet\Php82::create(); // Usar el conjunto de reglas para PHP 8.2

// Crear configuración de reglas
$config = Config\Factory::fromRuleSet($ruleSet);

// Deshabilitar la regla `phpdoc_to_property_type`
$config->getRules()['phpdoc_to_property_type'] = false;  // Desactiva esta regla

// Buscar todos los archivos PHP dentro del directorio actual
$config->getFinder()->in(__DIR__);

// Configuración del archivo de caché para mejorar el rendimiento
$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/.php-cs-fixer.cache');

return $config;
