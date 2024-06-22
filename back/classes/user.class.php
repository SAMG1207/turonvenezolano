<?php


Class User{
    //Edicion hecha el 08-06
protected Connection $pdo;
protected string $email;
 
public function __construct(string $email){
  
    $this->email = $email;
    $this->pdo = new Connection();
   
}

public function getEmail(){
    return $this->email;
}

//


public function insertUser($nombre):bool{
    $sql ="INSERT INTO users(nombre, email) VALUES (?,?)";
    try{
        $stmt = $this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2, $this->email);
        $stmt->execute();
        $this->pdo->close();
        return true;
    }catch(PDOException $e){
        echo "Error al ejecutar la consulta: " . $e->getMessage();
        return false;
    }catch(exception $e){
        echo "Error: " . $e->getMessage();
        return false;
    } 
}

public function userExists():bool{
   $sql ="SELECT id_usuario FROM users WHERE email = ?";
   try{
    $stmt = $this->pdo->connect()->prepare($sql);
    $stmt->bindParam(1, $this->email);
    $stmt->execute();
    $row = $stmt->rowCount();
    return $row>0;
   }catch(Exception $e){
    echo "Error: " . $e->getMessage();
    return false;
   }
  
}

public function selectUserId(){
    $sql ="SELECT id_usuario FROM users WHERE email = ?";
    try{
     $stmt = $this->pdo->connect()->prepare($sql);
     $stmt->bindParam(1, $this->email);
     $stmt->execute();
     $row = $stmt->fetch();
     if($stmt->rowCount()>0){
        return $row;
     }else{
        return false;
     }
    }catch(Exception $e){
     echo "Error: " . $e->getMessage();
     return false;
    }
   
 }

public function insertSession($idUser){
$sql ="INSERT INTO session_log(idUser, fechaInicio) VALUES(?,?)";
try{
    $stmt=$this->pdo->connect()->prepare($sql);
    $fecha = new DateTime();
    $fechaString = $fecha->format('Y-m-d H:i:s');
    $stmt->bindParam(1,$idUser);
    $stmt->bindParam(2,$fechaString);
    $stmt->execute();
}catch(Exception $e){
    echo "Error: " . $e->getMessage();
    return false;
   }
}

public function userData(){
    $sql ="SELECT id_usuario FROM users WHERE email = ?";
    try{
     $stmt = $this->pdo->connect()->prepare($sql);
     $stmt->bindParam(1, $this->email);
     $stmt->execute();
     $row = $stmt->fetch();
     return $row;
    }catch(Exception $e){
     echo "Error: " . $e->getMessage();
     return false;
    }
   
 }

public function registroDireccionEntrega(string $nombre, int $ccaa, int $provincia, int $municipio,  string $nombreVia, string $datosCompletos, int $cp, int $tlf):bool{
    try{
        $user=$this->userData();
        $id=$user["id_usuario"];
        $sql = "INSERT INTO direccionenvio(nombre,idUser, idCCAA, idProvincia, idMunicipio, nombreVia, datosCompletos, cp, tlf) VALUES(?,?,?,?,?,?,?,?,?)";
        $stmt=$this->pdo->connect()->prepare($sql);
        $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);
        $stmt->bindParam(3,$ccaa, PDO::PARAM_INT);
        $stmt->bindParam(4, $provincia, PDO::PARAM_INT);
        $stmt->bindParam(5, $municipio, PDO::PARAM_INT);
        $stmt->bindParam(6, $nombreVia, PDO::PARAM_STR);
        $stmt->bindParam(7, $datosCompletos, PDO::PARAM_STR);
        $stmt->bindParam(8, $cp, PDO::PARAM_INT);
        $stmt->bindParam(9, $tlf, PDO::PARAM_INT);
        $insert =$stmt->execute();
        return $insert;

    }catch(Exception $e){
       error_log ($e->getMessage());
       return false;
    }
}

   public function dimeDireccion(){
    $user=$this->userData();
    $id=$user["id_usuario"];
    $sql = "SELECT d.nombre, c.Nombre, m.Municipio, p.Provincia, d.nombreVia, d.datosCompletos, d.cp, d.tlf
    FROM direccionenvio d
    INNER JOIN ccaa c ON c.idCCAA = d.idCCAA
    INNER JOIN provincias p ON p.idProvincia = d.idProvincia
    INNER JOIN municipios m ON m.idMunicipio = d.idMunicipio
    WHERE d.idUser = ?";
    $stmt=$this->pdo->connect()->prepare($sql);
    $stmt->bindParam(1,$id);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
   }
}