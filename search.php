<?php
if (isset($_COOKIE['__scid'])) {
    $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
    if ($link) {
        mysql_select_db(SAE_MYSQL_DB, $link);
    } else {
        die(mysql_error());
    }
    $command = "SELECT * FROM persistent_cookies WHERE hashid='$_COOKIE[__scid]' AND TIMESTAMPDIFF(SECOND, expire, NOW()) < 0";
    $rows = mysql_query($command);
    if (isset($_SESSION["sid"])) {
        setcookie("__rcv", "y", time() + 3600*24);
    } else if (isset($_COOKIE["__scid"]) && mysql_num_rows($rows) > 0) {
        mysql_query("DELETE FROM persistent_cookies WHERE hashid='$_COOKIE[__scid]'");
        session_start();
        $row = mysql_fetch_array($rows, MYSQL_ASSOC);
        $_SESSION["sid"] = $row["id"];
        $hashid = sha1(mt_rand().session_id().mt_rand());
        $command = "INSERT INTO persistent_cookies (id, hashid, expire) VALUES('$_SESSION[sid]', '$hashid', '$row[expire]')";
        mysql_query($command);
        setcookie("__scid", $hashid, time()+3600*24*30);
        setcookie("__rcv", "y", time() + 3600*24);
    } else {
        setcookie("__rcv", "n", time() + 3600*24);
        setcookie("__scid", "", time() - 3600);
    }
    mysql_close($link);
}
?>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">

    <title>搜索结果</title>

    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="/favicon.ico" rel="icon">

    <style type="text/css">	body{background-color:#f5f5f5}.container{margin-left:auto;}nav.navbar{margin-top:25px;
                                                                                              max-width:750px;}footer{clear:both;text-align:center;margin-top:30px;margin-bottom:20px;padding-top:15px;border-top:1px solid gray}nav.pages{margin-left: 30px;}
    </style>
</head>
<body>
<div class="container">
    <div class="page-header"><h1>同济评课<small>一个汇集大众观点的平台</small></h1></div>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">首页</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="changelog/">更新日志</a></li>
                    <li><?php
                        if (isset($_COOKIE["__scid"]) && $_COOKIE["__rcv"]=="y") {
                            echo "<a href='/me'>个人中心</a>";
                        } else {
                            echo "<a href='/login?redirect=http://2.tjdb.sinaapp.com'>登录</a>";
                        }
                        ?></li>
                    <li><a href="contact">向我们提建议</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">友情链接 <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="http://xuanke.tongji.edu.cn" target="_blank">同济大学教务信息系统</a></li>
                            <li><a href="http://4m3.tongji.edu.cn" target="_blank">同济本研一体化教务系统</a></li>
                            <li><a href="http://jwc.tongji.edu.cn" target="_blank">同济大学教务处</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="http://tieba.baidu.com/f?kw=%E5%90%8C%E6%B5%8E%E5%A4%A7%E5%AD%A6" target="_blank">百度同济大学吧</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="javascript:void(0);" target="_blank">虚位以待</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="navbar-form navbar-left" role="search" onsubmit="return openSearch()">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="课名、老师、专业…">
                    </div>
                    <button type="submit" class="btn btn-default">搜索</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="course-list">
        <?php
        require('search_query.php');

        $items_per_page = 10;
        $num_of_pages = intval(ceil(count($result_id) / $items_per_page));

        // if no course satisfies such condition, what will do?
        if ($num_of_pages == 0) {
            echo "对不起，没有您想要的结果！</div>";
            goto end;
        }

        if ($_GET["page"] < 0 || $_GET["page"] > $num_of_pages) {
            die("Invalid page number!");
        }
        $current_page = $_GET["page"];
        $start_item = ($current_page - 1) * $items_per_page;

        $data_link = mysql_connect(SAE_MYSQL_HOST_M . ":" . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
        if ($data_link) {
            mysql_select_db(SAE_MYSQL_DB, $data_link);
        } else {
            die(mysql_error());
        }

        // displaying the search result
        function getCourseDetail($rid) {
            $scommand = "SELECT COUNT(*) AS nums, IFNULL(FORMAT(AVG(score), 2), '0.00') AS ascore FROM tjscores WHERE course_id='$rid'";
            $srows = mysql_query($scommand);
            $srow = mysql_fetch_array($srows);
            $res = "<span>评价人数：".$srow["nums"]."，平均给分：".$srow["ascore"]."，加星：";
            $scommand = "SELECT COUNT(star) AS ss FROM tjscores WHERE course_id='".$rid."' AND star=1";
            $srows = mysql_query($scommand);
            $srow = mysql_fetch_array($srows);
            $res .= $srow["ss"]."</span>";
            return $res;
        }

        $command = "SELECT teacher, course_name, description FROM tjcourses WHERE course_id=";
        $result_str = "<ol start='".($start_item+1)."'>";

        foreach (array_slice($result_id, $start_item, $items_per_page, true) as $name => $iv) {
            $rows = mysql_query($command."'".$name."'");
            $row = mysql_fetch_array($rows);
            $result_str .= "<li class='single-course'><a href='/course/".$name."'><h4>".$row["teacher"]." - "
                .$row["course_name"]."</h4></a>
".getCourseDetail($name)."</li><br>";
        }

        $result_str .= "</ol></div>";
        echo $result_str;

        // generate the page numbers
        if ($num_of_pages != 1) {
            if ($num_of_pages <= 7) {
                $page_display_list = range(1, $num_of_pages);
            } else if ($current_page - 1 < 4) {
                $page_display_list = range(1, 7);
            } else if ($num_of_pages - $current_page < 4) {
                $page_display_list = range($num_of_pages - 6, $num_of_pages);
            } else {
                $page_display_list = range($current_page - 3, $current_page + 3);
            }
            echo "<nav class='pages'><ul class='pagination'><li" . (($current_page == 1) ? " class='disabled'" : "") . "><a href='/search/"
                . $_GET['q'] . "/" . ($current_page - 1) . "' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
            foreach ($page_display_list as $n) {
                echo "<li";
                if ($current_page == $n) {
                    echo " class='active'";
                }
                echo "><a href='/search/" . $_GET['q'] . "/" . $n . "'>" . $n . "</a></li>";
            }
            echo "<li" . (($current_page == $num_of_pages) ? " class='disabled'" : "") . "><a href='/search/" . $_GET['q'] . "/" .
                ($current_page + 1) . "' aria-label='Previous'><span aria-hidden='true'>&raquo;</span></a></li>";
            echo "</ul></nav>";
        }
        end:
        ?>

        <footer><p>2015</p><p>Powered by <a href="http://www.bootcss.com" target="_blank">Bootstrap</a>.</p></footer>
        <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
        <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script>
            function openSearch() {
                var searchkey = document.getElementsByClassName("form-control")[0].value;
                searchkey = searchkey.trim();
                if (searchkey == "") {
                    alert("搜索项不能为空.");
                    return false;
                }
                searchkey = encodeURIComponent(searchkey);
                searchkey = searchkey.replace(/%20/g, "+");
                window.open("/search/"+searchkey, "_self");
                return false;
            }
        </script>
</body>
</html>
