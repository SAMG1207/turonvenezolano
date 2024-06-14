<?php
require_once __DIR__ . '/../includes/autoloader.inc.php';
$stock = new Stock();
$allMerch = $stock->seleccionaMarcas();

if($_SERVER['REQUEST_METHOD'] === 'POST'
&& isset($_POST["enviar"])
&& isset($_POST["marca"])
&& isset ($_POST["botella"])
&& !empty($_POST["marca"])
&& isset ($_POST["precio"])
&& isset($_FILES["foto"])){
  
  
   $aviso = "";
   
    $marca = $_POST["marca"];
    $botella = $_POST["botella"];
    $precio= $_POST["precio"];
    $file = $_FILES["foto"];

    if(!empty($marca) && !empty($botella) && !empty($precio) && !empty($file)){
      $fileName = $file['name'];
      $fileTmpName = $file['tmp_name'];
      $fileSize = $file['size'];
      $fileError = $file['error'];
      $fileType = $file['type'];
  
      $fileExt = explode('.', $fileName);
      $fileActualExt = strtolower(end($fileExt));
  
      $allowed = array("jpg","jpeg","png");
      if($precio < 100 && is_numeric($precio)){
        if(in_array($fileActualExt, $allowed)){
          if($fileError === 0){
             if($fileSize < 500000){
               $fileNameNuevo = uniqid('', true).".".$fileActualExt;
               $fileDestination ="../back/imgs/".$fileNameNuevo;
               move_uploaded_file($fileTmpName, $fileDestination);
              
               if($stock->revisarBBDD($marca, $botella)){
                $aviso="Este modelo ya está en la base de datos";
               }else{
                $stock->insertarBBDD($stock->testData($marca), $stock->testData($botella), $stock->testData($precio), $fileNameNuevo);
                $aviso = "Se han almacenado los valores correctamente";
               }
             }else{
               $aviso="Imagen muy pesada, debe ser inferior a los 5mb";
             }
          }else{
           $aviso="Imagen dañada :(";
          }
       }else{
         $aviso ="Ha intentado subir un archivo con un formato no permitido, sólo se permite jpg, jpeg y png";
       }
      }else{
        $aviso ="El precio de la botella supera los €100, no tenemos ninguna botella que supere ese precio o el valor introducido no es un número";
      }
    }else{
      $aviso="Alguno de los valores está vacío, por favor compruebe";
    }

}else if(
  isset($_POST["registrar"])
  && isset($_POST["marcaIngreso"])
  && isset($_POST["modeloIngreso"])
  && isset($_POST["cantidad"])
){
  $marca = $_POST["marcaIngreso"];
  $modelo = $_POST["modeloIngreso"];
  $cantidad = $_POST["cantidad"];
  $rounds = intval($cantidad);
  if(!empty($marca) && !empty($modelo) && !empty($cantidad)){
     if(is_numeric($cantidad) && $cantidad > 0 && $cantidad <= 100){
        $stock->entradaNueva($marca, $modelo, $cantidad);
        $aviso="Inventario Actualizado";
     }else{
      $aviso="La cantidad debe ser un número positivo inferior a 100";
     }
  }else{
    $aviso = "No debe introducir valores vacíos";
  }
}else if(
  isset($_POST["cambiar"])
  && isset($_POST["marcaPrecio"])
  && isset($_POST["modeloPrecio"])
  && isset($_POST["nuevoPrecio"])
  ){
  $marca = $_POST["marcaPrecio"];
  $modelo = $_POST["modeloPrecio"];
  $nuevoPrecio = $_POST["nuevoPrecio"];
 
  if(!empty($marca) && !empty($modelo) && !empty($nuevoPrecio)){
    $np = intval($nuevoPrecio);
   $precioBase = $stock->seleccionaIdPrecioPorModelo($marca, $modelo);
   $precioMinimo = $precioBase -($precioBase * 0.15);
   if(is_numeric($np) && $precioMinimo <= $np){
     $precioCambiado = $stock->updatePrecio($np, $marca, $modelo);
     if($precioCambiado){
      $aviso="Se han modificado los precios con éxito";
     }else{
      $aviso="No se han podido cambiar los precios";
     }
   }else{
    $aviso="El precio introducido no es correcto";
   }
  }else{
    $aviso ="No debe introducir valor vacíos";
  }
}else if(isset($_POST["perdidas"])
&& isset($_POST["marcaPerdida"])
&& isset($_POST["modeloPerdida"])
&& isset($_POST["cantidadPerdida"])){
   
  $marca = $_POST["marcaPerdida"];
  $modelo = $_POST["modeloPerdida"];
  $cantidad = $_POST["cantidadPerdida"];

  if(!empty($marca) && !empty($modelo) && !empty($cantidad)){
     $id=$stock->seleccionaIdPorModelo($marca, $modelo);
     $cuenta = intval($stock->cuentaAlmacen($id));
     $cantidadNumero = intval($cantidad);
     if($cuenta >= $cantidadNumero){
      $stock->actualizaPerdidas($marca, $modelo, $cantidad);
      $aviso ="Se han actualizado las pérdidas correctamente";
     }else{
      $aviso ="el número de estas botellas es: ".$cuenta.", debe introducir un número igual o menor";
     }
    
  }else{
    $aviso ="Los valores están vacíos";
  }
}