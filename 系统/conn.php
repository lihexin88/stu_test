<?php
	header("Content-Type: text/html; charset=utf-8");
	$servername = "localhost";
	$username = "**********";
	$password = "**********";
	$dbname = "stu_test";
	// 创建连接时直接打开数据库
	$conn = new mysqli($servername, $username, $password, $dbname);
	$conn -> set_charset('utf8');
?>
