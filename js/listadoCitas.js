// Este evento se dispara cuando el DOM ha sido completamente cargado y parseado
document.addEventListener('DOMContentLoaded', function() {
    // Selecciona todos los elementos con la clase 'btnAceptar' y los almacena en btnAceptar
    const btnAceptar = document.querySelectorAll('.btnAceptar');
    // Selecciona todos los elementos con la clase 'btnCancelarEspera' y los almacena en btnCancelarEspera
    const btnCancelarEspera = document.querySelectorAll('.btnCancelarEspera');
    // Selecciona todos los elementos con la clase 'btnCancelarOcupado' y los almacena en btnCancelarOcupado
    const btnCancelarOcupado = document.querySelectorAll('.btnCancelarOcupado');

    // Itera sobre cada botón de aceptar
    btnAceptar.forEach(btn => {
        // Añade un event listener para el evento 'click' a cada botón de aceptar
        btn.addEventListener('click', function() {
            const idHorario = this.dataset.idhorario;
            console.log(idHorario);
            // Obtiene los datos de la cita desde el atributo 'data-cita' del elemento padre del botón
            //const cita = this.parentElement.parentElement.dataset.cita;
            //console.dir(cita);
            // Llama a la función 'aceptarCita' pasando los datos de la cita como argumento
            //console.log('ID del horario asociado al botón Aceptar:', idHorario);
            aceptarCita(idHorario);
        });
    });

    // Itera sobre cada botón de cancelar espera
    btnCancelarEspera.forEach(btn => {
        // Añade un event listener para el evento 'click' a cada botón de cancelar espera
        btn.addEventListener('click', function() {
            const idHorario = this.dataset.idhorario;
            console.log('ID del horario asociado al botón Cancelar Espera:', idHorario);
            cancelarCitaEspera(idHorario);
        });
    });

    // Itera sobre cada botón de cancelar ocupado
    btnCancelarOcupado.forEach(btn => {
        // Añade un event listener para el evento 'click' a cada botón de cancelar ocupado
        btn.addEventListener('click', function() {
            const idHorario = this.dataset.idhorario; 
            console.log('ID del horario asociado al botón Cancelar Ocupado:', idHorario);
            cancelarCitaOcupado(idHorario);
        });
    });

    // Función para enviar una solicitud al servidor para aceptar una cita
    function aceptarCita(cita) {
        //console.log("cita aceptada");
        fetch('../php/aceptarCita.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(cita)
        })
        .then(response => response.text())
        .then(data => {
            // Muestra la respuesta del servidor en la consola
            console.log('Respuesta del servidor:', data);
            // Recarga la página después de aceptar la cita
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

    // Función para enviar una solicitud al servidor para cancelar una cita en espera
    function cancelarCitaEspera(cita) {
        fetch('../php/cancelarCita.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(cita)
        })
        .then(response => response.text())
        .then(data => {
            // Muestra la respuesta del servidor en la consola
            console.log('Respuesta del servidor:', data);
            // Recarga la página después de cancelar la cita en espera
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

    // Función para enviar una solicitud al servidor para cancelar una cita ocupada
    function cancelarCitaOcupado(cita) {
        fetch('../php/cancelarCitaAceptada.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(cita)
        })
        .then(response => response.text())
        .then(data => {
            // Muestra la respuesta del servidor en la consola
            console.log('Respuesta del servidor:', data);
            // Recarga la página después de cancelar la cita ocupada
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }
});
