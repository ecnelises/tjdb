<?php
$query_string = preg_replace("/\s{2,}/", " ", $_GET["q"]);
$query_list = explode(" ", $query_string);

// link to the database
$data_link = mysql_connect(SAE_MYSQL_HOST_M . ":" . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
if ($data_link) {
    mysql_select_db(SAE_MYSQL_DB, $data_link);
} else {
    die(mysql_error());
}

// expand the abbr query if so
$command = "SELECT res FROM abbr_names WHERE src='";
foreach ($query_list as &$each_query) {
    if (mysql_num_rows($rows = mysql_query($command.$each_query."'")) != 0) {
        $row = mysql_fetch_array($rows, MYSQL_ASSOC);
        $each_query = $row["res"];
    }
}

// search each query in database and calculate the score
$command = "SELECT course_id FROM tjcourses WHERE ";
$search_item_order = array("course_name", "teacher", "description");
$result_id = array();
for ($j = 0; $j < count($query_list); $j++) {
    for ($i = 0; $i < 3; $i++) {
        $rows = mysql_query($qe = $command.$search_item_order[$i]." LIKE '%".$query_list[$j]."%'");
        if (mysql_num_rows($rows) != 0) {
            while ($row = mysql_fetch_array($rows)) {
                ++$result_id[$row["course_id"]];
            }
       }
    }
}
arsort($result_id);

mysql_close($data_link);
?>
