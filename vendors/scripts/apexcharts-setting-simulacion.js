document.addEventListener('DOMContentLoaded', function() {
  // Obtener los valores de los elementos
  let valorCapitalTexto = document.getElementById('valor-capital').innerText;
  let valorIndustriaTexto = document.getElementById('valor-industria').innerText;

  // Función para limpiar la cadena y convertirla en número entero
  function convertirANumero(valor) {
      return parseInt(valor.replace(/[\$,.\s]/g, ''));
  }

  // Convertir los valores a números enteros
  let valorCapital = convertirANumero(valorCapitalTexto);
  let valorIndustria = convertirANumero(valorIndustriaTexto);
  
  // Paso 2: Leer desde el localStorage en JavaScript
  var usuariosCapital = localStorage.getItem('usuariosCapital');
  var usuariosIndustria = localStorage.getItem('usuariosIndustria');
  var inversionTipo1 = parseInt(localStorage.getItem('inversionTipo1'));
  var inversionTipo2 = parseInt(localStorage.getItem('inversionTipo2'));
  var inversionTipo3 = parseInt(localStorage.getItem('inversionTipo3'));
  let total = inversionTipo1 + inversionTipo2 + inversionTipo3;
console.log(`Total: ${total}`);

// Calcular los porcentajes
 inversionTipo1= Math.trunc((inversionTipo1 / total) * 100);
 inversionTipo2 = Math.trunc((inversionTipo2 / total) * 100);
 inversionTipo3 = Math.trunc((inversionTipo3 / total) * 100);





  console.log(usuariosCapital, usuariosIndustria);
  console.log(inversionTipo1, inversionTipo2, inversionTipo3);

  // Configuración del gráfico de pastel (Pie Chart)
  const tbody = document.querySelector('#data-table-body');
    const rows = tbody.querySelectorAll('tr');

    // Inicializar arrays para los datos
    let data = [];
    let labels = [];

    // Recorrer las filas para extraer los porcentajes y las etiquetas
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const porcentaje = parseFloat(cells[4].textContent.replace(',', '.').replace('%', '')); // Extraer y limpiar porcentaje
        const nombre = cells[0].textContent;

        if (!isNaN(porcentaje)) {
            labels.push(nombre);
            data.push(porcentaje);
        }
    });
  var options = {
    series: [{
    data: data
  }],
    chart: {
    height: 350,
    type: 'bar',
    events: {
      click: function(chart, w, e) {
        // console.log(chart, w, e)
      }
    }
  },
  plotOptions: {
    bar: {
      columnWidth: '45%',
      distributed: true,
    }
  },
  dataLabels: {
    enabled: false
  },
  legend: {
    show: false
  },
  xaxis: {
    categories: labels,
    labels: {
      style: {
        fontSize: '12px'
      }
    }
  }
  };

  var chart = new ApexCharts(document.querySelector("#barras"), options);
  chart.render();



  var options1 = {
      series: [valorCapital, valorIndustria],
      chart: {
          width: 380,
          type: 'pie',
      },
      colors: ['#4472C4', '#954F72'],
      labels: ['Aportes Capital', 'Aportes Industria'],
      responsive: [{
          breakpoint: 480,
          options: {
              chart: {
                  width: 150
              },
              legend: {
                  position: 'bottom'
              }
          }
      }]
  };
  var chart1 = new ApexCharts(document.querySelector("#chart8Valor"), options1);
  chart1.render();

  // Configuración del gráfico de líneas (Line Chart)
  var options = {
      series: [
          {
              name: "Capital",
              data: Array(12).fill(valorCapital) // Crear un array con el mismo valor para cada mes
          },
          {
              name: "Industria",
              data: Array(12).fill(valorIndustria) // Crear un array con el mismo valor para cada mes
          }
      ],
      chart: {
          height: 350,
          type: 'line',
          dropShadow: {
              enabled: true,
              color: '#000',
              top: 18,
              left: 7,
              blur: 10,
              opacity: 0.2
          },
          zoom: {
              enabled: false
          },
          toolbar: {
              show: false
          }
      },
      colors: ['#77B6EA', '#545454'],
      dataLabels: {
          enabled: true,
      },
      stroke: {
          curve: 'smooth'
      },
      title: {
          text: 'Cantidad de inversiones tipo x mes',
          align: 'left'
      },
      grid: {
          borderColor: '#e7e7e7',
          row: {
              colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
              opacity: 0.5
          },
      },
      markers: {
          size: 1
      },
      xaxis: {
          categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
          title: {
              text: 'Meses'
          }
      },
      yaxis: {
          title: {
              text: 'Cantidad'
          },
          min: 5000000,
          max: 10000000
      },
      legend: {
          position: 'top',
          horizontalAlign: 'right',
          floating: true,
          offsetY: -25,
          offsetX: -5
      }
  };
  var chart2 = new ApexCharts(document.querySelector("#TimeLine"), options);
  chart2.render();

  // Configuración del gráfico de área polar (Polar Area Chart)
  var options2 = {
      series:  [inversionTipo1, inversionTipo2, inversionTipo3],
      chart: {
          width: 370,
          type: 'polarArea',
      },
      stroke: {
          colors: ['#fff']
      },
      fill: {
          opacity: 0.8
      },
      colors: ['#607D8B', '#FFC107', '#4CAF50'],
      labels: ['Dinero', 'Especie', 'Industria'],
      responsive: [{
          breakpoint: 480,
          options: {
              chart: {
                  width: 100
              },
              legend: {
                  position: 'bottom'
              }
          }
      }]
  };
  var chart3 = new ApexCharts(document.querySelector("#chart8Tipos"), options2);
  chart3.render();

  // Configuración del gráfico radial (Radial Bar Chart)
  var options9 = {
      series: [usuariosCapital, usuariosIndustria],
      chart: {
          height: 250,
          type: 'radialBar',
      },
      plotOptions: {
          radialBar: {
              offsetY: 0,
              startAngle: 0,
              endAngle: 270,
              hollow: {
                  margin: 5,
                  size: '40%',
                  background: 'transparent',
                  image: undefined,
              },
              dataLabels: {
                  name: {
                      show: false,
                  },
                  value: {
                      show: false,
                  }
              }
          }
      },
      colors: ['#1ab7ea', '#0084ff'],
      labels: ['Capital', 'Industria'],
      legend: {
          show: true,
          floating: true,
          fontSize: '14px',
          position: 'left',
          offsetX: 40,
          offsetY: 15,
          labels: {
              useSeriesColors: true,
          },
          markers: {
              size: 0
          },
          formatter: function(seriesName, opts) {
              return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
          },
          itemMargin: {
              vertical: 3
          }
      },
      responsive: [{
          breakpoint: 480,
          options: {
              legend: {
                  show: false
              }
          }
      }]
  };
  var chart4 = new ApexCharts(document.querySelector("#chart9"), options9);
  chart4.render();
  var options = {
    series: [3],
    chart: {
    height: 300,
    type: 'radialBar',
    
  },
  plotOptions: {
    radialBar: {
      startAngle: -135,
      endAngle: 225,
       hollow: {
        margin: 0,
        size: '70%',
        background: '#fff',
        image: undefined,
        imageOffsetX: 0,
        imageOffsetY: 0,
        position: 'front',
        dropShadow: {
          enabled: true,
          top: 3,
          left: 0,
          blur: 4,
          opacity: 0.24
        }
      },
      track: {
        background: '#fff',
        strokeWidth: '67%',
        margin: 0, // margin is in pixels
        dropShadow: {
          enabled: true,
          top: -3,
          left: 0,
          blur: 4,
          opacity: 0.35
        }
      },
  
      dataLabels: {
        show: true,
        name: {
          offsetY: -10,
          show: true,
          color: '#888',
          fontSize: '17px'
        },
        value: {
          formatter: function(val) {
            return parseInt(val);
          },
          color: '#111',
          fontSize: '36px',
          show: true,
        }
      }
    }
  },
  fill: {
    type: 'gradient',
    gradient: {
      shade: 'dark',
      type: 'horizontal',
      shadeIntensity: 0.5,
      gradientToColors: ['#ABE5A1'],
      inverseColors: true,
      opacityFrom: 1,
      opacityTo: 1,
      stops: [0, 100]
    }
  },
  stroke: {
    lineCap: 'round'
  },
  labels: ['Usuarios'],
  };

  var chart = new ApexCharts(document.querySelector("#velocimetro"), options);
  chart.render();
});