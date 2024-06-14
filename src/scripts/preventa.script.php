<?php
require_once __DIR__ . '/../includes/sesion_config.php';
require_once __DIR__ . '/../includes/autoloader.inc.php';
// require_once __DIR__ . '/../../vendor/autoload.php';

if(isset($_SESSION["email"]) && isset($_SESSION["nombre"])){
    $name=$_SESSION["nombre"];
    $email=$_SESSION["email"];
    $preventa = new Preventa($email);
    $productos = $preventa->listaProductos();
    $total=0;
    foreach($productos as $producto){
      $total+=$producto['total_precio'];
    }
    
    $user = new User($email);
    $datosEnvio = $user->dimeDireccion();
  
  if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["id_botella"])){
      $eliminado =$preventa->borraProductosPreventa($_POST["id_botella"]);
      if($eliminado){
          header("Location: ".$_SERVER['REQUEST_URI']);
          exit();
      }
   }
  }else{
      header("location:index.php");
      exit();
  }