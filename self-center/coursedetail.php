<?php
require('../scripts/functions.php');

echo isset($cid = $_GET['rcid']);
$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
if (!$link) {
    die('Could not connect: '.mysql_error());
} else {
    mysql_select_db(SAE_MYSQL_DB, $link) or die ('Could not select database: '.mysql_error());
    $sql_command = "SELECT tjcourses.course_name, tjcourses.teacher, tjscores.score, tjscores.mark, tjscores.comment FROM tjcourses, tjscores WHERE tjscores.student_id='$_COOKIE[userid]' AND tjcourses.course_id='$_GET[rcid]'";
    $result = mysql_query($sql_command);
    while ($row = mysql_fetch_array($result)) {
        echo "<tr><td>课程名：</td><td>".$row['course_name']."</td></tr>";
        echo "<tr><td>教师名：</td><td>".$row['teacher']."</td></tr>";
        echo "<tr><td>得分：</td><td>".num2Score($row['score'])."</td></tr>";
        echo "<tr><td>您的评分（满分5）</td><td>".$row['mark']."</td></tr>";
        echo "<tr><td>评价：</td><td>".$row['comment']."</td></tr>";
    }
}

?>
