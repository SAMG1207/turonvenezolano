<?php
require_once __DIR__ . '/../includes/sesion_config.php';
require_once __DIR__ . '/../includes/autoloader.inc.php';
require_once __DIR__ . '/../../vendor/autoload.php';

if( isset($_SESSION["email"])&&
    isset($_SESSION["nombre"]) &&
    isset($_GET["idCompra"])){
    $compra = new Compra($_SESSION["email"]);
    
    if($compra->existeId($_GET["idCompra"])){
      $compras = $compra->selectBotellasCompradasPorIdCompra($_GET["idCompra"]);
      if($compras && count($compras)>0){
        $lista=" <table class='overflow-hidden rounded table table-striped table-bordered table-hover shadow-lg'>";
        $lista.="<thead>";
        $lista.="<tr><th scope ='col'>#</th><th scope='col'>Marca</th><th scope ='col'>Modelo</th> <th scope ='col'>Precio U</th><th scope ='col'>Cantidad</th> <th scope ='col'>Total</th> </tr>";
        $lista.="</thead>";
        $lista.="<tbody>";
        $cantidadTotal = 0;
        foreach($compras as $compraU){
          $lista .="<tr>";
          $lista.="<th scope='row'>".htmlspecialchars($compraU['idBotella'])."</th>";
          $lista.="<th scope='row'>".htmlspecialchars($compraU['marca'])."</th>";
          $lista.="<th scope='row'>".htmlspecialchars($compraU['modelo'])."</th>";
          $lista.="<th scope='row'>".htmlspecialchars($compraU['precio'])."</th>";
          $lista.="<th scope='row'>".htmlspecialchars($compraU['cantidad'])."</th>";
          $lista.="<th scope='row'>".htmlspecialchars($compraU['total'])."</th>";
          $lista .="</tr>";
          $cantidadTotal+=$compraU['total'];
        }
        $lista.="<tr>";
          $lista.="<td colspan='4'><h4>Total Compra</h4></td>";
          $lista.="<td colspan='2'><h4>€".$cantidadTotal."</h4></td>";
          $lista.="</tr>";
        $lista.=" </tbody>";
        $lista.="</table>";
      }else{
        $lista ="<p>No hay compras con este número de pedido </p>";
      }
    }else{
      header("location:index.php");
      exit();
    }

    }else{
      header("location:index.php");
      exit();
    }