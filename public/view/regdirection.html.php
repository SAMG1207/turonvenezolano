<?php
require_once __DIR__ . '/../../src/scripts/regdirection.script.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <?php echo window::renderHead("Registro de dirección, TuRonVenezolano")?>
</head>
<body class="market">
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-6 mx-auto border border-dark rounded border-5 shadow-lg mb-5">
                <h1 class="text-center border-bottom">Bienvenido, <?php echo $_SESSION["nombre"] ?></h1>
                <p class="text-center">Necesitamos tener por lo menos una dirección de envío</p>
                <h4 class="text-center">Sólo se hacen envíos dentro de España</h4>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="border border-dark rounded border-3 shadow-lg mb-2">

                     <div class="form-group p-2">
                        <label for="nombre" class="d-block">Nombre de la dirección</label>
                        <input type="text" name="nombre" id="" class="element">
                    </div>
                    <div class="form-group p-2">
                      <label for="ccaa" class="d-block">Comunidad Autónoma</label>
                      <select name="ccaa" id="ccaa" class="element">
                          <option value="" hidden selected>Escoja una opción</option>
                          <?php 
                           foreach($ccaa as $comunidad){
                            $elemento .= '<option value="' . htmlspecialchars($comunidad['idCCAA']) . '">';
                            $elemento .= htmlspecialchars($comunidad['Nombre']);
                            $elemento .= '</option>';
                           }
                           echo $elemento;
                           ?>
                      </select>
                    </div>
                    
                    <div class="form-group p-2">
                        <label for="provincia" class="d-block">Provincia</label>
                        <select name="provincia" id="provincia" class="element"></select>
                    </div>

                    <div class="form-group p-2">
                        <label for="municipio" class="d-block">Municipio</label>
                        <select name="municipio" id="municipio" class="element"></select>
                    </div>

                    <div class="form-group p-2">
                        <label for="nombreVia" class="d-block">Nombre de la vía</label>
                        <select name="nombreVia" id="" class="element">
                            <option value="" hidden selected>Escoja una opción</option>
                            <option value="avenida">Avenida</option>
                            <option value="calle">Calle</option>
                            <option value="cuesta">Cuesta</option>
                            <option value="glorieta">Glorieta</option>
                            <option value="pasaje">Pasaje</option>
                            <option value="plaza">Plaza</option> 
                            <option value="otro">Otro, especifique abajo</option> 
                        </select>
                    </div>
                    <div class="form-group p-2">
                        <label for="nombreVia" class="d-block">Nombre de la vía</label>
                        <input type="text" name="nombreVia" class="w-75 element">
                    </div>
                    <div class="form-group p-2">
                        <label for="datosCompletos" class="d-block">Nro, Puerta</label>
                        <input type="text" name="datosCompletos" class="element">
                    </div>
                    <div class="form-group p-2">
                        <label for="cp" class="d-block">Código Postal:</label>
                        <input type="number" name="cp" id="" class="element">
                    </div>
                    <div class="form-group p-2">
                        <label for="tlf" class="d-block">Teléfono</label>
                        <input type="number" name="tlf" id="" class="element">
                    </div>
                    <div class="form-group p-2">
                     <button type="submit" class="p-1 bg-primary" id="buttonSend">Registrar Dirección</button>
                    </div>
                </form>
                <a href="index.php"><button class="w-100 bg-primary mb-2">Volver a la página principal</button></a>
            </div>
        </div>
    </div>
    <script src="public/js/regDirection.js" type="module"></script>
</body>
</html>