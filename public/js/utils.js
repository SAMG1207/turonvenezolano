export async function countCar(email) {
  var URL = `../async/countCar.php?email=${encodeURIComponent(email)}`;

  // try {
      const response = await fetch(URL);
      if (response.ok) {
          const data = await response.json(); // Parsear la respuesta JSON
          console.log("Éxito count Car:", data);
          return data.count; // Retornar los datos obtenidos
      // } else {
      //     throw new Error('La solicitud fetch falló');
      // }
  // } catch (error) {
  //     console.error('Error en countCar:', error);
  //     return null; // Retorna null en caso de error
  // }
}}

export async function actualizaCarrito(email){
  document.getElementById("cantidad").innerText = await countCar(email);
}

export async function fetchURL(url) {
  try {
      const response = await fetch(url);
      if (response.ok) {
          console.log("Respuesta exitosa de la URL:", url);
          const data = await response.json();
          return { success: true, data: data };
      } else {
          console.error("Error en la solicitud GET:", response.statusText);
          return { success: false, error: response.statusText };
      }
  } catch (error) {
      console.error("Error en la solicitud GET:", error);
      return { success: false, error: error };
  }
}

export function soloLetras(cadena) {
  return /^[a-zA-Z0-9\s.,]*$/.test(cadena);
}

export function soloNumeros(cadena){
  return /^[0-9]+$/.test(cadena);
}
