var xmlHttp;

function getXmlHttpObject()
{
    var xh = null;
    try {
        xh = new XMLHttpRequest();
    } catch(e) {
        try {
            xh = new ActiveXObject("Msxm12.XMLHTTP");
        } catch(e) {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xh;
}

function showResult() {
    xmlHttp = getXmlHttpObject();
    var url = "/courses/searchcourse.php";
    var method;
    if (document.getElementById("method_by_teacher").checked) {
        method = "by_teacher";
    } else {
        method = "by_course_name";
    }
    var key = document.getElementById("select_item").value;
    if (key === "") {
        document.getElementById("select_result").innerHTML = "搜索项不能为空！";
        return;
    }
    xmlHttp.onreadystatechange = stateChanged;
    xmlHttp.open("POST", url, true);
    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttp.send("q="+key+"&method="+method);
}

function stateChanged() {
    if (xmlHttp.readyState == 4 || xmlHttp.status == 200) {
        document.getElementById("select_result").innerHTML = xmlHttp.responseText;
    }
}

function showCourseInTable() {
    xmlHttp = getXmlHttpObject();
    var url = "/self-center/courses.php";
    xmlHttp.onreadystatechange = stateChanged;
    xmlHttp.open("GET", url, true);
    xmlHttp.send();
}

function showInfoInTable() {
    xmlHttp = getXmlHttpObject();
    var url = "/self-center/showinfo.php";
    xmlHttp.onreadystatechange = stateChangedOfInfo;
    xmlHttp.open("GET", url, true);
    xmlHttp.send();
}

function stateChangedOfInfo() {
    document.getElementById("infopage").innerHTML = xmlHttp.responseText;
}

function writeComment() {
    var radio_buttons = document.getElementsByName('item');
    var all_unchecked = true;
    var checked_course_name, checked_teacher_name;
    for (var i = 0; i < radio_buttons.length; i++) {
        if (radio_buttons[i].checked) {
            all_unchecked = false;
            checked_course_name = document.getElementsByName('course_name')[i].innerHTML;
            checked_teacher_name = document.getElementsByName('teacher_name')[i].innerHTML;
            break;
        }
    }
    if (all_unchecked) {
        alert("请选择一门课程！");
        return;
    }
    var towrite_form = document.getElementById("select_result");
    towrite_form.innerHTML = "<form><p id='coursen'>课程名：" + checked_course_name + "</p>";
    towrite_form.innerHTML += "<p id='teachern'>教师名：" + checked_teacher_name + "</p>";
    towrite_form.innerHTML += "<p>您这门课程的得分：<select id='got_score'><option value='5'>优</option><option value='4'>良</option><option value='3'>中</option><option value='2'>及格</option><option value='0'>不及格</option></select></p>";
    towrite_form.innerHTML += "<p>您对这门课程的评分：<select id='mark'><option value='5'>非常好</option><option value='4'>好</option><option value='3'>一般</option><option value='2'>不行</option><option value='1'>很烂</option></select></p>";
    towrite_form.innerHTML += "<p>其他评价：<textarea maxlength='140' id='other_comment'></textarea></p>";
    towrite_form.innerHTML += "<input type='button' value='提交' onclick='commitComment()' />";
}

function commitComment() {
    var got_score_element = document.getElementById("got_score");
    var course_score = got_score_element.options[got_score_element.selectedIndex].value;
    var mark_element = document.getElementById("mark");
    var course_mark = mark_element.options[mark_element.selectedIndex].value;
    var else_comment = document.getElementById("other_comment").value;
    var course_name = document.getElementById("coursen").innerHTML.substring(4);
    var teacher_name = document.getElementById("teachern").innerHTML.substring(4);
    var student_id = getCookie("userid");
    xmlHttp.open("POST", "commitcomment.php", true);
    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttp.send("score="+course_score+"&mark="+course_mark+"&comment="+else_comment+"&teacher="+teacher_name+"&coursename="+course_name+"&id="+student_id);
}

function keyEnter() {
    if (event.keyCode == 13) {
        showResult();
    }
}
