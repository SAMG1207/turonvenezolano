<?php 
require_once __DIR__ . '/../includes/sesion_config.php';
require_once __DIR__ . '/../includes/autoloader.inc.php';
if(isset($_SESSION["email"]) && isset($_SESSION["nombre"])){
    $user = new User($_SESSION["email"]);
    $id = $user->selectUserId();
    $pdo = new Connection();
    $sql="SELECT * FROM ccaa";
    $stmt = $pdo->connect()->prepare($sql);
    $stmt->execute();
    $ccaa = $stmt->fetchAll();
   
    if($_SERVER["REQUEST_METHOD"]==="POST" 
    && isset($_POST["nombre"])
    && isset($_POST["ccaa"])
    && isset($_POST["provincia"])
    && isset($_POST["municipio"])
    && isset($_POST["nombreVia"])
    && isset($_POST["datosCompletos"])
    && isset($_POST["cp"])
    && isset($_POST["tlf"])){
        $nombre = Basic::isPotentiallyDangerous($_POST["nombre"])?$_POST["nombre"]:null;
        $ccaa = is_numeric($_POST["ccaa"])?intval($_POST["ccaa"]):"";
        $provincia = is_numeric($_POST["provincia"])?intval($_POST["provincia"]):"";
        $municipio = is_numeric($_POST["municipio"])?intval($_POST["municipio"]):"";
        $nombreVia = Basic::isPotentiallyDangerous($_POST["nombreVia"])?$_POST["nombreVia"]:null;
        $datosCompletos = Basic::isPotentiallyDangerous($_POST["datosCompletos"])?$_POST["datosCompletos"]:null;
        $cp = is_numeric($_POST["cp"])?intval($_POST["cp"]):"";
        $tlf = is_numeric($_POST["tlf"])?intval($_POST["tlf"]):"";

        $user->registroDireccionEntrega($nombre,$ccaa, $provincia, $municipio, $nombreVia, $datosCompletos, $cp, $tlf); 
    }

}else{
    header("location:https://localhost/turonvenezolano/public/index.php");
    exit();
}