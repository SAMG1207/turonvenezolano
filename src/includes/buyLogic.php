<?php
require_once __DIR__ . '/sesion_config.php';
require_once __DIR__ . '/autoloader.inc.php';
// require_once '../../back/classes/connection.class.php';
// require_once '../../back/classes/user.class.php';
// require_once '../../back/classes/compra.class.php';


if(isset($_SESSION["nombre"])
&& isset($_SESSION["email"])
&& isset($_SESSION["de"])
&& isset($_SESSION["totalAPagar"])){

    $total = $_SESSION["totalAPagar"];
    $de = $_SESSION["de"];
    try{
        $compra = new Compra($_SESSION["email"]);
        $nuevaCompra=$compra->insertVenta($total, $de);
        if($nuevaCompra){
            $insert =$compra->insertBotellaCompradas();
            if($insert){
                   $update = $compra->updateAlmacen();
                   if($update){
                    header("location:https://localhost/turonvenezolano/index");
                    exit();
                   }
            }
        }
        
       
        
       
    }catch(Exception $e){
         echo $e->getMessage();
         echo $e->getFile();
         echo $e->getLine();
    }
  

}