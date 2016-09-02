<?php
if (isset($_COOKIE['__scid'])) {
	$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
	if ($link) {
		mysql_select_db(SAE_MYSQL_DB, $link);
	} else {
		die(mysql_error());
	}
    $command = "SELECT * FROM persistent_cookies WHERE hashid='$_COOKIE[__scid]' AND TIMESTAMPDIFF(SECOND, expire, NOW()) < 0";
    $rows = mysql_query($command);
	if (isset($_SESSION["sid"])) {
		setcookie("__rcv", "y", time() + 3600*24);
	} else if (isset($_COOKIE["__scid"]) && mysql_num_rows($rows) > 0) {
		mysql_query("DELETE FROM persistent_cookies WHERE hashid='$_COOKIE[__scid]'");
		session_start();
		$row = mysql_fetch_array($rows, MYSQL_ASSOC);
		$_SESSION["sid"] = $row["id"];
		$hashid = sha1(mt_rand().session_id().mt_rand());
		$command = "INSERT INTO persistent_cookies (id, hashid, expire) VALUES('$_SESSION[sid]', '$hashid', '$row[expire]')";
		mysql_query($command);
		setcookie("__scid", $hashid, time()+3600*24*30);
		setcookie("__rcv", "y", time() + 3600*24);
	} else {
		setcookie("__rcv", "n", time() + 3600*24);
		setcookie("__scid", "", time() - 3600);
	}
	mysql_close($link);
}
?>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="renderer" content="webkit">
 
    <title>同济评课</title>
	
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
	<link href="/favicon.ico" rel="icon">
	
	<style type="text/css">	body{background-color:#f5f5f5}.container{margin-left:auto}nav{margin-top:25px}
		.site-summary{width:30%;float:left}.current-courses{width:65%;float:right;margin-left:30px}.guess-like{float:left;width:30%}.hot-courses{float:left;width:30%}.otherinfo{float:right;width:65%;margin-bottom:40px}.book-recommend{float:left;width:55%}.book-recommend img{float:left;margin-right:15px}.article-recommend{float:right;width:40%}footer{clear:both;text-align:center;margin-top:30px;margin-bottom:20px;padding-top:15px;border-top:1px solid gray}
	</style>
  </head>
  <body>
	<div class="container">
      <div class="page-header"><h1>同济评课<small>一个汇集大众观点的平台</small></h1></div>
	  <nav class="navbar navbar-default">
		<div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">首页</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="changelog/">更新日志</a></li>
            <li>
<?php
if (isset($_COOKIE["__scid"]) && $_COOKIE["__rcv"]=="y") {
	echo "<a href='me'>个人中心</a>";
} else {
	echo "<a href='login?redirect=http://2.tjdb.sinaapp.com'>登录</a>";
}
?>
			</li>
			<li><a href="contact">向我们提建议</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">友情链接 <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="http://xuanke.tongji.edu.cn" target="_blank">同济大学教务信息系统</a></li>
                <li><a href="http://4m3.tongji.edu.cn" target="_blank">同济本研一体化教务系统</a></li>
				<li><a href="http://jwc.tongji.edu.cn" target="_blank">同济大学教务处</a></li>
                <li role="separator" class="divider"></li>
				<li><a href="http://tieba.baidu.com/f?kw=%E5%90%8C%E6%B5%8E%E5%A4%A7%E5%AD%A6" target="_blank">百度同济大学吧</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="javascript:void(0);" target="_blank">虚位以待</a></li>
              </ul>
            </li>
          </ul>
		  <form class="navbar-form navbar-left" role="search" onsubmit="return openSearch()">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="课名、老师、专业…">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
          </form>
        </div>
        </div>
      </nav>
	  <div class="site-summary panel panel-default ">
	    <div class="panel-heading"><span>网站简介</span></div>
		<div class="panel-body">
		  <p>我的经历就是到了上海，到了15
			  年年初的时候，我想我估计也快要转专业了，我想我应该去软件。于是我就给袁毅哲会长、周以恒同志，给他们写了一个报告。他们说欢迎你来，不过，这个你要去做一个网站，然后才有的这个网站。
		  </p>
		</div>
	  </div>
	  <div class="current-courses panel panel-default ">
	    <div class="panel-heading"><span>最新课程</span></div>
		<table class="table table-striped" style="font-size: 14px;">
		  <tr><th>#</th><th>课名</th><th>老师</th><th>评价</th></tr>
<?php
$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
if ($link) {
	mysql_select_db(SAE_MYSQL_DB, $link);
} else {
	die(mysql_error());
}

// generate the newest remarked courses
$i = 1;
$command = "SELECT * FROM tjscores, tjcourses WHERE tjscores.course_id=tjcourses.course_id  ORDER BY create_time DESC LIMIT 0, 8";
$query_res = mysql_query($command);
while ($row = mysql_fetch_array($query_res, MYSQL_ASSOC)) {
	$lin = "<a href=\"course/".$row["course_id"]."\">";
	echo "<tr>";
	echo "<td>".$lin.($i++)."</a></td>";
	echo "<td>".$lin.$row["course_name"]."</a></td>";
	echo "<td>".$lin.$row["teacher"]."</a></td>";
	echo "<td>".$lin.(mb_strlen($row["comment"], 'UTF-8')<=20?$row["comment"]:(mb_substr($row["comment"], 0, 20, 'UTF-8')."…"))."</a></td>";
	echo "</tr>";
}
echo "</table></div><div class=\"guess-like panel panel-default \"><div class=\"panel-heading\"><span>猜你喜欢</span></div><div class=\"panel-body\">";

// generate the liked courses
$command = "SELECT COUNT(*) AS nums FROM tjcourses";
$query_res = mysql_query($command);
$row = mysql_fetch_array($query_res, MYSQL_ASSOC);
$total_records = $row["nums"];
for ($i = 0; $i < 3; $i++) {
	$randnums[$i] = mt_rand(1, $total_records);
}
$command = "SELECT course_name FROM tjcourses WHERE course_id='$randnums[0]' OR course_id='$randnums[1]' OR course_id='$randnums[2]'";
$i = 0;
$query_res = mysql_query($command);
while ($row = mysql_fetch_row($query_res)) {
	echo "<p><a href=\"course/".$randnums[$i++]."\">".$row[0]."</a></p>";
}

// generate the most remarked courses
echo "</div></div><div class=\"hot-courses panel panel-default \"><div class=\"panel-heading\"><span>最热课程</span></div><table class=\"table table-striped\" style='font-size: 14px;'>";
$command = "SELECT * FROM tjcourses ORDER BY remark_times DESC LIMIT 0, 5";
$i = 0;
$query_res = mysql_query($command);
while ($row = mysql_fetch_array($query_res, MYSQL_ASSOC)) {
	$lin = "<a href=\"course/".$row["course_id"]."\">";
	echo "<tr>";
	echo "<td>".$lin.(++$i)."</a></td>";
	echo "<td>".$lin.$row["course_name"]."</a></td>";
	echo "<td>".$lin.$row["teacher"]."</a></td>";
	echo "</tr>";
}

// generate the books
echo "</table></div><div class=\"otherinfo\"><div class=\"book-recommend panel panel-default \"><div class=\"panel-heading\"><span>好书推荐</span></div><div class=\"panel-body\">";
$command = "SELECT * FROM recommend_books ORDER BY RAND() LIMIT 0, 1";
$query_res = mysql_query($command);
$row = mysql_fetch_array($query_res, MYSQL_ASSOC);
echo "<a href=\"http://".$row["douban_link"]."\" target='_blank'><img src=\"http://".$row["image_link"]."\" alt=\"".$row["bookname"]."\" width='40%' height='30%'></a>";
echo "<p>".$row["remark"]."</p>";

// generate the articles
echo "</div></div><div class=\"article-recommend panel panel-default \"><div class=\"panel-heading\"><span>好文推荐</span></div><div class=\"panel-body\">";
$command = "SELECT COUNT(*) AS nums FROM recommend_articles";
$query_res = mysql_query($command);
$row = mysql_fetch_array($query_res, MYSQL_ASSOC);
$total_records = $row["nums"];
for ($i = 0; $i < 3; $i++) {
	$randnums[$i] = mt_rand(1, $total_records);
}
$command = "SELECT * FROM recommend_articles WHERE id='$randnums[0]' OR id='$randnums[1]' OR id='$randnums[2]'";
$query_res = mysql_query($command);
while ($row = mysql_fetch_array($query_res)) {
	echo "<p>";
	echo "<a href=\"".$row["url"]."\" target='_blank'>".$row["title"]."</a></p>";
}
echo "</div></div>";
mysql_close($link);
?>
		</div>
		<footer><p>2015</p><p>Powered by <a href="http://www.bootcss.com" target="_blank">Bootstrap</a>.</p></footer>
    </div>
	
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  	<script>
		function openSearch() {
			var searchkey = document.getElementsByClassName("form-control")[0].value;
			searchkey = searchkey.trim();
			if (searchkey == "") {
				alert("搜索项不能为空.");
				return false;
			}
			searchkey = encodeURIComponent(searchkey);
			searchkey = searchkey.replace(/%20/g, "+");
			window.open("/search/"+searchkey, "_self");
			return false;
		}
	</script>
  </body>
</html>
