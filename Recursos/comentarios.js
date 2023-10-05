// -------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function(){
    // Declaración variable para la columna de comentarios:
    var columnaComentarios = document.getElementById("columnaComentarios");
    var cuerpo = document.getElementById("seccion_fija");
    var columnaVisible = false;
    
    // Función para abrir y cerrar el menú.
    function abrirMenu(){
        if(columnaVisible){
            columnaComentarios.style.display = "none";
            cuerpo.style.marginRight = "0%";
            columnaVisible = false;
        }else{
            columnaComentarios.style.display = "block";
            cuerpo.style.marginRight = "20%";
            columnaVisible = true;
        }
    }

    // Declaro una función que comprueba que no haya palabras prohibidas en el comentario:
    const nuevoComentario = document.getElementById("comentario");
    var palabrasProhibidas = {};
    nuevoComentario.addEventListener("input", function(){
        // Extraigo las palabras prohibidas de la base de datos:
        fetch('../Vista/palabras_prohibidas.php')
        .then(response => response.json())
        .then(palabras => {
            palabrasProhibidas = palabras;
        })
        .catch(error => {
            alert("No se han podido obtener las palabras prohibidas...")
        });
        
        let texto = nuevoComentario.value;
        for(let i = 0; i < palabrasProhibidas.length; i++){
            // Creamos una expresión regular para que no censure la palabra en caso de estar contenida:
            const expresionRegular = new RegExp(`\\b${palabrasProhibidas[i]}\\b`, 'gi');

            if(texto.match(expresionRegular)){
                texto = texto.replace(expresionRegular, "*".repeat(palabrasProhibidas[i].length));
            }
        }

        nuevoComentario.value = texto;
    });

    // Declaración del botón para que aparezca y desaparezca la sección:
    var boton = document.getElementById("boton_comentarios");
    boton.addEventListener("click", abrirMenu)

    // Comprobación de los campos del formulario:
    function comprobarForm(){
        var nombre = document.getElementById("nombre").value;
        var email = document.getElementById("email").value;
        var coment = document.getElementById("comentario").value;
        var email_formato = /^\S+@\S+\.\S+$/;

        if(nombre === "" || coment === "" || email === ""){ // Comprobación de que los campos están rellenos:
            alert("¡Debe rellenar todos los campos!");
            return false;
        }else if(!email_formato.test(email)){ // Comprobación del formato del e-mail:
            alert("¡El e-mail proporcionado no sigue el formato adecuado!");
            return false;
        }else{
            // Se ha cumplido que todos los campos del formulario son correctos.
            return true;
        } 
    }

    // Una vez está todo correcto:
    var form = document.getElementById("formulario");
    
    form.addEventListener("submit", function(evento){
        evento.preventDefault();

        // Obtener el ID del científico
        var idCientifico = document.getElementById("enviar_comentario").dataset.id;

        if(comprobarForm()){
            // Vamos a guardar la información recogida del formulario en un nuevo comentario.
            var nombre = document.getElementById("nombre").value;
            var email = document.getElementById("email").value;
            var comentario = document.getElementById("comentario").value;
            
            // Declaramos variables para saber la fecha y hora del comenario nuevo.
            const fecha = new Date();
            const dia = fecha.getDate();
            const mes = fecha.getMonth() + 1; // Se suma uno porque los meses comienzan en 0.
            const anio = fecha.getFullYear();

            const hora = fecha.getHours();
            const minutos = fecha.getMinutes().toLocaleString('es-ES', {minute: '2-digit'});

            const fecha_resultado = `${dia}/${mes}/${anio} ${hora}:${minutos}`;

            // Enviamos información al fichero PHP:
            var envio = new XMLHttpRequest();
            envio.onreadystatechange = function(){
                // Si se realiza de forma correcta, añadimos:
                if(envio.readyState === XMLHttpRequest.DONE){
                    if(envio.status === 200){
                        const nuevo = `
                            <div class="comentario">
                                <h2 class="autor"> ${nombre} </h2>
                                <h3 class="fechaHora"> ${fecha_resultado} </h3>
                                <p> ${comentario.replace(/(?:\r\n|\r|\n)/g, '<br>')} </p>
                            </div>
                        `;

                        document.getElementById("zona_comentarios").innerHTML += nuevo;
                        form.reset(); // Reseteamos el formulario para que se quede en blanco de nuevo.)
                    }
                    // En caso de error, enviamos una alerta:
                    else{
                        alert("No se ha podido guardar el comentario de forma correcta.");
                    }
                }
            };

            envio.open("POST", "../Controlador/añadir_comentarios.php", true);
            envio.setRequestHeader('Content-Type', 'application/json');
            envio.send(JSON.stringify({
                id_cientifico: idCientifico, 
                nombre: nombre,
                email: email,
                comentario: comentario,
                fecha: `${dia}/${mes}/${anio}`,
                hora: `${hora}:${minutos}`
            }));
        }
    });  
});

// --------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    var borrarBotones = document.querySelectorAll('[id^="borrar_comentario_"]');
    borrarBotones.forEach(function(boton) {
        boton.addEventListener('click', function() {
            var idComentario = this.dataset.id; // Obtener el id del comentario específico
    
            if (confirm("¿Estás seguro de que quieres borrar este comentario?")) {
                // Realizar la solicitud AJAX para borrar el comentario:
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../Controlador/eliminar_comentario.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // El comentario se borró exitosamente.
                            console.log("Comentario borrado.");
            
                            // Eliminar el comentario de la página:
                            var comentario = document.getElementById("comentario_" + idComentario);
                            if (comentario) {
                                comentario.parentNode.removeChild(comentario);
                            }
                        } 
                        else{
                            // Ocurrió un error al borrar el comentario.
                            console.error("Error al borrar el comentario.");
                        }
                    }
                };
        
                console.log(idComentario);
                xhr.send(JSON.stringify({
                    id_comentario: idComentario
                }));
            }
        });
    });
  
    var editarBotones = document.querySelectorAll('[id^="editar_comentario_"]');
    var formEdiciones = document.querySelectorAll('.form_edicion');
    
    // ---------------- EDITAR COMENTARIO ----------------
    editarBotones.forEach(function(boton, index) {
        boton.addEventListener('click', function() {
            var idComentario = boton.dataset.id;
            var formEdicion = formEdiciones[index];
            var textareaEdicion = formEdicion.querySelector('.textarea_edicion');
            var comentarioAnterior = boton.parentNode.parentNode.querySelector('p').innerText;
            textareaEdicion.value = comentarioAnterior;
            
            var comentarioContenedor = document.querySelector('#comentario_' + idComentario);
            formEdicion.classList.add('active'); // Agregamos la clase 'active' al formulario de edición.
            comentarioContenedor.classList.add('active'); // Agregamos la clase 'active' al contenedor del comentario.
        });
    });
  
    // ---------------- CANCELAR EDICIÓN ----------------
    var cancelarBotones = document.querySelectorAll('.btn_cancelar');
    cancelarBotones.forEach(function(boton) {
        boton.addEventListener('click', function() {
            var formEdicion = boton.parentNode;
            formEdicion.style.display = 'none';

            var comentarioContenedor = document.querySelector('#comentario_' + idComentario);
            form_edicion_comentario.classList.remove('active'); // Eliminamos la clase 'active' del formulario de edición.
            comentarioContenedor.classList.remove('active'); // Eliminamos la clase 'active' del contenedor del comentario.
        });
    });

    // ---------------- GUARDAR COMENTARIO ----------------
    var guardarBotones = document.querySelectorAll('.btn_guardar');
    guardarBotones.forEach(function(boton) {
        boton.addEventListener('click', function() {
            var idComentario = boton.parentNode.dataset.id;
            var idCientifico = boton.parentNode.dataset.otroId;
            var formEdicion = boton.parentNode;
            var textareaEdicion = formEdicion.querySelector('.textarea_edicion');
            var nuevoComentario = textareaEdicion.value;

            // Realizar la solicitud AJAX para guardar el comentario editado:
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../Controlador/editar_comentario.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // El comentario se editó exitosamente.
                        console.log("Comentario editado.");

                        // Actualizar el comentario en la página:
                        var comentario = document.getElementById("comentario_" + idComentario);
                        if (comentario) {
                            nuevoComentario += "  *Editado por el moderador";

                            comentario.querySelector('p').innerText = nuevoComentario;
                        }

                        // Ocultar el formulario de edición:
                        formEdicion.style.display = 'none';
                        window.location.href = "../Vista/cientificos.php?cientifico=" + idCientifico;

                    } else {
                        // Ocurrió un error al editar el comentario.
                        console.error("Error al editar el comentario.");
                    }
                }
            };

            xhr.send(JSON.stringify({
                id_comentario: idComentario,
                id_cientifico: idCientifico,
                nuevo_comentario: nuevoComentario
            }));
        });
    });
});  

    
// -------------------------------------------------------------------
// Queremos convertir el texto donde las frases están separadas por saltos de línea en una lista:

document.addEventListener('DOMContentLoaded', function() {
    var frasesContainer = document.getElementById('frases');
    var frases = frasesContainer.innerHTML.trim().split('<br>'); // Obtener frases separadas por '<br>'

    frasesContainer.innerHTML = ''; // Limpiar el contenedor

    frases.forEach(function(frase) {
        var li = document.createElement('li');
        li.textContent = frase.trim();
        frasesContainer.appendChild(li);
    });
});