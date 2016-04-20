<?
	session_start();
	include('ilcs_lib.php');

	$user_id = $_SESSION['user_id'];
	$user_pw = $_SESSION['user_pw'];
	
	if(strlen($user_id) < 1){//테스트용
		$user_id = 'shxodwk';
	}
	if(strlen($_POST['year_va_start'])  > 0 ){
		$year_va_start 		= $_POST['y_start_year']."년".$_POST['year_va_start'];
	}
	if(strlen($_POST['year_va_end'])  > 0 ){
		$year_va_end 		= $_POST['y_end_year']."년".$_POST['year_va_end'];
	}
	

	if(strlen($_POST['half_va_start'])  > 0 ){
		$half_va_start 		= $_POST['h_start_year']."년".$_POST['half_va_start'];
	}
	if(strlen($_POST['half_va_end'])  > 0 ){
		$half_va_end 		= $_POST['h_end_year']."년".$_POST['half_va_end'];
	}
	

	if(strlen($_POST['public_va_start'])  > 0 ){
		$public_va_start 		= $_POST['p_start_year']."년".$_POST['public_va_start'];
	}
	if(strlen($_POST['public_va_end'])  > 0 ){
		$public_va_end 		= $_POST['p_end_year']."년".$_POST['public_va_end'];
	}
	

	if($year_va_start > $year_va_end){
		$year_va_end = $year_va_start;
	} 
	
	if($half_va_start > $half_va_end){
		$half_va_end = $half_va_start;
	} 
	
	if($public_va_start > $public_va_end){
		$public_va_end = $public_va_start;
	} 
	
	$year_count = chk_week_sum($year_va_start,$year_va_end);
	$half_count = chk_week_sum($half_va_start,$half_va_end)*0.5;
	$public_count = chk_week_sum($public_va_start,$public_va_end);
	
	$va_reason = $_POST['va_reason'];
	$half_option = $_POST['half_option'];

	$result_va_count=$dbCon->db_query("SELECT count(*) from vacation;");
	$rarray_va_count=$dbCon->sql_fetchRow($result_va_count);

	$idx = $rarray_va_count[0] +1;

	$dbCon->db_query("insert into vacation(idx, user_id,year_va_start, year_va_end, year_count, half_va_start, half_va_end, half_count, public_va_start, public_va_end, public_count, va_reason, half_option, permit_1) values ('$idx','$user_id','$year_va_start', '$year_va_end','$year_count', '$half_va_start', '$half_va_end','$half_count', '$public_va_start', '$public_va_end','$public_count', '$va_reason','$half_option', '1')");

	_msg_go("입력완료!","today_list.php");


?>