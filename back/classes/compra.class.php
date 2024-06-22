<?php 
declare(strict_types=1);
class Compra extends User{
    private $idUser;

    public function __construct($email) {
        parent::__construct($email); // Llama al constructor de la clase User
        $this->idUser = $this->selectUserId()["id_usuario"];
        if (!$this->idUser) {
            throw new Exception("No se pudo obtener el ID del usuario para el email: $email");
        }
    }

    public function existeId(int $id):bool{
           if(is_int($id)){
            try{
                $sql = "SELECT * FROM compras WHERE idUser = ? AND idPedido = ?";
                $stmt = $this->pdo->connect()->prepare($sql);
                $stmt->bindParam(1, $this->idUser, PDO::PARAM_INT);
                $stmt->bindParam(2, $id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result !== false;
            }catch(Exception $e){
                echo "Error: " . $e->getMessage();
                return false;
              }   
            }return false;
           }
       
    

        public function selectCompras():array {
            try {
                $sql = "SELECT * FROM compras WHERE idUser = ?";
                $stmt = $this->pdo->connect()->prepare($sql);
                $stmt->bindParam(1, $this->idUser);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                return $rows; // Devuelve siempre el array, puede estar vacío si no hay resultados
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
                return []; // Devuelve un array vacío en caso de error
            }
        }
        
   
    private function selectIdDireccion($direccion):int{
        try{
            $sql = "SELECT idDireccion FROM direccionenvio WHERE nombre = ?";
            $stmt=$this->pdo->connect()->prepare($sql);
            $stmt->execute([$direccion]);
            $array= $stmt->fetch(PDO::FETCH_ASSOC);
            $direccion = $array["idDireccion"];
             return $direccion;
        }catch(Exception $e){
            echo $e->getMessage();
            return 0;
        }
       
    }
    public function insertVenta(float $total, string $direccion): bool {
        $fechaH = new DateTime();
        $fechaString = $fechaH->format('Y-m-d H:i:s');
        $idDireccion = $this->selectIdDireccion($direccion);
        
        if (!$idDireccion) {
            echo "Error: Dirección no encontrada.";
            return false;
        }
        
        $sql = "INSERT INTO compras (idUser, fechaCompra, direccion, totalCompra) VALUES(?,?,?,?)";
        try {
            $conn = $this->pdo->connect();
            $conn->beginTransaction();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $this->idUser, PDO::PARAM_INT);
            $stmt->bindParam(2, $fechaString);
            $stmt->bindParam(3, $idDireccion, PDO::PARAM_INT);
            $stmt->bindParam(4, $total);
            $stmt->execute();
            $conn->commit();
            return true;
        } catch (Exception $e) {
            if ($conn) {
                $conn->rollBack();
            }
            echo "Error en insertVenta: " . $e->getMessage();
            return false;
        }
    }

    public function getBotellasPreventa():array{
        try{
            $sql = "SELECT id_entrada FROM preventa WHERE id_usuario = ?";
            $stmt = $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1, $this->idUser);
            $stmt->execute();
            $botellasSeleccionadas = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $botellasSeleccionadas;
        }catch(Exception $e){
            echo "Error: ". $e->getMessage();
            return [];
        }
       
    }

    private function selectUltimaCompra():int {
        try {
            $sql = "SELECT idPedido FROM compras WHERE idUser = ? ORDER BY idPedido DESC LIMIT 1";
            $stmt = $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1, $this->idUser);
            $stmt->execute();

            $idCompra = $stmt->fetchColumn();
            if ($idCompra !== false) {
                return $idCompra;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            echo "Error: ". $e->getMessage();
            return 0;
        }
    }
    


   public  function insertBotellaCompradas():bool{
        $con = $this->pdo->connect();
        $con->beginTransaction();
        $ids = $this->getBotellasPreventa();
        $idCompra = $this->selectUltimaCompra();
        $sql = "INSERT INTO botellascompradas (id_entrada, id_compra) VALUES ";
        $values = array();
        foreach ($ids as $id) {
            $values[] = "($id, $idCompra)";
        }
        $sql .= implode(", ", $values);
        try {
            $this->pdo->connect()->beginTransaction();
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $con->commit();
            
            return true; 
        } catch (PDOException $e) {
            $con->rollBack();
            
            return false; 
        }
    }
    

    public function updateAlmacen(){
        $ids = $this->getBotellasPreventa();
        $fecha = date('Y-m-d');
        
        $sql = "UPDATE almacen SET estado ='vendida', fechaCambio = ? WHERE estado = 'preventa' AND id_entrada IN (" . implode(", ", $ids) . ");";
        try {
            $con=$this->pdo->connect();
            $con->beginTransaction();
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $fecha);
            $stmt->execute();
            $con->commit();
            return true; 
        } catch (PDOException $e) {
            echo $e->getMessage(); 
            $con->rollBack();
            return false; 
        }
    }

    
    public function selectBotellasCompradas(){
        $sql = "SELECT idPedido FROM compras WHERE idUser = ? ";
        $stmt =$this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1,$this->idUser);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if($stmt->rowCount()>0){
            return $rows;
        }else{
            return false;
        }
    }

    public function selectBotellasCompradasPorIdCompra($idCompra){
        $sql ="SELECT pr.idBotella, pr.marca, pr.modelo, pr.precio, COUNT(*) as cantidad, SUM(pr.precio) as total FROM almacen a
        INNER JOIN productos pr ON pr.idBotella = a.id_botella
        INNER JOiN botellascompradas b ON b.id_entrada = a.id_entrada
        WHERE b.id_compra = ?
        GROUP BY a.id_botella";
        $stmt =$this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1,$idCompra);
        $stmt->execute();
        if($stmt->rowCount()>0){
           $row=$stmt->fetchAll();
           return $row;
        }else{
            return false;
        }
    }

    

}