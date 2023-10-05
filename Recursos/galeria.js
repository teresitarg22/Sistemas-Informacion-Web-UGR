// Este documento va a controlar los botones de la galería de fotos de los ciéntificos.

document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar elementos
    var fotos = document.getElementById('fotos');
    var items = fotos.querySelectorAll('#foto_galeria');
    var pies = fotos.querySelectorAll('#piefoto_galeria');
    var prevButton = document.getElementById('anterior');
    var nextButton = document.getElementById('siguiente');
  
    // Declaramos los atributos que vamos a necesitar:
    var numItems = items.length;
    var currentIndex = 0;
  
    // Ocultar todos los elementos menos el primero:
    for (var i = 1; i < numItems; i++) {
      items[i].style.display = 'none';
      pies[i].style.display = 'none';
    }
  
    // Mostrar el pie de foto de la primera imagen:
    pies[currentIndex].style.display = 'block';
  
    // Agregamos manejadores de eventos a los botones:
  
    // ---------------- RETROCEDER UNA IMAGEN ----------------
    prevButton.addEventListener('click', function() {
      // Si ya estamos en la última imagen, retroceder lleva a la primera imagen:
      currentIndex--;
  
      if (currentIndex < 0) {
        currentIndex = numItems - 1;
      }
  
      for (var i = 0; i < numItems; i++) {
        items[i].style.display = 'none';
        pies[i].style.display = 'none';
      }
  
      items[currentIndex].style.display = 'block';
      pies[currentIndex].style.display = 'block';
    });
  
    // ---------------- AVANZAR UNA IMAGEN ----------------
    nextButton.addEventListener('click', function() {
      // Si ya estamos en la última imagen, avanzar lleva a la primera imagen:
      currentIndex++;
  
      if (currentIndex >= numItems) {
        currentIndex = 0;
      }
  
      for (var i = 0; i < numItems; i++) {
        items[i].style.display = 'none';
        pies[i].style.display = 'none';
      }
  
      items[currentIndex].style.display = 'block';
      pies[currentIndex].style.display = 'block';
    });

    // ---------------- BORRAR UNA IMAGEN ----------------
    const enlacesEliminar = document.querySelectorAll('.eliminar-imagen');
  enlacesEliminar.forEach(enlace => {
      enlace.addEventListener('click', function(event) {
          event.preventDefault();
          const idImagen = this.getAttribute('data-id');
          eliminarImagen(idImagen);
      });
  });

  function eliminarImagen(idImagen) {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', '../Controlador/eliminar_imagen.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
          if (xhr.status === 200) {
              location.reload();
          }
      };
      xhr.send('idImagen=' + idImagen);
  }
  });
  