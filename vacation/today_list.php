<?
	session_start();
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
	
	
	$mode = $_GET['mode'];
	if($mode == 'del'){
		$idx = $_GET['idx'];
		$dbCon->db_query("delete from vacation where idx = '$idx'");
		_messagebox_nomal('삭제완료!',1);
	}
	
	$data=_load_htm("today_list");
	
	
	$block_vacation=_get_str_block("vacation_list",&$data);
	$result_va_list=$dbCon->db_query("SELECT  idx,year_va_start, year_va_end, half_va_start, half_va_end, public_va_start, public_va_end, permit_1, permit_2, permit_3, permit_4, permit_5, va_reason from vacation where user_id = '$user_id' order by idx desc;");
	while($rarray_va_list=$dbCon->sql_fetchRow($result_va_list))
	{
		$va_type = "";
		$start_date = "";
		$end_date = "";
		$chk_permit = 0;
		
		if(strlen($rarray_va_list[2]) > 0){
			$va_type .= "연차";
			
			$start_date = $rarray_va_list[1];
			$end_date = $rarray_va_list[2];
			
		}
		if(strlen($rarray_va_list[4]) > 0){
			if(strlen($rarray_va_list[2]) > 0){
				$va_type .= "+반차";
				if($start_date > $rarray_va_list[3]){
					$start_date = $rarray_va_list[3];
				}
				if($end_date < $rarray_va_list[4]){
					$end_date =  $rarray_va_list[4];
				}				
			}else{
				$va_type .= "반차";
				$start_date = $rarray_va_list[3];
				$end_date =  $rarray_va_list[4];
			}
			
		}	
		if(strlen($rarray_va_list[6]) > 0){
			if(strlen($rarray_va_list[2]) > 0  || strlen($rarray_va_list[4]) > 0){
				$va_type .= "+공가";
				if($start_date > $rarray_va_list[5]){
					$start_date = $rarray_va_list[5];
				}
				if($end_date < $rarray_va_list[6]){
					$end_date =  $rarray_va_list[6];
				}				
			}else{
				$va_type .= "공가";
				$start_date = $rarray_va_list[5];
				$end_date =  $rarray_va_list[6];
			}			
		}
		if($rarray_va_list[7] == 0){
			$permit_1 = "<input type='checkbox'  />";
		}else{
			$permit_1 = "<input type='checkbox' checked='true' />";
		}
		if($rarray_va_list[8] == 0){
			$permit_2 = "<input type='checkbox'  />";
		}else{
			$permit_2 = "<input type='checkbox' checked='true' />";
			$chk_permit = 1;
		}
		if($rarray_va_list[9] == 0){
			$permit_3 = "<input type='checkbox'  />";
		}else{
			$permit_3 = "<input type='checkbox' checked='true' />";
			$chk_permit = 1;			
		}
		if($rarray_va_list[10] == 0){
			$permit_4 = "<input type='checkbox'  />";
		}else{
			$permit_4 = "<input type='checkbox' checked='true' />";
			$chk_permit = 1;
		}
		
		$va_reason = mb_substr_ko($rarray_va_list[12],12);
		
		if($chk_permit == 1){
			$modi_del = "승인진행중";
		}else{
			$modi_del = "<div style='margin-bottom:3px'><input type='button' onclick='modi_val($rarray_va_list[0])' value='수정'></div><div><input type='button' onclick='del_val($rarray_va_list[0])'  value='삭제'></div>";
		}
		
		$temp_data= array (	
								_mz("va_type") => $va_type,
								_mz("start") => $start_date,
								_mz("end") => $end_date,
								_mz("permit_1") => $permit_1,
								_mz("permit_2") => $permit_2,
								_mz("permit_3") => $permit_3,
								_mz("permit_4") => $permit_4,
								_mz("reason") => mb_substr_ko($rarray_va_list[12],8),
								_mz("modi_del") => $modi_del,
								_mz("idx") => $rarray_va_list[0],
						   );
		$block_temp=_str_replace($temp_data,$block_vacation);
		$data=_insert_str_block("vacation_list",$block_temp,$data);
	}

	echo $data;
?>