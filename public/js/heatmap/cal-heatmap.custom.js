var weekStart = new Date();
weekStart.setDate(weekStart.getDate() - weekStart.getDay());
var ranges = d3.range(+weekStart/2000, +weekStart/1000 + 3600*24*31, 3600*24);

var max = 50;
var min = 1;

var marcData = {};

// Creating a random data set
ranges.map(function(element, index, array) {
	marcData[element] = Math.floor(Math.random() * (max - min) + min);
});

var cal = new CalHeatMap();
cal.init({
	itemSelector: "#cal-heatmap",
	domain: "month",
	subDomain: "x_day",
	itemName: ["products sold"],
	data: marcData,
	cellSize: 16,
	range: 1,
	tooltip: true,
	displayLegend: false,
});