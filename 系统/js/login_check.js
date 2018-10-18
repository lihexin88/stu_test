		function check() {
			var username = document.getElementById("username").value;
			var password = document.getElementById("password").value;
			if(username == "" || password == "") // 如果用户名或者密码为空
			{
				window.alert("用户名或密码不能为空!");
				return false;
			}

			if(window.XMLHttpRequest) {
				// IE7+, Firefox, Chrome, Opera, Safari 浏览器执行的代码
				xmlhttp = new XMLHttpRequest();
			} else {
				//IE6, IE5 浏览器执行的代码
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xmlhttp.onreadystatechange = function() {
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					if (xmlhttp.responseText == "success") {
						//下面两种方式都可以实现跳转						 
						 //window.location.href = "welcome.php"
						document.getElementById("form1").submit();
					} else{
					    document.getElementById("txtHint").innerHTML = "用户名或密码错！";						
					}
				}
			}
			//false就是等待有返回数据的时候再继续往下走，还没有得到数据的时候就会卡在那里，直到获取数据为止。
			//true就是不等待,直接返回，这就是所谓的异步获取数据！	 
			xmlhttp.open("GET", "login.php?username=" + username + "&password=" + password, true);									
			xmlhttp.send(null);
		}
