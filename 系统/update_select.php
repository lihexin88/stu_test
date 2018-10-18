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

	if(!empty($_POST['stu_no'])){//修改学生信息
		$stu_no = $_POST['stu_no'];//先打印学生信息，到一个form中，对from内容直接进行修改，提交
		//$stu_no = 151007101;
		$select_sql = "select * from stu_info where stu_no = $stu_no";
		$result = $conn->query($select_sql);
		if($result){
			while($row = $result->fetch_assoc()){
			echo "<form action = 'update.php' method = 'post'>
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
	}
	
	if(!empty($_POST['classroom_id'])){//打印教室信息
		$classroom_id = $_POST['classroom_id'];
		//$classroom_id = 1;
		$clsaaroom_update_sql = "select * from classroom_info where classroom_id = '$classroom_id';";
		echo $clsaaroom_update_sql;
		$classroom_update_select = $conn->query($clsaaroom_update_sql);
		if($classroom_update_select){
			while($row = $classroom_update_select->fetch_assoc()){
			echo "<form action = 'update.php' method = 'post'>
				教室id:<input  name ='classroom_id'title='该字段不允许修改' onfocus=this.blur() value = '{$row['classroom_id']}' >
				教室编号:<input title='该字段不允许修改' onfocus=this.blur() type ='text' name = 'classroom_name' value = '{$row['classroom_name']}'>
				教室容量:<input type ='number' title='该字段不允许修改' onfocus=this.blur() name = 'classroom_size' value = '{$row['classroom_size']}'>
				考试课程:
				<select name = 'course_id'>
					".$cors_id_option."
				</select>
				
				开始时间：<input type ='datetime-local' name = 'start_time' value = '{$row['start_time']}' >
				结束时间：<input type ='datetime-local' name = 'end_time' value = '{$row['end_time']}'>
						<input type = 'submit' name = 'update' value = '修改'>
			</form>";
		
			}
		}
	}
?>