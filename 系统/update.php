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
	if(!empty($_POST['stu_no'])){//----------------修改学生信息，，具体php
			$stu_no = $_POST['stu_no'];
			$stu_class = $_POST['stu_class'];
			$fac_no = $_POST['fac_no'];
			if(!empty($_POST['test_course'])){
				$test_course = "'".$_POST['test_course']."'";	
			}else{
				$test_course = 'NULL';
			}
			$stu_name = $_POST['stu_name'];
			$update_sql = "update stu_info set stu_class =$stu_class,fac_no = $fac_no,test_course = $test_course,stu_name = '$stu_name' where stu_no = $stu_no ;";
			echo $update_sql;
			if($conn->query($update_sql)){
				$message = "成功修改{$stu_no}的数据，点击查看：<form id = 'stu_display_form' action = 'select.php' method = 'post'><input type = 'submit' name = 'stu_detials' value = '$stu_no'></form>";
				setcookie("message",$message);
				header("Location:stu_display.php");
			}else{
				echo $conn->error;
			}
	}
	
	if(!empty($_POST['classroom_id'])){//-----------------修改教室信息
		$classroom_id = $_POST['classroom_id'];
		$classroom_name = $_POST['classroom_name'];
		$course_id = $_POST['course_id'];
		if(!empty($_POST['start_time'])){
			$start_time = $_POST['start_time'];
		}else{
			$start_time = 'NULL';
		}
		if(!empty($_POST['end_time'])){
			$end_time = $_POST['end_time'];
		}else{
			$end_time = 'NULL';
		}
		if(!empty($_POST['course_id'])){
			$course_id = $_POST['course_id'];
		}else{
			$course_id = 'NULL';
		}
		echo $classroom_id.$course_id;
		echo $start_time;
		$update_sql = "update classroom_info set course_id =$course_id, start_time = '$start_time', end_time = '$end_time' where classroom_id = $classroom_id;";
		echo $update_sql;
		if($conn->query($update_sql)){
			$message = "成功修改{$classroom_name}的数据";
			setcookie("message",$message);
			header("Location:select.php?classroom_id={$classroom_id}");
		}else{
			echo $conn->error;
		}
	}
?>