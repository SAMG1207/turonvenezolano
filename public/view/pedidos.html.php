<?php 
require_once __DIR__ . '/../../src/scripts/pedidos.script.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php echo window::renderHead("Tu Ron Venezolano - Pedidos") ?>
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
                   $tabla.="<th scope='row'><a href='detallado?idCompra=".$pedido['idPedido']."'>".$pedido['idPedido']."</th>";
                   $tabla .="<td>".htmlspecialchars($pedido["fechaCompra"])."</td>";
                   $tabla .="<td>€".htmlspecialchars($pedido["totalCompra"])."</td>";
                   $tabla .="</tr>";
                }
                $tabla .="</tbody>";
                $tabla .="</table>";
                $tabla.="<a href='index.php'><button type='button' class='btn btn-primary w-100 mb-1 border border-dark shadow-lg'><h6>Volver a la página principal</h6></button></a>";
                $tabla .="</div>";
                echo $tabla;
            } else{
                echo("No hay pedidos");
            }
            ?>
            
        </div>
    </div>
</body>
</html>