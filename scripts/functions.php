<?php

function num2Score($sc) {
    switch ($sc) {
    case 5:
        return "优";
    case 4:
        return "良";
    case 3:
        return "中";
    case 2:
        return "及格";
    case 0;
        return "不及格";
    default:
        return "";
    }
}

function cutComment($cmt) {
    if (mb_strlen($cmt, 'UTF-8') < 20) {
        return $cmt;
    } else {
        return mb_substr($cmt, 0, 17, 'UTF-8')."...";
    }
}

function abbrCourseName($snm) {
    $res = trim($snm);
    switch ($res) {
    case "高数":
        return "高等数学";
    case "思修":
        return "思想道德修养和法律基础";
    case "军理":
        return "军事理论";
    case "马原":
        return "马克思主义基本原理";
    case "近纲":
        return "中国近现代史纲要";
    case "普物":
        return "普通物理";
    case "大英":
        return "大学英语";
    case "毛概":
        return "毛泽东思想和中国特色社会主义理论体系概论";
    case "线代":
        return "线性代数";
    case "大机":
        return "大学计算机";
    }
    return $res;
}

?>
