document.addEventListener('DOMContentLoaded', function() {
    const url = '../js/listadoCitas.json';

    function crearTablaCitas(citas) {
        const contenedor = document.querySelector('.contenidoListado');
        const tabla = document.createElement('table');
        tabla.classList.add('tablaCitas');

        // Crear el thead de la tabla
        const thead = document.createElement('thead');
        const encabezadoFila = document.createElement('tr');

        const encabezados = ['Fecha Inicio', 'Fecha Fin', 'Nombre', 'Email', 'Motivo Consulta', 'Vía', 'Acciones'];
        encabezados.forEach(texto => {
            const th = document.createElement('th');
            th.textContent = texto;
            encabezadoFila.append(th);
        });

        thead.append(encabezadoFila);
        tabla.append(thead);

        // Crear el body de la tabla
        const tbody = document.createElement('tbody');

        // Iterar sobre cada cita y crear una fila en la tabla para cada una -> añadir info de cada una
        citas.forEach(cita => {
            const fila = document.createElement('tr');

            const fechaInicio = document.createElement('td');
            fechaInicio.textContent = cita.fechaInicio;
            fila.append(fechaInicio);

            const fechaFin = document.createElement('td');
            fechaFin.textContent = cita.fechaFin;
            fila.append(fechaFin);

            const nombre = document.createElement('td');
            nombre.textContent = cita.nombre;
            fila.append(nombre);

            const email = document.createElement('td');
            email.textContent = cita.emailPaciente;
            fila.append(email);

            const motivoConsulta = document.createElement('td');
            motivoConsulta.textContent = cita.motivo;
            fila.append(motivoConsulta);

            const via = document.createElement('td');
            via.textContent = cita.via;
            fila.append(via);

            const acciones = document.createElement('td');

            const btnAceptar = document.createElement('button');
            btnAceptar.textContent = 'Aceptar';
            // Agregar un event listener para el botón de Aceptar
            btnAceptar.addEventListener('click', function() {
                // ACEPTAR LA CITA
                const citaData = {
                    idCita: cita.idCita,
                    idHorario: cita.idHorario, 
                    fechaInicio: cita.fechaInicio,
                    fechaFin: cita.fechaFin,
                    nombre: cita.nombre,
                    emailPaciente: cita.emailPaciente,
                    motivo: cita.motivo,
                    via: cita.via
                };

                //console.log('Datos a enviar:', citaData); //BORRAR ESTO

                // Enviar la información de la cita al archivo PHP mediante una solicitud HTTP POST
                fetch('../php/aceptarCita.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(citaData)
                })
                .then(response => response.text()) 
                .then(data => {
                    console.log('Respuesta del servidor:', data);
                })
                .catch(error => console.error('Error:', error));
            });

            const btnCancelar = document.createElement('button');
            btnCancelar.textContent = 'Cancelar';
            // Agregar un event listener para el botón de Cancelar
            btnCancelar.addEventListener('click', function() {
                // CANCELAR LA CITA
                console.log('Cita cancelada');
                //----------------------------------
                const citaData = {
                    idCita: cita.idCita,
                    idHorario: cita.idHorario,
                    fechaInicio: cita.fechaInicio,
                    fechaFin: cita.fechaFin,
                    nombre: cita.nombre,
                    emailPaciente: cita.emailPaciente,
                    motivo: cita.motivo,
                    via: cita.via
                };

                //console.log('Datos a enviar:', citaData); //BORRAR ESTO

                // Enviar la información de la cita al archivo PHP mediante una solicitud HTTP POST
                fetch('../php/cancelarCita.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(citaData)
                })
                .then(response => response.text())  // Cambiar a .text() para ver la respuesta como texto
                .then(data => {
                    console.log('Respuesta del servidor:', data);
                })
                .catch(error => console.error('Error:', error));

                //AQUI
            });

            acciones.append(btnAceptar, btnCancelar);
            fila.append(acciones);

            tbody.append(fila);
        });

        tabla.append(tbody);
        contenedor.append(tabla);
    }

    // Fetch los datos del archivo JSON y llamar a la función crearTablaCitas
    fetch(url)
        .then(response => response.json())
        .then(data => crearTablaCitas(data))
        .catch(error => console.error('Error al cargar el archivo JSON:', error));
});
