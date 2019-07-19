<!DOCTYPE html>
<html>
<head>
	<title>Grafico de Palavras</title>
</head>
<body>
	<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</body>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script type="text/javascript">	

	$.ajax({
		url: 'http://127.0.0.1:8000/api/gerar-grafico',
		type: 'GET'		
	})
	.done(function(response) {
		console.log(response)
		let arrPalavras = [];
		let arrQuantidade = [];
		$.each(response, function( key, value ) {
			arrPalavras.push(value.palavra);
			arrQuantidade.push(value.quantidade);
		});
		console.log(arrPalavras)
		console.log(arrQuantidade)
		
		Highcharts.chart('container', {

			chart: {
				polar: true,
				type: 'line'
			},

			title: {
				text: 'Palavras mais usadas no Taskinho',
				x: -80
			},

			pane: {
				size: '80%'
			},

			xAxis: {
				categories: arrPalavras,
				tickmarkPlacement: 'on',
				lineWidth: 0
			},

			yAxis: {
				gridLineInterpolation: 'polygon',
				lineWidth: 0,
				min: 0
			},

			tooltip: {
				shared: true,
				pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
			},

			legend: {
				align: 'right',
				verticalAlign: 'middle'
			},

			series: [{
				name: 'Repetição',
				data: arrQuantidade,
				pointPlacement: 'on'
			}],		

		});
		
	})
	.fail(function() {
		console.log("error");
	});






</script>
</html>