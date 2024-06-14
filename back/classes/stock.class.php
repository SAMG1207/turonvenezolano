<?php
require_once "exceptionE.class.php";
class Stock{
    
    private $exception;

    private Connection $pdo;

    public function __construct(){
        $this->exception = new ExceptionE();
        $this->pdo = new Connection();
    }
    


    public function seleccionaMarcas(){
        $sql="SELECT DISTINCT marca FROM productos";
        $stmt = $this->pdo->connect()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public function testData($data){
    
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = strip_tags($data);
        $data = stripslashes($data);
        return $data;
    }
    public function insertarBBDD($marca, $modelo,$precio, $fotoURL){
        $sql = "INSERT INTO productos(marca, modelo, precio, fotoUrl) values (?, ?, ?, ?)";
        $stmt= $this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1,$marca);
        $stmt->bindParam(2,$modelo);
        $stmt ->bindParam(3, $precio);
        $stmt->bindParam(4, $fotoURL);
        $stmt ->execute();
        $this->pdo->close();
    }


    public function revisarBBDD($marca, $modelo){
        $sql = "SELECT * FROM productos WHERE marca = ? AND modelo = ?";
        $stmt= $this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1,$marca);
        $stmt->bindParam(2,$modelo);
        $stmt->execute();
        return $stmt->rowCount()>0;
    }

    public function seleccionaModeloPorMarca($marca){
        $sql = "SELECT * FROM productos WHERE marca = ?";
        $stmt= $this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1,$marca);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public function seleccionaIdPorModelo($marca, $modelo){
        $sql = "SELECT * FROM productos WHERE marca = ? AND modelo =?";
        $stmt= $this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1,$marca);
        $stmt->bindParam(2,$modelo);
        $stmt->execute();
        $rows = $stmt->fetch();
        // return $rows["precio"];
        return $rows["idBotella"];
    }

    public function seleccionaIdPrecioPorModelo($marca, $modelo){
        $sql = "SELECT * FROM productos WHERE marca = ? AND modelo =?";
        $stmt= $this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1,$marca);
        $stmt->bindParam(2,$modelo);
        $stmt->execute();
        $rows = $stmt->fetch();
        // return $rows["idBotella"];
        return $rows["precio"];
    }

    public function entradaNueva($marca, $modelo, $cantidad){
       
        $sql="INSERT INTO almacen(id_botella, fecha, estado) VALUES (?,?,?)";
        $fecha = date('Y-m-d');
        $id = $this->seleccionaIdPorModelo($marca, $modelo);
        $stmt= $this->pdo->connect()->prepare($sql);
        
        for($i = 0; $i<$cantidad; $i++){
            $estado ="almacen";
            $stmt->bindParam(1,$id);
            $stmt->bindParam(2,$fecha);
            $stmt->bindParam(3,$estado);
            $stmt->execute();
        }
        
    }

    public function countMarcaModelo($marca, $modelo){
        $sql ="SELECT COUNT(*) AS total FROM productos WHERE marca = ? AND modelo = ?";
        $stmt= $this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1,$marca);
        $stmt->bindParam(2,$modelo);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row["total"];
    }

    public function updatePrecio($precio, $marca, $modelo) {
        $sql = "UPDATE productos SET precio = ? WHERE marca = ? AND modelo = ?";
        $stmt = $this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1, $precio);
        $stmt->bindParam(2, $marca);
        $stmt->bindParam(3, $modelo);
        return $stmt->execute();
    }

    public function actualizaPerdidas($marca, $modelo, $cantidad) {
        try {
            $fecha = date('Y-m-d');
            $id = $this->seleccionaIdPorModelo($marca, $modelo);
            
            if ($id === null) {
                throw new Exception("ID no encontrado para la combinación de marca y modelo proporcionada.");
            }
            
            $sql = "UPDATE almacen 
                    SET estado = CASE 
                                    WHEN estado = 'almacen' THEN 'perdida' 
                                    ELSE estado 
                                 END, 
                        fechaCambio = ? 
                    WHERE id_botella = ? 
                    LIMIT ?";
            $stmt = $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1, $fecha);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->bindParam(3, $cantidad, PDO::PARAM_INT);
            
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                throw new Exception("No se actualizó ninguna fila. Verifique los parámetros proporcionados.");
            }
            
            return true;
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    
    public function cuentaAlmacen($id) {
        try {
            $sql = "SELECT id_botella FROM almacen WHERE id_botella = ? and estado = 'almacen'";
            $stmt = $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount(); // Devuelve el número de filas devueltas por la consulta
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return false;
        }
    }
}