<? 
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Login		                					                      |
// | Requirements: n/a				                                      |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: July 8, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
//error_reporting(E_ALL);
include 'functions.php';
dbConnect();
$good='';
$error='';
if (isset($_POST['submit']) || ( isset($_GET['email']) && isset($_GET['password']))){
	if (isset($_POST['username'])){
	$user = $_POST['username'];
	$pass = $_POST['password'];
	}else{
	$user = $_GET['email'];
	$pass = $_GET['password'];
	}
	$q1 = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";		
	$r1 = @mysql_query ($q1) or die(mysql_error());
	if ($data = mysql_fetch_array($r1, MYSQL_ASSOC)){
		if (isset( $_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
		$ip = "DIRECT";
		}
		$proxy = $_SERVER['REMOTE_ADDR'];
		@mysql_query("UPDATE users SET system_ip='$ip', system_proxy='$proxy' WHERE username = '$user' AND password = '$pass'");
			setcookie ("userdata[user_id]", $data['user_id'], "0", "/", ".mdwestserve.com");
			setcookie ("userdata[email]", $data['email'], "0", "/", ".mdwestserve.com");
			setcookie ("userdata[name]", $data['name'], "0", "/", ".mdwestserve.com");
			setcookie ("userdata[tag]", $data['tag'], "0", "/", ".mdwestserve.com");
			setcookie ("userdata[status]", $data['status'], "0", "/", ".mdwestserve.com");
			setcookie ("userdata[isadmin]", $data['isadmin'], "0", "/", ".mdwestserve.com");
			setcookie ("userdata[ACok]", $data['ACok'], "0", "/", ".mdwestserve.com");
			setcookie ("userdata[home]", $data['home_page'], "0", "/", ".mdwestserve.com");
		$good="1";
	} else {
		//log_action(0,"Attempted Login by $user using $pass");
		$error = "Invalid Username / Password";
	}
}
?>
<body background="gfx/login.jpg">
<h1 align="center">HWA llc. Accounting Core</h1>
<h3 align="center">AC-CORE Beta</h3>
<table align="center" border="1" bgcolor="#FF6600" cellspacing="0">
<? if (!isset($_GET['account'])){ ?>
<form method="post">
<? 
if ($good){
	echo "<script>window.location='home.php';</script>";
}else{
	if ($error){ ?>
	<tr>
		<td bgcolor="#FFFF00" colspan="2" align="center"><?=$error?></td>
	</tr>
	<? } ?>
	<tr>
		<td colspan="2" align="center">Please Log In</td>
	</tr>
	<tr>
		<td>Username</td>
		<td><input name="username" type="text" /></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input name="password" type="password" /></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input name="submit" type="submit" value="Log In"></td>
	</tr>
</form>
<? } } ?>
</table>
<script>function setSize(width,height) {
	if (window.outerWidth) {
		window.outerWidth = width;
		window.outerHeight = height;
	}
	else if (window.resizeTo) {
		window.resizeTo(width,height);
	}
	else {
		alert("Not supported.");
	}
}

//setSize(800,1000)
</script>
