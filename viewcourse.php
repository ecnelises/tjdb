<?php
$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
if ($link) {
    mysql_select_db(SAE_MYSQL_DB, $link);
} else {
    die(mysql_error());
}
if (isset($_COOKIE['__scid'])) {
    $command = "SELECT * FROM persistent_cookies WHERE hashid='$_COOKIE[__scid]' AND TIMESTAMPDIFF(SECOND, expire, NOW()) < 0";
    $rows = mysql_query($command);
    if (isset($_SESSION["sid"])) {
        setcookie("__rcv", "y", time() + 3600*24);
        $rcv = true;
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
        $rcv = true;
    } else {
        setcookie("__rcv", "n", time() + 3600*24);
        setcookie("__scid", "", time() - 3600);
        $rcv = false;
    }
    //mysql_close($link);
}

echo "<html lang=\"zh-CN\"><head><meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta name=\"renderer\" content=\"webkit\">

    <link href=\"//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css\" rel=\"stylesheet\">
    <link href=\"/favicon.ico\" rel=\"icon\">

    <style type=\"text/css\">	body{background-color:#f5f5f5}.container{margin-left:80px}nav.navbar{margin-top:25px;max-width:750px;}footer{clear:both;text-align:center;margin-top:30px;margin-bottom:20px;padding-top:15px;border-top:1px solid gray}nav.pages{margin-left: 30px;}.course-detail{margin-left:20px;}.line-header{max-width:720px;}.panel{max-width: 720px;}form{margin:20px;}label{margin-right:25px;}.button-star{float:right;}
    </style>

    <title>课程资料</title>
</head>

<body>
<div class=\"container\">
    <div class=\"page-header\"><h1>同济评课<small>一个汇集大众观点的平台</small></h1></div>
    <nav class=\"navbar navbar-default\">
        <div class=\"container-fluid\">
            <div class=\"navbar-header\">
                <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#bs-example-navbar-collapse-1\" aria-expanded=\"false\">
                    <span class=\"sr-only\">Toggle navigation</span>
                    <span class=\"icon-bar\"></span>
                    <span class=\"icon-bar\"></span>
                    <span class=\"icon-bar\"></span>
                </button>
                <a class=\"navbar-brand\" href=\"/\">首页</a>
            </div>

            <div class=\"collapse navbar-collapse\" id=\"bs-example-navbar-collapse-1\">
                <ul class=\"nav navbar-nav\">
                    <li><a href=\"changelog/\">更新日志</a></li>
                    <li>";

                        if (isset($_COOKIE["__scid"]) && $rcv) {
                            echo "<a href='me'>个人中心</a>";
                        } else {
                            echo "<a href='login?redirect=http://2.tjdb.sinaapp.com'>登录</a>";
                        }

echo "
                    </li>
                    <li><a href=\"contact\">向我们提建议</a></li>
                    <li class=\"dropdown\">
                        <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">友情链接 <span class=\"caret\"></span></a>
                        <ul class=\"dropdown-menu\">
                            <li><a href=\"http://xuanke.tongji.edu.cn\" target=\"_blank\">同济大学教务信息系统</a></li>
                            <li><a href=\"http://4m3.tongji.edu.cn\" target=\"_blank\">同济本研一体化教务系统</a></li>
                            <li><a href=\"http://jwc.tongji.edu.cn\" target=\"_blank\">同济大学教务处</a></li>
                            <li role=\"separator\" class=\"divider\"></li>
                            <li><a href=\"http://tieba.baidu.com/f?kw=%E5%90%8C%E6%B5%8E%E5%A4%A7%E5%AD%A6\" target=\"_blank\">百度同济大学吧</a></li>
                            <li role=\"separator\" class=\"divider\"></li>
                            <li><a href=\"javascript:void(0);\" target=\"_blank\">虚位以待</a></li>
                        </ul>
                    </li>
                </ul>
                <form class=\"navbar-form navbar-left\" role=\"search\" onsubmit=\"return openSearch()\">
                    <div class=\"form-group\">
                        <input type=\"text\" class=\"form-control\" placeholder=\"课名、老师、专业…\">
                    </div>
                    <button type=\"submit\" class=\"btn btn-default\">搜索</button>
                </form>
            </div>
        </div>
    </nav>
    <div class=\"course-detail\">
        <div class=\"line-header\">";

/* 取得当前浏览者的信息
 * 判断当前浏览者是否填写过评价,是否加过星
 * 加载这门课总共的评价加星信息
 * 显示评价内容(表格)
 */

// link to the database
// get the information of guest
if (!isset($rcv) || !$rcv) {
    $stared = false;
    $needlogin = true;
} else {
    $needlogin = false;
    $command = "SELECT id FROM persistent_cookies WHERE hashid='$hashid' AND TIMESTAMPDIFF(SECOND, expire, NOW()) < 0";
    $rows = mysql_query($command);
    echo "<script>alert('".$hashid."');</script>";
    $row = mysql_fetch_array($rows);
    $command = "SELECT star FROM tjscores WHERE course_id='$_GET[q]' AND student_id='$row[id]'";
    echo "<script>function getUserId(){return '".$row['id']."';}</script>";
    echo "<script>function getCourseId(){return '".$_GET['q']."';}</script>";
    $rows = mysql_query($command);
    if (mysql_num_rows($rows) == 0) {
        $stared = 0;
    } else {
        $row = mysql_fetch_array($rows);
        if ($row["star"] == '0') {
            $stared = 0;
        } else {
            $stared = 1;
        }
    }
}

// get the records of comments
$command = "SELECT course_name, teacher, description FROM tjcourses WHERE course_id='$_GET[q]'";
$rows = mysql_query($command);
$row = mysql_fetch_array($rows);
$course_name = $row["course_name"];
$course_teacher = $row["teacher"];
$course_description = $row["description"];

$command = "SELECT COUNT(*) AS cn FROM tjscores WHERE score!=-1 AND course_id='$_GET[q]'";
$rows = mysql_query($command);
$row = mysql_fetch_array($rows);
$commented_numbers = $row['cn'];

$command = "SELECT COUNT(*) AS cn FROM tjscores WHERE star=1 AND course_id='$_GET[q]'";
$rows = mysql_query($command);
$row = mysql_fetch_array($rows);
$stared_numbers = $row['cn'];

$command = "SELECT FORMAT(AVG(score), 2) AS ac FROM tjscores WHERE score!=-1 AND course_id='$_GET[q]'";
$rows = mysql_query($command);
$row = mysql_fetch_array($rows);
$average_score = $row['ac'];

$command = "SELECT comment, create_time FROM tjscores WHERE score!=-1 AND course_id='$_GET[q]' AND comment!=''";
$rows = mysql_query($command);


echo "<button type='button' class='btn btn-default btn-lg button-star' style='background-color:".
($stared?"#FFFFB7":"white")."'><span class='glyphicon ".
($stared?"glyphicon-star":"glyphicon-star-empty")."' aria-hidden='true'></span><span class='button-star-text'>"
.($stared?"Unstar":"Star")."</span></button>";

echo "<h3>".$course_name."</h3><h4>".$course_teacher."</h4></div>";

echo "<p>共有<em>".$commented_numbers."</em>人评价了这门课程，<em>".$stared_numbers."</em>人为这门课打了星标。</p>";

echo "<p>平均得分为：<em>".$average_score."</em>（满分5）</p>";

if ($needlogin) {
    echo "<p>请<a href='/login'>登录</a>以创建自己的内容</p>";
} else {
    echo "<p><a href='javascript:showCommentForm();' id='writecomment'>填写自己的评价</a></p>";
}

echo "<div class='panel panel-default'><div class='panel-heading'>课程评价</div><table class='table
comments'><tr><th>#</th><th>时间</th><th>内容</th></tr>";

$i = 1;
while ($row = mysql_fetch_array($rows)) {
    echo "<tr><td>".$i++."</td><td>".$row["create_time"]."</td><td>".$row["comment"]."</td></tr>";
}

echo "</table>";

mysql_close($link);
?>

            <form role="form" class="form-comment" hidden>
                <div class="form-group">
                    <input type="text" class="form-control"
                           placeholder="请输入对课程的评价" name="comment">
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="score" value="5">优</label><label>
                        <input type="radio" name="score" value="4">良</label><label>
                        <input type="radio" name="score" value="3">中</label><label>
                        <input type="radio" name="score" value="2">及格</label><label>
                        <input type="radio" name="score" value="0">不及格
                    </label>
                </div><br>
                <button type="submit" class="btn btn-default">提交</button>
            </form>
        </div>
    </div>
</div></div></div>
<footer><p>2015</p><p>Powered by <a href="http://www.bootcss.com" target="_blank">Bootstrap</a>&nbsp;&amp;&nbsp;Icons from <a href="glyphicons.com" target="_blank">Glyphicons</a>.</p>
</footer>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $(".button-star").click(function(){
            var stared = getStarInfo();
            $.post("/starinfo.php", (stared === true)?{dire:"off", student: getUserId(), course:
                getCourseId()}:{dire:"on", student:getUserId(), course:getCourseId()}, function(data, status)
            {changeStarStatus();});
        });
    });

    function showCommentForm() {
        if (document.getElementsByClassName("comments")[0].hasAttribute("hidden")) {
            document.getElementsByClassName("comments")[0].removeAttribute("hidden");
            document.getElementsByClassName("form-comment")[0].setAttribute("hidden", "true");
            document.getElementById("writecomment").innerHTML = "填写自己的评价";
        } else {
            document.getElementsByClassName("form-comment")[0].removeAttribute("hidden");
            document.getElementsByClassName("comments")[0].setAttribute("hidden", "true");
            document.getElementById("writecomment").innerHTML = "查看他人评价";
        }
    }

    function getStarInfo() {
        if (document.getElementsByClassName("button-star")[0].style.backgroundColor === "rgb(255, 255, 183)") {
            return true;
        } else if (document.getElementsByClassName("button-star")[0].style.backgroundColor === "white") {
            return false;
        }
        return false;

    }

    function changeStarStatus() {
        var star_btn = document.getElementsByClassName("button-star")[0];
        var ico = document.getElementsByClassName("glyphicon")[0];
        if (star_btn.style.backgroundColor === "rgb(255, 255, 183)") {
            star_btn.style.backgroundColor = "white";
            ico.className = "glyphicon glyphicon-star-empty";
            document.getElementsByClassName("button-star-text")[0].innerHTML = "Star";
        } else if (star_btn.style.backgroundColor === "white") {
            star_btn.style.backgroundColor = "#FFFFB7";
            ico.className = "glyphicon glyphicon-star";
            document.getElementsByClassName("button-star-text")[0].innerHTML = "Unstar";
        }
    }
</script>
</body>
</html>
