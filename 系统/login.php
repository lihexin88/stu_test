<?php 
	/*
	 * 把用户信息存储到 PHP session 中之前，首先必须启动会话。注释
	 * session_start() 函数必须位于 <html> 标签之前：
	 */
	session_start(); 
?>
<?php 
require_once('conn.php'); 
?>
<?php
	/*
	 * 使用用户输入的用户名和密码到数据库中进行查询，若用户名密码正确则到首页面，
	 * 否则返回登录页面
	 */
	if($_POST['usertype'] =='学生'){
		echo "学生登录";
		$sql = "select * from stu_info where stu_no = '{$_POST['username']}' and password = '{$_POST['password']}';";
		$result = $conn->query($sql);
	 	$conn -> set_charset('utf8');
	 	if ($result->num_rows > 0){
	 		$_SESSION['login_type'] = $_POST['usertype'];
	 		$_SESSION['username'] = $_POST['username'];
	 			 header("Location:student.php");
	 	}else{
	  		 header("Location:login.html");
  		 }
	}elseif($_POST['usertype'] =='教师'){//教师
		echo "教师登录";
		$sql = "select * from cors_info where cors_teacher = '{$_POST['username']}' and password = '{$_POST['password']}';";
		$result = $conn->query($sql);
	 	$conn -> set_charset('utf8');
	 	if ($result->num_rows > 0){
	 		$_SESSION['login_type'] = $_POST['usertype'];
	 		$_SESSION['username'] = $_POST['username'];
	 		header("Location:teacher.php");
	 	}else{
	  		 header("Location:login.html");
  		 }
	}else{//管理员登录
	$sql = "select * from manager_account where manager_username = '";
	$sql.= $_POST['username']."' and manager_password = '";
	$sql.= $_POST['password']."';";
	$result = $conn->query($sql);
	 $conn -> set_charset('utf8');
//	if ($result->num_rows > 0) {
//	    // 若存在此用户
//	        echo "success";  
//	} else {
//	    echo "wrong";
//	}
	/*
	 * SQL测试通过后，页面进行跳转
	 * 上面语句就是测试用，通过后用下面的语句进行跳转
	 */
   if ($result->num_rows > 0){
   	  /*
   	   * 保存用户名，到下个页面使用
   	   */
   	  
	 	$_SESSION['login_type'] = $_POST['usertype'];
   	    $_SESSION['username'] = $_POST['username'];
   	    $login_username =$_POST['username'];
   	    $login_password = $_POST['password'];
   	    setcookie('login_username',$login_username);//记录登录的账户名和密码 
		setcookie('login_password',$login_password);
		header("Location:test_anpai.php");//跳转主页，并将数据传递过去。
		//确保重定向后，后续代码不会被执行 
        exit;
   }else{
	   header("Location:login.html");
   }	
	}
	
?>