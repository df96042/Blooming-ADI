<meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=0.8, minimum-scale=0, user-scalable=no, target-densitydpi=medium-dpi" />

<meta http-equiv="refresh" content="3">

<html>
<title>PILL</title>


<?php

$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = 'root';
$mysql_db = 'adi';

$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);


$sql="SELECT * from Alarm";
$result = mysqli_query($conn, $sql) ;

echo " Pill : ";
while($arr = mysqli_fetch_array($result)){
	if($arr[2]=='1')
echo"<div align=\"center\"><a href='/'><img src=\"/green.png\" width:\"10px\" height:\"10px\"></a></div>";
	else
echo"<div align=\"center\"><a href='/'><img src=\"/ringer.png\" width:\"10px\" height:\"10px\"></a></div>";
}
?>
</html>

