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
<link rel="stylesheet" type="text/css" href="css/stu_display.css"/>
<?php 
	if(!empty($_COOKIE['login_username'])||!empty($_COOKIE['login_password']))//判断用户
	{
		$user_info =  "当前用户：".$_COOKIE['login_username'];
		echo $user_info;
	}
	echo "<form style = 'float:right;margin-right:183px'  action='select.php' method='post'>
				<input type='text' name='stu_select_type' id='stu_select_type' value='1' style='display: none;' />
				<input name='stu_select' id='stu_no' value='' placeholder='学生有关信息' />
				<input type='submit' value='模糊查询' />
		</form>";
?>
<?php
	if(!empty($_COOKIE['message']))
	{
		$message = $_COOKIE['message'];
		echo $message;
		setcookie('message',NULL);//cookie三联，获取，打印，设空！
	}
	if(!empty($_GET['p'])){
		$page = $_GET['p'];	
	}else{
		$page = 2;
	}
	$page_size = 20;
	//计算记录的起始位置
	$record = ($page - 1) * $page_size;
	//显示的页码数
	$show_page = 11;
	//处理我们的数据
	echo "<div class='content'>";
		
    echo "<form action = 'delete.php' method = 'post'>";//提供多条删除

	echo "<table width='80%' style = 'text-align:center'  border = '1' cellspacing = '0' >";
	echo "<tr style = 'text-align:center'>";
	echo "<td></td>";
    echo " <td>学号</td>";
    echo " <td>姓名</td>";
	echo " <td>班级</td>";
    echo " <td>院系</td>";
    echo " <td>课程</td>";
    echo " <td colspan=3>操作</td>";
    echo "</tr>";
	$sql = "SELECT * FROM stu_info limit $record, $page_size";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()){
		echo "<tr>";
		echo "<td><input  name = 'delete_stu[]' type = 'checkbox' value = '{$row['stu_no']}'>{$row['stu_no']}</td>";//多条删除
		echo "<td>{$row['stu_no']}</td>";
		echo "<td>{$row['stu_name']}</td>";
		echo "<td>{$row['stu_class']}</td>";
		echo "<td>{$row['fac_no']}</td>";	
		echo "<td>{$row['test_course']}</td>";
		//修改数据
		echo "<form></form>";//结束上一个form，避免出现form内嵌套form
		echo "<td><form class = 'stu_display_form' action = 'update_select.php' method = 'post'><input style = 'display:none' name = 'stu_no' value = '{$row['stu_no']}'><input type = 'submit' value = '修改'></form></td>";
		//删除数据
		echo "<td><form class = 'stu_display_form' action = 'delete.php' method = 'post'><input style = 'display:none' name = 'delete_stu[]' value = '{$row['stu_no']}'><input type = 'submit' value = '删除'></form></td>"	;
		echo "<td></td>";
		echo "</tr>";
	}
	echo "<tr><td style = 'text-align:center'><input style = 'width:100%' type = 'submit' value = '多条删除'></td></tr></table></form>";
	echo '</div>';
	//获取数据总数
	$sql = "SELECT count(*) AS count FROM stu_info";
	$result = $conn->query($sql);
	$total_result = $result->fetch_assoc();
	$total = $total_result['count'];
	 //echo "总条数：".$total;
	 //计算页数
	 $total_pages = ceil($total/$page_size);
	//显示数据+分页条
	$p1 = $page - 1;
	$p2 = $page + 1;
	$page_banner = "";
	//计算偏移量
	$page_offset = ($show_page - 1)/2;
	$start = 1;
	$end = $total_pages;

	if($page > 1){
		$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=1'>首页 </a>";
	    $page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=$p1'> 上一页 </a>";
		}else{
			$page_banner .= "<span class='disable'>首页 </span>";
			$page_banner .= "<span class='disable'> 上一页 </span>";
		}
	if($total_pages > $show_page){//若总页数比要显示的页码数多，开始处理
		  if($page > $page_offset + 1){ //如果当前页比偏移量多1，那么出现...表示有页码没有显示
			  $page_banner .= "...";
		  }
		  if($page > $page_offset){
		  	//若当前页大于偏移量，证明前面足够的页码能够显示
		  	//但是后面的页码不一定，所以要判断后面的页码数是否够
			$start = $page - $page_offset;
			$end = $total_pages > $page + $page_offset ? $page + $page_offset : $total_pages;
		}else{
			//如果不大于偏移量，前面的页码不够显示，则从1开始显示
			//还要判断总页数是否够显示的页码数
			$start = 1;
			$end = $total_pages > $show_page ? $show_page : $total_pages;
		}
		if($page + $page_offset > $total_pages){
			//调整末尾几页的起始页码数
			$start = $start - ($page + $page_offset - $end);
			
		}
	}
    
	for($i = $start; $i <= $end; $i++){//循环显示页码数
		if($page == $i){
			$page_banner .= "<span class='current'>$i</span>";
		}else{
		$page_banner .= "<a  href='".$_SERVER['PHP_SELF']."?p=$i'> $i </a>";
		}	
	}
	
	//尾部省略
	if($total_pages > $show_page && $total_pages > $page + $page_offset){
		$page_banner .= "...";
	}
	
	if($page < $total_pages){
	   $page_banner .= "<a  href='".$_SERVER['PHP_SELF']."?p=$p2'>下一页</a>";
	   $page_banner .= "<a  href='".$_SERVER['PHP_SELF']."?p=$total_pages'>尾页</a>";
	}else{
		$page_banner .= "<span class='disable'>下一页</span>";
		$page_banner .= "<span class='disable'>尾页</span>";		
		}
	$page_banner .= "共{$total_pages}页";
	$page_banner .= "<form action='insert.php' method='get' >";
	$page_banner .= "<input type='number' size='2'max={$total_pages} min=0 name='p' />";
	$page_banner .="<input type='submit' value='跳转'>";
	$page_banner .= '</form>';
	echo '<br />';
	echo '<div class="page">';
	echo $page_banner;
	echo '</div>';
?>