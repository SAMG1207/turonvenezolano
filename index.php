<?php
require 'src/includes/function.php';

$uri = $_SERVER['REQUEST_URI'];

// Remover cualquier parámetro de query
$uri = strtok($uri, '?');

// Enrutamiento básico
switch ($uri) {
    case '/turonvenezolano/':
    case '/turonvenezolano/index':
        require 'public/view/index.php';
        break;

    case '/turonvenezolano/preventa':
        require 'public/view/preventa.php';
        break;

    case '/turonvenezolano/pedidos':
        require 'public/view/pedidos.html.php';
        break;    
    case '/turonvenezolano/regdireccion':
        require 'public/view/regdirection.html.php';
        break;  

     case '/turonvenezolano/detallado':
        require 'public/view/detallado.html.php';
        break; 

    case '/turonvenezolano/administrador':
        require 'public/view/administrador.html.php';
         break;
         case '/turonvenezolano/comprar':
            require 'src/scripts/comprar.php';
            break;
    default:
        echo "Página no encontrada";
        http_response_code(404);
        break;
}
