<?php

$major2name = array("class_mng"=> "社会科学实验班（管理学类）","mng_project"=> "工程管理","mng_info"=> "信息管理与信息系统","mng_transport"=> "物流管理","market_sale"=> "市场营销","accounting"=> "会计学","mng_administration"=> "行政管理","class_economy"=> "社会科学实验班（经济学类）","finance"=> "金融学","international_trade"=> "国际经济与贸易","architecture"=> "建筑学","urban_planning"=> "城乡规划","historic_preservation"=> "历史建筑保护工程","landscape"=> "风景园林","industrial_design"=> "工业设计","class_design"=> "设计学类","class_journal"=> "新闻传播学类","tele_broadcast"=> "广播电视学","advertisement"=> "广告学","cartoon"=> "动画","tele_bc_directing"=> "广播电视编导","performance"=> "表演","music_performance"=> "音乐表演","class_civileng"=> "工科实验班（土木工程类）","civil_engineering"=> "土木工程","port_shore"=> "港口河道与海岸工程","geology_engineering"=> "地质工程","survey_map"=> "测绘工程",
"class_jt"=> "工科实验班（交通运输类）","traffic_engineering"=> "交通工程","carriage"=> "交通运输","transport_engineering"=> "物流工程",
"class_sese"=> "工科实验班（环境科学与工程类）","water_supply_drainage"=> "给排水科学与工程","environmental_engineering"=> "环境工程","environmental_science"=> "环境科学","material"=> "材料科学与工程","class_mefaculty"=> "工科实验班（机械能源班）","machinery_automation"=> "机械设计制造及其自动化","industrial_engineering"=> "工业工程","energy_appliance"=> "建筑环境与能源应用工程","energy_power"=> "能源与动力工程","vehicle_engineering"=> "车辆工程","vehicle_engineering"=> "车辆工程",
"class_computer"=> "工科实验班（计算机类）","computer_science"=> "计算机科学与技术","information_security"=> "信息安全","class_information"=> "工科实验班（电气信息类）","automation"=> "自动化","electrical_engineering"=> "电气工程及其自动化","infomation_engineering"=> "电子信息工程","communication_engineering"=> "通信工程","electrical_science"=> "电子科学与技术","software_engineering"=> "软件工程",
         "class_cdhaw"=> "机械类（中外合作办学）","machinery_electrical_engineering"=> "机械电子工程","car_service"=> "汽车服务工程","intelligentization"=> "建筑电气与智能化",
         "class_life"=> "生物科学类","biology_technology"=> "生物技术","biology_information"=> "生物信息学",
         "clinical_medicine"=> "临床医学","rehabilitation"=> "康复治疗学",
         "stomatology"=> "口腔医学",
         "class_mgg"=> "理科实验班（海洋科学与地球物理学类）","geology"=> "地质学","earth_physics"=> "地球物理学","sea_explicit"=> "海洋资源开发技术",
         "engineering_mechanics"=> "工程力学","flight_engineering"=> "飞行器制造工程",
         "class_math"=> "理科实验班（数学系）","math_appliance"=> "数学与应用数学","statistics"=> "统计学",
  "class_physics"=> "理科实验班（物理学类）","physics_appliance"=> "应用物理学","photoelectricity_science"=> "光电信息科学与工程",
        "class_chemistry"=> "理科实验班（化学系）","chemistry_appliance"=> "应用化学","chemical_engineering"=> "化学工程与工艺","jurisprdence"=> "法学", "english"=> "英语","japanese"=> "日语","german"=> "德语","class_sal"=> "人文科学实验班","philosophy"=> "哲学","cultural_industry_management"=> "文化产业管理","chinese"=> "汉语言文学", "political_administrative_science"=> "政治学与行政学","sociology"=> "社会学");

$ht2name = array("bj"=>"北京",
        "sh"=>"上海",
        "tj"=>"天津",
        "cq"=>"重庆",
        "nmg"=>"内蒙古",
        "sxmountain"=>"山西",
        "sx"=>"陕西",
        "hnriver"=>"河南",
        "xj"=>"新疆",
        "fj"=>"福建",
        "hn"=>"湖南",
        "yn"=>"云南",
        "zj"=>"浙江",
        "hlj"=>"黑龙江",
        "jl"=>"吉林",
        "ln"=>"辽宁",
        "jx"=>"江西",
        "ah"=>"安徽",
        "sc"=>"四川",
        "xz"=>"西藏",
        "qh"=>"青海",
        "hbriver"=>"河北",
        "sd"=>"山东",
        "js"=>"江苏",
        "nx"=>"宁夏",
        "gs"=>"甘肃",
        "hb"=>"湖北",
        "hnsea"=>"海南",
        "gd"=>"广东",
        "gx"=>"广西",
        "gz"=>"贵州",
        "gat"=>"港澳台",
        "oversea"=>"海外"
);
    $con = mysql_connect(SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
    if (!$con) {
        die("无法连接到数据库: ".mysql_error());
    } else {
        mysql_select_db(SAE_MYSQL_DB, $con);
        $res = mysql_fetch_array(mysql_query("SELECT * FROM tjstudents WHERE id='$_COOKIE[userid]'"));
        echo "<tr><td>学号：</td><td>".$res['id']."</td></tr>";
        echo "<tr><td>姓名：</td><td>".$res['name']."</td></tr>";
        echo "<tr><td>专业：</td><td>".$major2name[$res['current_major']]."</td></tr>";
        echo "<tr><td>入学年：</td><td>".$res['admission_year']."</td></tr>";
        echo "<tr><td>性别：</td><td>".($res['gender']=="male"?"男":"女")."</td></tr>";
        echo "<tr><td>生源：</td><td>".$ht2name[$res['hometown']]."</td></tr>";
        echo "<tr><td>生日：</td><td>".$res['birthday']."</td></tr>";
        mysql_close($con);
    }

?>
