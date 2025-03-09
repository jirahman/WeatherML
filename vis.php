<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {
    var dataPoints = [];

    var chart = new CanvasJS.Chart("chartContainer", {
        title: {
            text: "Temperature Data"
        },
        axisY: {
            title: "Temperature",
            includeZero: false
        },
        data: [{
            type: "line",
            dataPoints: dataPoints
        }]
    });

    function updateChart() {
        fetch('tempData.xml')
            .then(response => response.text())
            .then(data => {
                var parser = new DOMParser();
                var xmlDoc = parser.parseFromString(data, "application/xml");
                var records = xmlDoc.getElementsByTagName('record');
                dataPoints.length = 0;

                for (var i = 0; i < records.length; i++) {
                    var temperature = parseFloat(records[i].getElementsByTagName('temperature')[0].childNodes[0].nodeValue);
                    var date = records[i].getElementsByTagName('date')[0].childNodes[0].nodeValue;
                    var time = new Date(date.split('-').reverse().join('-')).getTime();
                    dataPoints.push({ x: new Date(time), y: temperature });
                }

                chart.render();
            });
    }

    updateChart();
    setInterval(updateChart, 5000);
}
</script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
</body>
</html>
