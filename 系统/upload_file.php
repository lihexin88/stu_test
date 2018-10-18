<?php
	header("Content-Type: text/html; charset=utf-8");
	$servername = "120.24.48.59";
	$username = "root";
	$password = "Lihexin123";
	$dbname = "userbooks";
	// 创建连接时直接打开数据库
	$conn = new mysqli($servername, $username, $password, $dbname);
	$conn -> set_charset('utf8');
?>
<?php
	$year = date ( 'Y' );   //获得年份, 例如： 2006  
	$month = date ( 'n' );  //获得月份, 例如： 04  
	$day = date ( 'j' );    //获得日期, 例如： 3  
	$dir = iconv("UTF-8", "GBK", "upload/".$year."/".$month."/".$day."/");
       	if(is_dir($dir)){
            echo '已经存在';
            echo $dir;
        } else {
        	mkdir($dir,0777,true);
            echo '创建文件夹成功';
        }

 if($_POST['from'] == "books"){//接收从books传过来的文件
  if ($_FILES["file"]["error"] > 0)//接收失败
    {
    echo "失败返回码： " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
	    if(!empty($_POST['username'])){
	    	$username = $_POST['username'];
	    	$user_question = $_POST['user_question'];
	    	$user_insert = "insert into user_info(username,user_question) values('$username','$user_question');";
	    	$user_insert_result = $conn->query($user_insert);
	    	if($user_insert_result){
	    		echo "insert secuss!";
	    	}else{
	    	echo 	$conn->error;
	    	}
	    }

	    echo "文件名: " . $_FILES["file"]["name"] . "<br />";
	    echo "类型: " . $_FILES["file"]["type"] . "<br />";
	    echo "大小: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
	    echo "临时文件夹: " . $_FILES["file"]["tmp_name"] . "<br />";
	    if (file_exists( $dir. $_FILES["file"]["name"]))
	      {
	      echo $_FILES["file"]["name"] . " 已存在 ";
	      }
	    else
	      {
	      move_uploaded_file($_FILES["file"]["tmp_name"],$dir.$_FILES["file"]["name"]);
	      echo "Stored in: " . $dir . $_FILES["file"]["name"];
	      }
	}
  }
?>