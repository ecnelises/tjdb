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
      <div class="content viewcourse">
          <div class="courseslist">
          <table class="othercomment">
<?php
require('../scripts/functions.php');
$rcid = $_GET['rcid'];
$con = mysql_connect(SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
if (!$con) {
    die("无法连接到数据库: ".mysql_error());
} else {
    mysql_select_db(SAE_MYSQL_DB, $con);
    $courseinfo = mysql_query("SELECT * FROM tjcourses WHERE course_id='$rcid'");
    if (mysql_num_rows($courseinfo) == 0) {
        echo "抱歉，并不存在这门课程！";
        echo "<script>setTimeout(function(){window.location.href='/self-center/';}, 2500);</script>";
        exit();
    }
    $courseinfo = mysql_fetch_array($courseinfo);
    echo $courseinfo['teacher']."  ".$courseinfo['course_name'];
    $res = mysql_query("SELECT tjscores.mark, tjscores.score, tjscores.comment FROM tjscores WHERE tjscores.course_id='$rcid'");
    $count = 0;
    while ($row = mysql_fetch_array($res)) {
        $mks += $row['mark'];
        $scs += $row['score'];
        $count++;
        echo "<tr><td>".num2Score($row['score'])."</td><td>".$row['mark']."</td><td>".($row['comment']==""?"暂无评价":$row['comment'])."</td></tr>";
    }
    if ($count == 0) {
        echo '<p>很抱歉，还没有关于这门课程的资料哦。</p>';
        echo "<p><a href='writecourse.php?rcid=".$rcid."'>点此去填写你的评价</a></p>";
    } else {
        echo "<tr><td>共".$count."条评价</td><td>平均给分：".($scs/$count)."</td><td>平均评分：".($mks/$count)."</td></tr>";
        $res = mysql_query("SELECT * FROM tjscores WHERE student_id='$_COOKIE[userid]'");
        if ($row = mysql_fetch_array($res)) {
            echo "<p><a href='writecourse.php?rcid=".$rcid."'>点此修改你填写的评价</a></p>";
            echo "<p><a href='deletecourse.php?rcid=".$rcid."'>点此删除你填写的评价</a></p>";
        } else {
            echo "<p>你还没有填写这条课程的信息哦，<a href='writecourse.php?rcid=".$rcid."'>点此去填写</a></p>";
        }
    }
    mysql_close($con);
}

?>
          </table>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
