<?
	session_start();
	include("../ilcs_lib.php");
	$user_id = $_SESSION['user_id'];
	
	if(strlen($user_id) > 1){
		$result_login=$dbCon->db_query("select name, team, position, vacation_lv, notice_lv, in_date from user where user_id = '$user_id' && user_pw = '$user_pw'");
		$rarray_login=$dbCon->sql_fetchRow($result_login);
		$name = $rarray_login[0];
		$team = $rarray_login[1];
	}else{
		_msg_go("로그인이 필요합니다", "index.html");
	}
	
	$idx = $_GET['idx'];
	
	$dbCon->db_query("delete from user where idx = '$idx'");
	
	_messagebox_nomal("삭제완료",1);
?>