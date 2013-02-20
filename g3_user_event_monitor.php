<?php
include 'g3_user_event_config.php';
?>
<html>
<head>
<title>G3 User Event Monitor</title>
<meta http-equiv="refresh" content="300" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">

function formatDate(time) {
    var s = function(n) { var x=("0"+n); return x.substring(x.length-2); } // pad
    var d = new Date(time);
    return (d.getMonth()+1)+"/"+s(d.getDate())+" "+d.getHours()+":"+s(d.getMinutes())+":"+s(d.getSeconds());
}

$(function () {

	var INTERVAL_CUTOFF = 30000; // max interval (milliseconds) to include in graphs

	var selectPatientChart;
	var startETChart;
    var stepChart;
    var signChart;

	// Chart used to track time from ET selection to starting form
    selectPatientChart = new Highcharts.Chart({
        chart: {
            renderTo: 'selectPatientContainer',
            type: 'column',
            zoomType: 'x'
        },
        title: {
            text: 'Patient Selection'
        },
        subtitle: {
            text: 'Interval between selecting patient and dashboard'
        },
        xAxis: {
            title: {
                enabled: false
            },
             startOnTick: true,
            endOnTick: true,
            showLastLabel: true,
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: 'Interval (s)'
            }
        },
        tooltip: {
            formatter: function() {
                    return '<b>' + this.point.o.username+'</b><br/>' +
                        this.y +'s<br/>' +
                        this.point.o.from.name + ' > ' + this.point.o.to.name + '<br/>' +
                        formatDate(this.x);
            }
        },
        legend: { enabled: false },
        credits: { enabled: false },
        series: [{
            data: []
        }]
    });

	// Chart used to track time from ET selection to starting form
    startETChart = new Highcharts.Chart({
        chart: {
            renderTo: 'startETContainer',
            type: 'column',
            zoomType: 'x'
        },
        title: {
            text: 'Encounter Type Startup'
        },
        subtitle: {
            text: 'Interval between selecting ET and first step'
        },
        xAxis: {
            title: {
                enabled: false
            },
             startOnTick: true,
            endOnTick: true,
            showLastLabel: true,
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: 'Interval (s)'
            }
        },
        tooltip: {
            formatter: function() {
                    return '<b>' + this.point.o.username+'</b><br/>' +
                        this.y +'s<br/>' +
                        this.point.o.from.name + ' > ' + this.point.o.to.name + '<br/>' +
                        formatDate(this.x);
            }
        },
        legend: { enabled: false },
        credits: { enabled: false },
        series: [{
            data: []
        }]
    });

	// Chart used to track intervals betweeen ET steps
    stepChart = new Highcharts.Chart({
        chart: {
            renderTo: 'stepContainer',
            type: 'scatter',
            zoomType: 'x'
        },
        title: {
            text: 'Encounter Transaction Steps'
        },
        subtitle: {
            text: 'Interval between separate transaction steps'
        },
        xAxis: {
            title: {
                enabled: false
            },
             startOnTick: true,
            endOnTick: true,
            showLastLabel: true,
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: 'Interval (s)'
            }
        },
        plotOptions: {
            series: {
                pointWidth: 20
            }
        },
        tooltip: {
            formatter: function() {
                    return '<b>' + this.point.o.username+'</b><br/>' +
                        this.y +'s<br/>' +
                        this.point.o.from.name + ' > ' + this.point.o.to.name + '<br/>' +
                        formatDate(this.x);
            }
        },
        legend: { enabled: false },
        credits: { enabled: false },
        series: [{
            data: []
        }]
    });

	// Chart used to track interval from start to end signing
    signChart = new Highcharts.Chart({
        chart: {
            renderTo: 'signContainer',
            type: 'column',
            zoomType: 'x'
        },
        title: {
            text: 'Sign Interval'
        },
        subtitle: {
            text: 'Interval from start to end of signing orders'
        },
        xAxis: {
            title: {
                enabled: false
            },
             startOnTick: true,
            endOnTick: true,
            showLastLabel: true,
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: 'Interval (s)'
            }
        },
        plotOptions: {
            series: {
                pointWidth: 20
            }
        },
        tooltip: {
            formatter: function() {
                    return '<b>' + this.point.o.username+'</b><br/>' +
                        this.y +'s<br/>' +
                        this.point.o.from.name + ' > ' + this.point.o.to.name + '<br/>' +
                        formatDate(this.x);
            }
        },
        legend: { enabled: false },
        credits: { enabled: false },
        series: [{
            data: []
        }]
    });

    // Highcharts defaults to UTC; we want local times
    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    })

	$.getJSON("<?php echo $USER_EVENT_DATA_URL?>?logfile=<?php echo $USER_EVENT_LOG_FILE?>", function(data) {
		for (d in data)
		{
			for (var i=0; i<data[d].length; i++)
			{
				var v = data[d][i];
				if (v.from.name.match(/^patientSelected$/) &&
					v.to.name.match(/^patLand\.enter$/) && v.milliseconds < INTERVAL_CUTOFF)
					{
						// Starting an ET
						var point = {x:v.to.time, y:v.milliseconds/1000, o:v};
                        selectPatientChart.series[0].addPoint(point);
					}
				else if (v.from.name.match(/^patLandETTypeSelected/) &&
					v.to.name.match(/^etstep\./) && v.milliseconds < INTERVAL_CUTOFF)
					{
						// Starting an ET
						var point = {x:v.to.time, y:v.milliseconds/1000, o:v};
                        startETChart.series[0].addPoint(point);
					}
				else if (v.from.name.match(/^etstep\..*\.leave$/) &&
					v.to.name.match(/^etstep\..*\.enter$/) && v.milliseconds < INTERVAL_CUTOFF)
					{
						// Left one step and entered another
						var point = {x:v.to.time, y:v.milliseconds/1000, o:v};
                        stepChart.series[0].addPoint(point);
					}
				else if (v.from.name.match(/^etstep\.sign\.leave$/) &&
					v.to.name.match(/^patLandETTypes$/) && v.milliseconds < INTERVAL_CUTOFF)
					{
						// Left one step and entered another
						var point = {x:v.to.time, y:v.milliseconds/1000, o:v};
                        signChart.series[0].addPoint(point);
					}
			}
		}
	});
});
</script>
</head>
<body>

<h3>Data from <?php echo $USER_EVENT_LOG_FILE?></h3>

<div id="selectPatientContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
<div id="startETContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
<div id="stepContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
<div id="signContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
</body>
</html>
