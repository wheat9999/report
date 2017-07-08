/**
 * Created by daceychen on 16/10/23.
 */
function chgProjState(){
    $("#projAdd").toggle();
    $("#bt_add_proj").toggle();
}
function addUsers(users,newuser)
{

    var userArray = users.split(",");
    if(userArray.indexOf(newuser) == -1)
    {
        userArray.push(newuser);
    }
    return userArray.join(",");

}

function changeTable()
{
    var proj_name = $("#proj").find("option:selected").text();
    var isFound = 0;
    $("table tr:gt(0)").each(function(j){
        if($(this).children("td").eq(0).text() == proj_name)
        {
            isFound = 1;
            var detail = $("#detail").val();
            var user = $("#user").find("option:selected").text();
            var oldvalue = $(this).children("td").eq(2).text();
            var newvalue = "<br/><span id='newAdd'> * "+ detail + "</span>";
            $(this).children("td").eq(2).append(newvalue);
            var oldusers = $(this).children("td").eq(4).text();
            var newusers = addUsers(oldusers,user);
            $(this).children("td").eq(4).text(newusers);
        }
    });

    if(isFound == 0)
    {

        var proname  = $("#proj").find("option:selected").text();
        var projkeys = $("#proj").val().split("$$#$$");
        var id  = projkeys[0];
        var jira = projkeys[1];
        var ownerid = projkeys[2];
        var plan = projkeys[3];
        var owner = $("#owner_id option[value='"+ownerid+"']").text();
        var user = $("#user").find("option:selected").text();
        var detail = $("#detail").val();

        var jiraIdFix = "FFWORKFLOW-"+jira;

        //不是数字
        if(isNaN(jira)) {
             jiraIdFix = jira;
        }

        var jiraurl = "<a target='_blank'  href='http://jira.dianshang.wanda.cn/browse/"+
            jiraIdFix+"'>"+jiraIdFix+"</a>";
        var tr = "<tr id='newadd'>"+
            "<td>"+proname+"</td>"+
            "<td>"+owner +"</td>"+
            "<td>"+detail +"</td>"+
            "<td>"+plan+"</td>"+
            "<td>"+user+"</td>"+
            "<td>"+jiraurl+"</td>"+
            "</tr>";
        $("table").append(tr);

    }
}
function changeHint()
{
    var hint = $("#unfinished").text().trim();
    var nameArray = hint.split(" ,");
    var user = $("#user").find("option:selected").text();
    var newArray = [];
    for(var i = 0; i< nameArray.length;i++)
    {
        if(nameArray[i] != user)
        {
            newArray.push(nameArray[i]);
        }
    }
    if(newArray.length == 0)
    {
        $("#unfunished").hide();
        $("#hint").hide();
    }
    else {
        var newhint = newArray.join(" ,");
        $("#unfinished").text(newhint);
    }
}

function addReportSucc()
{       changeTable();
    changeHint();
    $("#detail").val("");
    $('#popreport').show();
    $('#popreport').delay(2000).hide(0);

    $("#btn_add_report").text("添加");
    $("#btn_add_report").attr("onclick","addReport()");
}

function addProjectSucc(msg)
{
    chgProjState();
    $('#popproject').show();
    $('#popproject').delay(2000).hide(0);
    var proj_name = $("#proj_name").val();
    var jira = $("#jira").val();
    var plan = $("#plan").val();
    var owner_id = $("#owner_id").val();
    var provalue = msg + "$$#$$" + jira + "$$#$$" + owner_id + "$$#$$" + plan;
    $("#proj").append("<option value='"+provalue+"'>"+proj_name+"</option>");
    $("#proj").val(provalue);

    selectProj();
    $("#proj_name").val("");
    $("#jira").val("");
    $("#plan").val("");

}

function addProject()
{
    var proj_name = $("#proj_name").val();
    var jira = $("#jira").val();
    var plan = $("#plan").val();
    var owner_id = $("#owner_id").val();


    if(proj_name == "" )
    {
        alert("请输入项目名称!")
        return;
    }

    if(jira == "" )
    {
        alert("请输入JIRA ID!")
        return;
    }

    if(plan == "" )
    {
        alert("请输入计划上线时间!")
        return;
    }

    if(owner_id == 0)
    {
        alert("请选择PL!")
        return;
    }


    var fdStart = jira.indexOf("PROJ");
    //不是proj开头
    if(fdStart == -1)
    {
        //并且不是数字
        if(isNaN(jira)) {
            alert("JIRAID不符合格式,必须纯数字!");
            return;
        }

    }

    var data =
    {
        name:proj_name,
        jira:jira,
        plan:plan,
        owner_id:owner_id

    }

    $.ajax({
        type: 'Get',
        url: 'site/addProject',
        data: data,
        dataType: "json",
        cache: false,
        success: function (msg) {

            if(msg == 0)
            {
                alert("该Jiar ID 已经被创建，请检查!")
            }
            else{
                addProjectSucc(msg);
            }



        }
    });

}

function emptyClick()
{

}

function selectProj()
{
    var proname  = $("#proj").find("option:selected").text();

    if($("#proj").val() == 0)
    {
        var jira = 0;
    }
    else
    {
        var projkeys = $("#proj").val().split("$$#$$");

        var jira = projkeys[1];
    }

    if(jira == 0)
    {
        $("#projLink").text("项目链接");
        $("#projLink").attr("href","javascript:emptyClick()");
    }
    else
    {


        $("#projLink").text(proname.substr(0,18));

        //不是数字开头
        if(isNaN(jira)) {

            var  href="http://jira.dianshang.wanda.cn/browse/"+jira;
        }
        else
        {
            var  href="http://jira.dianshang.wanda.cn/browse/FFWORKFLOW-"+jira;
        }


        $("#projLink").attr("href",href);

    }






}

function addReport()
{
    var user = $("#user").val();
    var proj = $("#proj").val();
    var detail = $("#detail").val();


    if(user == 0 )
    {
        alert("请选择用户!")
        return;
    }

    if(proj == 0)
    {
        alert("请选择项目!")
        return;
    }else
    {
        var projkeys = proj.split("$$#$$");
        proj = projkeys[0];
    }

    if(detail == '')
    {
        alert("输入点工作内容吧!")
        return;
    }

    $("#btn_add_report").text("加载中");
    $("#btn_add_report").attr("onclick","emptyClick()");

    var data =
    {
        user:user,
        proj:proj,
        detail:detail

    }

    $.ajax({
        type: 'Get',
        url: 'site/addReport',
        data: data,
        dataType: "json",
        cache: false,
        success: function (msg) {
            addReportSucc();


        }
    });
}
