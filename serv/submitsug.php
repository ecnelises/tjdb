<?php
$mysql = new SaeMysql();

$sql = "INSERT INTO suggestions (onwhich, content, time) VALUES('" . $_POST['itemname'] . "', '" . $_POST["suggestion"] . "', NOW())";
$mysql->runSql($sql);
if ($mysql->errno() != 0) {
    die("Error:".$mysql->errmsg());
}
$mysql->closeDb();
echo "谢谢你的回复！";
?>
