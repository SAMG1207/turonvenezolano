<?php 

class Compra extends User{
    private $idUser;

    public function __construct($email) {
        parent::__construct($email); // Llama al constructor de la clase User
        $this->idUser = $this->selectUserId()["id_usuario"];
        if (!$this->idUser) {
            throw new Exception("No se pudo obtener el ID del usuario para el email: $email");
        }
    }

    public function existeId($id){
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
        }
    

        public function selectCompras() {
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
        
   
    public function selectIdDireccion($direccion){
        $sql = "SELECT idDireccion FROM direccionenvio WHERE nombre = ?";
        $stmt=$this->pdo->connect()->prepare($sql);
        $stmt->execute([$direccion]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function insertVenta( $total, $direccion){
        $fechaH = new DateTime();
        $fechaString = $fechaH->format('Y-m-d H:i:s');
        $idDireccion = $this->selectIdDireccion($direccion)["idDireccion"];
        $sql = "INSERT INTO compras (idUser, fechaCompra, direccion, totalCompra) VALUES(?,?,?,?)";
        try{
            $stmt = $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1, $this->idUser);
            $stmt->bindParam(2, $fechaString);
            $stmt->bindParam(3, $idDireccion);
            $stmt->bindParam(4, $total);
            $stmt->execute();
            return true;
        }catch(Exception $e){
           echo "Error: ". $e->getMessage();
        }
     
    }

    public function getBotellasPreventa(){
        $sql = "SELECT id_entrada FROM preventa WHERE id_usuario = ?";
        $stmt = $this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1, $this->idUser);
        $stmt->execute();
        $botellasSeleccionadas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $botellasSeleccionadas;
    }

    private function selectUltimaCompra() {
        try {
            $sql = "SELECT idPedido FROM compras WHERE idUser = ? ORDER BY idPedido DESC LIMIT 1";
            $stmt = $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1, $this->idUser);
            $stmt->execute();

            $idCompra = $stmt->fetchColumn();
            if ($idCompra !== false) {
                return $idCompra;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: ". $e->getMessage();
            return false;
        }
    }
    


   public  function insertBotellaCompradas(){
        $ids = $this->getBotellasPreventa();
        $idCompra = $this->selectUltimaCompra();
        $sql = "INSERT INTO botellascompradas (id_entrada, id_compra) VALUES ";
        $values = array();
        foreach ($ids as $id) {
            $values[] = "($id, $idCompra)";
        }
        $sql .= implode(", ", $values);
        try {
            $stmt = $this->pdo->connect()->prepare($sql);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            return false; 
        }
    }
    

    public function updateAlmacen(){
        $ids = $this->getBotellasPreventa();
        $fecha = date('Y-m-d');
        
        $sql = "UPDATE almacen SET estado ='vendida', fechaCambio = ? WHERE estado = 'preventa' AND id_entrada IN (" . implode(", ", $ids) . ");";
        try {
            $stmt = $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1, $fecha);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            return $e->getMessage(); 
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