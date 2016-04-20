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
		_msg_go("로그인이 필요합니다", "index.html");
	}
	$mode = $_POST['mode'];
	if($mode == "modi"){
		$user_id = $_POST['user_id'];
		$basic_id = $_POST['basic_id'];
		$user_pw = $_POST['user_pw'];
		$name = $_POST['name'];
		$mail_addr = $_POST['mail_addr'];
		$team = $_POST['team'];
		$position = $_POST['position'];
		$phone = $_POST['phone'];
		$in_date = $_POST['in_date'];
		$idx = $_POST['idx'];
		$vaca_option = $_POST['vaca_option'];
		$notice_option = $_POST['notice_option'];
		
		
		if($user_id == ""){
			_messagebox_nomal("ID가 없습니다",1);			
		}
		if($user_id != $basic_id){
			$user_id_chk=$dbCon->db_query("select count(*) from user where user_id = '$user_id'");
			$user_id_chk_val=$dbCon->sql_fetchRow($user_id_chk);	
			if($user_id_chk_val[0] >0){
				_messagebox_nomal("중복된 ID값 입니다");
			}
		}
		if(strlen($user_pw)> 0){
			$user_pw = md5($user_pw);
			$dbCon->db_query("update user set user_id = '$user_id', user_pw = '$user_pw',name = '$name', mail_addr = '$mail_addr, team = '$team', position = '$position';, phone='$phone', in_date = '$in_date', notice_lv = $notice_option, vacation_lv = $vaca_option  where idx = '$idx'");
		}else{
			$dbCon->db_query("update user set user_id = '$user_id', name = '$name', mail_addr = '$mail_addr', team = '$team', position = '$position', phone='$phone', in_date = '$in_date' , notice_lv = $notice_option, vacation_lv = $vaca_option  where idx = '$idx'");
			
		}	
		
		_msg_go("수정완료!","user_list.php");
	}else{
	
		$idx = $_GET['idx'];
		$result_user=$dbCon->db_query("select user_id, name, team, position, vacation_lv, notice_lv, in_date, mail_addr,phone from user where idx = '$idx'");
		$rarray_user=$dbCon->sql_fetchRow($result_user);
		
	
			switch($rarray_user[4]){
				case 0 : $vacation_lv0 = "selected"; 
						 break;
				case 1 : $vacation_lv1 = "selected"; 
						 break;
				case 2 : $vacation_lv2 = "selected"; 
						 break;		 		 
			}
			
			switch($rarray_user[5]){
				case 0 : $notice_lv0 = "selected"; 
						 break;
				case 1 : $notice_lv1 = "selected"; 
						 break;
				case 2 : $notice_lv2 = "selected"; 
						 break;		 		 
			}
		
		$data=_load_htm("user_mod");
		$lnk_data = array( 
						 	_mz("idx") => $idx,
						  	_mz("user_id") => $rarray_user[0],
						   	_mz("name") => $rarray_user[1],
						   	_mz("mail_addr") => $rarray_user[7],
						   	_mz("team") => $rarray_user[2],
						   	_mz("position") => $rarray_user[3],
						   	_mz("phone") => $rarray_user[8],
						   	_mz("in_date") => $rarray_user[6],
						   	_mz("vaca_noti_select0") => $notice_lv0,
						   	_mz("vaca_noti_select1") => $notice_lv1,
						   	_mz("vaca_noti_select2") => $notice_lv2,
						  	_mz("vaca_op_select0") => $vacation_lv0,
						   	_mz("vaca_op_select1") => $vacation_lv1,
						   	_mz("vaca_op_select2") => $vacation_lv2,
						  );
	
		$data=_str_replace($lnk_data ,$data);
		
		echo $data;
	}
?>