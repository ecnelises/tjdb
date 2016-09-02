function changeTabDisplay(pos) {
    document.getElementsByClassName("tab active")[0].className = "tab";
    if (pos == 'course') {
        document.getElementsByClassName("tab")[0].className = "tab active";
        document.getElementsByClassName("innerpart")[0].style.borderTopLeftRadius = "0px";
        document.getElementsByClassName("info")[0].setAttribute("hidden", "true");
        document.getElementsByClassName("courseslist")[0].removeAttribute("hidden");
    } else {
        document.getElementsByClassName("tab")[1].className = "tab active";
        document.getElementsByClassName("innerpart")[0].style.borderTopLeftRadius = "8px";
        document.getElementsByClassName("info")[0].removeAttribute("hidden");
        document.getElementsByClassName("courseslist")[0].setAttribute("hidden", "true");
        showInfoInTable();
    }
}

function changeRowBg(pos, mt) {
    if (mt === 'over') {
        document.getElementsByClassName("course_row")[pos].style.backgroundColor = "#C0C0C0";
        document.getElementsByClassName("course_row")[pos].style.color = "white";
        document.getElementsByClassName("course_row")[pos].style.borderColor = "#C0C0C0";
    } else if (mt === 'out') {
        document.getElementsByClassName("course_row")[pos].style.backgroundColor = "white"; 
        document.getElementsByClassName("course_row")[pos].style.color = "black";
        document.getElementsByClassName("course_row")[pos].style.borderColor = "#F5F5F5";
    }
    document.getElementsByClassName("course_row")[pos].style.cursor = "pointer";
}

function rowClickin(pos) {
    window.location.href = '/courses/viewcourse.php?rcid=' + document.getElementsByClassName("rcid")[pos].innerHTML;
}

function rowClickinRes(pos) {
    window.location.href = '/courses/viewcourse.php?rcid=' + document.getElementsByClassName("rcid")[pos].innerHTML;
}