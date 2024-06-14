<?php 

include '../src/includes/autoloader.inc.php';


if($_SERVER["REQUEST_METHOD"]==="GET" &&
 isset($_GET["email"])){
    $email = filter_var($_GET["email"], FILTER_VALIDATE_EMAIL);
  
    if($email){
        try{
            $preventa = new Preventa($email);
            $count = $preventa->cuentaCarrito();
            echo json_encode(['success' => true, 'count' => $count]);
        }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['success'=>false, 'message' =>'Internal server error']);


        }
    }else{
        http_response_code(400);
        echo json_encode(['sucess'=>false, 'message'=>'Formato equivocado de email']);
    }
   
}else{
    http_response_code(405);
    echo json_encode(['sucess'=>false, 'message'=>'Método no permitido']);
}