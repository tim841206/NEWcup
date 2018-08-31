<?php
$mysql = mysqli_connect('localhost', 'root', '');
mysqli_query($mysql, "SET NAMES 'utf8'");
mysqli_select_db($mysql, 'NEWcup');

function querySignup() {
	$sql = mysqli_query($mysql, "SELECT SIGNUP FROM setup");
	$fetch = mysqli_fetch_row($mysql, $sql);
	$return = ($fetch[0] == 1) ? 1 : 0;
	return $return;
}

function checkManager() {
	if (isset($_COOKIE['account']) && $_COOKIE['account'] == "NTUcup") return true;
	else return false;	
}

if (isset($_GET['signup'])) {
	if (in_array($_GET['signup'], array("MS", "WS", "MD", "WD", "XD"))) {
		if (querySignup()) {
			include_once("view/header.html");
			include_once("view/".$_GET['signup'].".html");
			include_once("view/footer.html");
		}
		else {
			// alert 已不開放報名
			include_once("view/header.html");
			include_once("view/index.html");
			include_once("view/footer.html");
		}
	}
	elseif (in_array($_GET['signup'], array("directMS", "directWS", "directMD", "directWD", "directXD"))) {
		if (checkManager()) {
			include_once("view/header.html");
			include_once("view/".$_GET['signup'].".html");
			include_once("view/footer.html");
		}
		else {
			include_once("view/header.html");
			include_once("view/index.html");
			include_once("view/footer.html");
		}
	}
	else {
		include_once("view/header.html");
		include_once("view/index.html");
		include_once("view/footer.html");
	}
}
elseif (isset($_GET['route'])) {
	if (in_array($_GET['route'], array("login", "document")) {
		include_once("view/header.html");
		include_once("view/".$_GET['route'].".html");
		include_once("view/footer.html");
	}
	elseif ($_GET['route'] == "list") {
		include_once("view/header.html");
		include_once("list.php");
		include_once("view/footer.html");
	}
	elseif ($_GET['route'] == "output") {
		if (checkManager()) {
			include_once("output.php");
		}
		else {
			include_once("view/header.html");
			include_once("view/index.html");
			include_once("view/footer.html");
		}
	}
	elseif (in_array($_GET['route'], array("manager", "update_playerdata")) {
		if (checkManager()) {
			include_once("view/header.html");
			include_once("view/".$_GET['route'].".html");
		}
		else {
			include_once("view/header.html");
			include_once("view/index.html");
			include_once("view/footer.html");
		}
	}
	elseif ($_GET['route'] == "update_paystat" && checkManager()) {
		include_once("view/header.html");
		include_once("update_paystat.php");
		include_once("view/footer.html");
	}
	else {
		include_once("view/header.html");
		include_once("view/index.html");
		include_once("view/footer.html");
	}
}
elseif (isset($_POST['service'])) {
	if ($_POST['service'] == "login") {
		if ($_POST['account'] == "NTUcup" && $_POST['password'] == "0986036999") {
			if (setcookie("account", "NTUcup")) {
				return json_encode(array("msg" => "ok"));
			}
			else {
				return json_encode(array("msg" => "Unable to perform login"));
			}
		}
		else {
			return json_encode(array("msg" => "Wrong account or password"));
		}
	}
	elseif ($_POST['service'] == "logout") {
		if (setcookie("account", "", time()-3600)) {
			return json_encode(array("msg" => "ok"));
		}
		else {
			return json_encode(array("msg" => "Unable to perform logout"));
		}
	}
	elseif ($_POST['service'] == "signup") {
		if (in_array($_POST['type'], array('MS', 'WS', 'MD', 'WD', 'XD'))) return curl_post($_POST);
		elseif (in_array($_POST['type'], array('directMS', 'directWS', 'directMD', 'directWD', 'directXD'))) {
			if (checkManager()) return curl_post($_POST);
			else return // not manager
		}
	}
}
elseif (isset($_GET['service'])) {
	if ($_GET['service'] == "clearList") {
		if (checkManager()) {
			$deleteMS = "DELETE FROM MS WHERE 1";
			mysqli_query($mysql, $deleteMS);
			$deleteWS = "DELETE FROM WS WHERE 1";
			mysqli_query($mysql, $deleteWS);
			$deleteMD = "DELETE FROM MD WHERE 1";
			mysqli_query($mysql, $deleteMD);
			$deleteWD = "DELETE FROM WD WHERE 1";
			mysqli_query($mysql, $deleteWD);
			$deleteXD = "DELETE FROM XD WHERE 1";
			mysqli_query($mysql, $deleteXD);
			$init = "UPDATE setup SET MS_NUM=1, WS_NUM=1, MD_NUM=1, WD_NUM=1, XD_NUM=1";
			mysqli_query($mysql, $init);
			// alert success
			include_once("view/header.html");
			include_once("view/manager.html");
		}
		else {
			include_once("view/header.html");
			include_once("view/index.html");
			include_once("view/footer.html");
		}
	}
	elseif ($_GET['service'] == "checkList") {
		if (checkManager()) {
			$deleteMS = "DELETE FROM MS WHERE PAYSTAT=0";
			mysqli_query($mysql, $deleteMS);
			$deleteWS = "DELETE FROM WS WHERE PAYSTAT=0";
			mysqli_query($mysql, $deleteWS);
			$deleteMD = "DELETE FROM MD WHERE PAYSTAT=0";
			mysqli_query($mysql, $deleteMD);
			$deleteWD = "DELETE FROM WD WHERE PAYSTAT=0";
			mysqli_query($mysql, $deleteWD);
			$deleteXD = "DELETE FROM XD WHERE PAYSTAT=0";
			mysqli_query($mysql, $deleteXD);
			// alert success
			include_once("view/header.html");
			include_once("view/manager.html");
		}
		else {
			include_once("view/header.html");
			include_once("view/index.html");
			include_once("view/footer.html");
		}
	}
	elseif ($_GET['service'] == "closeSignup") {
		if (checkManager()) {
			$sql = "UPDATE setup SET SIGNUP=0";
			if (mysqli_query($mysql, $sql)) {
				// alert success
			} else {
				// alert fail
			}
			include_once("view/header.html");
			include_once("view/manager.html");
		}
		else {
			include_once("view/header.html");
			include_once("view/index.html");
			include_once("view/footer.html");
		}
	}
	elseif ($_GET['service'] == "openSignup") {
		if (checkManager()) {
			$sql = "UPDATE setup SET SIGNUP=1";
			if (mysqli_query($mysql, $sql)) {
				// alert success
			} else {
				// alert fail
			}
			include_once("view/header.html");
			include_once("view/manager.html");
		}
		else {
			include_once("view/header.html");
			include_once("view/index.html");
			include_once("view/footer.html");
		}
	}
}
else {
	include_once("view/header.html");
	include_once("view/index.html");
	include_once("view/footer.html");
}

function curl_post($post) {
	$ch = curl_init();
	$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http";
	curl_setopt($ch, CURLOPT_URL, $protocol.'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/service.php');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}