<?php
require_once '../src/includes/sesion_config.php';
require_once '../src/includes/autoloader.inc.php';
require_once '../vendor/autoload.php';
$compra = new Compra("sergioalejandro1991@gmail.com");
var_dump ($compra->selectBotellasCompradasPorIdCompra(8));

