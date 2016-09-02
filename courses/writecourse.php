<meta charset="UTF-8">
<html>
<head>
<title>个人中心</title>
<link type="text/css" rel="stylesheet" href="/stylesheets/tjdb.css" />
<script type="text/javascript" src="/scripts/check.js"></script>
<script type="text/javascript" src="/scripts/style.js"></script>
<script type="text/javascript" src="/scripts/course.js"></script>
</head>
<body onload="checkCookie();">
<div id="toolbar">
<a id="tjdb_name">同济公共课程数据库计划</a>
<div id="options_container">
<a id="user_name" href="/self-center"></a>
<a id="logout" href="/" onclick="deleteCookie()">退出</a>
</div>
</div>
<div class="content_container">
<div class="content submitcourse">
<?php
    require('../scripts/functions.php');
    $rcid = $_GET['rcid'];
    $con = mysql_connect(SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
    if (!$con) {
        die("无法连接到数据库: ".mysql_error());
    } else {
        mysql_select_db(SAE_MYSQL_DB, $con);
        if (!mysql_fetch_array(mysql_query("SELECT * FROM tjcourses WHERE course_id='$rcid'"))) {
            echo '<p>很抱歉，还没有关于这门课程的资料哦。</p>';
            echo "<script>setTimeout(function(){window.location.href='/self-center/';}, 1500);</script>";
        } else {
            $res = mysql_query("SELECT * FROM tjscores WHERE student_id='$_COOKIE[userid]' AND course_id='$rcid'");
            $existyet = mysql_fetch_array($res)?true:false;
            $cinfo = mysql_fetch_array(mysql_query("SELECT * FROM tjcourses WHERE course_id='$rcid'"));
            echo "<form action='submitcourse.php' method='post'><input type='text' name='rcid' value='$rcid' hidden /><p>课程名称：".$cinfo['course_name']."</p><p>教师：".$cinfo['teacher']."</p><p>得分：<select name='gscore'><option value='5'>优</option><option value='4'>良</option><option value='3'>中</option><option value='2'>及格</option><option value='0'>不及格</option></select></p><p>评价：<select name='gmark'><option value='5'>非常好</option><option value='4'>好</option><option value='3'>一般</option><option value='2'>不行</option><option value='1'>非常差</option></select></p><p>额外评论：<textarea name='ocomment' length='300'></textarea></p><input type='submit' value='提交'/></form>";
        }
        mysql_close($con);
    }
?>
</div>
</div>
</body>
</html>
