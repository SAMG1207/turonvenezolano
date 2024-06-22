<?php
declare(strict_types=1);
Class Preventa extends User{

  
    private $user;
    private $idUser;



    public function __construct(string $email) {
        parent::__construct($email); // Llama al constructor de la clase User
        $this->idUser = $this->selectUserId()["id_usuario"];
        if (!$this->idUser) {
            throw new Exception("No se pudo obtener el ID del usuario para el email: $email");
        }
    }
    
    private function selectBotellaAlmacen(int $id): int {
        try {
            $selectSql = "SELECT id_entrada FROM almacen WHERE id_botella = ? AND estado = 'almacen'";
            $stmtSelect = $this->pdo->connect()->prepare($selectSql);
            $stmtSelect->bindParam(1, $id);
            $stmtSelect->execute();
            $idSeleccionado = $stmtSelect->fetch(PDO::FETCH_ASSOC);
            
            if ($idSeleccionado === false) {
                throw new Exception("No entry found in 'almacen' with id_botella = $id and estado = 'almacen'");
            }
            return $idSeleccionado["id_entrada"];
        } catch (Exception $e) {
            $e->getMessage();
            return 0;
        }
    }

    private function updateAlmacenPreventa(int $id): bool {
        $fecha = date('Y-m-d');

        try {
            $sqlUpdate = "UPDATE almacen SET estado = 'preventa', fechaCambio = ? WHERE id_entrada = ?";
            $stmtUpdate = $this->pdo->connect()->prepare($sqlUpdate);
            $stmtUpdate->bindParam(1, $fecha);
            $stmtUpdate->bindParam(2, $id);
            $stmtUpdate->execute();
            return true;
        } catch (Exception $e) {
            $e->getMessage();
            return false;
        }
    }

    private function insertPreventa(int $idSeleccionado): bool {
        try {
            $insertSql = "INSERT INTO preventa (id_entrada, id_usuario, fechaHora) VALUES (?, ?, ?)";
            $fechaH = new DateTime();
            $fechaString = $fechaH->format('Y-m-d H:i:s');
            $stmtInsert = $this->pdo->connect()->prepare($insertSql);
            $stmtInsert->bindParam(1, $idSeleccionado);
            $stmtInsert->bindParam(2, $this->idUser);
            $stmtInsert->bindParam(3, $fechaString);
            $stmtInsert->execute();
            return true;
        } catch (PDOException $e) {
            $e->getMessage();
            return false;
        }
    }

    public function alterTable(int $idBotella): bool {
        try {
            $this->pdo->connect()->beginTransaction();

            $idSeleccionado = $this->selectBotellaAlmacen($idBotella); //SELECCIONAMOS LAS BOTELLAS A CAMBIAR.
            if ($idSeleccionado === 0) {
                throw new Exception("No entry found in 'almacen' with id_botella = $idBotella and estado = 'almacen'");
            }

            if (!$this->updateAlmacenPreventa($idSeleccionado)) {
                throw new Exception("Failed to update almacen");
            }

            if (!$this->insertPreventa($idSeleccionado)) {
                throw new Exception("Failed to insert into preventa");
            }

            $this->pdo->connect()->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->connect()->rollBack();
            $e->getMessage();
            return false;
        }
    }

    // public function alterTable($idBotella, string $email, $limit = 1) {
    //     try {$fecha = date('Y-m-d');
    //         // Retrieve user data based on the email
    //         // $userData = $this->user->userData($email);
    //         // $idUsuario = $userData["id_usuario"];
    
            
    
    //         // // Prepare the SQL statement to select id_entrada from almacen table
    //          $selectSql = "SELECT id_entrada FROM almacen WHERE id_botella = ? AND estado = 'almacen'";
    //         $stmtSelect = $this->pdo->connect()->prepare($selectSql);
    //          $stmtSelect->bindParam(1, $idBotella);
    //          $stmtSelect->execute();
    
    //          $idSeleccionado = $stmtSelect->fetch(PDO::FETCH_ASSOC);
    
    //          if ($idSeleccionado === false) {
    //             throw new Exception("No entry found in 'almacen' with id_botella = $idBotella and estado = 'almacen'");
    //          }
    
    //          $idSeleccionado = $idSeleccionado['id_entrada'];
    //         // var_dump($idSeleccionado);
    
    //         // Update the almacen table
    //         $sqlUpdate = "UPDATE almacen SET estado = 'preventa', fechaCambio = ? WHERE id_entrada = ?";
    //         $stmtUpdate = $this->pdo->connect()->prepare($sqlUpdate);
    //         $stmtUpdate->bindParam(1, $fecha);
    //         $stmtUpdate->bindParam(2, $idSeleccionado);
    //         $stmtUpdate->execute();
    
    //         // Insert into preventa table
    //         $insertSql = "INSERT INTO preventa (id_entrada, id_usuario, fechaHora) VALUES (?, ?, ?)";
    //         $fechaH = new DateTime();
    //         $fechaString = $fechaH->format('Y-m-d H:i:s');
    //         $stmtInsert = $this->pdo->connect()->prepare($insertSql);
    //         $this->pdo->connect()->beginTransaction();
    //         $stmtInsert->bindParam(1, $idSeleccionado); 
    //         $stmtInsert->bindParam(2, $this->idUser);
    //         $stmtInsert->bindParam(3, $fechaString);
    //         $stmtInsert->execute();
    //         $this->pdo->connect()->commit();
    //         $this->pdo->close();
    //         return true;
    //     } catch (PDOException $e) {
    //         // Log or handle the PDO exception
    //         echo "PDOException: " . $e->getMessage();
    //         $this->pdo->connect()->rollBack();
    //         $this->pdo->close();
    //         return false;
    //     } catch (Exception $e) {
    //         // Handle other exceptions
    //         echo "Exception: " . $e->getMessage();
    //         $this->pdo->connect()->rollBack();
    //         $this->pdo->close();
    //         return false;
    //     }
    // }
    
    public function cuentaCarrito():int{
        try{
            $sql="SELECT * FROM preventa WHERE id_usuario=?";
            $stmt = $this->pdo->connect()->prepare($sql);
            $stmt->bindParam(1, $this->idUser);
            $stmt->execute();
            $count= $stmt->rowCount();
            $this->pdo->close();
            return $count;
        }catch(Exception $e){
            echo $e->getMessage();
            $this->pdo->close();
            return 0;
        }     
    }

    public function listaProductos():array{
        try{
            $sql="SELECT a.id_botella, COUNT(*) as cantidad, pr.marca, pr.modelo, SUM(pr.precio) as total_precio
            FROM almacen a 
            INNER JOIN preventa p ON a.id_entrada = p.id_entrada 
            INNER JOIN productos pr ON pr.idBotella = a.id_botella
            WHERE p.id_usuario = ? AND a.estado = 'preventa'
            GROUP BY a.id_botella";
           
           $stmt=$this->pdo->connect()->prepare($sql);
           $stmt->bindParam(1, $this->idUser);
           $stmt->execute();
           $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
           $this->pdo->close();
           return $rows;
        }catch(Exception $e){
           echo $e->getMessage();
           $this->pdo->close();
           return [];
        }
      
    }

    private function selectBotellasaBorrar(int $id):array{
        try{
            $sql_select = "SELECT a.id_entrada
                       FROM almacen a
                       INNER JOIN productos p ON p.idBotella = a.id_botella
                       INNER JOIN preventa pr ON pr.id_entrada = a.id_entrada
                       WHERE pr.id_usuario = ? AND a.id_botella = ?
                       GROUP BY a.id_entrada";
        $stmt_select = $this->pdo->connect()->prepare($sql_select);
        $stmt_select->bindParam(1, $this->idUser, PDO::PARAM_INT);
        $stmt_select->bindParam(2, $id, PDO::PARAM_INT);
        $stmt_select->execute();
        $id_entradas = $stmt_select->fetchAll(PDO::FETCH_COLUMN);
        $this->pdo->close();
        return $id_entradas;
        }catch(Exception $e){
            echo $e->getMessage();
           $this->pdo->close();
           return [];
        }
    }


    public function borraProductosPreventa(int $id_botella):bool {
        try{
            $id_entradas = $this->selectBotellasaBorrar($id_botella);
            $this->pdo->connect()->beginTransaction();
            $sql_delete = "UPDATE almacen SET estado ='almacen', fechaCambio=NULL WHERE id_entrada IN (".implode(',', $id_entradas).")";
            $stmt_delete = $this->pdo->connect()->prepare($sql_delete);
            $stmt_delete->execute();
            $this->pdo->connect()->beginTransaction();
            $this->pdo->close();
            return true;
        }catch(Exception $e){
            echo $e->getMessage();
            $this->pdo->connect()->rollBack();
            $this->pdo->close();
            return false;
        }
        

    }

}
    
    
