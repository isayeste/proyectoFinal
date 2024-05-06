// Variable global para almacenar la fecha actual
let fechaActual = new Date();
// ---------------------------------------------------------------------
function generarCalendario(fecha) {
    const cuerpoCalendario = document.getElementById('calendario');
    const nombreMesElemento = document.getElementById('nombreMes');
    cuerpoCalendario.innerHTML = '';
    
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
            const celda = document.createElement('td');
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

    
    
    


}

function mesAnterior() {
    fechaActual.setMonth(fechaActual.getMonth() - 1);
    generarCalendario(fechaActual);
}

function mesSiguiente() {
    fechaActual.setMonth(fechaActual.getMonth() + 1);
    generarCalendario(fechaActual);
}


// ---------------------------------------------------------------------

function obtenerFechas(){
    console.log("holi");
    fetch('./lecturaHorario.json')
    .then(respuesta => respuesta.json())
    .then(horarios => {
        // Obtener fechas únicas
        const fechas = [...new Set(horarios.map(horario => horario.fechaInicio.split(' ')[0]))];
        // Actualizar los elementos en el DOM con los valores calculados
        document.getElementById('fechas').textContent = fechas.join(', ');
    })
    .catch(console.error);
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
            mostrarHoras(fecha);
        }
    });
});

