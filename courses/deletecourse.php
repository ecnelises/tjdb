<?php
if (!isset($_COOKIE['userid'])) {
    echo "<script charset='UTF-8'>alert(请先登录！);window.location.href='/';</script>";
}
    if (!isset($_GET['rcid'])) {
        echo "<script charset='UTF-8'>alert(出现错误！);window.location.href='/';</script>";
    }
$con = mysql_connect(SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
if (!$con) {
    die("无法连接到数据库: ".mysql_error());
} else {
    mysql_select_db(SAE_MYSQL_DB, $con) or die("Could not use database: ".mysql_error());
    if (!mysql_fetch_array(mysql_query("SELECT * FROM tjcourses WHERE course_id='$_GET[rcid]'"))) {
        echo "<script charset='UTF-8'>alert(出现错误！);window.location.href='/';</script>";
    }
    if (!mysql_fetch_array(mysql_query("SELECT * FROM tjscores WHERE course_id='$_GET[rcid]' AND student_id='$_COOKIE[userid]'"))) {
        echo "<script charset='UTF-8'>alert(你没有填写关于这门课程的资料！);window.location.href='/';</script>";
    }
    if (mysql_query("DELETE FROM tjscores WHERE course_id='$_GET[rcid]' AND student_id='$_COOKIE[userid]'")) {
        echo "删除成功";
    } else {
        echo "删除失败";
    }
    mysql_close($con);
    echo "<script>setTimeout(\"window.location.href='/self-center/';\", 1500);</script>";
}

?>
