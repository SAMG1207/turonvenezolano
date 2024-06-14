        import { fetchURL, soloLetras, soloNumeros } from "./utils.js";
        
        //DEFINICION DE LAS VARIABLES
        const formGroup = document.querySelectorAll(".element");
        let ccaa = document.getElementById("ccaa");
        let provincia = document.getElementById("provincia");
        let municipio = document.getElementById("municipio");
        let buttonSend = document.getElementById("buttonSend");
        let inputMembers = [formGroup[0], formGroup[5], formGroup[6]]; // Correct elements
        let changeMembers = [formGroup[1], formGroup[2], formGroup[3], formGroup[4]];


          //URLs para fetchs
        let URLProvincia = '../async/provincia.php?idCCAA='
        let URLMunicipio =  '../async/municipio.php?idProvincia='

        //Métodos Genéricos
        function removeAllChildren(padre){
            while(padre.firstChild){
                padre.removeChild(padre.firstChild);
            }
           }
        
 function crearHidden(padre){
            var hiddenOption = document.createElement("option");
                hiddenOption.disabled = true;
                hiddenOption.selected = true;
                hiddenOption.style.display = "none";
                hiddenOption.innerText = "Escoge tu provincia";
                padre.appendChild(hiddenOption);
            }

function manejarEvento(event) {
                const elemento = event.target;
                if (soloLetras(elemento.value)) {
                    elemento.classList.remove("wrong");
                    elemento.classList.add("well");
                } else {
                    elemento.classList.remove("well");
                    elemento.classList.add("wrong");
                }
                 }
            
            
    function allGood() {
                        return [...formGroup].every((formG) => formG.classList.contains("well"));
                    }

    //DESARROLLO
   
    ccaa.addEventListener("change",async function(){
        var URL =URLProvincia+ccaa.value
        var response = await fetchURL(URL);
        var provincias = response.data;
        var datosSeparadosProvincias =[];
        datosSeparadosProvincias = provincias.map(item=>({
                idProvincia: item.idProvincia,
                idCCAA: item.idCCAA,
                Provincia: item.Provincia
        }))
         removeAllChildren(provincia)
        crearHidden(provincia)
        datosSeparadosProvincias.forEach(provinciaData =>{
            var provinciaOpt= document.createElement("option")
            provinciaOpt.innerText = provinciaData.Provincia
            provinciaOpt.value=provinciaData.idProvincia
            provincia.appendChild(provinciaOpt)
        })
        
    })


    provincia.addEventListener("change", async function(){
        var URL =URLMunicipio+provincia.value
        var response = await fetchURL(URL);
        var municipios = response.data;
        var datosSeparadosMunicipios =[];
        datosSeparadosMunicipios = municipios.map(item=>({
                idMunicipio: item.idMunicipio,
                Municipio: item.Municipio
        }))

        removeAllChildren(municipio)
        crearHidden(municipio)
        datosSeparadosMunicipios.forEach(MunicipioData =>{
            var municipioOpt= document.createElement("option")
            municipioOpt.innerText = MunicipioData.Municipio
            municipioOpt.value=MunicipioData.idMunicipio
            municipio.appendChild(municipioOpt)
        })
    }) 

    inputMembers.forEach(elemento => {
    elemento.addEventListener("input", manejarEvento);
     });

    changeMembers.forEach(elemento => {
    elemento.addEventListener("change", manejarEvento);
    });

    formGroup[7].addEventListener("input", () => {
    if (soloNumeros(formGroup[7].value)) {
        formGroup[7].classList.remove("wrong");
        formGroup[7].classList.add("well");
    } else {
        formGroup[7].classList.remove("well");
        formGroup[7].classList.add("wrong");
    }
});

formGroup[8].addEventListener("input", () => {
    if (soloNumeros(formGroup[8].value)) {
        formGroup[8].classList.remove("wrong");
        formGroup[8].classList.add("well");
    } else {
        formGroup[8].classList.remove("well");
        formGroup[8].classList.add("wrong");
    }
})

buttonSend.addEventListener("click", (event) => {
    if (!allGood()) {
        event.preventDefault();
        console.log("not ok");
    } else {
        console.log("ok");
    }
});