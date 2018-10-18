<?php require_once("conn.php"); ?>



<?php 
       function check_dir(){
       	$year = date ( 'Y' );   //获得年份, 例如： 2006  
       	$month = date ( 'n' );  //获得月份, 例如： 04  
       	$day = date ( 'j' );    //获得日期, 例如： 3  
       	$dir = iconv("UTF-8", "GBK", "ali-oss/".$year."/".$month."/".$day);
       	if(is_dir($dir)){
            echo '已经存在';
            echo $dir;
        } else {
        	mkdir($dir,0777,true);
            echo '创建文件夹成功';
        }
       }
	check_dir();

?> 