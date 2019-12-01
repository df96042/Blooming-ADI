<meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=0.8, minimum-scale=0, user-scalable=no, target-densitydpi=medium-dpi" />


<!DOCTYPE html>
<html>
<head>
<title>Room Setup</title>
<link href="/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="maindiv">
<div class="divA">
<div class="title">
<h2>ROOM TEMPERATURE SETTING</h2>
</div>
<div class="divB">
<div class="divD">
<?php
$connection = mysqli_connect("localhost", "root", "root","adi");
session_start();
if (isset($_GET['submit'])) {
$number = 0;
$temperature = $_GET['dtemperature'];
$sql = "update room set temperature='$temperature' where number='$number'";
$result = mysqli_query($connection, $sql);
}
$sql = "select * from room";
$result = mysqli_query($connection, $sql);

while ($row = mysqli_fetch_array($result)) {
echo "<b><a href='updatephp.php?update={$row['number']}'>ROOM : {$row['number']}</a></b>";
echo "<br />";
}
?>
</div><?php
//if (isset($_GET['submit'])) {
//$setup = $_GET['submit'];
$sql = "select * from room where number=1";
$result = mysqli_query($connection, $sql);
while ($row2 = mysqli_fetch_array($result)){
$sql2 = "select * from room where number=0";
$result2 = mysqli_query($connection, $sql2);
while ($row1 = mysqli_fetch_array($result2)) {
echo "<form class='form' method='get'>";
echo "<h2>room number</h2>";
echo "<hr/><br /><br />";
echo " Temperature NOW : {$row2['temperature']} <br><br><br>";
echo "<label>" . "실내 온도" . "</label>" . "<br />";
echo" <input class='input' type='text' name='dtemperature' value='{$row1['temperature']}' />";
echo "<br />";
echo "<input class='submit' type='submit' name='submit' value='setup' />";
echo "</form>";
}
}
if (isset($_GET['submit'])) {
echo '<div class="form" id="form3"><br><br><br><br>
<Span> 설정변경 완료 :></span></div>';
//$number = 0;
$temperature = $_GET['dtemperature'];
$sql = "update room set temperature='$temperature' where number=0";
$result = mysqli_query($connection, $sql);
}


?>
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
</div><?
mysqli_close($connection);
?>
<br><br><p></p><br>
<br><br><br><p></p><br>
<div align=center>
<button class="button" type="button" onclick="location.href='/index.php'"> MAIN </button></div>

</body>
</html>

