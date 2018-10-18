<?php require_once("conn.php");		?>
<?php 
	session_start(); 
?>
<link rel="stylesheet" type="text/css" href="css/stu_display.css"/>
<?php
	if(empty($_SESSION['login_type'])){//判断是否登录。
		header("Location:login.html");
	}
	
	 ?>
<?php
	if(!empty($_COOKIE['message']))
	{
		$message = $_COOKIE['message'];
		echo $message;
		setcookie('message',NULL);//cookie三联，获取，打印，设空！
	}
	?>
<?php
		if(!empty($_POST['stu_select_type'])||!empty($_POST['stu_detials'])){//查询学生信息
		if(!empty($_POST['stu_detials'])){
				$stu_detials = $_POST['stu_detials'];
				$select_sql="select * from stu_info where stu_no = '$stu_detials'";
				echo $select_sql;
		}else if(!empty($_POST['stu_select_type'])&&empty($_POST['stu_select'])){
 				header("Location:stu_display.php");	
		}else{
				$stu_select = $_POST['stu_select'];
				$select_sql="select * from stu_info where stu_no like '%$stu_select%' or stu_class like '%$stu_select%' or fac_no like '%$stu_select%' or test_course like '%$stu_select%' or stu_name like '%$stu_select%' ";
			}
		$result = $conn->query($select_sql);
			if($result){
				if(!empty($_POST['stu_select'])){//如果数据来源于模糊查询，开始计算模糊查询返回的数量
						$sql = "SELECT count(*) AS count FROM stu_info where stu_no like '%$stu_select%' or stu_class like '%$stu_select%' or fac_no like '%$stu_select%' or test_course like '%$stu_select%' or stu_name like '%$stu_select%' ";
						$result_totle = $conn->query($sql);
						$total_result = $result_totle->fetch_assoc();
						$total = $total_result['count'];//计算所有条数
						echo "查询到有关<span style='color:blue'>'".$stu_select."'</span>的信息为：".$total."条";					
				}

						echo "<table width = 80% border = '1' cellspacing = '0' >
						<tr style='text-align:center'>
							<td>学号</td>
							<td>班级</td>
							<td>院系</td>
							<td>课程</td>
							<td>姓名</td>
							<td colspan = 2>操作</td>
						</tr>";
						while($row = $result->fetch_assoc()){
							echo "<tr>";
							echo "<td> {$row['stu_no']} </td>";
							echo "<td> {$row['stu_class']} </td>";
							echo "<td> {$row['fac_no']} </td>";
							echo "<td> {$row['test_course']} </td>";	
							echo "<td> {$row['stu_name']} </td>";				
							//修改数据
							echo "<td>
								<form id = 'stu_display_form' action = 'update_select.php' method = 'post'>
									<input style = 'display:none' name = 'stu_no' value = '{$row['stu_no']}'>
									<input type = 'submit' value = '修改'>
								</form>
								</td>";
							//删除数据
							echo "<td><form id = 'stu_display_form' action = 'delete.php' method = 'post'><input style = 'display:none' name = 'delete_stu[]' value = '{$row['stu_no']}'><input type = 'submit' value = '删除'></form></td>"	;
							echo "</tr>";
						}
			echo "</table>";
			}else{
				echo "die";
				}
	}
//----------------------------学生信息查询结束
if(!empty($_POST['cors_selected'])||!empty($_POST['cors_stu_selected'])){//课程信息查询
		if(!empty($_POST['cors_stu_selected'])){//模糊查询
				$cors_stu_selected = $_POST['cors_stu_selected'];
				$select_sql="select * from cors__classroom where test_course = '$cors_stu_selected';";
		}else{
		$cors_selected = $_POST['cors_selected'];//详细查询
		$select_sql="select * from cors_classroom where test_course like '%$cors_selected%' ";
			}
			echo $select_sql;
		$result = $conn->query($select_sql);
			if($result){
						echo "<table width = 40% border = '1' cellspacing = '0'  align='center'>
		<tr>
			<td>课程名</td>
			<td>教室</td>
		</tr>";
			while($row = $result->fetch_assoc()){
	echo "<tr>";
	echo "<td> {$row['test_course']} </td>";
	echo "<td> {$row['classroom_name']} </td>";					
	echo "</tr>";
	}
	echo "</table>";
			}else{
				echo "die";
				}
}

if(!empty($_POST['course_infos'])||!empty($_POST['course_selecte_type'])){//课程信息查询
	if(!empty($_POST['course_infos'])){
		$course_infos = $_POST['course_infos'];
		$course_select = "select * from stu_info where  test_course = '$course_infos' ;";
	}else{
		header("Location:cors_display.php");
	}
		echo $course_select;
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

if(!empty($_POST['classroom_info'])||(!empty($_GET['classroom_id']))||(!empty($_COOKIE['course_id']))){
	if($_SESSION['login_type']=='教师'){
		$cors_teacher = $_SESSION['username'];
		$select_cors = "select * from cors_info where cors_teacher = '$cors_teacher'";
		$select_cors_result = $conn->query($select_cors);
		$course_id = NULL;
		if($select_cors_result){
			while($row = $select_cors_result->fetch_assoc()){
				$course_id = $row['cors_no'];
			}
		}
			$classroom_sql = "select * from classroom_info where course_id ='$course_id' ;";
		}elseif($_SESSION['login_type']=='管理员'){
			$classroom_sql = "select * from classroom_info;";	
	}
	$result = $conn->query($classroom_sql);
	if($result){
		echo "<table width='80%'  border = '1' cellspacing = '0'  align='center'>
		<tr>
			<td width = '65px'>教室编号</td>
			<td width = '65px' style = 'text-align:center'>地点</td>
			<td style = 'text-align:center'>容量</td>
			<td style = 'text-align:center'>是否使用</td>
			<td style = 'text-align:center' width = '85px'>安排的课程</td>
			<td style = 'text-align:center'>考试时间</td>
			<td style = 'text-align:center'>修改</td>
		</tr>";
		while($row = $result->fetch_assoc()){
			if(!empty($_GET['classroom_id'])&&(($row['classroom_id']==$_GET['classroom_id']))){//刚更新的教室，显示方式不一样
				echo $row['classroom_id'].$_GET['classroom_id']." <br />  ";	
			echo "<tr class='has_update'>";
			}elseif(!empty($_COOKIE['course_id'])&&($row['course_id'] == $_COOKIE['course_id'])){
				//如果course_id的cookie存在，并且等于当前的course_id显示方式变化
				echo "<tr height = '25px' class='has_update'>";	
			}else{
				echo "<tr height = '25px'>";
			}
			echo "<td style = 'text-align:center'> {$row['classroom_id']}</td>";
			echo "<td style = 'text-align:center'> {$row['classroom_name']}</td>";
			echo "<td style = 'text-align:center'> {$row['classroom_size']}</td>";
			if($row['classroom_used']==1){
				echo "<td style = 'text-align:center'>已使用</td>";
			}else{
				echo "<td style = 'text-align:center'></td>";
			}
			echo "<td style = 'text-align:center'> {$row['course_id']}</td>";
			if($row['classroom_used'] == 0){//如果没有使用，就不显示时间
				echo "<td></td>";
			}else{
				echo "<td style = 'text-align:center'> {$row['start_time']}&nbsp;&nbsp;~&nbsp;&nbsp;{$row['end_time']}</td>";
			}
			
			if($_SESSION['login_type']=='教师'){
				
			echo "<td>
				<form action ='update_select.php' method='post'>
					<input type='text' name='classroom_id' style='display:none'  value='{$row['classroom_id']}'>
					<input style='width:100%' type = 'button'  onfocus=this.blur()  value='已安排'>
				</form>
				</td>";//每个教室信息后面都添加修改按钮，而不是直接在classroom——info中直接修改，防止误操作
			echo "</tr>";
			}else{
				echo "<td>
				<form action ='update_select.php' method='post'>
					<input type='text' name='classroom_id' style='display:none'  value='{$row['classroom_id']}'>
					<input style='width:100%' type='submit' value='修改'>
				</form>
				</td>";//每个教室信息后面都添加修改按钮，而不是直接在classroom——info中直接修改，防止误操作
			echo "</tr>";	
			}
			
			}
		}
		if(!empty($_COOKIE['course_id'])){//第一次打开可能没有cookie['course']。
			setcookie("course_id",NULL);//用完就删除course_id 的cookie
		}
		echo "</table>";
	}


?>