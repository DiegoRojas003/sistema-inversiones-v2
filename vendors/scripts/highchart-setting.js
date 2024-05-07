

// chart 6
Highcharts.chart('chart6', {
	chart: {
		type: 'pie',
		options3d: {
			enabled: true,
			alpha: 45
		}
	},
	title: {
		text: 'Participación Accionaria'
	},
	subtitle: {
		text: 'Gráfico Circular Participación'
	},
	plotOptions: {
		pie: {
			innerSize: 100,
			depth: 45
		}
	},
	series: [{
		name: 'Porcentaje participación',
		data: [
		['Socio 1', 8.5],
		['Socio 2', 3],
		['Socio 3', 1],
		['Socio 4', 6],
		['Socio 5', 8],
		['Socio 6', 4],
		['Socio 7', 4],
		['Socio 8', 1],
		['Socio 9', 1]
		]
	}]
});

