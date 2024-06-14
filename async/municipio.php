<?php 

require '../src/includes/autoloader.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["idProvincia"]) && is_numeric($_GET["idProvincia"])) {
    $idPr = intval($_GET["idProvincia"]);

    if ($idPr > 0) {
        $pdo = new Connection();
        $sql = "SELECT * FROM municipios WHERE idProvincia = ?";
        $stmt = $pdo->connect()->prepare($sql);
        $stmt->bindParam(1, $idPr, PDO::PARAM_INT); 
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        echo json_encode($rows);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "El parámetro idCCAA debe ser un entero positivo"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método de solicitud incorrecto o falta el parámetro idCCAA"]);
}