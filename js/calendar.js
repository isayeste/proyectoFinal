// Variable global para almacenar la fecha actual
let fechaActual = new Date();

// Función para obtener la fecha en formato "YYYY-MM-DD"
function obtenerFechaFormateada(fecha) {
    const dia = fecha.getDate();
    const mes = fecha.getMonth() + 1;
    const anio = fecha.getFullYear();
    return `${anio}-${mes < 10 ? '0' + mes : mes}-${dia < 10 ? '0' + dia : dia}`;
}

function generarCalendario(fecha) {
    const cuerpoCalendario = document.getElementById('calendario');
    const nombreMesElemento = document.getElementById('nombreMes');
    cuerpoCalendario.innerHTML = '';

    // Variable local para almacenar las fechas del JSON
    let fechasBD;

    // Llamada a la función para obtener las fechas
    obtenerFechas().then(fechas => {
        // Almacenar las fechas en la variable local
        fechasBD = fechas;

        // Obtener el primer y último día del mes
        const primerDiaMes = new Date(fecha.getFullYear(), fecha.getMonth(), 1);
        const ultimoDiaMes = new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0);

        // Obtener el día de la semana del primer día del mes (0 = domingo, 1 = lunes, ..., 6 = sábado)
        let primerDiaSemana = primerDiaMes.getDay();
        // Ajustar para que el lunes sea el primer día (1 = lunes, ..., 7 = domingo)
        primerDiaSemana = (primerDiaSemana === 0) ? 7 : primerDiaSemana;
        // Crear una fila para los nombres de los días de la semana
        const filaDiasSemana = document.createElement('tr');
        const nombresDiasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        nombresDiasSemana.forEach(nombreDia => {
            const celdaDiaSemana = document.createElement('th');
            celdaDiaSemana.textContent = nombreDia;
            filaDiasSemana.append(celdaDiaSemana);
        });
        cuerpoCalendario.append(filaDiasSemana);

        // Crear una fecha para iterar sobre los días del mes
        let fechaIteracion = new Date(primerDiaMes);
        
        fechaIteracion.setDate(fechaIteracion.getDate() - (primerDiaSemana - 1)); // Ajustar para empezar desde el lunes
        // Generar las filas del calendario
        for (let i = 0; i < 6; i++) {
            const fila = document.createElement('tr');
            for (let j = 0; j < 7; j++) {
                const diaC = fechaIteracion.getDate();
                const mesC = fechaIteracion.getMonth();
                const anioC = fechaIteracion.getFullYear();
                const fechaC = obtenerFechaFormateada(fechaIteracion);

                const celda = document.createElement('td');
                // Comparar la fecha formateada con las fechas del JSON
                if(fechasBD.includes(fechaC)){
                    console.log('holilla');
                    celda.style.backgroundColor = '#98FB98';
                }

                
                // Establecer el número del día en la celda
                celda.textContent = fechaIteracion.getDate();
                // Estilo para los días que no pertenecen al mes actual
                if (fechaIteracion.getMonth() !== primerDiaMes.getMonth()) {
                    celda.style.color = '#D3D3D3';
                }

                // Añadir la celda a la fila
                fila.append(celda);
                // Avanzar al siguiente día
                fechaIteracion.setDate(fechaIteracion.getDate() + 1);
            }
            // Añadir la fila al cuerpo del calendario
            cuerpoCalendario.append(fila);
        }
        // Mostrar el nombre del mes
        const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        nombreMesElemento.textContent = meses[fecha.getMonth()] + ' ' + primerDiaMes.getFullYear();
    }).catch(error => {
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
            // Eliminar duplicados manteniendo el orden original
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
