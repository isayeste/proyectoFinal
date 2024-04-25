// Función para generar el calendario
function generarCalendario() {
    // Obtener el elemento que contendrá el calendario
    const calendarBody = document.getElementById('calendar');
    calendarBody.innerHTML = ''; // Limpiar el contenido previo del calendario

    // Obtener la fecha actual
    const fechaActual = new Date();
    // Obtener el primer día del mes actual
    const primerDiaMes = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 1);
    // Obtener el último día del mes actual
    const ultimoDiaMes = new Date(fechaActual.getFullYear(), fechaActual.getMonth() + 1, 0);
    // Obtener el día de la semana del primer día del mes
    const primerDiaSemana = primerDiaMes.getDay();

    // Crear una fecha para iterar sobre los días del mes
    let fecha = new Date(primerDiaMes);
    fecha.setDate(fecha.getDate() - primerDiaSemana); // Ajustar para empezar desde el domingo

    // Generar las filas del calendario
    for (let i = 0; i < 6; i++) { // Se generan 6 filas para mostrar todos los posibles días del mes
        const row = document.createElement('tr');
        for (let j = 0; j < 7; j++) { // Se generan 7 celdas para cada día de la semana
            const cell = document.createElement('td');
            // Establecer el número del día en la celda
            cell.textContent = fecha.getDate();
            // Estilo para los días que no pertenecen al mes actual
            if (fecha.getMonth() != primerDiaMes.getMonth()) {
                cell.style.color = 'lightgray';
            }
            // Resaltar el día actual
            if (fecha.toDateString() === fechaActual.toDateString()) {
                cell.classList.add('selected');
            }
            // Manejar el evento click para seleccionar la fecha
            cell.addEventListener('click', function() {
                const selectedCells = document.querySelectorAll('.selected');
                for (let k = 0; k < selectedCells.length; k++) {
                    selectedCells[k].classList.remove('selected');
                }
                this.classList.add('selected');
            });
            // Añadir la celda a la fila
            row.append(cell);
            // Avanzar al siguiente día
            fecha.setDate(fecha.getDate() + 1);
        }
        // Añadir la fila al cuerpo del calendario
        calendarBody.append(row);
    }

    // Obtener los eventos del Google Calendar y señalarlos en el calendario
    obtenerEventosGoogleCalendar();
}

// Función para obtener los eventos del Google Calendar y señalarlos en el calendario
function obtenerEventosGoogleCalendar() {
    // Realizar una solicitud AJAX para obtener los eventos del Google Calendar
    fetch('../php/obtenerEventosGoogleCalendar.php')
    .then(response => response.json())
    .then(data => {
        señalarEventosEnCalendario(data);
    })
    .catch(error => {
        console.error('Error al obtener los eventos:', error);
    });
}

// Función para señalar los eventos del Google Calendar en el calendario
function señalarEventosEnCalendario(eventos) {
    eventos.forEach(function(evento) {
        const diaEvento = new Date(evento.start);
        const celdas = document.querySelectorAll('td');
        celdas.forEach(function(celda) {
            // Verificar si la celda corresponde al día del evento
            if (parseInt(celda.textContent) === diaEvento.getDate() &&
                celda.parentNode.rowIndex - 1 === diaEvento.getDay()) {
                celda.classList.add('evento');
            }
        });
    });
}

// Llamar a la función para generar el calendario al cargar la página
window.addEventListener('load', generarCalendario);
