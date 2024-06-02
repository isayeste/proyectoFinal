document.addEventListener('DOMContentLoaded', function () {
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
                fetch('actualizarEstado.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ idHorario: cita.idHorario })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        console.log(data.message);
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
