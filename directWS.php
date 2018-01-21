<?php
session_start();
if (!isset($_SESSION['valid']) || $_SESSION['valid'] != 'Y'){
    ?>
    <script>
        alert('您無權限觀看此頁面');
        location.replace("index.html");
    </script>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="icon.png">
	<meta name="description" content="國立臺灣大學新生盃羽球賽報名系統">
	<meta name="author" content="國立臺灣大學羽球校隊">
	<title>國立臺灣大學新生盃羽球賽</title>
	<link href="bootstrap.min.css" rel="stylesheet">
	<link href="custom.css" rel="stylesheet">
</head>
<body>
	<header>
		<div class="container">
			<h1 class="center">2018新生盃羽球賽報名表-女單</h1>
		</div>
	</header>

	<section class="content">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<p>項目：<input type="text" value="女單" readonly /></p>
					<p>學號：<input type="text" id="id" onchange="check_id()" /></p>
					<p class="text-danger" id="id_result"></p>
					<p>姓名：<input type="text" id="name" /></p>
					<p>系別：<input type="text" id="major" /></p>
					<p>年級：<select id="grade">
							<option value=""></option>
							<option value="A">大一</option>
							<option value="B">大二</option>
							<option value="C">碩一</option>
							<option value="D">博一</option>
					</select></p>
					<p>聯絡電話：<input type="text" id="phone" /></p>
					<p>出生日期：<input class="smaller_box" type="text" id="birthy" placeholder="西元" /> 年
						<input class="smallest_box" type="text" id="birthm" /> 月
						<input class="smallest_box" type="text" id="birthd" /> 日</p>
					<p>身分證字號：<input type="text" id="identity" /></p>
					<button onclick="sign_up()">確定報名</button>
				</div>
			</div>
		</div>
	</section>

	<section class="footer">
		<div class="container">
			<div class="row center">
				<span><a href="https://www.facebook.com/ntubadminton2012/?fref=ts" target="_blank"><img src="facebook.png" class="small_pic" /></a></span>
				<span class="small">&copy; 2018 NTU Badminton All Rights Reserved</span>
			</div>
		</div>
	</section>

	<script type="text/javascript">
		function check_id() {
			var request = new XMLHttpRequest();
			request.open("POST", "service.php");
			var data = "type=WS&id=" + document.getElementById("id").value;
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.send(data);

			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200) {
					var data = JSON.parse(request.responseText);
					if (data.msg != 'ok') {
						document.getElementById("id_result").innerHTML = data.msg;
					}
					else {
						document.getElementById("id_result").innerHTML = "";
					}
				}
			}
		}

		function sign_up() {
			var request = new XMLHttpRequest();
			request.open("POST", "service.php");
			var data = "new=directWS&type=WS" + 
					   "&id=" + document.getElementById("id").value +
					   "&name=" + document.getElementById("name").value +
					   "&major=" + document.getElementById("major").value +
					   "&grade=" + document.getElementById("grade").value +
					   "&phone=" + document.getElementById("phone").value +
					   "&birthy=" + document.getElementById("birthy").value +
					   "&birthm=" + document.getElementById("birthm").value +
					   "&birthd=" + document.getElementById("birthd").value +
					   "&identity_W=" + document.getElementById("identity").value;
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.send(data);

			request.onreadystatechange = function() {
				if (request.readyState === 4 && request.status === 200){
					var data = JSON.parse(request.responseText);
					if (data.msg == 'ok'){
						alert('報名成功，您的編號為 ' + data.num + '。請於指定時間內繳費');
						location.replace("index.html");
					}
					else {
						alert(data.msg);
					}
				}
			}
		}
	</script>
</body>
</html>