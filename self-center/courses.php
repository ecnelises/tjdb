<?php
    require('../scripts/functions.php');
    $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
    if (!$link) {
        die('Could not connect: '.mysql_error());
    } else {
        mysql_select_db(SAE_MYSQL_DB, $link) or die('Could not select database: '.mysql_error());
        $sql_command = "SELECT tjcourses.course_id,tjcourses.course_name,tjcourses.teacher,tjcourses.credits,tjscores.score,tjscores.mark,tjscores.comment FROM tjcourses,tjscores WHERE tjscores.student_id='$_COOKIE[userid]' AND tjscores.course_id=tjcourses.course_id ORDER BY tjcourses.course_id";
        $result = mysql_query($sql_command);
        $count = 0;
        while ($row = mysql_fetch_array($result)) {
            echo "<tr class='course_row' onmouseover=\"changeRowBg('$count', 'over')\" onmouseout=\"changeRowBg('$count', 'out')\" onclick=\"rowClickin('$count')\">";
            echo "<td class='rcid' hidden>".$row['course_id']."</td>";
            echo "<td class='record teacher'>".$row['teacher']."</td>";
            echo "<td class='record coursename'>".$row['course_name']."</td>";
            echo "<td class='record score'>".num2Score($row['score'])."</td>";
            echo "<td class='record mark'>".$row['mark']."</td>";
            echo "<td class='record comment'>".cutComment($row['comment'])."</td>";
            echo "</tr>";
            $count++;
        }
    }
    mysql_close($link);
?>
