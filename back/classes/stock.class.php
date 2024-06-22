<?php
declare(strict_types=1);
require_once "exceptionE.class.php";
class Stock{
    
    private $exception;

    private Connection $pdo;

    public function __construct(){
        $this->exception = new ExceptionE();
        $this->pdo = new Connection();
    }
    
    public function seleccionaMarcas():array{
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
    public function insertarBBDD(string $marca, string $modelo, string $precio, string $fotoURL):bool{
        try{
            $sql = "INSERT INTO productos(marca, modelo, precio, fotoUrl) values (?, ?, ?, ?)";
            $stmt= $this->pdo->connect()->prepare($sql);
            $this->pdo->connect()->beginTransaction();
            $stmt->bindParam(1,$marca);
            $stmt->bindParam(2,$modelo);
            $stmt ->bindParam(3, $precio);
            $stmt->bindParam(4, $fotoURL);
            $stmt ->execute();
            $this->pdo->connect()->commit();
            $this->pdo->close();
            return true;
        }catch(ExceptionE $e){
            $this->pdo->connect()->rollBack();
            echo $e->getMessage();
            return false;
        }
     
    }


    public function revisarBBDD(string $marca, string $modelo):bool{
        $sql = "SELECT * FROM productos WHERE marca = ? AND modelo = ?";
        $stmt= $this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1,$marca);
        $stmt->bindParam(2,$modelo);
        $stmt->execute();
        return $stmt->rowCount()>0;
    }

    public function seleccionaModeloPorMarca(string $marca):array{
        $sql = "SELECT * FROM productos WHERE marca = ?";
        $stmt= $this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1,$marca);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public function seleccionaIdPorModelo(string $marca, string $modelo):int{
        try{
            $sql = "SELECT * FROM productos WHERE marca = ? AND modelo =?";
            $stmt= $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1,$marca);
            $stmt->bindParam(2,$modelo);
            $stmt->execute();
            $rows = $stmt->fetch();
            // return $rows["precio"];
            $this->pdo->close();
            return $rows["idBotella"];
        }catch(ExceptionE $e){
            echo $e->getMessage();
            return 0;
        }
     
    }

    public function seleccionaIdPrecioPorModelo(string $marca, string $modelo):float{
        try{
            $sql = "SELECT * FROM productos WHERE marca = ? AND modelo =?";
            $stmt= $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1,$marca);
            $stmt->bindParam(2,$modelo);
            $stmt->execute();
            $rows = $stmt->fetch();
            $this->pdo->close();
            // return $rows["idBotella"];
            return $rows["precio"];
        }catch(Exception $e){
           echo $e->getMessage();
           return 0;
        }
       
    }

    public function entradaNueva(string $marca, string $modelo, int $cantidad):bool{
        try{
            $sql="INSERT INTO almacen(id_botella, fecha, estado) VALUES (?,?,?)";
            $fecha = date('Y-m-d');
            $id = $this->seleccionaIdPorModelo($marca, $modelo);
            $this->pdo->connect()->beginTransaction();
            $stmt= $this->pdo->connect()->prepare($sql);
            
            for($i = 0; $i<$cantidad; $i++){
                $estado ="almacen";
                $stmt->bindParam(1,$id);
                $stmt->bindParam(2,$fecha);
                $stmt->bindParam(3,$estado);
                $stmt->execute();
            }
            $this->pdo->connect()->commit();
            $this->pdo->close();
            return true;
        }catch(Exception $e){
            $this->pdo->connect()->rollBack();
            $this->pdo->close();
            return false;
        }
       
    }

    public function countMarcaModelo(string $marca, string $modelo):array{
        try{
            $sql ="SELECT COUNT(*) AS total FROM productos WHERE marca = ? AND modelo = ?";
            $stmt= $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1,$marca);
            $stmt->bindParam(2,$modelo);
            $stmt->execute();
            $row = $stmt->fetch();
            return $row["total"];
        }catch(ExceptionE $e){
            echo $e->getMessage();
            return [];
        }
       
    }

    public function updatePrecio(string $precio,string $marca, string $modelo):bool {
        try{
            $sql = "UPDATE productos SET precio = ? WHERE marca = ? AND modelo = ?";
            $stmt = $this->pdo->connect()->prepare($sql);
            $this->pdo->connect()->beginTransaction();
            $stmt->bindParam(1, $precio);
            $stmt->bindParam(2, $marca);
            $stmt->bindParam(3, $modelo);
            if($stmt->execute()){
                $this->pdo->connect()->commit();
                $this->pdo->close();
                return true;
            }
            $this->pdo->connect()->rollBack();
            $this->pdo->close();
            return false;
        }catch(ExceptionE $e){
            echo $e->getMessage();
            $this->pdo->connect()->rollBack();
            $this->pdo->close();
            return false;
        }
        
    }

    public function actualizaPerdidas(string $marca, string $modelo, int $cantidad):bool {
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
            $this->pdo->connect()->beginTransaction();
            $stmt->bindParam(1, $fecha);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
            $stmt->bindParam(3, $cantidad, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                throw new Exception("No se actualizó ninguna fila. Verifique los parámetros proporcionados.");
            }
            
            $this->pdo->connect()->commit();
            $this->pdo->close();
            return true;
        } catch (PDOException $e) {
            $this->pdo->connect()->rollBack();
            $this->pdo->close();
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            $this->pdo->connect()->rollBack();
            $this->pdo->close();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    
    public function cuentaAlmacen(int $id):int {
        try {
            $sql = "SELECT id_botella FROM almacen WHERE id_botella = ? and estado = 'almacen'";
            $stmt = $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount(); // Devuelve el número de filas devueltas por la consulta
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return 0;
        }
    }
}