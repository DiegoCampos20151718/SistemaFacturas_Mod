// Modularización del código
const codificacionSelect = document.getElementById('codificacion');
const yearSelect = document.getElementById('year');
const chart = document.getElementById('myChart');

// Función para cargar las codificaciones
function loadCodificaciones() {
  fetch('php/grafica/get-codificaciones.php')
    .then(response => response.json())
    .then(codificaciones => {
      codificacionSelect.innerHTML = '<option value="">Selecciona una opción</option>';
      codificaciones.forEach(codificacion => {
        const option = document.createElement('option');
        option.value = codificacion;
        option.text = codificacion;
        codificacionSelect.add(option);
      });
    })
    .catch(error => {
      console.error('Error al cargar las codificaciones:', error);
      Swal.fire('Error', 'Error al cargar las codificaciones', 'error');
    });
}

// Función para cargar los años
function loadYears() {
  const codificacion = codificacionSelect.value;
  if (!codificacion) {
    yearSelect.innerHTML = '<option value="">Selecciona una opción</option>';
    return;
  }

  fetch(`php/grafica/get-years.php?codificacion=${codificacion}`)
    .then(response => response.json())
    .then(years => {
      yearSelect.innerHTML = '<option value="">Selecciona una opción</option>';
      years.forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.text = year;
        yearSelect.add(option);
      });
    })
    .catch(error => {
      console.error('Error al cargar los años:', error);
      Swal.fire('Error', 'Error al cargar los años', 'error');
    });
}

// Función para cargar la gráfica
let myChart = null;

function loadChart() {
  const codificacion = codificacionSelect.value;
  const year = yearSelect.value;

  fetch(`php/grafica/bar-chart.php?codificacion=${codificacion}&year=${year}`)
    .then(response => response.json())
    .then(chartData => {
      if (chartData.length === 0) {
        chart.style.display = 'none';
        Swal.fire('Sin datos', 'No hay datos disponibles para la selección actual', 'info');
        return;
      }

      
      if (myChart) {
        myChart.destroy();
      }

      const labels = chartData.map(row => row.periodo);
      const disponibleAcumulado = chartData.map(row => Math.max(0, row.disponibleAcumulado));

      const chartConfig = {
        type: 'bar',
        data: {
          labels,
          datasets: [
            {
              label: 'Disponible acumulado',
              data: disponibleAcumulado,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      };

      myChart = new Chart(chart, chartConfig);
      chart.style.display = 'block';
    })
    .catch(error => {
      console.error('Error al cargar los datos del gráfico:', error);
      Swal.fire('Error', 'Error al cargar los datos del gráfico', 'error');
    });
}

codificacionSelect.addEventListener('change', loadYears);
yearSelect.addEventListener('change', loadChart);


loadCodificaciones();
