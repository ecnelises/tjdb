<?php
require ("../scripts/functions.php");
$method = $_POST["method"];
$select_item = abbrCourseName($_POST["q"]);
$con = mysql_connect(SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
if (!$con) {
    die("无法连接到数据库: ".mysql_error());
} else {
    mysql_select_db(SAE_MYSQL_DB, $con);
    if ($method == "by_teacher") {
        $sql_command = "SELECT * FROM tjcourses WHERE teacher LIKE '%$select_item%'";
    } else {
        $sql_command = "SELECT * FROM tjcourses WHERE course_name LIKE '%$select_item%' or comment LIKE '%$select_item%'";
    }
    $head_str = "<table class='course_table'><th>课程名称</th><th>学分</th><th>教师名</th><th>备注</th>";
    $res = mysql_query($sql_command);
    $any_res = false;
    $count = 0;
    while ($row = mysql_fetch_array($res)) {
        if ($count == 0) {
            echo $head_str;
        }
        echo "<tr class='course_row' onmouseover=\"changeRowBg('$count', 'over')\" onmouseout=\"changeRowBg('$count', 'out')\" onclick=\"rowClickinRes('$count')\">";
        echo "<td class='rcid' hidden>".$row['course_id']."</td>";
        echo "<td name='course_name'>".$row['course_name']."</td>";
        echo '<td>'.$row['credits'].'</td>';
        echo "<td name='teacher_name'>".$row['teacher'].'</td>';
        echo '<td>'.$row['comment'].'</td>';
        echo '</tr>';
        $count++;
    }
    if ($count != 0) {
        echo "</table>";
    } else {
        echo "暂无结果！";
    }
    mysql_close($con);
}
?>
