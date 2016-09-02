<?php
$con = mysql_connect(SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
if (!$con) {
    die("无法连接到数据库: ".mysql_error());
} else {
    mysql_select_db(SAE_MYSQL_DB, $con) or die("Could not use database: ".mysql_error());
    $res = mysql_fetch_array(mysql_query("SELECT * FROM tjscores WHERE student_id='$_COOKIE[userid]' AND course_id='$_POST[rcid]'"));
    if ($res) {
        $resstr = "更新";
        $res = mysql_query("UPDATE tjscores SET score=CONVERT('$_POST[gscore]', SIGNED), mark=CONVERT('$_POST[gmark]', SIGNED), comment='$_POST[ocomment]' WHERE course_id='$_POST[rcid]' AND student_id='$COOKIE[userid]'");
    } else {
        $resstr = "创建";
        $res = mysql_query("INSERT INTO tjscores VALUES ('$_POST[rcid]', '$_COOKIE[userid]', CONVERT('$_POST[gscore]', SIGNED), CONVERT('$_POST[gmark]', SIGNED),'$_POST[ocomment]')");
    }
    if ($res) {
        echo $resstr."成功";
    } else {
        echo $resstr."失败";
        echo mysql_error("INSERT INTO tjscores VALUES ('$_POST[rcid]', '$_COOKIE[userid]', CONVERT('$_POST[gscore]', SIGNED), CONVERT('$_POST[gmark]', SIGNED),'$_POST[ocomment]')");
    }
    mysql_close($con);
    echo "<script>setTimeout(\"window.location.href='/self-center/';\", 1500);</script>";
}
?>
