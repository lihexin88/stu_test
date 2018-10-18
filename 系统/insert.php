<?php require_once("conn.php"); ?>
<?php 
	/*
	 * 把用户信息存储到 PHP session 中之前，首先必须启动会话。注释
	 * session_start() 函数必须位于 <html> 标签之前：
	 */
	session_start(); 
?>
<?php
	if(empty($_SESSION['login_type'])){//判断是否登录。
		header("Location:login.html");
	}
?>
<?php
if(!empty($_POST['stu_insert'])){//学生信息插入	
		echo "start insert";
		 $stu_no = $_POST['stu_no'];
		 $insertSQL = "INSERT INTO stu_info(stu_no,stu_class,fac_no,test_course,stu_name) VALUES ('";
		 $insertSQL.= $stu_no."',";
		 $insertSQL.= $_POST['stu_class'].",";
		 if(!empty($_POST['test_course'])){
		 	$insertSQL.= $_POST['fac_no'].",'";
		 	$insertSQL.= $_POST['test_course']."','"; 
		 }else{
		 	$insertSQL.= $_POST['fac_no'].",";
		 	$insertSQL.= "NULL".",'";
		 }
		 $insertSQL.= $_POST['stu_name']."');";
		 echo $insertSQL;
		  	if ($conn->query($insertSQL) === TRUE) {
		  		$message = "成功插入数据：<form id = 'stu_display_form' action = 'select.php' method = 'post'><input type = 'submit' name = 'stu_detials' value = '$stu_no'></form>";
				setcookie("message",$message);
				header("Location:stu_display.php");//执行成功三联，声明，设置，跳转(学生详细信息显示)
			} else {
				$message = "<script>alert('{$_POST['stu_no']}插入失败！！')</script>";
				setcookie("message",$message);
				header("Location:stu_display.php");
			}
	}
if(!empty($_POST['cors_insert'])){//课程信息插入
	echo "start insert cors";
	$test_course = $_POST['test_course'];
	$cors_teacher = $_POST['cors_teacher'];
	$cors_selected = 0;
	$classroom_id = NULL;
	$sql = "insert into cors_info(test_course,cors_teacher) values('$test_course','$cors_teacher');";
	echo $sql;
	if ($conn->query($sql)) {
		  		$message = "成功插入数据：$test_course";
				setcookie("message",$message);
				header("Location:cors_display.php");//执行成功三联，声明，设置，跳转
			}else{
			    echo "<script>alert('{$_POST['stu_no']}插入失败！！')</script>" . $conn->error;
				header("Location:stu_os.html");
			    exit;
			}
}
?>