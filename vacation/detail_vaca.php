<?
	session_start();
	include("../ilcs_lib.php");
	$user_id = $_SESSION['user_id'];
	if(strlen($user_id) < 1){//테스트용
		$user_id = 'shxodwk';
	}
	$data=_load_htm("detail_vaca");
	
	$mode = $_GET['mode'];
	$idx = $_GET['idx'];
	
	if($mode == 'del'){
		$idx = $_GET['idx'];
		$dbCon->db_query("delete from vacation where idx = '$idx'");
		_msg_script_go('삭제완료!',"window.opener.location.reload();self.close()");
	}
	
	
	$manager = "승인함";
	$department_head = "승인함";
	$manage_support = "승인함";
	$delegate = "승인함";
	
	$result_va_list=$dbCon->db_query("SELECT  idx, year_va_start, year_va_end, half_va_start, half_va_end, half_option, public_va_start, public_va_end, permit_1, permit_2, permit_3, permit_4, permit_5, va_reason,year_count,half_count,public_count from vacation where user_id = '$user_id' and idx = '$idx'");
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
	if(strlen($half_start)>0){
		if($rarray_va_list[5] == 0){
			$half_type = "<span class='date_span' style='background-color:#FFFFFF'><font color='#000000'>&nbsp;오전&nbsp;</font></span>";
		}else{
			$half_type = "<span class='date_span' style='background-color:#000000'><font color='#FFFFFF'>&nbsp;오후&nbsp;</font></span>";
		}
	}
	$public_start = $rarray_va_list[6];
	$public_end = $rarray_va_list[7];
	
	if(strlen($public_end)<1){
		$public_end = $public_start;
	} 

	$va_reason = $rarray_va_list[13];
	
	if(strlen($public_start)>0){
		$public_count = $rarray_va_list[16];
	}
	
	if(strlen($half_start)>0){
		$half_count = $rarray_va_list[15];
	}
	
	if(strlen($year_start)>0){
		$year_count = $rarray_va_list[14];
	}
	$total = $public_count + $half_count + $year_count;

	
	
	$lnk_data = array( _mz("idx") => $idx,
					   _mz("manager") => $manager,
					   _mz("department_head") => $department_head,
					   _mz("manage_support") => $manage_support,
					   _mz("delegate") => $delegate,
					   _mz("year_start") => $year_start,
					   _mz("year_end") => $year_end,
					   _mz("year_count") => $year_count,
					   _mz("half_start") => $half_start,
					   _mz("half_end") => $half_end,
					   _mz("half_count") => $half_count,
					   _mz("half_type") => $half_type,
					   _mz("public_start") => $public_start,
					   _mz("public_end") => $public_end,
					   _mz("public_count") => $public_count,
					   _mz("va_reason") => $va_reason,
					   _mz("total") => $total,
					  );

	$data=_str_replace($lnk_data ,$data);



	
	echo $data;
?>