<?php
require_once __DIR__ . '/../src/scripts/detallado.script.php';
 ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <?php echo window::renderHead("Tus Pedidos") ?>
    </head>
    <body class="market">
        <div class="container">
          <div class="row mx-auto text-center">
            <div class="col-lg-9 mt-5 mx-auto">
              <h3>Tu compra <?php echo $_GET["idCompra"] ?></h3>
            <?php
              echo $lista;
            ?>

            <div>
            <a href="pedidos.php"><button type="button" class="btn btn-primary w-100 mb-1 border border-dark shadow-lg"><h6>Volver a las compras</h6></button></a>
            </div>
            </div>
          </div>
        </div>
    </body>
    </html>