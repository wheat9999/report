<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<head>
	<script type="text/javascript" src="/report/assets/jquery.js"></script>
	<script type="text/javascript" src="/report/js/main.js"></script>
	<link rel="stylesheet" type="text/css" href="/report/css/index.css" />
</head>
<body>

<script>

</script>
<h1>Welcome to <i>Auto-Report V1.1</i>:第<?php echo $weekId ?>周
【<?php echo $weekRange["start"] ?>-<?php echo $weekRange["end"] ?>】
</h1>
<div id="left">
    <br/>
	&nbsp;&nbsp;&nbsp;选择名字:&nbsp;&nbsp;<select id="user">

		<option value="0">---请选择名字---</option>
		<?php
		foreach($users as $user)
		{

		?>

		<option value="<?php echo $user['id'] ?>"><?php echo $user['name'] ?></option>
		<?php } ?>

	</select>
	<br/>
	<br/>
	&nbsp;&nbsp;&nbsp;选择项目:&nbsp;&nbsp;<select id="proj" onchange="selectProj()">
		<option value="0">---请选择项目---</option>
		<?php
		foreach($projs as $proj)
		{

		?>
		<option value="<?php echo 
                $proj['id']."$$#$$".$proj['jira']."$$#$$".$proj['owner_id']."$$#$$".$proj['plan'] 
                ?>"><?php echo $proj['name'] ?></option>
		<?php } ?>

	</select>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<button id="bt_add_proj" onclick="chgProjState()">增加</button>
	<div id="projShow">
		&nbsp;&nbsp;&nbsp;&nbsp;<a id="projLink" href="javascript:emptyClick()" target="_blank">项目链接</a>
	</div>

	<div id="projAdd">
	  <br/>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;项目名称:&nbsp;&nbsp;<input id="proj_name" type="text" onMouseOver="this.select();" VALUE="跟JIRA的项目名称保持一致" onClick="if(this.value==this.defaultValue){this.value=''}" onblur="if(this.value==''){this.value=this.defaultValue}"/>
		<br/>
		<br/>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JIRA&nbsp;&nbsp;&nbsp;&nbsp;ID:&nbsp;&nbsp;<input id="jira" type="text" onMouseOver="this.select();" VALUE="纯数字或者PROJ开头格式" onClick="if(this.value==this.defaultValue){this.value=''}" onblur="if(this.value==''){this.value=this.defaultValue}"/>
		<br/>
		<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;上线时间:&nbsp;&nbsp;<input id="plan" type="text"/>
		<br/>
		<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;选择&nbsp;&nbsp;&nbsp;&nbsp;PL:&nbsp;&nbsp;
		<select id="owner_id">
			<option value="0">---请选择项目PL---</option>
			<?php
			foreach($owners as $owner)
			{

				?>
				<option value="<?php echo $owner['id'] ?>"><?php echo $owner['name'] ?></option>
			<?php } ?>

		</select>
		<br/>
		<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button onclick="addProject()">增加</button>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;
		<button onclick="chgProjState()">取消</button>
		<br/>
		<br/>

	</div>

	<br/>
	&nbsp;&nbsp;&nbsp;工作内容:
	&nbsp;&nbsp;&nbsp;
	<button id="btn_add_report" onclick="addReport()">添加</button>

	<br/>
	&nbsp;&nbsp;&nbsp;<textarea id="detail" type="text"></textarea>

	<br/>
	<div id="popreport"><i>已成功添加一项工作记录！</i></div>
	<div id="popproject"><i>已成功添加一个项目！</i></div>
        <div id="hint">
        <br/>
        <?php if(count($unfinished)>0) { ?>
        *下列同学请递交工作内容*
        <br/>
        <?php } ?>
        <span id = "unfinished" >
       <?php  
       echo  implode(" ,",$unfinished);
   ?>
       </span>
        </div>
  
</div>


<div id="right">
<table class="datatable">
<th>项目名称</th>
<th>负责人</th>
<th>工作内容</th>
<th>计划上线</th>
<th>参与人</th>
<th>JIRA</th>
<?php
foreach($reports as $report)
{
?>
<tr>
<td><?php echo $report["name"] ?></td>
<td><?php echo $report["owner"] ?></td>
<td><?php echo $report["details"] ?></td>
<td><?php echo $report["plan"] ?></td>
<td><?php echo $report["users"] ?></td>
<td><a target="_blank" href="http://jira.dianshang.wanda.cn/browse/<?php if(strpos($report["jira"], "PROJ") !== 0) echo "FFWORKFLOW-" ?><?php echo $report["jira"] ?>"><?php if(strpos($report["jira"], "PROJ") !== 0) echo "FFWORKFLOW-" ?><?php echo $report["jira"] ?></a></td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>


