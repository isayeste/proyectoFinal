document.addEventListener('DOMContentLoaded', function() {
  const calendarEl = document.getElementById('calendario');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: ['dayGrid'], // Usa el plugin dayGrid para mostrar la vista de día
    defaultView: 'dayGridMonth', // Vista predeterminada del calendario
    locale: 'es', // Configura el idioma español
    events: {
      url: 'calendario.php', // URL del script PHP para obtener eventos de Google Calendar
      method: 'GET', // Método HTTP para la solicitud AJAX
      failure: function() {
        alert('Hubo un error al cargar los eventos del calendario.');
      }
    }
  });

  calendar.render(); // Renderiza el calendario
});
