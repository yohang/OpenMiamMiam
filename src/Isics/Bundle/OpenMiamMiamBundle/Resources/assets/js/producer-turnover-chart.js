(function($) {
    'use strict';

    function drawChart($element) {
        var data    = google.visualization.arrayToDataTable($element.data('chart-data'));
        var options = {
            title:     'Chiffre d’affaires',
            curveType: 'function',
            chartArea: { left: 50, top: 50, width: '75%', height: '60%' },
            vAxis:     { viewWindow: { min: 0 } },
            interpolateNulls: true
        };

        var chart = new google.visualization.LineChart($element[0]);
        chart.draw(data, options);
    }

    function computeChart($element) {
        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart.bind(null, $element));
    }

    $('[data-behavior="producer-turnover-chart"]').each(function() { computeChart($(this)); });

}(jQuery));
