
	window.onload = function(){
		
		if ($('#canvas').length > 0) {

	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
		var lineChartData = {
			// labels : ["January","February","March","April","May","June","July"],
			labels: my_labels,
			datasets : [
				{
					label: "My First dataset",
					fillColor : "rgba(220,220,220,0.2)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(220,220,220,1)",
					// data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
					data : km_work
				},
				{
					label: "My Second dataset",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					// data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
					data: km_priv
				}
			]
		}

			
			Chart.defaults.global.responsive = true;
			
			var ctx = document.getElementById("canvas").getContext("2d");
			window.myLine = new Chart(ctx).Line(lineChartData, {
				responsive: true
			});
		}
	}
