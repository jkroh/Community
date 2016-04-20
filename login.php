<?
session_start();
include "ilcs_lib.php";
$user_id = $_POST['login_id'];
if(strlen($user_id)<1){
	$user_id = $_SESSION['user_id'];
	$user_pw = $_SESSION['user_pw'];
}else{
	$user_pw = md5($_POST['login_pw']);
}


if(strlen($user_id) < 1){
	$data = _load_htm("login");
}else{
	$data = _load_htm("info");
	

	$result_login=$dbCon->db_query("select name, team, position, vacation_lv, notice_lv, in_date from user where user_id = '$user_id' && user_pw = '$user_pw'");

	$rarray_login=$dbCon->sql_fetchRow($result_login);


	if(strlen($rarray_login[0])<1){
		_msg_go("로그인실패!!","login.htm");
	}
	
	$_SESSION['user_id'] = $user_id;
	$_SESSION['user_pw'] = $user_pw;
	
	
	
	$date_data = array(
		_mz("name") => $rarray_login[0],
		_mz("team") => $rarray_login[1],
		_mz("position") => $rarray_login[2],
		_mz("in_date") => $rarray_login[5],
		_mz("vacation_lv") => chk_lv_option($rarray_login[3]),
		_mz("notice_lv") => chk_lv_option($rarray_login[4]),
		
	);
	$data=_str_replace($date_data ,$data);
	
}

echo $data;
?>