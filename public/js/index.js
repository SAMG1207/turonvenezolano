

import { countCar, actualizaCarrito, soloNumeros } from './utils.js';

document.addEventListener("DOMContentLoaded", async function() {
  

  console.log(logedIn);
  console.log(emailUser);

  if (logedIn) {
    document.getElementById("cantidad").innerText = await countCar(emailUser);
  }

  // CARGAR COMPRA AL CARRITO
  let botellas = document.querySelectorAll(".botellas");
  let idsBotellas = Array.from(botellas).map(elemento => elemento.id);
  let cantidadesDisponibles = document.querySelectorAll(".cantidadesDisponibles");

  let compras = document.querySelectorAll(".compra");
  let aviso = "";

  compras.forEach((compra, index) => {
    compra.addEventListener("click", async function() {
      if (!logedIn) {
        aviso = "Debe iniciar sesión para poder comprar";
        alert(aviso);
      } else {
        let idBottle = idsBotellas[index];
        let cantidadAComprar = cantidadesDisponibles[index].value;
        if(soloNumeros(cantidadAComprar)){
          let limite = cantidadesDisponibles[index].getAttribute("max");
        if (emailUser) {
          console.log(idBottle, emailUser);
          let url = `async/insertPreventa.php?id_botella=${idBottle}&email=${emailUser}`;

          for (let i = 0; i < cantidadAComprar; i++) {
            if (i < limite) {
              try {
                let response = await fetch(url);
                if (response.ok) {
                  console.log("Solicitud GET exitosa");
                  await actualizaCarrito(emailUser);
                } else {
                  console.error('Error en la solicitud GET:', response.statusText);
                }
              } catch (error) {
                console.error('Error en la solicitud GET:', error);
              }
            } else {
              aviso = "La cantidad ingresada supera el stock de mercancía";
              alert(aviso);
              return;
            }
          }
        }
        }
        
      }
    });
  });

   async function loadscript(data) {
     var script = document.createElement("script");
     script.src = "https://maps.googleapis.com/maps/api/js?key=" + data + "&callback=initMap";
     script.async = true;
    script.onload = () => { initMap(); };
    document.body.appendChild(script);
   }

   try {
     let response = await fetch("async/apikey.php");
     let data = await response.json();
     await loadscript(data);
   } catch (error) {
     console.log(error);
   }

   async function initMap() {
     let coord = { lat: 40.408590, lng: -3.707530 };
     let map = new google.maps.Map(document.getElementById('map'), {
      zoom: 15,
      center: coord
     });

     let marker = new google.maps.Marker({
       position: coord,
    map: map
     });
   }
});
