<?php
require_once __DIR__."/../../src/scripts/preventa.script.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php echo window::renderHead("Preventa - Tu Ron Venezolano") ?>
</head>
<body class="market">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mt-5 border border-dark rounded border-5 mx-auto">
               <div class="nombre text-center mt-3">
                   <h4 class="border-bottom">Bienvenido, <?php echo $name ?></h4>
                 
               </div>
               <div class="text-center mt-3">
                    <h5>Tus compras son:</h5>
                    <div class="compras">
                        <?php 
                        $precioTotal = null;
                        if($productos && count($productos)>0){
                            $precioTotal = 0;
                            
                          $lista="<table class='overflow-hidden rounded table table-striped table-bordered table-hover shadow-lg'>";
                          $lista.="<thead>";
                          $lista.=" <tr> <th scope='col'>#</th> <th scope='col'>Marca</th> <th scope='col'>Modelo</th> <th scope='col'>Cantidad</th> <th scope='col'>Precio Total</th> <th scope='col'>Eliminar</th> </tr>";
                          $lista.="</thead>";
                          $lista.="<tbody>";
                          foreach($productos as $producto){
                           $lista.="<tr>";
                           $lista.="<th scope='row'>".$producto['id_botella']."</th>";
                           $lista.="<td>".htmlspecialchars($producto['marca'])."</td>";
                           $lista.="<td>".htmlspecialchars($producto['modelo'])."</td>";
                           $lista.="<td>".htmlspecialchars($producto['cantidad'])."</td>";
                           $lista.="<td> €".htmlspecialchars($producto['total_precio'])."</td>";
                           $lista .= "<td>
                                        <form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>
                                        <input type='hidden' name='id_botella' value='" . htmlspecialchars($producto['id_botella']) . "'>
                                        <button type='submit' class='btn btn-danger'><i class='bi bi-trash'></i></button>
                                    </form>
                                </td>";
                           $lista.="</tr>";
                           $precioTotal+=$producto['total_precio'];
                          }
                          $lista.="<tr>";
                          $lista.="<td colspan='4'><h4>Total Precio</h4></td>";
                          $lista.="<td colspan='2'><h4>€".$precioTotal."</h4></td>";
                          $lista.="</tr>";
                          $lista.="</tbody>";
                          $lista.="</table>";
                         
                        }else{
                           
                           $lista="<p>No hay productos que mostrar</p>";
                        }
                        echo $lista;
                        ?>
                    </div>
                    <?php if($precioTotal > 0):?>
                    <div>
                        <form action="/turonvenezolano/comprar" method="post">
                        <?php
                        $label ="<div class='bg-light mb-2'>";
                        $label .= "<h3 class='border border-bottom'> Seleccione la dirección de envío </h3>";
                        foreach($datosEnvio as $datos){
                        $label.="<div class='d-block'>";
                        $label.="<label class='p-2'>";
                        $label.="<input type='radio' name='de' value='".$datos["nombre"]."'>".$datos["nombre"]."</input> </label>";
                        $label.="</div>";
                         }
                         $label.="</div>";
                         echo $label;
                         ?>
                           <input type="hidden" name="totalAPagar" value="<?php echo htmlspecialchars($precioTotal)?>">
                           <button type="submit" class="btn btn-warning w-100 mb-1 border border-dark shadow-lg"><h5>Comprar ya!</h5></button>
                        </form>
                    </div>
                    <?php endif; ?>
                    <a href="index"><button type="button" class="btn btn-primary w-100 mb-1 border border-dark shadow-lg"><h6>Volver a la página principal</h6></button></a>
               </div>
            </div>
        </div>
    </div>
    <script src="public/js.js"></script>
</body>
</html>