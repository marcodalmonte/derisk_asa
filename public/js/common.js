'use strict';

$(function () {

	// ScrollUp
	$.scrollUp({
		scrollName: 'scrollUp',
		scrollDistance: 180,
		scrollFrom: 'top',
		scrollSpeed: 300,
		easingType: 'linear',
		animation: 'fade',
		animationSpeed: 200,
		scrollTrigger: false,
		scrollText: '<i class="icon-flight"></i>',
		scrollTitle: false,
		scrollImg: false,
		activeOverlay: false,
	});

	// SparkLine overall income
	$('#overallIncome').sparkline([5,2,4,9,5,4,6,4,6,3,3,2,1,1,1], {
		height: '30',
		type: 'bar',
		barSpacing: 3,
		barWidth: 7,
		barColor: '#e84f4c',
		tooltipPrefix: 'Users: '
	});
	$('#overallIncome').sparkline([3,3,4,5,5,5,4,4,4,3,2,1,1,1,1,1], {
		composite: true,
		height: '30',
		fillColor:false,
		lineColor: '#000000',
		tooltipPrefix: 'Sales Online: '
	});

	// Header todo list dropdown
	$('.dropdown-menu .todo').on('click', function(e) {
		e.stopPropagation();
	});

	// Task list
	$('.task-list').on('click', 'li.list', function() {
		$(this).toggleClass('completed');
	});

	// Page loading
	setTimeout(function() {
		$('body').addClass('loaded');
	}, 3000);

	// Bootstrap dropdown
	// $('.nav li.dropdown').on('mouseenter mouseleave click tap', function() {
	// 	$(this).toggleClass("open");
	// });

	// Tooltip & Popover
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();

});