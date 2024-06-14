<?php
require 'includes/autoloader.inc.php';
$pass = new Pass();
$password = $pass->giveMeG("mapa");
echo json_encode($password);

