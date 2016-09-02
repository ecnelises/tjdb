function changeSelect(item) {
    var deplist = {
        "sem": {"class_mng": "社会科学实验班（管理学类）","mng_project": "工程管理","mng_info": "信息管理与信息系统","mng_transport": "物流管理","market_sale": "市场营销","accounting": "会计学","mng_administration": "行政管理","class_economy": "社会科学实验班（经济学类）","finance": "金融学","international_trade": "国际经济与贸易"},
        "caup": {"architecture": "建筑学","urban_planning": "城乡规划","historic_preservation": "历史建筑保护工程","landscape": "风景园林"},
        "tjdi": {"industrial_design": "工业设计","class_design": "设计学类"},
        "cart": {"class_journal": "新闻传播学类","tele_broadcast": "广播电视学","advertisement": "广告学","cartoon": "动画","tele_bc_directing": "广播电视编导","performance": "表演","music_performance": "音乐表演"},
        "civileng": {"class_civileng": "工科实验班（土木工程类）","civil_engineering": "土木工程","port_shore": "港口河道与海岸工程","geology_engineering": "地质工程"},
        "celiang": {"survey_map": "测绘工程"},
        "jt": {"class_jt": "工科实验班（交通运输类）","traffic_engineering": "交通工程","carriage": "交通运输","transport_engineering": "物流工程"},
        "sese": {"class_sese": "工科实验班（环境科学与工程类）","water_supply_drainage": "给排水科学与工程","environmental_engineering": "环境工程","environmental_science": "环境科学"},
        "mat": {"material": "材料科学与工程"},
        "mefaculty": {"class_mefaculty": "工科实验班（机械能源班）","machinery_automation": "机械设计制造及其自动化","industrial_engineering": "工业工程","energy_appliance": "建筑环境与能源应用工程","energy_power": "能源与动力工程"},
        "auto": {"vehicle_engineering": "车辆工程"},
        "railway": {"vehicle_engineering": "车辆工程"},
        "see": {"class_computer": "工科实验班（计算机类）","computer_science": "计算机科学与技术","information_security": "信息安全","class_information": "工科实验班（电气信息类）","automation": "自动化","electrical_engineering": "电气工程及其自动化","infomation_engineering": "电子信息工程","communication_engineering": "通信工程","electrical_science": "电子科学与技术"},
        "sse": {"software_engineering": "软件工程"},
        "cdhaw": {"class_cdhaw": "机械类（中外合作办学）","machinery_electrical_engineering": "机械电子工程","car_service": "汽车服务工程","intelligentization": "建筑电气与智能化"},
        "life": {"class_life": "生物科学类","biology_technology": "生物技术","biology_information": "生物信息学"},
        "med": {"clinical_medicine": "临床医学","rehabilitation": "康复治疗学"},
        "kq": {"stomatology": "口腔医学"},
        "mgg": {"class_mgg": "理科实验班（海洋科学与地球物理学类）","geology": "地质学","earth_physics": "地球物理学","sea_explicit": "海洋资源开发技术"},
        "aeromech": {"engineering_mechanics": "工程力学","flight_engineering": "飞行器制造工程"},
        "methematics": {"class_math": "理科实验班（数学系）","math_appliance": "数学与应用数学","statistics": "统计学"},
        "physics": {"class_physics": "理科实验班（物理学类）","physics_appliance": "应用物理学","photoelectricity_science": "光电信息科学与工程"},
        "chemistry": {"class_chemistry": "理科实验班（化学系）","chemistry_appliance": "应用化学","chemical_engineering": "化学工程与工艺"},
        "law": {"jurisprdence": "法学"},
        "sfl": {"english": "英语","japanese": "日语","german": "德语"},
        "sal": {"class_sal": "人文科学实验班","philosophy": "哲学","cultural_industry_management": "文化产业管理","chinese": "汉语言文学"},
        "spsir": {"political_administrative_science": "政治学与行政学","sociology": "社会学"}
    };
    
    var towritecol, towritemaj;
    if (item === 'current') {
        towritecol=document.getElementById("current_college");
        towritemaj=document.getElementById("current_major");
    } else if (item === 'past') {
        towritecol=document.getElementById("past_college");
        towritemaj=document.getElementById("past_major");
    }
    
    if (towritecol.options[towritecol.selectedIndex].value !== "none") {
        towritemaj.removeAttribute("hidden");
        towritemaj.parentNode.removeAttribute("hidden");
    } else {
        towritemaj.setAttribute("hidden", "true");
        towritemaj.parentNode.setAttribute("hidden", "true");
        return;
    }
    
    towritemaj.options.length=0;
    
    var i = 0;
    var z;

    for (x in deplist) {
        if (x === towritecol.options[towritecol.selectedIndex].value) {
            for (y in deplist[x]) {
                z = document.createElement("option");
                z.innerHTML=deplist[x][y];
                z.value=y;
                towritemaj.add(z, null);
            }
            break;
        }
    }
}