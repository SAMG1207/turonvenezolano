const marca = document.getElementById("marca");
const botella = document.getElementById("botella");
let marcaIngreso = document.getElementById("marcaIngreso")
let modeloIngreso  = document.getElementById("modeloIngreso")
let marcaPrecio = document.getElementById("marcaPrecio")
let modeloPrecio = document.getElementById("modeloPrecio")
let marcaPerdida = document.getElementById("marcaPerdida")
const lista = document.querySelectorAll(".lista");
const listaDealer = document.querySelectorAll(".lista-dealer")
const cambiar = document.getElementById("cambiar")
let newPrice = document.getElementById("nuevoPrecio")


lista.forEach(elemento=>{
  elemento.style.display="none";
})

listaDealer.forEach((elemento, index) => {
  elemento.addEventListener("click", () => {
    lista.forEach((formulario, idx) => {
      if (idx === index) {
        formulario.style.display = "block";
      } else {
        formulario.style.display = "none";
      }
    });
  });
});

marca.addEventListener("change",()=>selecBotellas(marca.value, botella));

function selecBotellas(valor, padre){
  switch(valor){
    case "cacique":
            agregarOpciones(["500", "standar", "antiguo"], padre);
            break;
        case "santa teresa":
            agregarOpciones(["clásico", "1796","linaje"], padre);
            break;
        case "pampero":
            agregarOpciones(["clasico", "aniversario", "blanco"], padre);
            break;
        case "diplomatico":
            agregarOpciones(["mantuano", "reserva", "añejo"], padre);
            break;
        default:
            break;
  }
}

function agregarOpciones(optionsArray, padre) {
    padre.innerHTML = "";
    var optionNull = document.createElement("option");
    optionNull.value = "Seleccione";
    optionNull.setAttribute("disabled", "disabled");
    optionNull.setAttribute("selected", "selected");
    optionNull.innerText = "Seleccione una opción";
    padre.appendChild(optionNull);

    optionsArray.forEach(opcion => {
        var option = document.createElement("option");
        option.value = opcion;
        option.innerText = opcion;
        padre.appendChild(option);
    });
}


marcaIngreso.addEventListener("change", async()=>{
  await addBottles(marcaIngreso, modeloIngreso);
})

function returnMeResponse(URL){
    return fetch(URL)
    .then(response => {
        if(response.ok){
          console.log(response)
            return response.json();
        }
        throw new Error('La respuesta no fue exitosa');
    })
  }

let responsePrecio=[]
async function addBottles(marca, modelo) {
  var marcaSeleccionada = encodeURIComponent(marca.value).replace(/%20/g, '+');
  console.log(marcaSeleccionada);
   var URL = "../async/givemebottles.php?marca=" + marcaSeleccionada;
    var response = await returnMeResponse(URL);
    var responseModelo=[]
    responsePrecio=[]
      response.forEach(item => {
      responseModelo.push(item.modelo);
      responsePrecio.push(item.precio);
});
     agregarOpciones(responseModelo, modelo);
     console.log(responsePrecio)
}



cambiar.style.display="none";

let precio=""
marcaPrecio.addEventListener("change", async()=>{
  await addBottles(marcaPrecio, modeloPrecio)
  modeloPrecio.addEventListener("change", function(){
    document.getElementById("precioActual").innerText=""
    var modeloSeleccionado= modeloPrecio.selectedIndex;
   console.log(modeloSeleccionado)
   precio = responsePrecio[modeloSeleccionado]
   var nuevoPrecio = precio -(precio *0.15).toFixed(2)
   document.getElementById("precioActual").innerText= "€"+precio +", precio mínimo posible: €"+nuevoPrecio;
   newPrice.addEventListener("input", ()=>{
    var newPriceValue = parseFloat(newPrice.value)
    if(newPriceValue > parseFloat(nuevoPrecio)){
      cambiar.style.display="block";
      cambiar.classList.add("text-center")
    }else{
      cambiar.style.display="none";
    }
   })
  })
  
})

//Para saber los precios

marcaPerdida.addEventListener("change", async () => {
    await addBottles(marcaPerdida, modeloPerdida);
});
