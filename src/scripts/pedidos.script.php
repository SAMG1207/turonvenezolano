<?php
require_once __DIR__ . '/../includes/sesion_config.php';
require_once __DIR__ . '/../includes/autoloader.inc.php';
require_once __DIR__ . '/../../vendor/autoload.php';


if(isset($_SESSION["email"]) && isset($_SESSION["nombre"])){
$compra = new Compra($_SESSION["email"]);
$pedidos = $compra->selectCompras();

}else{
    header("location:index.php");
    exit();
}