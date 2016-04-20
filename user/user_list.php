<?
	session_start();
	header("Content-Type: text/html; charset=utf-8");
	include "../ilcs_lib.php";
	$user_id = $_SESSION['user_id'];
	$user_pw = $_SESSION['user_pw'];
	
	if(strlen($user_id) > 1){
		$result_login=$dbCon->db_query("select name, team, position, vacation_lv, notice_lv,manage_lv in_date from user where user_id = '$user_id' && user_pw = '$user_pw'");
		$rarray_login=$dbCon->sql_fetchRow($result_login);
		$name = $rarray_login[0];
		$team = $rarray_login[1];
		if($rarray_login[5] > 1){
			_msg_go("관리자만 접근가능합니다.", "/notice/today_list.php");
		}
	}else{
		_msg_go("로그인이 필요합니다", "/notice/today_list.php");
	}
		$data = _load_htm("user_list");
		
		$block_user=_get_str_block("user_all_list",&$data);
		$result_user_list = $dbCon->db_query("select idx,user_id, name, mail_addr, team, position, phone, vacation_lv, notice_lv, in_date from user");
		while($rarray_user_list=$dbCon->sql_fetchRow($result_user_list)){
		
		$vacation_lv = chk_lv_option($rarray_user_list[7]);
		
		$notice_lv = chk_lv_option($rarray_user_list[8]);
		
		$temp_data= array (	
								_mz("idx") => $rarray_user_list[0],
								_mz("user_id") => $rarray_user_list[1],
								_mz("name") => $rarray_user_list[2],
								_mz("mail_addr") => $rarray_user_list[3],
								_mz("team") => $rarray_user_list[4],
								_mz("position") => $rarray_user_list[5],
								_mz("phone") => $rarray_user_list[6],
								_mz("vacation_lv") => $vacation_lv,
								_mz("notice_lv") => $notice_lv,
								_mz("in_date") => $rarray_user_list[9],
						   );
		$block_temp=_str_replace($temp_data,$block_user);
		$data=_insert_str_block("user_all_list",$block_temp,$data);
		}


		echo $data;

?>