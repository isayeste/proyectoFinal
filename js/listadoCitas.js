document.addEventListener('DOMContentLoaded', function() {
    const url = '../js/listadoCitas.json';

    function crearTablaCitas(citas) {
        const contenedor = document.querySelector('.contenidoListado');
        const tabla = document.createElement('table');
        tabla.classList.add('tablaCitas');

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

                fetch('../php/citaOcupado.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ idHorario: cita.idHorario })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === "Estado actualizado correctamente") {
                        fetch('../php/crearEventoGoogleCalendar.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                cita: cita,
                                token: token
                            })
                        })
                        .then(response => response.json())
                        .then(eventData => {
                            if (eventData.success) {
                                alert('Evento creado en Google Calendar');
                            } else {
                                alert('Error al crear el evento en Google Calendar');
                            }
                        })
                        .catch(error => console.error('Error al crear el evento:', error));
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

    fetch(url)
        .then(response => response.json())
        .then(data => crearTablaCitas(data))
        .catch(error => console.error('Error al cargar el archivo JSON:', error));
});
