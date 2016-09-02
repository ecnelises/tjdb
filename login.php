<?php
$name = $_COOKIE['username'];
$con = mysql_connect(SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT,
    SAE_MYSQL_USER, SAE_MYSQL_PASS);
if (!$con) {
    die("无法连接到数据库：".mysql_error());
} else {
    $scphead = "<script type='text/javascript' charset='UTF-8'>";
    mysql_select_db(SAE_MYSQL_DB, $con);
    $res = mysql_query("SELECT * FROM `tjstudents` WHERE id='$_COOKIE[userid]'");
    $resnum = mysql_num_rows($res);
    if ($resnum == 0) {
        mysql_query("INSERT INTO `tjstudents` (id, name) VALUES ('$_POST[studentid]','$_POST[studentname]')");
        echo $scphead;
        echo "window.location.href='/self-center/fillinfo/'";
        echo "</script>";
    } else {
        $row = mysql_fetch_array($res);
        if ($row['name'] != $name) {
            setcookie("userid", "", time() - 3600, "/");
            setcookie("username", "", time() - 3600, "/");
            echo $scphead;
            echo "alert(String.fromCharCode(0x5B66,0x53F7,0x4E0E,0x59D3,0x540D,0x4E0D,0x7B26,0xFF01));window.location.href='index.html'";
            echo "</script>";
        } else if (!strcmp($row['gender'], "")) { // if gender(represents all infos) unrecorded
            echo $scphead;
            echo "window.location.href='/self-center/fillinfo/'";
            echo "</script>";
        } else {
            echo $scphead;
            echo "window.location.href='/self-center/'";
            echo "</script>";
        }
    }
}
mysql_close($con);
?>
