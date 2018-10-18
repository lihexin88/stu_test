<?php require_once("conn.php");		?>
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
	//$cors_no = 10002;
	if(!empty($_POST['cors_no'])){
	//	$cors_no = $_COOKIE['cors_no'];
	$cors_no = $_POST['cors_no'];
	}
	$classroom_sql = "select * from classroom_info;";
	$course_selected_sql = "select * from cors_info where cors_no = $cors_no;";
	$selected = NULL;//选择的人数
	$size_sum = NULL;//教室容量总和
	$classroom_tobe_use[] = NULL;//将要使用的教室
	$clasrom = 0;//
	$course_name = NULL;//课程名
	$course_selected_result = $conn->query($course_selected_sql);
	if($course_selected_result){
		while($row = $course_selected_result->fetch_assoc()){
			$selected = $row['cors_selected'];
			$course_name = $row['test_course'];
		}
	}
	echo "选择课程的人数是<br>".$selected;
	$classroom_result = $conn->query($classroom_sql);
			if($classroom_result){
						echo "<table width = 40% border = '1' cellspacing = '0'  align='center'>
						<tr>
							<td>是否使用</td>
							<td>容量</td>
							<td>教室</td>
							<td colspan = 2>考试时间</td>
						</tr>";
						while($row = $classroom_result->fetch_assoc()){//循环查询classroom表，主要是查询教室容量以及教室id
							if($row['classroom_used']!=1){//如果教室没有被使用，则进行筛选
								echo "<td>空</td>";//打印空的标志
								if($selected>$size_sum){//如果选课人数大于教室容量总和，继续进行查询，并且继续进行教室容量总和累加
								$classroom_tobe_use[$clasrom] =  $row['classroom_id'];//将教室id保存在数组中
								$clasrom = $clasrom + 1;
								$size_sum = $size_sum + $row['classroom_size']/2;	//累加教室容量
								}
							}else{
								echo "<td>已使用！！</td>";
							}
							echo "<td> {$row['classroom_size']} </td>";
							echo "<td> {$row['classroom_name']} </td>";
							echo "<td> {$row['start_time']} </td>";
							echo "<td> {$row['end_time']} </td>";
							echo "</tr>";
						}
			echo "</table>";
			if($size_sum<$selected){
				echo "<script>alert('教室不够！')</script>";
			}else{
			echo "教室总数量".$size_sum."使用的教室：";
			$classroom_tag = $classroom_tobe_use;
			$classroom_tobe_use = implode(",",$classroom_tobe_use);//将教室id保存在数组中,并且进行拆分  为1,2,3之类的
			echo $classroom_tobe_use;
			//-------------------------开始修改教室表
			$start_time = $_POST['start_time'];
			$end_time = $_POST['end_time'];
			$update_classroom_used = "update classroom_info set course_id = $cors_no,start_time = '$start_time',end_time = '$end_time' where classroom_id in($classroom_tobe_use); ";
			echo $update_classroom_used;
			$update_classroom_result = $conn->query($update_classroom_used);
			if($update_classroom_result){
				//开始给每个学生安排考号。
				$classroom_num = count($classroom_tag);
				echo $classroom_num."//打印教室总数量";
				for($i = 0;$i < $classroom_num;$i++ ){//从教室开始更新学生信息
						$now_id = $classroom_tag[$i];
						print_r($classroom_tag)."教室数组<br >";
						echo "当前教室".$now_id;
						$classroom_size = "select * from classroom_info where classroom_id = $now_id";
						echo $classroom_size."获取教室信息";
						$size_select = $conn->query($classroom_size);
						$size = 0;//教室容纳考生数量
						$name = NULL;//教室名
						while($row = $size_select->fetch_assoc()){
							$size = $row['classroom_size']/2;
							$name = $row['classroom_name'];
						}//得到当前教室的考生容量,和教室名
					for($k = 1;$k <= $size;$k++ ){
							$update_test_info = "update test_info";
							$old_no = $k-1;
							$update_test_info.=" set classroom_name ='$name',test_no=$k,start_time='$start_time',end_time ='$end_time' where (test_course = '$course_name' AND test_no is NULL) limit 1;";
							$update_stu = $conn->query($update_test_info);
							if(!$update_stu){
								echo $conn->error;
							}
					}
				}
					header("Location:select.php?classroom_id=select");
				
			}else{
				echo $conn->error;
			}			
				}

			}
	 ?>