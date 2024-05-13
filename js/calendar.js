// Variable global para almacenar la fecha actual
let fechaActual = new Date();

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

    // Variable para almacenar las fechas obtenidas
    let fechasBD;

    // Obtener fechas de la base de datos -> asíncronía para que de tiempo a que cargue
    obtenerFechas().then(function(fechas) {
        // Almacenar las fechas obtenidas
        fechasBD = fechas;

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
                if(fechasBD.includes(fechaC)){
                    //console.log('holilla');
                    celda.style.backgroundColor = '#98FB98';
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
            const fechas = horarios.map(horario => horario.fechaInicio.split(' ')[0]);
            // Eliminar duplicados
            const fechasUnicas = fechas.filter((fecha, index) => fechas.indexOf(fecha) === index);
            return fechasUnicas;
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
            // VOY POR AQUÍ!!!!!!!!!!!!!
            console.log(fecha);
        }
    });
});
