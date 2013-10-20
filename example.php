<?php
require_once('./HodgkinHuxleyNeuron.php');

$HodgkinHuxleyNeuron = new HodgkinHuxleyNeuron();

$I=10;
$Tmax=100;
$data=$HodgkinHuxleyNeuron->run($I,$Tmax);

?>

<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    // <![CDATA[
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['T', 'mV'],
          <?php
          $str='';
          foreach($data as $k=>$v) {
            $str.='['.$k.','.$v.'],';
          }
          echo trim($str,',');
          ?>
        ]);

        var options = {
          title: 'Hodgkin-Huxley Neuron',
          legend: 'none',
          hAxis: {title: 'Time (ms)'},
          vAxis: {title: 'Membrane potential (mV)'}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    // ]]>
    </script>
  </head>
  <body>
    <div id="chart_div" style="width:100%;min-width: 900px; height: 500px;"></div>
  </body>
</html>