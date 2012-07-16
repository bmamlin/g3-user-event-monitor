<?php
include 'g3_user_event_config.php';
?>
<html>
<head>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">
$(function () {

	var INTERVAL_CUTOFF = 30000; // max interval (milliseconds) to include in graphs

	var selectPatientChart;
	var startETChart;
    var stepChart;
    var signChart;

    $(document).ready(function() {

    	// Chart used to track time from ET selection to starting form
        selectPatientChart = new Highcharts.Chart({
            chart: {
                renderTo: 'selectPatientContainer',
                type: 'scatter',
                zoomType: 'xy'
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
                        return ''+
                        new Date(this.x) +': '+ this.y +'s ('
                        	+ this.point.o.from.name + ' to ' + this.point.o.to.name + ')';
                }
            },
            /*
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                backgroundColor: '#FFFFFF',
                borderWidth: 1
            },
            */
            credits: { enabled: false },
            plotOptions: {
                scatter: {
                    marker: {
                        radius: 5,
                        states: {
                            hover: {
                                enabled: true,
                                lineColor: 'rgb(100,100,100)'
                            }
                        }
                    },
                    states: {
                        hover: {
                            marker: {
                                enabled: false
                            }
                        }
                    }
                }
            }
        });

    	// Chart used to track time from ET selection to starting form
        startETChart = new Highcharts.Chart({
            chart: {
                renderTo: 'startETContainer',
                type: 'scatter',
                zoomType: 'xy'
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
                        return ''+
                        new Date(this.x) +': '+ this.y +'s ('
                        	+ this.point.o.from.name + ' to ' + this.point.o.to.name + ')';
                }
            },
            /*
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                backgroundColor: '#FFFFFF',
                borderWidth: 1
            },
            */
            credits: { enabled: false },
            plotOptions: {
                scatter: {
                    marker: {
                        radius: 5,
                        states: {
                            hover: {
                                enabled: true,
                                lineColor: 'rgb(100,100,100)'
                            }
                        }
                    },
                    states: {
                        hover: {
                            marker: {
                                enabled: false
                            }
                        }
                    }
                }
            }
        });

    	// Chart used to track intervals betweeen ET steps
        stepChart = new Highcharts.Chart({
            chart: {
                renderTo: 'stepContainer',
                type: 'scatter',
                zoomType: 'xy'
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
            tooltip: {
                formatter: function() {
                        return ''+
                        new Date(this.x) +': '+ this.y +'s ('
                        	+ this.point.o.from.name + ' to ' + this.point.o.to.name + ')';
                }
            },
            /*
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                backgroundColor: '#FFFFFF',
                borderWidth: 1
            },
            */
            credits: { enabled: false },
            plotOptions: {
                scatter: {
                    marker: {
                        radius: 5,
                        states: {
                            hover: {
                                enabled: true,
                                lineColor: 'rgb(100,100,100)'
                            }
                        }
                    },
                    states: {
                        hover: {
                            marker: {
                                enabled: false
                            }
                        }
                    }
                }
            }
        });

    	// Chart used to track interval from start to end signing
        signChart = new Highcharts.Chart({
            chart: {
                renderTo: 'signContainer',
                type: 'scatter',
                zoomType: 'xy'
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
            tooltip: {
                formatter: function() {
                        return ''+
                        new Date(this.x) +': '+ this.y +'s ('
                        	+ this.point.o.from.name + ' to ' + this.point.o.to.name + ')';
                }
            },
            /*
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                backgroundColor: '#FFFFFF',
                borderWidth: 1
            },
            */
            credits: { enabled: false },
            plotOptions: {
                scatter: {
                    marker: {
                        radius: 5,
                        states: {
                            hover: {
                                enabled: true,
                                lineColor: 'rgb(100,100,100)'
                            }
                        }
                    },
                    states: {
                        hover: {
                            marker: {
                                enabled: false
                            }
                        }
                    }
                }
            }
        });

		$.getJSON("<?=$USER_EVENT_DATA_URL?>", function(data) {
			for (d in data)
			{
				// The document suggests addSeries() should return the series; however,
				// this does not appear reliable and always fails on Safari, so we create
				// the series with an id, then get() it by id.
				selectPatientChart.addSeries(
					{id:'user-'+(Number(d)+1), name:'User '+(Number(d)+1), data:[]});
				var selectPatientChartSeries = selectPatientChart.get('user-'+(Number(d)+1));
				startETChart.addSeries(
					{id:'user-'+(Number(d)+1), name:'User '+(Number(d)+1), data:[]});
				var startETChartSeries = startETChart.get('user-'+(Number(d)+1));
				stepChart.addSeries(
					{id:'user-'+(Number(d)+1), name:'User '+(Number(d)+1), data:[]});
				var stepChartSeries = stepChart.get('user-'+(Number(d)+1));
				signChart.addSeries(
					{id:'user-'+(Number(d)+1), name:'User '+(Number(d)+1), data:[]});
				var signChartSeries = signChart.get('user-'+(Number(d)+1));
				for (var i=0; i<data[d].length; i++)
				{
					var v = data[d][i];
					if (v.from.name.match(/^patientSelected$/) &&
						v.to.name.match(/^patLandETTypes$/) && v.milliseconds < INTERVAL_CUTOFF)
						{
							// Starting an ET
							var point = {x:v.to.time, y:v.milliseconds/1000, o:v};
							if (!selectPatientChartSeries)
							{
								alert("selectPatientChartSeries is null!");
							}
							selectPatientChartSeries.addPoint(point);
						}
					else if (v.from.name.match(/^patLandETTypeSelected/) &&
						v.to.name.match(/^etstep\./) && v.milliseconds < INTERVAL_CUTOFF)
						{
							// Starting an ET
							var point = {x:v.to.time, y:v.milliseconds/1000, o:v};
							startETChartSeries.addPoint(point);
						}
					else if (v.from.name.match(/^etstep\..*\.leave$/) &&
						v.to.name.match(/^etstep\..*\.enter$/) && v.milliseconds < INTERVAL_CUTOFF)
						{
							// Left one step and entered another
							var point = {x:v.to.time, y:v.milliseconds/1000, o:v};
							stepChartSeries.addPoint(point);
						}
					else if (v.from.name.match(/^etstep\.sign\.leave$/) &&
						v.to.name.match(/^patLandETTypes$/) && v.milliseconds < INTERVAL_CUTOFF)
						{
							// Left one step and entered another
							var point = {x:v.to.time, y:v.milliseconds/1000, o:v};
							signChartSeries.addPoint(point);
						}
				}
			}
		});
    });
});
</script>
</head>
<body>
<div id="selectPatientContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
<div id="startETContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
<div id="stepContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
<div id="signContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
</body>
</html>