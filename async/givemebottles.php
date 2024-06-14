<?php
include '../src/includes/autoloader.inc.php';

if($_SERVER["REQUEST_METHOD"]==="GET" && isset($_GET["marca"])){
    try{
        $marca = $_GET["marca"];
        $stock = new Stock();
        $botellas = $stock->seleccionaModeloPorMarca($marca);
        echo json_encode($botellas);
    }catch(Exception $e){
        http_response_code(500);
        echo json_encode(['success'=>false, 'message' =>'Internal server error']);
    }
 
}else{
    http_response_code(405);
        echo json_encode(['success'=>false, 'message' =>'Internal server error']);
}



