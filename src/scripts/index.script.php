<?php 

require_once __DIR__ . '/../includes/sesion_config.php';
require_once __DIR__ . '/../includes/autoloader.inc.php'; 
require_once __DIR__ . '/../../vendor/autoload.php';



$stock = new Stock();
$marcas = $stock->seleccionaMarcas();
$passConnect = new Pass();
if (!isset($_SESSION["email"])) {
  $clientID = $passConnect->giveMeG("googleClientIDAuth");
  $clientSecret =$passConnect->giveMeG("GoogleClientAuthSecret");
  $redirectUri = 'https://localhost/turonvenezolano/index';
  $logedIn = false;

    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");

   // Verifica si hay un código de autorización en la URL
    if (isset($_GET['code'])) {
        // Obtiene el token de acceso
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token['access_token']);

        // Obtiene la información del perfil
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $email = filter_var($google_account_info->email, FILTER_SANITIZE_EMAIL);
        $name = $google_account_info->name;

        // Guarda el correo electrónico en la sesión
        $_SESSION["email"] = $email;
        $_SESSION["nombre"] =$name;
        $user = new User($email);
        if (!$user->userExists()) {
            $user->insertUser($name);
            $id = $user->selectUserId()["id_usuario"];
        } else {
            $id = $user->selectUserId()["id_usuario"];
        }
        $user->insertSession($id);
        // Redirige al usuario a otra página después de la autenticación
        header("Location: https://localhost/turonvenezolano/index");
        exit; // Detiene la ejecución del script después de redirigir
    } else {
        // Si no hay un código de autorización, genera el enlace de inicio de sesión de Google
        $googleLogin = $client->createAuthUrl();
        $login = "<a href='".$googleLogin."'>Google Login</a>";
    }
 } 


$login = isset($_SESSION["nombre"]) ? "Bienvenido, " . $_SESSION["nombre"] : "<a href=".$googleLogin.">Login</a>";
$logedIn = isset($_SESSION["email"])?true:false;
$emailClean = isset($_SESSION["email"]) ? filter_var($_SESSION["email"], FILTER_SANITIZE_EMAIL) : "";

if($_SERVER["REQUEST_METHOD"]==="POST" && 
isset($_POST["cerrar"])){
  session_unset();
  session_destroy();
  header("Location: index");
  exit;
}