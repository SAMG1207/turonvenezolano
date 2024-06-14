<?php 
require_once __DIR__ . '/../src/scripts/pedidos.script.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"defer></script>
    <title>TusCompras!</title>
</head>
<body class="market">
    <div class="container">
        <div class="row mt-5">
            <?php
            if(count($pedidos)>0){
                $tabla = "<div class ='col-10 mt-5 border border-dark rounded border-5 mx-auto'>";
                $tabla .="<h4 class ='text-center my-2'> Tus compras,". $_SESSION["nombre"]." </h4>";
                $tabla .="<table class='overflow-hidden rounded table table-striped table-bordered table-hover shadow-lg'>";
                $tabla .= "<thead>";
                $tabla .="<tr><th scope ='col'>#</th><th scope='col'> Fecha y hora compra </th><th scope ='col'> Total </th></tr>";
                $tabla .= "</thead>";
                $tabla .="<tbody>";
                foreach($pedidos as $pedido){
                   $tabla .="<tr>";
                   $tabla.="<th scope='row'><a href='detallado.php?idCompra=".$pedido['idPedido']."'>".$pedido['idPedido']."</th>";
                   $tabla .="<td>".htmlspecialchars($pedido["fechaCompra"])."</td>";
                   $tabla .="<td>€".htmlspecialchars($pedido["totalCompra"])."</td>";
                   $tabla .="</tr>";
                }
                $tabla .="</tbody>";
                $tabla .="</table>";
                $tabla.="<a href='index.php'><button type='button' class='btn btn-primary w-100 mb-1 border border-dark shadow-lg'><h6>Volver a la página principal</h6></button></a>";
                $tabla .="</div>";
                echo $tabla;
            }
            ?>
            
        </div>
    </div>
</body>
</html>