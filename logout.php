<?
	session_start();
	include "ilcs_lib.php";
	
	$_SESSION['user_id'] = "";
	$_SESSION['user_name'] = "";
	
	_msg_script_go("로그아웃 완료","window.parent.location.href='/index.html'");
	

?>