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
	
	$data=_load_htm("user_in");

	$mode = $_POST['mode'];

	
	if($mode == "insert"){
		$user_id = $_POST['user_id'];
		$user_pw = md5($_POST['user_pw']);
		$name = $_POST['name'];
		$mail_addr = $_POST['mail_addr'];
		$team = $_POST['team'];
		$position = $_POST['position'];
		$phone = $_POST['phone'];
		$vacation_lv = $_POST['vacation_lv'];
		$notice_lv = $_POST['notice_lv'];
		$in_date = $_POST['in_date'];		
		
		$result_check_id=$dbCon->db_query("select count(*) from user where user_id = '$user_id'");
		$rarray_check_id=$dbCon->sql_fetchRow($result_check_id);
		
		if($rarray_check_id[0] > 0){
			_messagebox_nomal("계정이 중복되었습니다",1);
		}
	
		$dbCon->db_query("insert into user (user_id, user_pw, name, mail_addr, team, position, phone, vacation_lv, notice_lv, in_date) values ('$user_id', '$user_pw', '$name', '$mail_addr', '$team', '$position', '$phone', '$vacation_lv', '$notice_lv', '$in_date')");
		
		_msg_go("추가완료","user_list.php");
	}


	echo $data;

?>