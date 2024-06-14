<?php

require_once '../src/includes/sesion_config.php';
require_once '../src/includes/autoloader.inc.php';
require "../vendor/autoload.php";
include_once "../back/classes/connect_pass.class.php";

if (isset($_SESSION["email"]) && isset($_SESSION["nombre"]) && isset($_POST["de"]) && isset($_POST["totalAPagar"])) {
    $name = $_SESSION["nombre"];
    $email = $_SESSION["email"];
    $_SESSION["de"] = $_POST["de"];
    $_SESSION["totalAPagar"] = is_numeric($_POST["totalAPagar"])?$_POST["totalAPagar"]:null;


    $pass = new Pass();
    $preventa = new Preventa($email);
    $productos = $preventa->listaProductos();
    $line_items = [];

    foreach ($productos as $producto) {
        $precioUnidad = $producto["total_precio"] / $producto["cantidad"];
        $line_items[] = [
            "quantity" => $producto["cantidad"],
            "price_data" => [
                "currency" => "eur",
                "unit_amount" => $precioUnidad * 100,
                "product_data" => [
                    "name" => $producto["marca"] . " " . $producto["modelo"]
                ]
            ]
        ];
    }

    
    $stripeSecretKey = $pass->giveMeG("stripe");
    \Stripe\Stripe::setApiKey($stripeSecretKey);
     
    $checkOutSession = \Stripe\Checkout\Session::create([
        "mode" => "payment",
        "success_url" => "https://localhost/turonvenezolano/src/includes/buyLogic.php",
        "cancel_url" => "https://localhost/turonvenezolano/public/preventa.php",
        "locale" => "es",
        "line_items" => $line_items
    ]);

    http_response_code(303);
    header("Location: " . $checkOutSession->url);
    exit();
} else {
    header("Location: https://localhost/turonvenezolano/src/index.php");
    exit();
}
