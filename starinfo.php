<?php
$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
if ($link) {
    mysql_select_db(SAE_MYSQL_DB, $link);
} else {
    die(mysql_error());
}

$command = "SELECT student_id, course_id, star FROM tjscores WHERE course_id='$_POST[course]' AND
student_id='$_POST[student]'";
if (mysql_num_rows(mysql_query($command)) == 0) {
    $existrecord = false;
} else {
    $existrecord = true;
}

if ($existrecord) {
    $command = "UPDATE tjscores SET star=";
    if ($_POST["dire"] == "off") {
        $command .= "0 ";
    } else {
        $command .= "1 ";
    }
    $command .= "WHERE course_id='$_POST[course] AND student_id='$_POST[student]'";
    mysql_query($command);
} else {
    $command = "INSERT INTO tjscores (star) VALUES(";
    if ($_POST["dire"] == "off") {
        $command .= "0"; // normally, this is an impossible situation
    } else {
        $command .= "1";
    }
    $command .= ")";
    mysql_query($command);
}

mysql_close($link);

?>