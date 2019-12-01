<meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=0.8, minimum-scale=0, user-scalable=no, target-densitydpi=medium-dpi" />
<meta http-equiv="refresh" content="3">
</script>
<?php
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = 'root';
$mysql_db = 'adi';
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password,$mysql_db);
//$dbconn = mysql_select_db($mysql_db, $conn);

//mysqli_query("set names utf8");


$sql="SELECT * FROM Alarm";
//echo $sql;

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_array($result);

include_once('./send_notification.php');

$message = sprintf("EMERGENCY");

printf("name : %s\n, state : ", $row[0], $ros[1]);
if($row[1] == 2){
	printf("danger!!!!! (send message!)");
	send_notification( 'dQyibs47D74:APA91bHXZ-HZ94XDCnWGpPc2aEwMAAHt6U211cXf01SPom2WhJXXp7_JM4uWBHYAp4qVBn5Jvg9rgqup-xZH93aHbN4vE0opS5mmoIWZ3Zvz1eytE0UYfF8l6LY6Py5kqzdJqXwqgKh-', $message);
}

?>

