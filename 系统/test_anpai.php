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

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>教务管理系统</title>
		<link rel="stylesheet" type="text/css" href="css/index.css"/>
	</head>
	<body>
		<div id="logout" style="float: right;margin: 2px;">
			<a href="logout.php"><input  style="width: 100px;height: 25px;border-radius: 10px;" type="button" value="登出" /></a>
		</div>
		<div id="header"><!-- 头部开始-->
			<div id="banner"><!-- banner条，背景郑州航院，title“郑州航院考试安排系统”-->
				<p id = "title_text">教务管理系统</p>
			</div>
			<div id="borders">
				<img style="width: 100%;" src="img/20150121191620_ykCkF.jpeg"/>
			</div>
		</div>
		<div id="mainbody"><!-- 主体部分-->
			<div id="leftbar"><!-- 左侧功能测-->
				<p style="padding:10PX ;"><a  href="stu_os.html" target="iframes"><input style="width: 100%; height: 30px;" type="button" name="" id="" value="学生信息" /></a></p>
					<pre>
		<span style="color: #999988;">              ---> 插入学生信息</span>
		
		<span style="color: #999988;">              ---> 学生基本信息</span>
		
		<span style="color: #999988;">              ---> 考试相关信息</span>
					</pre>
				<p style="padding:10PX ;"><a  href="cors_os.html" target="iframes"><input style="width: 100%;height: 30px;" type="button" name="" id="" value="课程及考试" /></a></p>
					<pre>
		<span style="color: #999988;">              ---> 插入课程信息</span>
		
		<span style="color: #999988;">              ---> 选课情况查询</span>
		
		<span style="color: #999988;">              ---> 教室安排情况</span>
					</pre>
			</div>
			<div id="rightbar"><!-- 右侧显示侧-->
				<iframe src="stu_display.php" scrolling="auto" height="800" width="100%" frameborder="no" name="iframes" id="iframes"></iframe>
			</div>
		</div>
		<div id="footer">
			<br />
			<span style = "color:gray">&copy;Copyright:151007211&nbsp;李贺鑫<span>
		</div>
	</body>
</html>
