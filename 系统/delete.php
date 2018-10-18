<?php require_once("conn.php");  ?>
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
	if(!empty($_POST['delete_stu'])){
		$delete_stu = implode(",",$_POST['delete_stu']);//将数组元素拆分，用,隔开，比如arr[]拆分为1,2,3
		$delete_sql="delete from stu_info where stu_no in($delete_stu)";
		$delete_result = $conn->query($delete_sql);
			if(!$delete_result){
				echo "删除失败！";
			}else{
			echo "<script>alert('{$delete_stu}删除成功！！');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";//返回上一页并且刷新
		}
	}else{
		echo "删除失败！！";
	}
?>