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


	if(!empty($_SESSION['username'])){//课程信息查询
		$cors_teacher = $_SESSION['username'];
		$select_cors = "select * from cors_info where cors_teacher = '$cors_teacher'";
		$select_cors_result = $conn->query($select_cors);
		$test_course = NULL;
		if($select_cors_result){
			while($row = $select_cors_result->fetch_assoc()){
				$test_course = $row['test_course'];
			}
			//echo $cors_option;
		}
		
	
		
		//----------------------------------查询教室安排情况
		
	$if_arreng = "select course_id from classroom_info;";////////---------查询已经安排考试的课程
	$i = 0;
	$course_id[] = NULL;
	error_reporting( E_ALL&~E_NOTICE );//排除notice警告
	$select_used = $conn->query($if_arreng);
	if($select_used){
		while($row=$select_used->fetch_assoc()){
			$course_id[$i]=$course_id[$i] + $row['course_id'];
			$i = $i + 1;
		}
		$i = 0;
	}
	
	$sql= "select * from cors_info where cors_teacher = '$cors_teacher'; ";
	$result = $conn->query($sql);
	if($result){
		echo "<table  border = '1'  cellspacing = '0' cellspacing = '1'  align='center'>
		<tr style='text-align:center'>
			<td width='50px'>课程号</td>
			<td>课程名</td>
			<td width='80px'>教师名</td>
			<td width='80px'>选课人数</td>
			<td>申请教室</td>
		</tr>";
	while($row = $result->fetch_assoc()){
	echo "<tr>";
	echo "<td style='text-align:center;'> {$row['cors_no']} </td>";
	echo "<td style='text-align:center;'><form id = 'stu_display_form' action = 'select.php' method = 'post'><input type = 'submit' name = 'course_infos' value = {$row['test_course']}></form></td>";
	echo "<td> {$row['cors_teacher']} </td>";
	echo "<td style='text-align:center;'> {$row['cors_selected']} </td>";
	echo "<td>
		<form action='classroomtest.php' method='post'>
			<input type='text'  name='cors_no' value='{$row['cors_no']}' style='display:none;' />";
	if(in_array($row['cors_no'],$course_id)){//判断是否已经安排教室，然后进行禁止选择，显示方式不同
		$select_time = "select start_time,end_time from classroom_info where course_id = '{$row['cors_no']}';";
		$start_time = NULL;
		$end_time = NULL;
		$time_result = $conn->query($select_time);
		while($time = $time_result->fetch_assoc()){
			$start_time = $time['start_time'];
			$end_time = $time['end_time'];
		} //查询已经安排考试的课程的考试时间
		echo "<input type ='text' style = 'width:40%;color:#999988' value = '$start_time' onfocus=this.blur()  title = '该字段不允许修改'>
			 <input type ='text'  style = 'width:40%;color:#999988' value = '$end_time' onfocus=this.blur()  title = '该字段不允许修改'>
			 <input onfocus=this.blur() style='width:15%;color:#999988'   value='已安排教室'>";
	}else{
		echo "<input type ='datetime-local' style = 'width:40%' required name='start_time'>
			<input type ='datetime-local' style = 'width:40%' required name='end_time'>
			<input type='submit' style = 'width:15%' value='申请教室'>";
	}
	echo "</form>
		</td>";				
	echo "</tr>";
	}
	echo "</table>";
		}else{
		echo "查询失败！".$conn->error;
	}
		
		
		//---------------------------------- 显示学生选课情况
	
		$course_select = "select * from stu_info where  test_course = '$test_course' ;";
		$result = $conn->query($course_select);
			if($result){
						echo "<table width = '80%' border = '1' cellspacing = '0' style = 'text-align:center'>
		<tr>
			<td>课程名</td>
			<td>学号</td>
			<td>姓名</td>
		</tr>";
			while($row = $result->fetch_assoc()){
	echo "<tr>";
	echo "<td> {$row['test_course']} </td>";
	echo "<td> {$row['stu_no']} </td>";
	echo "<td> {$row['stu_name']} </td>";					
	echo "</tr>";
	}
	echo "</table>";
			}else{
				echo "die";
				}
}


?>