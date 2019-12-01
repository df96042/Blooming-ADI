<meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=0.8, minimum-scale=0, user-scalable=no, target-densitydpi=medium-dpi" />
<?php

include_once('./index.php');

$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = 'root';
$mysql_db = 'adi';

// 접속
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);


// charset 설정, 설정하지 않으면 기본 mysql 설정으로 됨, 대체적으로 euc-kr를 많이 사용
//mysql_query("set names utf8");

session_start();
$sql="SELECT * from (SELECT * FROM patient1 ORDER BY time DESC LIMIT 15) as a order by time ASC";

$result = mysqli_query($conn, $sql) ;


$str_time="";
$str_atemper="";
$str_heart="";
while ($row = mysqli_fetch_array($result)) {
// echo($row['time']."--------------".$row['temperature']."<br>");
 $str_time .="'".$row['time']."',";
 $str_atemper .="".$row['temperature'].",";
 $str_heart .="".$row['heartbeat'].",";
}
$str_time= substr($str_time,0,-1);
$str_atemper= substr($str_atemper,0,-1);
$str_heart= substr($str_heart,0,-1);
$now_heart= $row['heartbeat'];
echo "$now_heart";
//$location = "/emergency.png";
//if($now_heart > 100){
//echo" <div align="center"><a href="/"><img src = "$location" width="100px" height="100px" align="center"></a></div> ";
?>
<!DOCTYPE HTML>
<html>
<title> Health Care </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style=font-weight:bold;> 
<div align="center"><a href="/"><img src = "/logo.png" width="100px" height="100px" align="center"></a></div>
<hr size="3px" color="black"><br>
<font size="4" align="center"><p><p><p><p><p><p> 
&nbsp; &nbsp;ADIs Data <br><br>
<hr size="3px" color="black"><br>
<pre size="10px"><span style=font-weight:bold;> Real-Time State <br>

&nbsp;&nbsp; HEART RATE<br>
<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <style type="text/css">
	${highcharts.css}
  </style>

  <link rel="stylesheet" type="text/css" href="./highchart/code/css/highcharts.css"/>
  <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'line'
//	    backgroundColor: 'black'
        },
        title: {
            text: 'Heart Rate'
        },
        subtitle: {
            text: 'Source: ilikesan.com'
        },
        xAxis: {
            categories: [<?php echo $str_time?>]
        },
        yAxis: {
            title: {
                text: ' Heart '
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: 'ADI',

            data: [<?php echo $str_heart?>]
        }
  ]
    });
});
  </script>

 
BODY TEMPERATURE <br>

<div id="container2" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

 <style type="text/css">
        ${highcharts.css}
  </style>

  <link rel="stylesheet" type="text/css" href="./highchart/code/css/highcharts.css"/>
  <script type="text/javascript">
$(function () {
    $('#container2').highcharts({
        chart: {
            type: 'line'
//          backgroundColor: 'black'
        },
        title: {
            text: 'Body Temperature'
        },
        subtitle: {
            text: 'Source: ilikesan.com'
        },
        xAxis: {
            categories: [<?php echo $str_time?>]
        },
        yAxis: {
            title: {
                text: ' 실시간 Temperature (°C)'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: 'ADI',
            data: [<?php echo $str_atemper?>]
        }
  ]
    });
});
  </script>

<br>
<script src="/highchart/code/js/highcharts.js"></script>
<script src="/highchart/code/js/modules/exporting.js"></script>


</html>

<?php
$refresh_time="3";
echo "<script language=\"javascript\">setTimeout(\"location.reload()\",".($refresh_time*1000).");</script>";
?>
