
<?php 
require '../src/includes/autoloader.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["idCCAA"]) && is_numeric($_GET["idCCAA"])) {
    $idCCAA = intval($_GET["idCCAA"]);

    if ($idCCAA > 0) {
        try{
            $pdo = new Connection();
            $sql = "SELECT * FROM provincias WHERE idCCAA = ?";
            $stmt = $pdo->connect()->prepare($sql);
            $stmt->bindParam(1, $idCCAA, PDO::PARAM_INT); 
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            echo json_encode($rows);
        }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['success'=>false, 'message' =>'Internal server error'.$e]);
        }
        
    } else {
        http_response_code(400);
        echo json_encode(["error" => "El parámetro idCCAA debe ser un entero positivo"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método de solicitud incorrecto o falta el parámetro idCCAA"]);
}

