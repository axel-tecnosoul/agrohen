var linecharts = {
    labels: ["Ene", "Feb", "Marz", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
    datasets: [{
        fillColor: "transparent",
        strokeColor: endlessAdminConfig.primary,
        pointColor: endlessAdminConfig.primary,
        data: [1, 2.5, 1.5, 3, 1.3, 2, 4, 4.5, 4.8, 5, 5.3]
    },
    {
        fillColor: "transparent",
        strokeColor: "#ff5370",
        pointColor: "#ff5370",
        data: [ 0, 3, 3.5, 2, 2.3, 4.5, 2, 10, 8, 3, 5.5]
    }
    ]
}
var ctx = document.getElementById("linecharts-bitcoin").getContext("2d");
var LineChartDemo = new Chart(ctx).Line(linecharts, {
    pointDotRadius: 2,
    pointDotStrokeWidth: 5,
    pointDotStrokeColor: "#ffffff",
    bezierCurve: false,
    scaleShowVerticalLines: false,
    scaleGridLineColor: "#eeeeee"
});

var morris_chart = {
    init: function() {
        $(function() {
            Morris.Donut({
                element: 'bitcoin-morris',
                data: [{
                    value: 40,
                    label: "Invest"
                },
                    {
                        value: 8,
                        label: "Invest"
                    },
                    {
                        value: 10,
                        label: "Invest"
                    }],
                backgroundColor: endlessAdminConfig.primary,
                labelColor: "#999999",
                colors: [endlessAdminConfig.primary ,"#f6f6f6" ,endlessAdminConfig.secondary],
                formatter: function(a) {
                    return a + "%"
                }
            });
        });
    }
};
(function($) {
    "use strict";
    morris_chart.init()
})(jQuery);

