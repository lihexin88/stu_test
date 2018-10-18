<?php require_once("conn.php"); ?>
<?php session_start(); ?>
<?php
	if(empty($_SESSION['login_type'])){//判断是否登录。
		header("Location:login.html");
	}
	
	 ?>
<?php
  
  			//查询所有课程，并将课程存在option中，方便在html中显示成select   option 
		
		$select_cors = "select * from cors_info";
		$select_cors_result = $conn->query($select_cors);
		$cors_name_option = "<option value = ''>空</option>";
		$cors_id_option = "<option value = ''>空</option>";
		if($select_cors_result){
			while($row = $select_cors_result->fetch_assoc()){
				//echo $row['test_course'];
				$cors_name_option = $cors_name_option."<option value = '{$row['test_course']}'>{$row['test_course']}</option>";
				$cors_id_option = $cors_id_option."<option value = '{$row['cors_no']}'>{$row['test_course']}</option>";
			}
			//echo $cors_option;
		}
	if(!empty($_SESSION['username'])){//修改学生信息
		$stu_no = $_SESSION['username'];//先打印学生信息，到一个form中，对from内容直接进行修改，提交
		//$stu_no = 151007101;
		$select_sql = "select * from stu_info where stu_no = $stu_no";
		$result = $conn->query($select_sql);
		if($result){
			while($row = $result->fetch_assoc()){
			echo "<form action = 'student_update.php' method = 'post'>
				学号:<input  name ='stu_no'title='该字段不允许修改' onfocus=this.blur() value = '{$row['stu_no']}' >
				班级:<input type ='number' name = 'stu_class' value = '{$row['stu_class']}'>
				院系:<input type ='number' name = 'fac_no' value = '{$row['fac_no']}'>
				课程：
				<select name = 'test_course'  require>
					".$cors_name_option."
				</select>
				姓名:<input type ='text' name = 'stu_name' value = '{$row['stu_name']}'>
					<input type = 'submit' name = 'update' value = '修改'>
			</form>";
				}
			}else{
			echo "die";
		}	
	echo "<br />";
	$test_sql="select * from test_info where stu_no = $stu_no";
		$test_result = $conn->query($test_sql);
		if($test_result){
			while($row = $test_result->fetch_assoc()){
				echo "<table border = '1'><tr>";
				echo "<td>学号</td>";
				echo "<td>姓名</td>";
				echo "<td>教室</td>";
				echo "<td>座号</td>";
				echo "<td>开始时间</td>";
				echo "<td>结束时间</td>";
				echo "<td>考试科目</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>{$row['stu_no']}</td>";
				echo "<td>{$row['stu_name']}</td>";
				echo "<td>{$row['classroom_name']}</td>";
				echo "<td>{$row['test_no']}</td>";
				echo "<td>{$row['start_time']}</td>";
				echo "<td>{$row['end_time']}</td>";
				echo "<td>{$row['test_course']}</td>";
				echo "</tr></table>";
			}
		}
	
	}


  
?>