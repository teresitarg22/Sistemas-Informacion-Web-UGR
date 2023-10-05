// ----------------------- ENCONTRAR CIENTÍFICOS -----------------------
document.addEventListener('DOMContentLoaded', function(){
    var inputBusqueda = document.getElementById('campo-busqueda');
    var listaCientificos = document.getElementById('lista-cientificos');
    var botonBuscar = document.getElementById('boton-buscar');

    botonBuscar.addEventListener('click', function() {
        var valorBusqueda = inputBusqueda.value.toLowerCase();
        var nombresCientificos = listaCientificos.getElementsByTagName('li');
    
        // Si el campo de búsqueda está vacío, no encuentra nada:
        if (valorBusqueda.trim() === '') {
            console.log('No se ha ingresado ningún término de búsqueda.');
            return;
        }

        // Recorre los científicos y busca cuáles coinciden:
        for (var i = 0; i < nombresCientificos.length; i++) {
            var nombreCientifico = nombresCientificos[i].innerText.toLowerCase();
        
            if (nombreCientifico.includes(valorBusqueda)) {
                nombresCientificos[i].classList.add('encontrado');
            } else {
                nombresCientificos[i].classList.remove('encontrado');
            }
        }
    });
});

// ----------------------- ENCONTRAR CIENTÍFICOS PORTADA -----------------------
document.addEventListener('DOMContentLoaded', function(){
    $(document).ready(function() {
        $('#campo-busqueda').on('input', function() {
            var query = $(this).val().trim();
            if (query !== '') {
                $.ajax({
                    url: '../Controlador/buscar_cientifico_portada.php',
                    method: 'POST',
                    data: { query: query },
                    dataType: 'json',
                    success: function(response) {
                        mostrarResultados(response);
                    },
                    error: function() {
                        mostrarResultados([]);
                    }
                });
            } else {
                mostrarResultados([]);
            }
        });

        $(document).on('click', function(event) {
            var resultados = $('#resultados');
            if (!resultados.is(event.target) && resultados.has(event.target).length === 0) {
                resultados.hide();
            }
        });
    });

    function mostrarResultados(resultados) {
        var listaResultados = $('#resultados');
        listaResultados.empty();
    
        if (resultados.length > 0) {
            for (var i = 0; i < resultados.length; i++) {
                var cientifico = resultados[i];
                var nombre = cientifico.nombre;
                var query = $('#campo-busqueda').val().trim();
                var nombreResaltado = resaltarTexto(nombre, query);
                var item = $('<li>').html('<a href="../Vista/cientificos.php?cientifico=' + cientifico.id + '">' + nombreResaltado + '</a>');
                listaResultados.append(item);
            }
        } else {
            var item = $('<li>').text('No se encontraron resultados');
            listaResultados.append(item);
        }
    
        listaResultados.show(); // Mostrar los resultados después de agregar los elementos
    }
    
    function resaltarTexto(texto, query) {
        var regex = new RegExp('(' + query + ')', 'gi');
        return texto.replace(regex, '<span class="resaltado">$1</span>');
    }    
});


// ----------------------- ENCONTRAR USUARIOS -----------------------
document.addEventListener('DOMContentLoaded', function(){
    var tablaUsuarios = document.getElementById('tabla-usuarios');

    // Función para ordenar la tabla de usuarios por el tipo de usuario = registrado, superusuario, moderador, gestor...
    ordenarTablaPorTipo();

    function ordenarTablaPorTipo() {
        // Obtiene todas las filas de la tabla excepto la primera (encabezado)
        var filasUsuarios = Array.from(tablaUsuarios.getElementsByTagName('tr')).slice(1);

        // Ordena las filas en función del tipo de usuario
        filasUsuarios.sort(function(a, b) {
            var tipoA = a.cells[2].innerText.toLowerCase();
            var tipoB = b.cells[2].innerText.toLowerCase();
            return tipoA.localeCompare(tipoB);
        });

        // Vuelve a insertar las filas ordenadas en la tabla
        filasUsuarios.forEach(function(fila) {
            tablaUsuarios.tBodies[0].appendChild(fila);
        });
    }

    // ------------------ BÚSQUEDA --------------------
    var inputBusqueda = document.getElementById('campo-busqueda');
    var tablaUsuarios = document.getElementById('tabla-usuarios');
    var botonBuscar = document.getElementById('boton-buscar');

    botonBuscar.addEventListener('click', function() {
        var valorBusqueda = inputBusqueda.value.toLowerCase();
        var filasUsuarios = tablaUsuarios.getElementsByTagName('tr');
    
        // Si el campo de búsqueda está vacío, no encuentra nada:
        if (valorBusqueda.trim() === '') {
            console.log('No se ha ingresado ningún término de búsqueda.');
            return;
        }

        // Recorre las filas de la tabla y busca cuáles coinciden:
        for (var i = 0; i < filasUsuarios.length; i++) {
            var nombreUsuario = filasUsuarios[i].innerText.toLowerCase();
        
            if (nombreUsuario.includes(valorBusqueda)) {
                filasUsuarios[i].classList.add('encontrado');
            } else {
                filasUsuarios[i].classList.remove('encontrado');
            }
        }
    });

    // ------------------ EDICIÓN DE ROLES ------------------
    var btnsDesplegable = document.querySelectorAll('.menu_despegable_btn');
    var menusDesplegable = document.querySelectorAll('.menu_despegable');

    btnsDesplegable.forEach(function(btn, index) {
        btn.addEventListener('click', function() {
            menusDesplegable[index].classList.toggle('mostrar');
        });
    });

    menusDesplegable.forEach(function(menu) {
        menu.addEventListener('click', function(event) {
            var opcionSeleccionada = event.target.innerText.trim();
            var fila = event.target.closest('tr');
            var nombreUsuario = fila.querySelector('.nombre-usuario').textContent;
    
            // Obtener el rol anterior del atributo de datos
            var rolAnterior = fila.querySelector('.tipo-usuario').getAttribute('data-original-rol');

            // Realizar la actualización del tipo de usuario en la base de datos
            // utilizando el nombre de usuario y la opción seleccionada
            var data = {
                usuario: nombreUsuario,
                tipo: opcionSeleccionada
            };
    
            // Realizar la solicitud HTTP POST al archivo PHP
            fetch('../Controlador/editar_rol_usuario.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(function(response) {
                if (response.ok) {
                    return response.text();
                } else {
                    throw new Error('Error al actualizar el tipo de usuario.');
                }
            })
            .then(function() {
                // Realizar la solicitud HTTP GET al archivo PHP que devuelve el JSON con el valor de count
                return fetch('../Controlador/obtener_superusuarios.php')
                    .then(function(response) {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('Error al obtener el recuento de superusuarios.');
                        }
                    });
            })
            .then(function(data) {
                var countSuperusuarios = data.count;
    
                if (rolAnterior == "superusuario" && countSuperusuarios == 1 && opcionSeleccionada != 'superusuario') {
                    alert('No se puede cambiar el rol del único usuario superusuario.');
                } 
                else {
                    // Actualizar el tipo de usuario en la página:
                    var tipoUsuarioElemento = fila.querySelector('.tipo-usuario');
                    tipoUsuarioElemento.textContent = opcionSeleccionada;
                }
            })
            .catch(function(error) {
                // Revertir el cambio en la ventana
                var tipoUsuarioElemento = fila.querySelector('.tipo-usuario');
                tipoUsuarioElemento.textContent = tipoUsuarioElemento.getAttribute('data-original-value');
            });
        });
    });       
});

