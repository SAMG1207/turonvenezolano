<?php 
require_once 'src/scripts/index.script.php';
require_once 'src/includes/function.php';
// require_once '../vendor/autoload.php';
// require_once '../src/includes/autoloader.inc.php';
// require_once '../src/includes/routing.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="public/css/style.css">
        <link rel="icon" href="public/img/ron.png" sizes="16x16" type="image/png">
        <link rel="icon" href="public/img/ron.png" sizes="32x32" type="image/png">
        <title>Tu Ron Venezolano</title>
      
  <script>
    <?php echo "let emailUser = " . json_encode($emailClean) . ";" ?>
    <?php echo "let logedIn = " . (isset($_SESSION["email"]) ? "true" : "false") . ";" ?>;
  </script> 
</head>
<body>
  
  <div class="row bg-dark">
   <p class="text-light" id="enlace"> <?php echo $login ?></p> 
  </div>
    <div class="container-fluid w-100">
        <div class="row">
          <div class="video-container">
           <iframe class="embed-responsive-item" src="public/img/playa.mp4"></iframe>
          </div>
        </div>
    </div>
    <section>
    <div class="container-fluid w-auto">
        <div class="row  pt-2 text-center anuncio">
            <h1>TuRonVenezolano</h1>
            <h2 class="enunciado"><span class="resaltado">El mejor ron del mundo</span></h2>
            <p class="enunciado">Somos una distribuidora de rones venezolanos ubicada en Madrid.
              Desde el año 2023, estamos trabajando sólo con ron venezolano, el mejor del mundo.
            </p>
            <p class="enunciado">Tenemos la mejor calidad y estamos listos para tu evento, ya sea boda, graduación, 
              cumpleaños o simplemente un buen momento, en tuRonVenezolano estamos listos</p>
        </div>
    </div>
</section>
<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active enunciado" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link enunciado" href="#">¿Quiénes somos?</a>
        </li>
        <li class="nav-item">
          <a class="nav-link enunciado" href="#">¿Dónde Estamos?</a>
        </li>
        <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle enunciado" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Nuestros Rones
    </a>
    <ul class="dropdown-menu">
      <?php
      $desplegable="";
      foreach($marcas as $brand){
        
        $desplegable .= "<li><a class='dropdown-item enunciado' href='#'>".$brand["marca"]."</a></li>";
       }
       echo $desplegable;
      ?>
    </ul>
    </li>
    <?php if(isset($_SESSION["email"])): ?>

      <li class="nav-item">
         <!-- <a class="nav-link enunciado" href="regdirection.php" target="_blank">Registrar Direcciones</a> -->
         <a class="nav-link enunciado" href="regdireccion" target="_blank">Registrar Direcciones</a>
      </li>

      <li class="nav-item">
         <!-- <a class="nav-link enunciado" href="pedidos.php">Mis Pedidos</a> -->
         <a class="nav-link enunciado" href="pedidos">Mis Pedidos</a>
      </li>
    
      <li class='nav-item justify-content-center mx-3 mt-3'>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type='submit' name='cerrar' value='Cerrar Sesión' class="bg-primary border rounded-4">
        </form>
       </li>
      
      </ul>
      <div class="position-relative contenedor">
      <div class="d-flex carrito-container ms-auto">
        <a href="preventa">
        <button class="btn btn-outline-success" type="submit">
               <i class="bi bi-cart"></i>
          </button>
        </a>
          
         <div class="circulo text-dark">
          <p id="cantidad"></p>
        </div>
       </div>
      </div>
      <?php endif; ?>
      
    </div>
  </div>
</nav>

  <section class="container-fluid market">
    <div class="row donde pt-5 text-center border-bottom mb-3 p-2">
       <div class="col-6 col-md-6 mx-auto my-2 d-flex align-items-center justify-content-center shadow-lg" id="map"></div>
       <div class="col-6 col-md-6 mx-auto d-flex align-items-center justify-content-center">
        <h3 class="text-center text-dark">Estamos ubicados en Calle Ribera de Curtidores, 10. Madrid, 28005</h3>
       </div>
    </div>

   <?php 
   foreach($marcas as $marca){
    $elementos = $stock->seleccionaModeloPorMarca($marca["marca"]);
    $section =  "<div class='row text-center my-2 p-3'>";
    $section .= "<h1>".$marca["marca"]."</h1>";
    foreach($elementos as $elemento){
      $section .="<div class='col-lg-4 p-2 mercancia'>";
      $section .= "<img src=\"back/imgs/" . $elemento["fotoUrl"] . "\" alt=\"\" class='imagenBotella shadow-lg border rounded-4'>";
      $section .="<h4 class='text-center mt-1 botellas'id=".$elemento["idBotella"]." value=".json_encode($elemento["idBotella"]).">".$elemento["marca"]." ".$elemento["modelo"]." solo por €".$elemento["precio"]."</h4>";
      $section .= "<label for ='cantidad'>Cantidad </label>";
      $cantidad = json_encode($stock->cuentaAlmacen($elemento["idBotella"]));
      $section .="<input type ='number' min=0 max=".$cantidad." class ='cantidadesDisponibles'>";
      $section .="<button class='d-block mx-auto mt-1 bg-dark rounded-5 shadow-lg compra'> <h6 class='text-light p-2'>Comprar</h6> </button>";
      $section .="</div>";
    }
    $section .= "</div>";
    echo $section;
   }
   ?>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="public/js/index.js" type="module"></script>
<!-- <script src="js/js.js"></script> -->
</body>
</html>