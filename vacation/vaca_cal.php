<?
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="/css/vaca.css" />
<script>
	
</script>
</head>
<body onload="window.parent.check_line()">
<?
	include("../ilcs_lib.php");
	$user_id = $_SESSION['user_id'];
	$user_pw = $_SESSION['user_pw'];
	
	if(strlen($user_id) > 1){
		$result_login=$dbCon->db_query("select name, team, position, vacation_lv, notice_lv, in_date from user where user_id = '$user_id' && user_pw = '$user_pw'");
		$rarray_login=$dbCon->sql_fetchRow($result_login);
		$name = $rarray_login[0];
		$team = $rarray_login[1];
	}else{
		_messagebox_nomal("로그인이 필요합니다",1);
	}

	$year = $_GET['year'];
	
	if(strlen($year) == 0){
		$year = date('Y');
	}
	$month = $_GET['month'];

	if(strlen($month) == 0){
		$month = date('m');
	}
	
	//	$day = $_GET['day'];
	$day = date('d');
	
	$daychk = $day;
	$chk_cnt = 0;
	$last_day = date("t",mktime(0,0,0,$month,1,$year));
	$year_plus = $year+1;
	$year_minus = $year-1;
//	$last_day = 
	echo "<input type='hidden' id='page_month' name='page_month' value='$month'/>";
	echo "<input type='hidden' id='page_year' name='page_year' value='$year' />";
	echo "<input type='hidden' id='page_end_day' name='page_end_day' value='$last_day' />";
	
	echo "<div style='width:680px;text-align:center'>";
		echo "<div style='margin-bottom:10px'>
		<select id='cal_month' name='cal_month' onchange='window.parent.change_year(this.value)'>
			<option value='$year_minus'>$year_minus</option>
			<option value='$year' selected>$year</option>
			<option value='$year_plus'>$year_plus</option> 
		</select>년
		<select id='cal_month' name='cal_month' onchange='window.parent.change_mon(this.value)'>";
		
		for($i=1;$i<13;$i++){
			$cal_month = sprintf("%02d",$i);
			if($cal_month == $month){
				echo "<option value='$cal_month' selected='selected'>$cal_month</option>";
			}else{
				echo "<option value='$cal_month'>$cal_month</option>";
			}
		}
		echo "</select>월</div>";

		
		
		for($j=1; $j<=$last_day; $j++){
			$box_date = $month."/".sprintf("%02d",$j);
			$year_box_date = "'year_".$box_date."'";
			$half_box_date = "'half_".$box_date."'";
			$public_box_date = "'public_".$box_date."'";
			
			$view_date = "'".$month."월".sprintf("%02d",$j)."일'";
			$week_day = date("w",mktime(0,0,0,$month,$j,$year));
			switch($week_day){
				case 0: 
					$week = 일;
					break;
				case 1: 
					$week = 월;
					break;
				case 2: 
					$week = 화;
					break;
				case 3: 
					$week = 수;
					break;
				case 4: 
					$week = 목;
					break;
				case 5: 
					$week = 금;
					break;
				case 6: 
					$week = 토;
					break;					
			
			}
			
			if( $daychk == $j && $month == date('m') && $year == date('Y')){
				echo "<div class='href_today'   onclick=".'"'."javascript:window.parent.date_in('$box_date',$view_date,$last_day)".'"'.">".$week.'<br/>'.$month."/".sprintf("%02d",$j)."
						
						<div class='under_border_year' id=$year_box_date ></div>
						<div class='under_border_half' id=$half_box_date ></div>
						<div class='under_border_public' id=$public_box_date ></div>
					  	
					  </div>";
				if($chk_cnt == 20) echo "<div style='clear:bonth'></div><br/>";
			}else if($week_day==6||$week_day ==0){
				echo "<div class='cal_text' >".$week.'<br/>'.$month."/".sprintf("%02d",$j)."
						<div class='under_border_year' id=$year_box_date ></div>
						<div class='under_border_half' id=$half_box_date ></div>
						<div class='under_border_public' id=$public_box_date ></div>
					  </div>";		
				if($chk_cnt == 20) echo "<div style='clear:bonth'></div><br/>";
			}else{
				echo "<div class='href_cal'  onclick=".'"'."javascript:window.parent.date_in('$box_date',$view_date,$last_day)".'"'.">".$week.'<br/>'.$month."/".sprintf("%02d",$j)."
						<div class='under_border_year' id=$year_box_date ></div>
						<div class='under_border_half' id=$half_box_date ></div>
						<div class='under_border_public' id=$public_box_date ></div>
					 </div>";
				if($chk_cnt == 20) echo "<div style='clear:bonth'></div><br/>";
			}
			$chk_cnt++;
		}

	
	echo "</div>";
?>
</html>