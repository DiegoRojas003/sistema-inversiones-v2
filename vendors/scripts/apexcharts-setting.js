let valorCapital =parseFloat(document.getElementById('valor-capital').innerText);
let valorIndustria =parseFloat(document.getElementById('valor-industria').innerText);
var options = {
	series: [valorCapital,valorIndustria],
	chart: {
	width: 380,
	type: 'pie',
  },
  labels: ['Aportes Capital', 'Aportes Industria'],
  responsive: [{
	breakpoint: 480,
	options: {
	  chart: {
		width: 200
	  },
	  legend: {
		position: 'bottom'
	  }
	}
  }]
  };
  var chart = new ApexCharts(document.querySelector("#chart8Valor"), options);
  chart.render();

var options = {
	series: [20, 55],
	chart: {
	width: 380,
	type: 'pie',
  },
  labels: ['Aportes Capital', 'Aportes Industria'],
  responsive: [{
	breakpoint: 480,
	options: {
	  chart: {
		width: 200
	  },
	  legend: {
		position: 'bottom'
	  }
	}
  }]
  };

  var chart = new ApexCharts(document.querySelector("#chart8Cantidad"), options);
  chart.render();

  var options = {
	series: [120, 55],
	chart: {
	width: 380,
	type: 'pie',
  },
  labels: ['Aportes KNOW HOW', 'Aportes en Trabajo'],
  responsive: [{
	breakpoint: 480,
	options: {
	  chart: {
		width: 200
	  },
	  legend: {
		position: 'bottom'
	  }
	}
  }]
  };

  var chart = new ApexCharts(document.querySelector("#chart8Industria"), options);
  chart.render();
var options9 = {
	series: [20, 55],
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
var chart = new ApexCharts(document.querySelector("#chart9"), options9);
chart.render();