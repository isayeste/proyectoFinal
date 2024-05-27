// Variable global para almacenar la fecha actual
let fechaActual = new Date();
let horariosCompleto = []; // Variable global para almacenar todos los horarios

// Función para obtener la fecha en formato "YYYY-MM-DD"
function obtenerFechaFormateada(fecha) {
    let dia = fecha.getDate();
    let mes = fecha.getMonth() + 1;
    let anio = fecha.getFullYear();
    if (mes < 10) {
        mes = '0' + mes;
    }
    if (dia < 10) {
        dia = '0' + dia;
    }
    return anio + '-' + mes + '-' + dia;
}

function generarCalendario(fecha) {
    // Obtener referencia al elemento del calendario y al elemento del nombre del mes
    let cuerpoCalendario = document.getElementById('calendario');
    let nombreMesElemento = document.getElementById('nombreMes');
    cuerpoCalendario.innerHTML = '';

    // Obtener fechas de la base de datos -> asíncronía para que de tiempo a que cargue
    obtenerFechas().then(function(fechas) {
        // Almacenar las fechas obtenidas
        horariosCompleto = fechas;
        let fechasBD = fechas.map(horario => horario.fecha);

        // Calcular el primer y último día del mes
        let primerDiaMes = new Date(fecha.getFullYear(), fecha.getMonth(), 1);
        let ultimoDiaMes = new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0);

        // Obtener el día de la semana del primer día del mes
        let primerDiaSemana = primerDiaMes.getDay();
        // Ajustar para que el lunes sea el primer día
        if (primerDiaSemana === 0) {
            primerDiaSemana = 7;
        }

        // Crear fila para los nombres de los días de la semana
        let filaDiasSemana = document.createElement('tr');
        let nombresDiasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        nombresDiasSemana.forEach(function(nombreDia) {
            let celdaDiaSemana = document.createElement('th');
            celdaDiaSemana.textContent = nombreDia;
            filaDiasSemana.append(celdaDiaSemana);
        });
        cuerpoCalendario.append(filaDiasSemana);

        // Crear fecha para iterar sobre los días del mes
        let fechaIteracion = new Date(primerDiaMes);
        fechaIteracion.setDate(fechaIteracion.getDate() - (primerDiaSemana - 1));
        // Generar las filas del calendario
        for (let i = 0; i < 6; i++) {
            let fila = document.createElement('tr');
            for (let j = 0; j < 7; j++) {
                let diaC = fechaIteracion.getDate();
                let mesC = fechaIteracion.getMonth();
                let anioC = fechaIteracion.getFullYear();
                let fechaC = obtenerFechaFormateada(fechaIteracion);

                let celda = document.createElement('td');
                // Comparar la fecha formateada con las fechas obtenidas de la base de datos -> colorear
                if (fechasBD.includes(fechaC)) {
                    celda.style.backgroundColor = '#acf2d4';
                }

                // Establecer el número del día en la celda
                celda.textContent = fechaIteracion.getDate();
                // Estilo para los días que no pertenecen al mes actual
                if (fechaIteracion.getMonth() !== primerDiaMes.getMonth()) {
                    celda.style.color = '#D3D3D3';
                }

                fila.append(celda);
                fechaIteracion.setDate(fechaIteracion.getDate() + 1);
            }
            cuerpoCalendario.append(fila);
        }
        // Mostrar el nombre del mes
        let meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        let nombreMes = meses[fecha.getMonth()];
        let anio = primerDiaMes.getFullYear();
        nombreMesElemento.textContent = nombreMes + ' ' + anio;
    }).catch(function(error) {
        console.error(error);
    });
}

function mesAnterior() {
    fechaActual.setMonth(fechaActual.getMonth() - 1);
    generarCalendario(fechaActual);
}

function mesSiguiente() {
    fechaActual.setMonth(fechaActual.getMonth() + 1);
    generarCalendario(fechaActual);
}

function obtenerFechas() {
    return fetch('../js/lecturaHorario.json')
        .then(respuesta => respuesta.json())
        .then(horarios => {
            const fechasHorarios = horarios.map(horario => ({
                fecha: horario.fechaInicio.split(' ')[0],
                hora: horario.fechaInicio.split(' ')[1].slice(0, 5), // Obtener sólo la hora y minuto
                estado: horario.estado
            }));
            return fechasHorarios;
        })
        .catch(error => {
            console.error(error);
            return []; // Si da error -> array vacío
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const btnAnterior = document.getElementById('anterior');
    const btnSiguiente = document.getElementById('siguiente');
    const cuerpoCalendario = document.getElementById('calendario');
    const tablaHorasDisponibles = document.getElementById('horasDisponibles');
    const modal = document.getElementById('myModal');
    const modalText = document.getElementById('modalText');
    const acceptButton = document.getElementById('acceptButton');
    const cancelButton = document.getElementById('cancelButton');

    btnAnterior.addEventListener('click', mesAnterior);
    btnSiguiente.addEventListener('click', mesSiguiente);
    // Inicializar el calendario con el mes actual
    generarCalendario(fechaActual);

    // Delegación de eventos para las celdas del calendario
    cuerpoCalendario.addEventListener('click', function(event) {
        const target = event.target;
        if (target.tagName === 'TD') {
            const dia = parseInt(target.textContent);
            const fecha = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), dia);
            const fechaFormateada = obtenerFechaFormateada(fecha);

            // Filtrar horarios para la fecha seleccionada
            const horariosSeleccionados = horariosCompleto.filter(horario => horario.fecha === fechaFormateada && horario.estado === 'libre');
            const horasDisponibles = horariosSeleccionados.map(horario => horario.hora);

            // Limpiar la tabla de horas disponibles
            tablaHorasDisponibles.innerHTML = '';

            // Imprimir horas disponibles en la consola y añadir filas a la tabla
            console.log(`Horas disponibles para ${fechaFormateada}:`, horasDisponibles);
            horasDisponibles.forEach(hora => {
                let fila = document.createElement('tr');
                let celda = document.createElement('td');
                celda.textContent = hora;
                fila.append(celda);
                tablaHorasDisponibles.append(fila);
            });
        }
    });

    // Delegación de eventos para las filas de la tabla de horas disponibles
    tablaHorasDisponibles.addEventListener('click', function(event) {
        const target = event.target;
        if (target.tagName === 'TD') {
            const time = target.textContent;
            console.log(`mostraste la hora: ${time}`);
            
            // Mostrar el modal con el time
            modal.style.display = "block";
            modalText.textContent = `mostraste la hora: ${time}`;

            // Acción al hacer clic en Aceptar
            acceptButton.onclick = function() {
                console.log(`Aceptar: ${time}`);
                modal.style.display = "none";
            }

            // Acción al hacer clic en Cancelar
            cancelButton.onclick = function() {
                console.log(`Cancelar: ${time}`);
                modal.style.display = "none";
            }

            // Acción al hacer clic fuera del modal
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
    });
});
