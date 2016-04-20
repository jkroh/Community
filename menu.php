<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="/css/menu.css" />
<script>
	function menu1_over(type){
		all_none();
		if(type == 0){
			document.getElementById('vaca_menu').style.display = "block";
		}else{
			document.getElementById('vaca_menu').style.display = "none";
		}	
	}
	function menu2_over(type){
		all_none();
		if(type == 0){
			document.getElementById('noti_menu').style.display = "block";
		}else{
			document.getElementById('noti_menu').style.display = "none";
		}	
	}
	function all_none(){
		document.getElementById('vaca_menu').style.display = "none";
		document.getElementById('noti_menu').style.display = "none";
	}
	function main_location(dir,page){
		parent.index_main.location.href=dir+"/"+page;
	}
	
</script>
</head>
<body>
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="linker_button" onmouseover="menu1_over(0)" onclick="main_location('vacation','today_list.php')" height="30"><b><font size="+1">연차관리</font></b></td>
			<td class="linker_button" onmouseover="menu2_over(0)" height="30" align="right" width="150"><b><font size="+1">공지사항</font></b></td>
			<td class="linker_button" onclick="main_location('user','user_list.php')" height="30" align="right" width="150"><b><font size="+1">사용자관리</font></b></td>
		</tr>
	</table>
	<div style="display:none" id="vaca_menu"  onmouseleave="menu1_over(1)">
		<table border="0" cellpadding="0" cellspacing="0" height="15" style="margin-top:5px">
			<tr height="30" style="line-height:15px">
				<td class="linker_button" width="60" onclick="main_location('vacation','vaca_main.htm')">연차신청</td><td class="linker_button" width="60" onclick="main_location('vacation','today_list.php')">연차확인</td>
			</tr>
		</table>
	</div>
		<div style="display:none;padding-left:150px" id="noti_menu"  onmouseleave="menu2_over(1)" >
		<table border="0" cellpadding="0" cellspacing="0" height="15" style="margin-top:5px" width="150">
			<tr height="30" style="line-height:15px">
				<td class="linker_button" width="60" onclick="main_location('#">고객지원팀</td><td class="linker_button" width="60" onclick="#')">서버솔루션팀</td>
			</tr>
		</table>
	</div>
</body>
</html>
