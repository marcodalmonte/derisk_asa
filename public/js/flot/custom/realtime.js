$(function () {

	var data = [];
	var dataset;
	var totalPoints = 100;
	var updateInterval = 1000;
	var now = new Date().getTime();

	function GetData() {
		data.shift();

		while (data.length < totalPoints) {     
			var y = Math.random() * 100;
			var temp = [now += updateInterval, y];
			data.push(temp);
		}
	}

	var options = {
		series: {
			lines: {
				show: true,
				lineWidth: 2,
				fill: true
			},
			points: {
				show: false,
				radius: 4,
				fill: true,
				fillColor: '#50B432',
				lineWidth: 2
			},
		},
		xaxis: {
			mode: "time",
			tickSize: [5, "second"],
			tickFormatter: function (v, axis) {
			var date = new Date(v);
			if (date.getSeconds() % 20 == 0) {
				var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
				var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
				return hours + ":" + minutes;
			} else {
					return "";
				}
			},
			axisLabel: "Time",
			axisLabelUseCanvas: true,
			axisLabelFontSizePixels: 12,
			axisLabelFontFamily: 'Verdana, Arial',
			axisLabelPadding: 10
		},
		yaxis: {
			min: 0,
			max: 100,        
			tickSize: 25,
			tickFormatter: function (v, axis) {
				if (v % 10 == 0) {
					return v + "%";
				} else {
					return "";
				}
			},
			axisLabel: "CPU loading",
			axisLabelUseCanvas: true,
			axisLabelFontSizePixels: 12,
			axisLabelFontFamily: 'Verdana, Arial',
			axisLabelPadding: 5
		},
		legend:{        
			show: true,
			position: 'ne'
		},

		tooltip: true,
		tooltipOpts: {
			content: '%s: %y'
		},

		colors: ['#3a86c8', '#64bd63', '#6dc6cd', '#52bf8a', '#638ca5'],

		grid: {
			hoverable: false,
			clickable: false,
			borderWidth: 0,
			tickColor: '#eaf3fb',
			borderColor: '#eaf3fb',
			verticalLines: true,
			horizontalLines: true,
		},
		shadowSize: 0,
	};

	GetData();
	dataset = [
		{ label: "Sales", data: data, color: '#3FC5AC' }
	];

	$.plot($("#flot-placeholder"), dataset, options);

	function update() {
		GetData();
		$.plot($("#flot-placeholder"), dataset, options)
		setTimeout(update, updateInterval);
	}

	update();
});
