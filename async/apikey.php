<?php
require '../src/includes/autoloader.inc.php';
$pass = new Pass();
$password = $pass->giveMeG("mapa");
echo json_encode($password);

