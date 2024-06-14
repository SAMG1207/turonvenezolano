<?php 
include '../src/includes/autoloader.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id_botella"]) && isset($_GET["email"])) {
    // Accede a los datos recibidos en la solicitud GET
    $id_botella = $_GET["id_botella"];
    $email = $_GET["email"];

    $preventa = new Preventa($email);
    $stock = new Stock();

    if($stock->cuentaAlmacen($id_botella)>0){
        if ($preventa->alterTable($id_botella, $email)) {
            echo json_encode(["success" => true, "message" => "La preventa se ha registrado correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Hubo un error al registrar la preventa en la base de datos"]);
        }
    }else{
        return;
    }
  
} else {
    echo json_encode(["success" => false, "message" => "No se recibieron todos los parámetros necesarios para procesar la solicitud"]);
}



