<?php
$servername = "localhost";
$username = "trboteu";
$password = "4dU00n;0;VPpfD";
$dbname = "trboteu_Library";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
   die("Connection error ". mysqli_connect_error());
}
//echo "Connected";

?>