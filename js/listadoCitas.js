document.addEventListener('DOMContentLoaded', function() {
    const url = '../js/lecturaHorario.json';

    function crearTablaCitasEnEspera(citas) {
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
            btnAceptar.addEventListener('click', function() {
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
            btnCancelar.addEventListener('click', function() {
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

                fetch('../php/cancelarCita.php', {
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

            acciones.append(btnAceptar, btnCancelar);
            fila.append(acciones);

            tbody.append(fila);
        });

        tabla.append(tbody);
        contenedor.append(tabla);
    }

    function crearTablaCitasAceptadas(citas) {
        const contenedor = document.querySelector('.contenidoListadoAceptadas');
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

            const btnCancelar = document.createElement('button');
            btnCancelar.textContent = 'Cancelar';
            btnCancelar.addEventListener('click', function() {
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

                fetch('../php/cancelarCitaAceptada.php', {
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

            acciones.append(btnCancelar);
            fila.append(acciones);

            tbody.append(fila);
        });

        tabla.append(tbody);
        contenedor.append(tabla);
    }

    setTimeout(function() {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Datos cargados del JSON:', data); // Verifica los datos aquí
                const citasEnEspera = data.filter(cita => cita.estado === "espera");
                crearTablaCitasEnEspera(citasEnEspera);

                const citasAceptadas = data.filter(cita => cita.estado === "ocupado");
                crearTablaCitasAceptadas(citasAceptadas);
            })
            .catch(error => console.error('Error al cargar el archivo JSON:', error));
    }, 1000);
});
