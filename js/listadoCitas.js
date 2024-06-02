document.addEventListener('DOMContentLoaded', function() {
    // Ruta del archivo JSON
    const url = '../js/listadoCitas.json';

    // Función para crear la tabla de citas
    function crearTablaCitas(citas) {
        const contenedor = document.querySelector('.contenidoListado');
        const tabla = document.createElement('table');
        tabla.classList.add('tablaCitas');

        // Crear el encabezado de la tabla
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

        // Crear el cuerpo de la tabla
        const tbody = document.createElement('tbody');
        
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
            btnAceptar.addEventListener('click', function () {
                const token = localStorage.getItem('googleAccessToken');
                if (!token) {
                    console.log('Inicia sesión primero');
                    return;
                }

                fetch('../php/citaGoogleCalendarOcupado.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ idHorario: cita.idHorario })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === "Estado actualizado correctamente") {
                        crearEventoGoogleCalendar(cita, token);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error al actualizar el estado:', error));
            });

            const btnCancelar = document.createElement('button');
            btnCancelar.textContent = 'Cancelar';
            btnCancelar.addEventListener('click', function () {
                // DENEGAR LA CITA
            });

            acciones.append(btnAceptar, btnCancelar);
            fila.append(acciones);

            tbody.append(fila);
        });

        tabla.append(tbody);
        contenedor.append(tabla);
    }

    // Leer el archivo JSON
    fetch(url)
        .then(response => response.json())
        .then(data => crearTablaCitas(data))
        .catch(error => console.error('Error al cargar el archivo JSON:', error));
});

// function crearEventoGoogleCalendar(cita, token) {
//     const event = {
//         summary: 'Cita con ' + cita.nombre,
//         start: {
//             dateTime: cita.fechaInicio,
//             timeZone: 'Europe/Madrid'
//         },
//         end: {
//             dateTime: cita.fechaFin,
//             timeZone: 'Europe/Madrid'
//         },
//         description: cita.motivo,
//         attendees: [{ email: cita.emailPaciente }],
//         reminders: {
//             useDefault: false,
//             overrides: [
//                 { method: 'email', minutes: 24 * 60 },
//                 { method: 'popup', minutes: 10 }
//             ]
//         }
//     };

//     fetch('https://www.googleapis.com/calendar/v3/calendars/primary/events', {
//         method: 'POST',
//         headers: {
//             'Authorization': `Bearer ${token}`,
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify(event)
//     })
//     .then(response => response.json())
//     .then(event => {
//         console.log('Evento creado: ' + event.htmlLink);
//         alert('Evento creado en Google Calendar');
//     })
//     .catch(error => console.error('Error al crear el evento:', error));
// }
