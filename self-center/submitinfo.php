<?php
if(!isset($_COOKIE["userid"])) {
    echo "<script type='text/javascript'>";
    echo "window.location.href='/index.html'";
    echo "</script>";
} else {
    $usrnm = $_COOKIE["userid"];
}

$con = mysql_connect(SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT,
    SAE_MYSQL_USER, SAE_MYSQL_PASS);
if (!$con) {
    die("无法连接到数据库：".mysql_error());
} else {
    mysql_select_db(SAE_MYSQL_DB, $con);
    $birthday_string = $_POST[birthday_year] . "-" . $_POST[birthday_month] . "-" . $_POST[birthday_day];
    $res = mysql_query("UPDATE `tjstudents` SET current_major='$_POST[current_major]',previous_major='$_POST[past_major]',admission_year='$_POST[admission_year]',gender='$_POST[gender]',hometown='$_POST[hometown]',birthday='$birthday_string' WHERE id='$usrnm'");
}
echo "<script type='text/javascript'>";
echo "window.location.href='/self-center/'";
echo "</script>";
mysql_close($con);
?>
