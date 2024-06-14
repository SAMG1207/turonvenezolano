<?php
require_once __DIR__ . '/../../src/scripts/administrador.script.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <?php echo window::renderHead("Web de Administrador") ?>
</head>
<body>
    <div class="container-fluid administrador">
    <?php 
       if(!empty($aviso)){
        echo "<div class ='row bg-dark'><p class ='text-light text-center p-2'>".$aviso."</p></div>";
       }
       ?>
       <div class="row">
        <div class="col-md-6 mt-5 d-block mx-3 p-4">
           <h1>¿Qué desea hacer?</h1>
           <div class="col-md-4 ">
           <button class="border-border border-dark-subtle bg-light mb-0 border border-dark-subtle opacity-75 w-100 p-2 d-block mx-auto lista-dealer">Dar de alta nuevos productos</button>
           <button class="border-border border-dark-subtle bg-light mb-0 border border-dark-subtle opacity-75 w-100 p-2 d-block lista-dealer">Entrada de mercancías</button>
           <button class="border-border border-dark-subtle bg-light mb-0 border border-dark-subtle opacity-75 w-100 p-2 d-block lista-dealer">Cambio de precios de un producto</button>
           <button class="border-border border-dark-subtle  bg-light mb-0 border border-dark-subtle opacity-75 w-100 p-2 d-block lista-dealer">Registrar falta de mercancía (roturas, robos, etc...)</button>
           <button class="border-border border-dark-subtle  bg-light mb-0 border border-dark-subtle opacity-75 w-100 p-2 d-block">Cerrar Sesión</button>
           </div>
        
        </div>
       </div>
        <div class="row lista">
            <div class="col-md-6 mt-5 d-block mx-auto bg-light text-center p-4 rounded-2 shadow-lg p-3">
            <h1 class ="border-bottom">Alta de producto <b>nuevo</b> en el sistema</h1>
               <p>Insertar botella</p>
               <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">

                <div class="form-group mt-2">
                <label for="marca">Seleccione la marca</label>
                 <select name="marca" id="marca">
                    <option value="seleccione" disabled selected>Seleccione una:</option>
                   <option value="cacique">Cacique</option>
                   <option value="santa">Santa Teresa</option>
                   <option value="pampero">Pampero</option>
                   <option value="diplomatico">Diplomático</option>
                </select>
                </div>
                <div class="form-group mt-2">
                  <label for="botella">Seleccione la botella</label>
                  <select name="botella" id="botella"></select>
                </div>
                <div class="form-group mt-2">
                  <label for="precio">Seleccione el precio por unidad</label>
                  <input type="number" name="precio" id="" min="1" step="0.01">
                </div>
                <div class="form-group mt-2">
                  <label for="foto">Suba la foto</label>
                <input type="file" name="foto" id="">
                </div>
               <div class="form-group mt-2">
               <input type="submit" value="enviar" name="enviar" class="rounded bg-primary text-light">
               </div>
               
             </form> 
            </div>
        </div>

        <div class="row lista">
          <div class="col-md-6 mt-5 d-block mx-auto bg-light text-center p-4 rounded-2 shadow-lg p-3">
            <h1 class ="border-bottom">
              Entrada de Producto al almacén
            </h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="form-group mt-2">
              <label for="">Marca</label>
              <select name="marcaIngreso" id="marcaIngreso">
              <option value="seleccione" disabled selected>Seleccione una:</option>
              <?php 
                foreach($allMerch as $merch){
                  echo "<option value='" . htmlspecialchars($merch['marca']) . "'>" . ucfirst($merch['marca']) . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group mt-2">
              <label for="modeloIngreso">Modelo</label>
              <select name="modeloIngreso" id="modeloIngreso"></select>
            </div>
            <div class="form-group mt-2">
              <label for="cantindad">Cantidad</label>
              <input type="number" min="0" max="150" name="cantidad">
            </div>
            <div class="form-group mt-2">
              <input type="submit" value="registrar" name="registrar" class="rounded bg-primary text-light">
            </div>
            </form>
          </div>
        </div>

        <div class="row lista">
          <div class="col-md-6 mt-5 d-block mx-auto bg-light text-center p-4 rounded-2 shadow-lg p-3">
            <h1 class ="border-bottom">Cambio de precio de producto</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="form-group mt-2">
              <label for="marcaPrecio">Marca</label>
              <select name="marcaPrecio" id="marcaPrecio">
              <?php 
                foreach($allMerch as $merch){
                  echo "<option value='" . htmlspecialchars($merch['marca']) . "'>" . ucfirst($merch['marca']) . "</option>";
                }
                ?>
              </select>
              <div class="form-group mt-2">
                <label for="modeloPrecio">Modelo:</label>
                <select name="modeloPrecio" id="modeloPrecio"></select>
              </div>
              <div class="form-group mt-2">
                 <label for="nuevoPrecio">Nuevo Precio (sólo se admite rebaja del 15% máximo), precio actual: <span id="precioActual"></span></label>
                 <input type="number" name="nuevoPrecio" id="nuevoPrecio">
              </div>
              <div class="form-group mt-2">
                <input type="submit" value="cambiar precio" name="cambiar" id="cambiar">
              </div>
            </div>
            </form>
          </div>
        </div>

        <div class="row lista">
         <div class="col-md-6 my-5 d-block mx-auto bg-light text-center p-4 rounded-2 shadow-lg p-3">
          <h1 class="border-bottom">Reportar pérdidas (robos, roturas, etc...)</h1>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="form-group mt-2">
              <label for="">Marca</label>
              <select name="marcaPerdida" id="marcaPerdida">
              <option value="seleccione" disabled selected>Seleccione una:</option>
              <?php 
                foreach($allMerch as $merch){
                  echo "<option value='" . htmlspecialchars($merch['marca']) . "'>" . ucfirst($merch['marca']) . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group mt-2">
              <label for="modeloPerdida">Modelo</label>
              <select name="modeloPerdida" id="modeloPerdida"></select>
            </div>
            <div class="form-group mt-2">
              <label for="cantidadPerdida">Cantidad</label>
              <input type="number" min="0" max="150" name="cantidadPerdida">
            </div>
            <div class="form-group mt-2 text-center">
              <input type="submit" value="Registrar pérdidas" name="perdidas" class="rounded bg-primary text-light">
            </div>
            </form>
         </div>
        </div>
    </div>
 </div>   
</body>
<script src="public/js/administrador.js"></script>
</html>
