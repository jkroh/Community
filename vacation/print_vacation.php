<?php
	session_start();
	header("Content-Type: text/html; charset=utf-8");
	include "../ilcs_lib.php";
	$user_id = $_SESSION['user_id'];
	if(strlen($user_id) > 1){//테스트용
		$result_login=$dbCon->db_query("select name, team, position, vacation_lv, notice_lv, in_date from user where user_id = '$user_id' && user_pw = '$user_pw'");
		$rarray_login=$dbCon->sql_fetchRow($result_login);
		$name = $rarray_login[0];
		$team = $rarray_login[1];
	}else{
		_msg_go("로그인이 필요합니다", "index.html");
	}
	$data = _load_htm("print_vacation");
	
	
	
	
	$idx = $_GET['idx'];	

	$today = date('Y 년	m 월	d 일');



	$result_va_list=$dbCon->db_query("SELECT  idx, year_va_start, year_va_end, half_va_start, half_va_end, half_option, public_va_start, public_va_end, permit_1, permit_2, permit_3, permit_4, permit_5, va_reason, year_count, half_count, public_count from vacation where user_id = '$user_id' and idx = '$idx'");
		$rarray_va_list=$dbCon->sql_fetchRow($result_va_list);
		
		
	$year_start = $rarray_va_list[1];
	$year_end = $rarray_va_list[2];
	
	if(strlen($year_end)<1){
		$year_end = $year_start;
	}
	
	$half_start = $rarray_va_list[3];
	$half_end = $rarray_va_list[4];
	
	if(strlen($half_end)<1){
		$half_end = $half_start;
	} 
	if($rarray_va_list[5] == 0){
		$half_type = "오전";
	}else{
		$half_type = "오후";
	}
	
	$public_start = $rarray_va_list[6];
	$public_end = $rarray_va_list[7];
	
	if(strlen($public_end)<1){
		$public_end = $public_start;
	} 
	$year_value = "";
	$half_value = "";
	$public_value = "";
	
	$year_count = $rarray_va_list[14];
	$half_count = $rarray_va_list[15];
	$public_count = $rarray_va_list[16];
	$va_value = "";
	$va_type = "";
	
	$year_start_sl = chage_date_form_slush($year_start);
	$year_end_sl = chage_date_form_slush($year_end);
	$half_start_sl = chage_date_form_slush($half_start);
	$half_end_sl = chage_date_form_slush($half_end);
	$public_start_sl = chage_date_form_slush($public_start);
	$public_end_sl = chage_date_form_slush($public_end);
	
	
	if(strlen($year_start)>0){
		$year_value = "연차:".$year_start_sl."일 ~ ".$year_end_sl." 일 (".$year_count."일간)";
		$va_value .= $year_value;
		$va_type ="연차";
	}
	if(strlen($half_start)>0){
		$half_value = "반차:".$half_start_sl."일 ~ ".$half_end_sl." 일 (".$half_count."일간/$half_type)";
		$va_value .= ", ".$half_value;
		$va_type .= "/반차";
		if(strlen($year_start) < 1){
			$va_value = $half_value;
			$va_type = "반차";
		}
	}
	if(strlen($public_start)>0){
		$public_value = "공가:".$public_start_sl."일 ~ ".$public_end_sl." 일 (".$public_count."일간)";
		$va_value = ", ".$public_value;
		$va_type .= "/공가";
		if(strlen($year_start) < 1 && strlen($half_start) <1){
			$va_value = $public_value;
			$va_type ="공가";
		}
	}
	
	

	$va_reason = $rarray_va_list[13];
		
		
		
		
		
		
		
		$query = "select * from user where name = '$name'";
		$option_result = $option_result=$dbCon->db_query($query);
		$option=$dbCon->sql_fetch_array($option_result);
		
		$date_data = array(
			_mz("idx") => $idx,
			_mz("name") => $name,
			_mz("half_type") => $half_type,
			_mz("va_type") => $va_type,
			_mz("va_reaseon") => $va_reason,
			_mz("team") => $team,
			_mz("position") => "사원",
			_mz("today") => $today,
			_mz("va_value") => $va_value,
			_mz("half_type") => $half_type,
				  
		);
		$data=_str_replace($date_data ,$data);		

		echo $data;
?>