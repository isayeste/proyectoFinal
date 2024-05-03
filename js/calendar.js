// Variable global para almacenar la fecha actual
let fechaActual = new Date();
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
// Función para generar el calendario para una fecha dada
function generarCalendario(fecha) {
    // Obtener el elemento que contendrá el calendario
    const cuerpoCalendario = document.getElementById('calendario');
    const nombreMesElemento = document.getElementById('nombreMes');
    // Limpiar el contenido previo del calendario
    cuerpoCalendario.innerHTML = '';
    
    // Obtener el primer día del mes
    const primerDiaMes = new Date(fecha.getFullYear(), fecha.getMonth(), 1);
    // Obtener el último día del mes
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
    for (let i = 0; i < 6; i++) { // Se generan 6 filas para mostrar todos los posibles días del mes
        const fila = document.createElement('tr');
        for (let j = 0; j < 7; j++) { // Se generan 7 celdas para cada día de la semana
            const celda = document.createElement('td');
            // Establecer el número del día en la celda
            celda.textContent = fechaIteracion.getDate();
            // Estilo para los días que no pertenecen al mes actual
            if (fechaIteracion.getMonth() !== fecha.getMonth()) {
                celda.style.color = 'lightgray';
            }
            // Resaltar el día actual
            if (fechaIteracion.toDateString() === new Date().toDateString()) {
                celda.classList.add('seleccionado');
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
    nombreMesElemento.textContent = meses[fecha.getMonth()] + ' ' + fecha.getFullYear();
}
// Función para obtener el mes anterior
function mesAnterior() {
    fechaActual.setMonth(fechaActual.getMonth() - 1);
    generarCalendario(fechaActual);
}
// Función para obtener el mes siguiente
function mesSiguiente() {
    fechaActual.setMonth(fechaActual.getMonth() + 1);
    generarCalendario(fechaActual);
}
// Función para mostrar las horas de un día dado
function mostrarHoras(fecha) {
    const horasTabla = document.getElementById('horas');
    const cuerpoHoras = document.getElementById('horasCuerpo');
    cuerpoHoras.innerHTML = ''; // Limpiar contenido previo
    // Crear filas para las horas
    for (let i = 0; i < 24; i++) {
        const filaHora = document.createElement('tr');
        const celdaHora = document.createElement('td');
        celdaHora.textContent = `${i < 10 ? '0' + i : i}:00`;
        filaHora.append(celdaHora);
        cuerpoHoras.append(filaHora);
    }
    // Mostrar la tabla de horas
    horasTabla.style.display = 'block';
}