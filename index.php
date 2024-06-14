<?php
require 'src/includes/function.php';

$uri = $_SERVER['REQUEST_URI'];

// Remover cualquier parámetro de query
$uri = strtok($uri, '?');

// Enrutamiento básico
switch ($uri) {
    case '/':
    case '/index.php':
        require 'turonvenezolano\public\view\index.php';
        break;
    // Aquí puedes agregar más rutas según sea necesario
    // Por ejemplo:
    // case '/another-page':
    //     require 'public/view/another-page.php';
    //     break;
    default:
        echo "Página no encontrada";
        http_response_code(404);
        break;
}
