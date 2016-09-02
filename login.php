<?php
if (isset($_POST["sid"])) {
    $link = mysql_connect(SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
    if ($link) {
        mysql_select_db(SAE_MYSQL_DB, $link);
    } else {
        die(mysql_error());
    }
    $command = "SELECT * FROM tjstudents WHERE id='$_POST[sid]'";
    if (mysql_num_rows($rows = mysql_query($command)) == 0) {
        $command = "INSERT INTO tjstudents (id, name) VALUES('$_POST[sid]', '$_POST[sname]')";
        mysql_query($command);
    } else {
        $row = mysql_fetch_array($rows, MYSQL_ASSOC);
        if ($_POST["sname"] != $row["name"]) {
            echo "<script>alert('姓名不对!');</script>";
            echo "<script>window.location.href='/login';</script>";
        }
    }
    session_start();
    $_SESSION["sid"] = $_POST["sid"];
    if ($_POST["remember-me"] == "remember-me") {
        $hashid = sha1(mt_rand() . session_id() . mt_rand());
        $command = "INSERT INTO persistent_cookies (id, hashid, expire) VALUES('$_SESSION[sid]', '$hashid', TIMESTAMPADD(DAY,30,NOW()))";
        mysql_query($command);
        setcookie("__scid", $hashid, time() + 3600 * 24 * 30);
        setcookie("__rcv", "y", time() + 3600 * 24 * 30);
    }
    mysql_close($link);/*
header('Location: '.$_POST["redirecturl"]);*/
    header('Location: /');
}
?>

<html lang="zh-CN">
  <head>
    <title>登录</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="renderer" content="webkit">

    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="/favicon.ico" rel="icon">

    <style type="text/css">      body{padding-top:40px;padding-bottom:40px;background-color:#f5f5f5}.form-signin{max-width:300px;padding:19px 29px 29px;margin:0 auto 20px;background-color:#fff;border:1px solid #e5e5e5;border-radius:5px;box-shadow:0 1px 2px rgba(0,0,0,.05)}.form-signin .form-signin-heading,.form-signin .checkbox{margin-bottom:10px}.form-signin .checkbox{margin-left:20px}.input-block-level{width:238px}.form-signin input[type="text"]{font-size:16px;height:auto;margin-bottom:15px;padding:7px 9px}
    </style>
  </head>
  <body>
    <div class="container">
      <form class="form-signin" action="" method="post">
        <h2 class="form-signin-heading">登录同济评课</h2>
        <input type="text" class="input-block-level" placeholder="学号" name="sid">
        <input type="text" class="input-block-level" placeholder="姓名" name="sname">
          <div class="alert alert-danger" role="alert" hidden></div>
          <label class="checkbox">
          <input type="checkbox" value="remember-me" name="remember-me" checked>保持登录状态
		  <a href="/" style="position:absolute;margin-left:100px;margin-top:40px;">返回</a>
        </label>
<?php
echo "<input type='text' name='redirecturl' value='";
echo $_GET["redirect"];
echo "' hidden>";
?>
        <button class="btn btn-large btn-primary" type="submit" onclick="return checkValidId();">登录</button>
      </form>
    </div>

    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script>
		function checkValidId() {
			var id = document.getElementsByClassName("form-signin")[0]["sid"].value;
			var name = document.getElementsByClassName("form-signin")[0]["sname"].value;
			if (id == "" || name == "") {
                document.getElementsByClassName("alert")[0].innerHTML = "<strong>不能为空!&nbsp;</strong>请重新填写.";
                document.getElementsByClassName("alert")[0].removeAttribute("hidden");
                return false;
            } else if (!checkIsNum(id)) {
                document.getElementsByClassName("alert")[0].innerHTML = "<strong>学号不合法!&nbsp;</strong>请重新填写.";
                document.getElementsByClassName("alert")[0].removeAttribute("hidden");
            } else {
                document.getElementsByClassName("alert")[0].setAttribute("hidden", "true");
                return true;
            }
		}
        function checkIsNum(src) {
            var mch = /1?[1-9][357]([0-9]){4}/g;
            return src.match(mch) == src;
        }
	</script>
  </body>
</html>
