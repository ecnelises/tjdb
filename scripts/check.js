function validateID(id) {
    if(id.length<7) {
        return "学号必须为7位！";
    } else if (id.charAt(0) != "0" && id.charAt(0) != "1") {
        return "您的学号不合法！";
    }
    return null;
}

function valiAlert(tstr) {
    var altmsg = document.getElementById("alertmsg");
    altmsg.removeAttribute("hidden");
    altmsg.innerHTML=tstr;
}

function clearAlert() {
    document.getElementById("alertmsg").setAttribute("hidden", true);
}

function validateForm(form) {
    var valires=validateID(form.studentid.value);
    if(form.studentname.value==="") {
        valiAlert("姓名不能为空！");
        form.studentname.focus();
        return false;
    } else if (valires != null) {
        valiAlert(valires);
        form.studentid.focus();
        return false;
    }
    var fd = document.forms[0];
    setCookie(fd.elements[0].value, fd.elements[1].value, 30);
    return true;
}

function setCookie(id, name, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString() + ";";
    document.cookie = "username=" + name  + ";";
    document.cookie = "userid=" + id + ";";
    document.cookie = expires;
}

function getCookie(cname) {
    var name = cname+"=";
    var ca = document.cookie.split(';');
    for (var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c=c.substring(1);
        if (c.indexOf(name)==0) return c.substring(name.length,c.length);
    }
    return "";
}

function checkCookie() {
    var user = getCookie("userid");
    var present_filename=window.location.pathname;
    if ((user === "" || user == undefined) && (present_filename !== "/index.html" && present_filename !== "/")) {
        alert("请先登录！");
        window.location.href = '/';
    } else if (user != "" && user != undefined && (present_filename === "/index.html" || present_filename === "/")) {
        window.location.href = '/login.php';
    }
    var oname = document.getElementById("user_name");
    if (oname && (present_filename !== "/index.html" || present_filename === "/")) {
        oname.innerHTML = getCookie("username");
    }
    document.getElementById("user_name").href = '/self-center/';
}

function deleteCookie() {
    document.cookie = "userid=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT";
    document.cookie = "username=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT";
}

function writeName() {
    document.getElementById("user_name").innerHTML = getCookie("username");}

function checkInfo() {
    var x = document.getElementsByName("gender");
    if (!x[0].checked && !x[1].checked) {
        valiAlert("请完整填写资料！");
        return false;
    } else {
        return true;
    }
}

function adjustDate() {
    var year=parseInt(document.getElementById("birthday_year").options[document.getElementById("birthday_year").selectedIndex].value);
    var month=parseInt(document.getElementById("birthday_month").options[document.getElementById("birthday_month").selectedIndex].innerHTML);
    var leap;
    if (year%100 === 0) {
        if (year%400 === 0) {
            leap=true;
        } else {
            leap=false;
        }
    } else {
        if (year%4 === 0) {
            leap=true;
        } else {
            leap=false;
        }
    }
    
    var days;
    
    switch (month) {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            days=31;
            break;
        case 4:
        case 6:
        case 9:
        case 11:
            days=30;
            break;
        case 2:
            days=leap?29:28;
            break
        default:
            break;
    }
    
    var towritedays=document.getElementById("birthday_day");
    towritedays.options.length=0;
    var nop;

    for (i=1;i<=days;i++) {
        nop=document.createElement("option");
        nop.value=i.toString();
        nop.innerHTML=i.toString();
        towritedays.add(nop, null);
    }
}
