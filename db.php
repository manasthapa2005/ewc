<?php
if(!isset($_SESSION)){
	session_start();
}
ob_start();
	/**/
	global $START_DATE;
	global $END_DATE;
	global $YEAR;
	global $connect;
	$connect=mysqli_connect('localhost','root','@123@','ews_info') or die("Database Error");
?>