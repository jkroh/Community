<?
//==================================================== 범용 함수 =============================================================
include "ilcs_var.php";



//--------------------------------------------------- MySql 전용 함수 ---------------------------------------------------

class ilcs_mysql
{		
	// member variables
	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	
	/**
	 * 생성자  -  db connection 을 맺는다
	 */
	function ilcs_mysql($server='', $user='', $password='', $dbName='', $persistency=true)
	{
		$this->server = $server;
		$this->dbname = $dbName;
		$this->user = $user;
		$this->passwd = $password;
		$this->persistency = $persistency;		
		
		if(($this->server == "") or ($this->dbname == "") or  ($this->user == "" ) )
		{
			return false;
		}
		
		if($this->persistency)
		{
			//$this->db_connect_id = @mysql_pconnect($this->server , $this->user, $this->passwd);
			$this->db_connect_id = mysql_pconnect($this->server , $this->user, $this->passwd);
		}
		else 
		{
			//$this->db_connect_id = @mysql_connect($this->server , $this->user, $this->passwd);
			$this->db_connect_id = mysql_connect($this->server , $this->user, $this->passwd);
		}
		
		if($this->db_connect_id) 
		{
			if($this->dbname !="" )
			{
				//$db_select_id = @mysql_select_db($this->dbname);				
				$db_select_id = mysql_select_db($this->dbname);				
				if(!$db_select_id)
				{
					@mysql_close($this->db_connect_id);
					$this->db_connect_id = $db_select_id;
				} 
			}
			return $this->db_connect_id;
		} else 
		{
			return false;
		}		
	} // constructor end
			
	/**
	 * db 연결을 close 한다.
	 */ 
	function db_close()
	{
		if($this->db_connect_id)
		{
			if($this->query_result)
			{
				@mysql_free_result($this->query_result);
			}
			$result = @mysql_close($this->db_connect_id);
			return $result;
		} else 
		{
			return false;
		}
	} // function 'db_close' end
	
	function db_query($query="")
	{
		// 이전 작업 결과 unset
		unset($this->query_result);
		
		if($query != "")
		{
			$this->num_queries++;
			
			$this->query_result = @mysql_query($query, $this->db_connect_id);
//echo 			mysql_error($this->db_connect_id);
			if($this->query_result)			
			{
				unset($this->row[$this->query_result]);
				unset($this->rowset[$this->query_result]);
				return $this->query_result;
			}			
		}
	} // function 'db_query' end
	
	/**
	 * SELECT 결과로부터 열 개수를 얻는다. 
	 * 결과 Resource를 받지 않는다면 this 결과 Resource값
	 */
	function sql_numRows($result_id=0)
	{
		if(!$result_id)
		{
			$result_id = $this->query_result;
		}
		
		if($result_id)
		{
			$row_num = @mysql_num_rows($result_id);
			return $row_num;
		} else
		{
			return false;
		}
	} // function 'sql_numRows' end
	
	/**
	 * SELECT 결과로부터 필드 개수를 얻는다. 
	 * 결과 Resource를 받지 않는다면 this 결과 Resource값
	 */
	function sql_numFields($result_id=0)
	{
		if(!$result_id)
		{
			$result_id = $this->query_result;
			// jyyoo
			echo "result " . $this->query_result . "<br>";
		}
		
		if($result_id)
		{
			$field_num = @mysql_num_fields($result_id);
			return $field_num;
		} else
		{
			return false;
		}
	} // function 'sql_numFields' end
	
	/**
	 * query 결과로 영향을 받은 열 개수를 얻는다.
	 * INSERT, UPDQTE, DELETE, SELECT
	 */
	function sql_affectedRows()
	{
		if($this->db_connect_id)
		{
			$affected_num = @mysql_affected_rows($this->db_connect_id);			
			return $affected_num;
		} else
		{
			return false;
		}
	} // function 'sql_affectedRows' end
	
	
	/**
	 * SELECT 결과로부터 필드의 이름을 반환
	 */
	function sql_fieldName($result_id=0, $offset)
	{
		if(!$result_id)
		{
			$result_id = $this->query_result;
		}
		
		if($result_id)
		{
			$result = @mysql_field_name($result_id, $offset);
			return $result;
		} else
		{
			return false;
		}
	} // function 'sql_fieldName' end
	
	/**
	 * 결과로부터 특정 필드의 데이터 형을 반환
	 */
	function sql_fieldType($result_id=0, $offset)
	{
		if(!$result_id)
		{
			$result_id = $this->query_result;
		}
		
		if($result_id)
		{
			$result = @mysql_field_type($result_id, $offset);
			return $result;
		} else
		{
			return false;
		}
	} // function 'sql_fieldType' end
	
	/**
	 * 결과를 숫자 색인으로된 배열로 반환
	 */
	function sql_fetchRow($result_id=0)
	{
		if(!$result_id)
		{
			$result_id = $this->query_result;
		}
		
		if($result_id)
		{
			$this->row[$result_id]=@mysql_fetch_row($result_id);
			return $this->row[$result_id];
		} else
		{
			return false;
		}
	} // function 'sql_fetchRow' end

	function sql_fetch_array($result_id=0) // 보강^^
	{
		if(!$result_id)
		{
			$result_id = $this->query_result;
		}
		
		if($result_id)
		{
			$this->row[$result_id]=@mysql_fetch_array($result_id,MYSQL_BOTH);
			return $this->row[$result_id];
		} else
		{
			return false;
		}
	} // function 'sql_fetchRow' end
	
	function sql_fetchRowSet($result_id=0)
	{
		if(!$result_id)
		{
			$result_id = $this->query_result;
		}
		if($result_id)
		{
			unset($this->rowset[$result_id]);
			unset($this->row[$result_id]);
			while($this->rowset[$result_id] = @mysql_fetch_array($result_id))
			{
				$result[] = $this->rowset[$result_id];				
			}
			return $result;
		} else
		{
			return false;
		}
	} // function 'sql_fetchRowSet' end
	
	function sql_fetchField($field, $rownum=-1, $result_id=0)
	{
		if(!$result_id)
		{
			$result_id = $this->query_result;
		}
		if($result_id)
		{
			if($rownum>-1)
			{
				$result=@mysql_result($result_id, $rownum, $field);
			} else
			{
				if(empty($this->row[$result_id] ) && empty($this->rowset[$result_id]))
				{
					if($this->sql_fetchrow())
					{
						$result = $this->row[$result_id][$field];
					}
				} else
				{
					if($this->rowset[$result_id][$field])
					{
						$result=$this->rowset[$query_id][$field];
					} else if($this->row[$result_id])
					{
						$result = $this->row[$result_id][$field];
					}
				}
			}
			return $result;
		} else 
		{
			return false;
		}
	} // function 'sql_fetchField' end
			
	function sql_rowSeek($rownum, $result_id=0)
	{
		if(!$result_id)
		{
			$result_id = $this->query_result;
		}
		if($result_id)
		{
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		} else 
		{
			return false;
		}
	} // function 'sql_rowSeek' end
	
	function sql_nextId()
	{
		if($this->db_connect_id)
		{
			$result=@mysql_insert_id($this->db_connect_id);
			return $result;
		} else 
		{
			return false;
		}
	} // function 'sql_nextid' end
	
	function sql_freeResult($result_id=0)
	{
		if(!$result_id)
		{
			$result_id=$this->query_result;
		} 
		if($result_id)
		{
			unset($this->row[$result_id]);
			unset($this->rowset[$result_id]);
			
			@mysql_free_result($result_id);
			return true;
		} else 
		{
			return false;
		}
	} // function 'sql_freeResult' end
	
	function sql_error($result_id=0)
	{
		$result["msg"] = @mysql_error($this->db_connect_id);
		$result["code"] = @mysql_errno($this->db_connect_id);
		return $result;
	} // function 'sql_error' end
	
	/**
	 * 정의해서 사용하자
	 */
	function sql_display_table ( ) { ; }  // function 'sql_display_table' end
			
} // 'database' class end
 
	//  db 연결 설정
	$dbCon = new ilcs_mysql($dbhost, $dbuser_id, $dbuser_pw, $dbname, false);

	if(!$dbCon->db_connect_id)
	{
   		// message_die(CRITICAL_ERROR, "Could not connect to the database");
   		echo "DB connect error";
	}

function _messagebox($message,$lv_hist)
{
    echo "<script>window.alert('".iconv("utf-8","euc-kr",$message)."');";
	if($lv_hist >0)
	{
		echo "history.go(-".$lv_hist.");";
		echo "</script>";
	  
		exit;
	}
	else
	{
		echo "</script>";
	}
}
function _messagebox_nomal($message,$lv_hist)
{
    echo "<script>window.alert('".$message."');";
	if($lv_hist >0)
	{
		echo "history.go(-".$lv_hist.");";
		echo "</script>";
	  
		exit;
	}
	else
	{
		echo "</script>";
	}
}

function _msg_go($message,$url)
{
    echo "<script>window.alert('".$message."');</script>";
	//echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$url.'">';
    echo "<script>location.href='".$url."'</script>";
}
function _msg_script_go($message,$script)
{
    /*echo "<script>window.alert('".$message."');</script>";*/ //스크립트 적용 불가(한글깨짐)
	echo "<script>window.alert('".$message."');</script>";
	//echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$url.'">';
    echo "<script>".$script."</script>";
}
function _script_go($script)
{
	//echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$url.'">';
    echo "<script>".$script."</script>";
}
function _force_go_url($url)
{
	echo "<script>location.href='".$url."'</script>";
}

function _parent_force_go_url($url)
{
	echo "<script>opener.parent.location.href='".$url."'</script>";
}
function _window_force_go_url($url)
{
	
	echo "<script>parent.document.location.href='".$url."'</script>";
}

function _go_url($url)
{
    echo "<script>location.href='".$url."'</script>";
	//echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$url.'">';
}

function _init_login($url,$child)
{
	session_start();

	if(session_is_registered("user_id") && session_is_registered("user_lv"))
	{
		return true;
	}
	else
	{
		@session_destroy();

		if(substr($url,0,7)=='http://')
		{
			if($child=='y')
			{
				_window_force_go_url($url);
				echo "<script>self.close();</script>";
			}
			else
			{
				_force_go_url($url);
			}
		}
		else
		{
			if($child=='y')
			{
				_window_force_go_url("http://".$url);
				echo "<script>self.close();</script>";
			}
			else
			{
				_window_force_go_url("http://".$url);
			}
		}
	}
}

// is_login ver 0.01
function _is_login($url,$dbCon,$sid,$usr_id,$child)
{
	$result_sid = $dbCon->db_query("select SESSION,LEVEL from ilcs_connection.`USER` where USER_ID='$usr_id'");
	$rarry_sid = $dbCon->sql_fetchRow($result_sid);

	if($rarry_sid[0]==$sid)
	{
		return true;
	}
	else
	{
	
		$dbCon->db_close();
		@session_destroy();

//		_messagebox("다른 곳에서 로그인이 되어 자동으로 로그아웃 되었습니다!!",0);

		if(substr($url,0,7)=='http://')
		{
			if($child=='y')
			{
				_parent_force_go_url($url."?mode=loggout");
				echo "<script>self.close();</script>";
			}
			else
			{
				_parent_force_go_url($url."?mode=loggout");
			}
		}
		else
		{
			if($child=='y')
			{
				_parent_force_go_url("http://".$url."?mode=loggout");
				echo "<script>self.close();</script>";
			}
			else
			{
				_parent_force_go_url("http://".$url."?mode=loggout");
			}
		}
	}
}

function _is_otp($url,$dbCon,$usr_id,$otp_flag)
{

	$result_otp = $dbCon->db_query("select OTP_FLAG from ilcs_connection.`USER` where USER_ID='$usr_id'");
	$rarry_otp = $dbCon->sql_fetchRow($result_otp);

	if($rarry_otp[0] == 1)
	{
		if($otp_flag) return true;
		else _window_parent_force_go_url("인증(OTP)되지 않은 사용자 입니다.","http://".$url);
	}
	else
	{
		return true;
	}
}
function _window_parent_force_go_url($message,$url)
{
	echo "<script>window.alert('".$message."');</script>";
	echo "<script>window.parent.location.href='".$url."'</script>";
}

// log_out ver 0.01
function _logout($url)
{
	session_start();
	@session_destroy();

	if(substr($url,0,7)=='http://')
	{
		_force_go_url($url);
	}
	else
	{
		_force_go_url("http://".$url);
	}
}

function _accept_lv($accept_lv,$s_user_lv)
{
//	session_start();

//	_messagebox($user_lv,0);

	if($s_user_lv <= $accept_lv)
	{
		return true;
	}
	else
	{
		_messagebox("권한이 없습니다!!",1);
	}
}
	

// 입력된 배열의 키네임과 같은 문자열을 해당 키네임의 값으로 치환하는 함수 ver 0.01
function _str_replace($array,$data)
{ 
	foreach ($array as $key => $val)
	{ 
		$data = str_replace("$key","$val","$data"); 
	} 

	return $data; 
} 

// 원하는 블럭데이터를 값을 리턴하는 함수 ver 0.01
function _get_str_block($block_name,$data)
{
	// 블럭 이름의 크기
	$fn_len=strlen($block_name)+8;

	// 시작 블럭 이름
	$start_block_name="<!--#".$block_name."-->";

	// 종료 블럭 이름
	$end_block_name="<!--".$block_name."#-->";

	// 블럭 시작 위치
	$start_pos=strpos($data,$start_block_name)+$fn_len;

	// 블럭 종료 위치
	$end_pos=strpos($data,$end_block_name)-$start_pos;

//	echo "s:".$start_pos."<br>e:".$end_pos."<br>";
	// 블럭을 입력된 데이터로 얻기
	$result=substr($data,$start_pos,$end_pos);
	$data=substr_replace($data,"",$start_pos,$end_pos);
	return $result;
}

function _remove_str_block($block_name,$data)
{
	// 블럭 이름의 크기
	$fn_len=strlen($block_name)+8;

	// 시작 블럭 이름
	$start_block_name="<!--#".$block_name."-->";

	// 종료 블럭 이름
	$end_block_name="<!--".$block_name."#-->";

	// 블럭 시작 위치
	$start_pos=strpos($data,$start_block_name);

	// 블럭 종료 위치
	$end_pos=strpos($data,$end_block_name)-$start_pos+$fn_len;

//	echo "s:".$start_pos."<br>e:".$end_pos."<br>";
	// 블럭을 입력된 데이터로 지우기
	$data=substr_replace($data,"",$start_pos,$end_pos);
}

function _update_str_block($block_name,$update_data,$data)
{
	// 블럭 이름의 크기
	$fn_len=strlen($block_name)+8;

	// 시작 블럭 이름
	$start_block_name="<!--#".$block_name."-->";

	// 종료 블럭 이름
	$end_block_name="<!--".$block_name."#-->";

	// 블럭 시작 위치
	$start_pos=strpos($data,$start_block_name)+$fn_len;

	// 블럭 종료 위치
	$end_pos=strpos($data,$end_block_name)-$start_pos;

//	echo "s:".$start_pos."<br>e:".$end_pos."<br>";
	// 블럭을 입력된 데이터로 업데이트
	$result=substr_replace($data,$update_data,$start_pos,$end_pos);

	return $result;
}

function _insert_str_block($block_name,$insert_data,$data)
{
	// 블럭 이름의 크기
	$fn_len=strlen($block_name)+8;

	// 시작 블럭 이름
	$start_block_name="<!--#".$block_name."-->";

	// 종료 블럭 이름
	$end_block_name="<!--".$block_name."#-->";

	// 블럭 시작 위치
	$start_pos=strpos($data,$start_block_name)+$fn_len;
	// 블럭 종료 위치
	$end_pos=strpos($data,$end_block_name);

/*
	$eofs=sizeof($data);

	$temp_bak="";

	for($i=($end_pos+$fn_len); $i < $eofs; $i++)
	{
		$temp_bak[($i-$end_pos+$fn_len)]=$data[$i];
	}
*/

	// 블럭안에 데이터 삽입
	$result=substr_replace($data,$insert_data,$end_pos,0);

	return $result;
}

function _substr_ko($data, $len)
{
	$result[0]=$data;

	if($len > 0 && $len < (strlen($data)/2))
	{
		$len=$len*2;

		$data=substr($data,0,$len);
		preg_match('/^([\x00-\x7e]|.{2})*/', $data, $result);
		$result[0]=$result[0]."...";
	}

	return $result[0];
} 

function mb_substr_ko($data, $len)
{
	$result[0]=$data;

	if(strlen($result[0]) > $len)
	{
		$result[0] = mb_substr($data, 0, $len, "utf-8")."..";
	}
	
	return $result[0];
} 

function set_merge_HILO($hi,$lo)
{
	$result="";

	if($hi > 15 || $lo > 15)
	{
		return false;
	}
	else
	{
		$hi=dechex($hi);
		$lo=dechex($lo);

		$result=$hi.$lo;
		$result=hexdec($result);
		return $result;
	}
}
function get_split_HI($hilo)
{

	$result=dechex($hilo);
	$hi="";

	if(strlen($result)==2)
	{
		$hi=substr($result,0,1);
		$result=hexdec($hi);
		return $result;
	}
	else
	{
		return false;
	}
}

function get_split_LO($hilo)
{
	$result=dechex($hilo);
	$lo="";

	if(strlen($result)==2)
	{
		$lo=substr($result,1,1);
		$result=hexdec($lo);
		return $result;
	}
	else
	{
		return false;
	}
}

//--------------------------------------------------- Socket 전용 함수 ---------------------------------------------------

class ilcs_Client_Socket
{
    var $sock;
    var $uid;
    var $dType;
    var $sType;
    var $len;
    var $data;
    
    function ilcs_Client_Socket($addr, $port)
    {
        $this->sock = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP)
            or die("create error!!\n");
		@socket_connect($this->sock, $addr, $port ) or $this->error_page('Server Connection Error');			
    } // Constructor end
	
	function error_page($str="")
	{
		printf ('
			<html>
			<body class=contents>
			<center>
			<br><br><br>
				<table border=0 width=700 cellpadding=0 cellspacing=0>
				<tr><td bgcolor="#FFF270" height="3"></td></tr>
				<tr><td bgcolor="#000000" height="3"></td></tr>
				<tr><td bgcolor="#FFF270" height="3"></td></tr>
				<!--/table --> 		
				<tr><td align=center>			
					<br><br>
					<font color="darkblue" face="Arial" size="6">
					<b>red</b>
					</font>
					<br><br>
					<font color="#000000" face="굴림" size="2">
					------------- 시스템 관리자에게 문의 하십시오. ---------------
					</font>
					<br><br>
				</td></tr>	
				<!-- table border=0 width=700 cellpadding=0 cellspacing=0 -->
				<tr><td bgcolor="#FFF270" height="3"></td></tr>
				<tr><td bgcolor="#000000" height="3"></td></tr>
				<tr><td bgcolor="#FFF270" height="3"></td></tr>
				</table>
			</center>
			</body>
			</html>
		', $str);
	} // funcion error_page end	
		
	function make_message($uid, $type, $sub, $len, $data)
	{
		$huid = $uid;
		$hlen = sprintf ("%08d", $len);
		$total = $type.$sub.$huid.$hlen.$data;
		return $total;
	} // 'make_message' function end
	
	function send ()
	{
		$u = $this->get_UID();
		$t = $this->get_dataType();
		$s = $this->get_subDataType();
		$l = $this->get_length();
		$d = $this->get_data();

		$buf = $t.$s.$u.$l.$d;

//		$buf = $t.$s."11111111111111111111111111111111".$l.$d;
		//$buf = "0020111111111111111111111111111111111100000037dcount=1&did1=1&turn_on=7&turn_off=7&";
//		$buf = "0030041111111111111111111111111111111100000013mid=m0000001&";

		//$d="dcount=1&did1=1&turn_on=7&turn_off=7&";
//		_messagebox($buf,0);
//	_messagebox(strlen($d),0);

//		echo("uid=".$u." dataType=".$t." subDataType=".$s." length=".$l." data=".$d." all=".$buf);
		
		$result = @socket_write($this->sock, $buf, strlen($buf));
		if ($result)
		{			
			$result = '03';		
		}
		else 
		{			
			$this->error_page('Socket Write Error');
			$result = '04';		// FAIL_RCV
		}
		/*echo "<script>alert('".$result."');</script>";*/
		return $result;
		//return @socket_write($this->sock, $buf, strlen($buf));		
	} // 'send' function end

	function send_seed_enc()
	{
		$i_key	= "locojoy-kj=enus!";
		$i_iv	= "locojoy-enus=kj!";
//	$enc2 = encrypt($g_bszIV, $g_bszUser_key, $g_bszPlainText);
//	$enc2 = Mzencrypt($g_bszIV, $g_bszUser_key, $g_bszPlainText);

		$t = '01';
		$s = $this->get_subDataType();
		$u = $this->get_UID();

		$l = $this->get_length();
		$d = $this->get_data();

		$enc = encrypt_seed($i_iv, $i_key, $d, 1);

		$buf = $t.$s.$u.sprintf ("%08d", $enc[0]).$enc[1];//$l.$d;

//		_messagebox($buf,0);



//		$buf = $t.$s."11111111111111111111111111111111".$l.$d;
		//$buf = "0020111111111111111111111111111111111100000037dcount=1&did1=1&turn_on=7&turn_off=7&";
//		$buf = "0030041111111111111111111111111111111100000013mid=m0000001&";

		//$d="dcount=1&did1=1&turn_on=7&turn_off=7&";
//		_messagebox($buf,0);
//	_messagebox(strlen($d),0);

//		echo("uid=".$u." dataType=".$t." subDataType=".$s." length=".$l." data=".$d." all=".$buf);
		
		$result = @socket_write($this->sock, $buf, strlen($buf));
		if ($result)
		{			
			$result = '03';		
		}
		else 
		{			
			$this->error_page('Socket Write Error');
			$result = '04';		// FAIL_RCV
		}
		/*echo "<script>alert('".$result."');</script>";*/
		return $result;
		//return @socket_write($this->sock, $buf, strlen($buf));		
	} // 'send' function end
	
	function recv($buf_len = 1024)
	{
		$result = @socket_read($this->sock, $buf_len);

		if($result==false)
		{
			$this->error_page('Socket Write Error');
			$result = '04';		// FAIL_RCV
		}
		/*
		if ( !$result || $result =='04')
		{
			//$this->error_page('Socket Read Error');
			$result ='04';		// FAIL_RCV
		}*/
		return $result;		
        //return @socket_read($this->sock, $buf_len);
    } // 'recv' function end
	
	function set_attribute($u, $t, $s, $d)
	{
		$this->uid = $u;
	    $this->dType = $t;
	    $this->sType = $s;
		$l = strlen($d);
    	$this->len = sprintf ("%08d", $l);
    	$this->data = $d;
	} // 'set_attribute' function end
	
	/*
	$binary_data = pack( "a4a2a4IA*" , '0000' , '11', '2100', strlen($sdata) , "$sdata");

	$test = unpack ( "a4UID/a2Dtype/a4SType/ILen/A*Data", $binary_data);
	*/
	function str_pack ($uid, $d_type, $sub_d_type, $len, $data)
	{
		//$binary_data = pack ("a4a2a4lA*",$uid, $d_type, $sub_d_type, $len, $data);
		$binary_data = pack ("LSLLA*",$uid, $d_type, $sub_d_type, $len, $data);
		return $binary_data; 
	} // str_pack function end
	
	function str_unpack ($binary_str)
	{
		// $data is array
		$data = unpack("a4uid/a2dType/a4sType/Ilen/A*data", $binary_str);
		return $data;
	} // 'str_unpack' function end

    function close()
	{
        socket_close($this->sock);
    } // 'close' function end
		
	function get_UID()
	{		
		return $this->uid;
	} // 'get_UID' end
	
	function set_UID($u)
	{
		$h = sprintf ("%08x", $u);
		$this->uid = $h;
	} // 'set_UID' end
	
	function get_dataType()
	{
		return $this->dType;
	} // 'get_dataType' end
	
	function set_dataType($t)
	{
		$this->dType = $t;
	} // 'set_dataType' end
	
	function get_subDataType()
	{
		return $this->sType;
	} // 'get_subDataType' end
	
	function set_subDataType($t)
	{
		$this->sType = $t;
	} // 'set_subDataType' end
	
	function get_length()
	{		
		return $this->len;
	} // 'get_length' end
	
	function set_length($l)
	{		
		$h = sprintf ("%08x", $l);
		$this->length = $h;
	} // 'set_length' end
	
	function get_data()
	{		
		return $this->data;
	} // 'get_data' end
	
	function set_data($d)
	{
		$this->data = $d;
	} // set_data end
	
}

// 통신 객체
//$uiSock = new ilcs_Client_Socket($svr_addr,$svr_port);

function To_Server ($u, $t, $s, $d, $Sock )
{//$out0 = To_Server($PHPSESSID,'00', $messageID , $s_data0, $uiSock0);
	$u = md5($u);
	
	$d.="z=";
    $d.=$GLOBALS["user_zid"];
	$d.="&";

	$Sock->set_attribute($u, $t, $s, $d);
	$inc = NLC_INC;
	
	if($inc == 0){
		$Sock->send();
	}else{
		$Sock->send_seed_enc();
	}
	$result = $Sock->recv();
	$Sock->close();
//	_messagebox($result,0);

	if ( $result == '03' ) // success
	{
		return true;
	}
	else if ($result == '04') // fail
	{
		return false;
	}
	else 
	{
		return $result;
	}	
} // 'To_Server' function end

//=================================================== ilcs 전용 함수 ============================================================
function msg_result_communication($error)
{
	if($error==true)
	{
		_messagebox("통신 실패!!",1);
	}
	else
	{
		_messagebox("설정 완료!!",1);
	}
}

function msg_result_communication2($error)
{
	if($error==true)
	{
		_messagebox("통신 실패!!",2);
	}
	else
	{
		_messagebox("설정 완료!!",2);
	}
}

function get_formal_ph_no($ph_no)
{
	$temp="";

	if (strlen($ph_no) == 11)
	{
		$temp= substr("$ph_no", 0, 3);
		$temp.="-";
		$temp.= substr("$ph_no", 3, 4);
		$temp.="-";
		$temp.= substr("$ph_no", 7, 4);
	}
	else if (strlen($ph_no) == 10)
	{
		$temp= substr("$ph_no", 0, 3);
		$temp.="-";
		$temp.= substr("$ph_no", 3, 3);
		$temp.="-";
		$temp.= substr("$ph_no", 6, 4);
	}
	else
	{
		$temp=$ph_no;
	}

	return $temp;
}
function get_formal_time($d_time)
{
	$temp="";

	if (strlen($d_time) == 4)
	{
		$temp= substr("$d_time", 0, 2);
		$temp.=":";
		$temp.= substr("$d_time", 2, 2);

		return $temp;
	}
	else
	{
		return false;
	}
}
function get_reservation_time($d_time)
{
	$temp="";
	$sub_d_time = substr($d_time, 0, 2);
	
	if($sub_d_time == "xx") $temp = "사용 안함";
	else {
		$temp= substr("$d_time", 0, 2);
		$temp.=":";
		$temp.= substr("$d_time", 2, 2);
		$temp.=" ~ ";
		$temp.= substr("$d_time", 4, 2);
		$temp.=":";
		$temp.= substr("$d_time", 6, 2);
	}

	return $temp;
}
function get_formal_time_bold($d_time,$i,$mon)
{
	$temp="";
    $i++;    
    $today = date("Ymd");
    $day = substr($today,4,4);
	$mon = sprintf('%02d',$mon);
 	
	if (strlen($d_time) == 4)
    {
    	if (($mon.$i) == $day)
                $temp="<div style='background-color:#C6DCF4; font-weight:bold;'>";
		$temp.= substr("$d_time", 0, 2);
		$temp.=":";
		$temp.= substr("$d_time", 2, 2);
        if ($i == $day)
        	$temp.="</div>";

		return $temp;
	}
	else
		return false;
}

function chk_state_dist($state,$err)
{
	$temp="";

	$temp='<center><img src="./img/';

	if($err=='2')
	{
		switch($state)
		{
			case '1':
				$temp.='on';
				break;
			case '2':
				$temp.='off';
				break;
		}
	}
	else if($err=='1')
	{
		$temp.='c_error';
	}
	else if($err=='3')
	{
		$temp.='s_error';
	}
	else
	{
		$temp.='unknown';
	}

	$temp.='.gif"></center>';
	
	return $temp;
}

function chk_state_plc($state)
{
	$temp="";

	$temp='<img src="./img/';

	switch($state)
	{
		case '0':
			$temp.='off'; // 소등
			break;
		case '1':
			$temp.='on'; // 점등
			break;
		case '2':
			$temp.='c_error'; // 통신두절
			break;
		case '3':
			$temp.='l_error'; // 램프 에러
			break;
		case '4':
			$temp.='s_error'; // 안전기 이상
			break;
		case '5':
			$temp.='ss_error'; // 센서 이상
			break;
		case '8':
			$temp.='light_no'; // 설치 x
			break;
/*		case '9':
			$temp.='p_down'; // 누전
			break;*/
		default:
			$temp.='unknown';
			break;
	}

	$temp.='.gif">';
	
	return $temp;
}


function chk_state_dist_txt($state,$err,$oper_state)
{

	$temp='<center>';

	if($err=='2' || $err=='3')
	{
		switch($state)
		{
			case '1':
				if($oper_state=='1')
				{
					$temp.='정상 점등(내장설정)';
				}
				else if($oper_state=='2')
				{
					$temp.='강제 점등';
				}
				else if($oper_state=='3')
				{
					$temp.='정상 점등(사용자설정)';
				}
				else if($oper_state=='4')
				{
					$temp.='RF제어 점등 상태';
				}
				else if($oper_state=='5')
				{
					$temp.='점검 점등 상태';
				}
				break;
			case '2':
				if($oper_state=='1')
				{
					$temp.='정상 소등(내장설정)';
				}
				else if($oper_state=='2')
				{
					$temp.='강제 소등';
				}
				else if($oper_state=='3')
				{
					$temp.='정상 소등(사용자설정)';
				}
				else if($oper_state=='4')
				{
					$temp.='RF제어 소등 상태';
				}
				else if($oper_state=='5')
				{
					$temp.='점검 소등 상태';
				}
				break;
			case '3':
			case '4':
				if($oper_state=='1')
				{
					$temp.='정상 격등(내장설정)';
				}
				else if($oper_state=='2')
				{
					$temp.='강제 격등';
				}
				else if($oper_state=='3')
				{
					$temp.='정상 격등(사용자설정)';
				}
				else if($oper_state=='4')
				{
					$temp.='RF제어 격등 상태';
				}
				else if($oper_state=='5')
				{
					$temp.='점검 격등 상태';
				}
				break;
		}
	}
	else if($err=='1')
	{
		$temp.='<font color="darkblue">통신에러</font>';
	}
/*	else if($err=='3')
	{
		$temp.='<font color="darkblue"><b>PLC에러</b></font>';
	}*/

	$temp.='</center>';
	
	return $temp;
}

function chk_state_dist_mode($oper_state)
{
	$temp='<center>';

	switch($oper_state)
	{
		case '1':
			$temp.="내장점소등 시간운영";
			break;
		case '2':
			$temp.="강제";
			break;
		case '3':
			$temp.="사용자설정 시간운영";
			break;
		case '4':
			$temp.="RF 제어";
			break;
		case '5':
			$temp.="점검";
			break;
	}
	$temp.='</center>';

	return $temp;
}

function chk_state_dist_run($state,$err)
{

	$temp='<center>';

	if($err=='2' || $err=='3')
	{
		switch($state)
		{
			case '1':
				$temp.='점등';
				break;
			case '2':
				$temp.='소등';
				break;
			case '3':
			case '4':
				$temp.='격등';
				break;
		}
	}
	else if($err=='1')
	{
		$temp.='<font color="darkblue">통신에러</font>';
	}
/*	else if($err=='3')
	{
		$temp.='<font color="darkblue"><b>PLC에러</b></font>';
	}*/

	$temp.='</center>';
	
	return $temp;
}

function chk_dstate_dist_txt($state,$err,$oper_state,$half_state)
{

	$temp='<center>';

	if($err=='2' || $err=='3')
	{
		if($half_state=='0')
		{
			$temp.='분전함격등 모드/';
		}
		else if($half_state=='1')
		{
			$temp.='PLC격등 모드/';
		}

		switch($state)
		{
			case '1':
				if($oper_state=='1')
				{
					$temp.='정상점등(내장설정)';
				}
				else if($oper_state=='2')
				{
					$temp.='강제점등';
				}
				else if($oper_state=='3')
				{
					$temp.='정상점등(사용자설정)';
				}
				else if($oper_state=='4')
				{
					$temp.='RF제어점등 모드';
				}
				else if($oper_state=='5')
				{
					$temp.='점검점등 모드';
				}
				break;
			case '2':
				if($oper_state=='1')
				{
					$temp.='정상소등(내장설정)';
				}
				else if($oper_state=='2')
				{
					$temp.='강제소등';
				}
				else if($oper_state=='3')
				{
					$temp.='정상소등(사용자설정)';
				}
				else if($oper_state=='4')
				{
					$temp.='RF제어소등 모드';
				}
				else if($oper_state=='5')
				{
					$temp.='점검소등 모드';
				}
				break;
			case '3':
			case '4':
				if($oper_state=='1')
				{
					$temp.='정상격등(내장설정)';
				}
				else if($oper_state=='2')
				{
					$temp.='강제격등';
				}
				else if($oper_state=='3')
				{
					$temp.='정상격등(사용자설정)';
				}
				else if($oper_state=='4')
				{
					$temp.='RF제어격등 모드';
				}
				else if($oper_state=='5')
				{
					$temp.='점검격등 모드';
				}
				break;
		}
	}
	else if($err=='1')
	{
		$temp.='<font color="darkblue">통신에러</font>';
	}
/*	else if($err=='3')
	{
		$temp.='<font color="darkblue"><b>PLC에러</b></font>';
	}*/

	$temp.='</center>';
	
	return $temp;
}
//================= mysql 함수 몇 개 (현재 사용하지 않음, mysql 함수는 간단하므로 함수나 클래스로 정의할 필요 없다고 생각함--)==================

function _db_connect($dbuser_id,$dbname,$dbuser_pw)
{
	mysql_connect("localhost","$dbuser_id","$dbuser_pw") or die( " SQL server에 연결할 수 없습니다.");
	mysql_select_db("$dbname") or die( " SQL server에 연결할 수 없습니다.");
}

function _db_close()
{
	mysql_close();
}

function _lock_table($tablename)
{
	mysql_query("LOCK TABLES $tablename WRITE");
}

function _unlock_table($tablename)
{
	mysql_query("UNLOCK TABLES");
}

function _mz($name)
{
	return ":Mz|".$name.":";
}

function _load_htm($name)
{
	$data="";

	$filename=$name.".htm";
	$fp = @fopen("$filename",r); 
	$data = @fread($fp,filesize("$filename")); 
	@fclose($fp);

	return $data;
}

function mz_bitwise_plc ($inp)
{
	$inp = decbin(hexdec($inp));

	$data = array ("쌍등", "격등", "수동모드", "램프1 ON", "램프1 고장", "램프1 안정기 고장","램프1 등주누전 발생", "램프1 등주누전 경고","선로누전 발생","선로누전 경보","제어기 고장","램프2 ON","램프2 고장", "램프2 안정기 고장","램프2 등주누전 발생", "램프2 등주누전 경고");

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result[]="통신두절입니다";
	}
	else
	{
		$total = strlen($inp);

		if($total < sizeof($data))
		{
			for ($i=0; $i < (sizeof($data)-$total); $i++)
			{
				$inp = '0'.$inp;
			}
			//$inp=sprintf("%016d",$inp);
		}

		$total = strlen($inp);

		if($total == sizeof($data))
		{
			for ($i=0; $i < $total; $i++)
			{
				if ($inp[$i] == 1)
				{
					if($i < 11)
					{
						$result[] = $data[$i];
					}
					else if ($i >= 11 && $inp[0]=='1')
					{
						$result[] = $data[$i];
					}
				}
				else if($inp[$i] == 0)
				{
					if($i == 3)
					{
						$result[] = "램프1 OFF";
					}
					else if($i == 11 && $inp[0]=='1')
					{
						$result[] = "램프2 OFF";
					}
				}
			}
		}
		else
		{
			$result[]="잘못된 데이터입니다";
		}
	}

	return $result;
}

function mz_get_plc_status ($lv,$v_hex)
{
	$result="empty";
//	$v_hex="1F1F";

//	$inp = decbin(hexdec($v_hex));
	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result="plc_no";
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{
			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{
			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

/*			for ($i=0; $i < (16-$total); $i++)
			{
				$inp = '0'.$inp;
			}
			*/
			//$inp=sprintf("%016d",$inp);

		$total = strlen($inp);

		if($total == 16)
		{
			if($inp=="0001111100011111")
			{
				$result="plc_comm_err";
			}
			else if($lv==1)
			{
				if($inp[10] == 1) // 제어기 고장
				{
					$result="plc_c_error";
				}
				else if ($inp[0] == 1) // 쌍등
				{
					$result="plc_twins";
				}
				else if ($inp[0] == 0)
				{
					$result="plc_single";
				}
			}
			else if($lv==2 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[4] == 1) // 고장
					{
						$result="plc_error1";
					}
					else if ($inp[3] == 1 && $inp[5] != 1 && $inp[6] != 1) // 점등
					{
						$result="plc_on1";
					}
				}
			}
			else if($lv==3 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[4] == 1) // 고장
					{
						$result="plc_serror";
					}
/*					else if ($inp[6] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[7] == 1) // 등주 누전 경보
					{
						$result="plc_leakage_alert2";
					}*/
					else if ($inp[3] == 1 && $inp[5] != 1) // 점등
					{
						$result="plc_son";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[12] == 1) // 고장
					{
						$result="plc_error2";
					}
		/*			else if ($inp[14] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[15] == 1) // 등주 누전 경보 
					{
						$result="plc_leakage_alert2";
					}*/
					else if ($inp[11] == 1 && $inp[13] != 1 && $inp[14] != 1) // 점등
					{
						$result="plc_on2";
					}
				}

			}
			else if($lv==4 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[5] == 1) // 안전기 고장
					{
						$result="plc_s_error1";
					}
				}

			}
			else if($lv==5 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[5] == 1) // 안전기 고장
					{
						$result="plc_s_error2";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[13] == 1) // 안전기 고장
					{
						$result="plc_s_error2";
					}
				}
			}
			else if($lv==6 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[6] == 1) // 등주 누전
					{
						$result="plc_leakage1";
					}
					else if ($inp[7] == 1) // 등주 누전 경보
					{
						$result="plc_leakage_alert1";
					}
				}

			}
			else if($lv==7 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[6] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[7] == 1) // 등주 누전 경보
					{
						$result="plc_leakage_alert2";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[14] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[15] == 1) // 등주 누전 경보 
					{
						$result="plc_leakage_alert2";
					}
				}
			}
			else if($lv==8 && $inp[10] == 0)
			{
				if ($inp[8] == 1) // 선로 누전
				{
					$result="plc_l_leakage";
				}
				else if ($inp[9] == 1) // 선로 누전 경보
				{
					$result="plc_l_leakage_alert";
				}
			}
			else if($lv==9 && $inp[10] == 0)
			{
				if ($inp[2] == 1) // 수동
				{
					$result="plc_manual";
				}
			}
		}
/*				if ($inp[$i] == 1)
				{
					if($i < 8)
					{
						$result[] = $data[$i];
					}
					else if ($i >= 8 && $inp[0]=='1')
					{
						$result[] = $data[$i];
					}
				}
				else if($inp[$i] == 0)
				{
					if($i == 3)
					{
						$result[] = "램프1 OFF";
					}
					else if($i == 11 && $inp[0]=='1')
					{
						$result[] = "램프2 OFF";
					}
				}
			}
		}*/
	}
	return $result;
}

function mz_get_plc_status_gn ($lv,$v_hex,$r_state)
{
	$result="empty";
//	$v_hex="1F1F";

//	$inp = decbin(hexdec($v_hex));
	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result="plc_single";

		if($lv==3 && $r_state==1)
		{
			$result="plc_son";
		}
		else if($r_state==2)
		{
		}
		//else
		//{
		//	$result="plc_no";
		//}
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{

			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{

			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

/*			for ($i=0; $i < (16-$total); $i++)
			{
				$inp = '0'.$inp;
			}
			*/
			//$inp=sprintf("%016d",$inp);

		$total = strlen($inp);

		if($total == 16)
		{
			if($inp=="0001111100011111")
			{
				$result="plc_comm_err";
			}
			else if($lv==1)
			{
				if($inp[10] == 1) // 제어기 고장
				{
					$result="plc_c_error";
				}
				else if ($inp[0] == 1) // 쌍등
				{
					$result="plc_twins";
				}
				else if ($inp[0] == 0)
				{
					$result="plc_single";
				}
			}
			else if($lv==2 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[3] == 1) // 점등
					{
						$result="plc_on1";
					}
				}
			}
			else if($lv==3 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[3] == 1) // 점등
					{
						$result="plc_son";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[11] == 1) // 점등
					{
						$result="plc_on2";
					}
				}

			}
			else if($lv==4 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[5] == 1) // 안전기 고장
					{
						$result="plc_s_error1";
					}
				}

			}
			else if($lv==5 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[5] == 1) // 안전기 고장
					{
						$result="plc_s_error2";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[13] == 1) // 안전기 고장
					{
						$result="plc_s_error2";
					}
				}
			}
			else if($lv==6 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[6] == 1) // 등주 누전
					{
						$result="plc_leakage1";
					}
					else if ($inp[7] == 1) // 등주 누전 경보
					{
						$result="plc_leakage_alert1";
					}
				}

			}
			else if($lv==7 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[6] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[7] == 1) // 등주 누전 경보
					{
						$result="plc_leakage_alert2";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[14] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[15] == 1) // 등주 누전 경보 
					{
						$result="plc_leakage_alert2";
					}
				}
			}
			else if($lv==8 && $inp[10] == 0)
			{
				if ($inp[8] == 1) // 선로 누전
				{
					$result="plc_l_leakage";
				}
				else if ($inp[9] == 1) // 선로 누전 경보
				{
					$result="plc_l_leakage_alert";
				}
			}
			else if($lv==9 && $inp[10] == 0)
			{
				if ($inp[2] == 1) // 수동
				{
				//	$result="plc_manual";
				}
			}
		}
	}
	return $result;
}

function mz_get_plc_status_gn2 ($lv,$v_hex,$r_state)
{
	$result="empty";
//	$v_hex="1F1F";

//	$inp = decbin(hexdec($v_hex));
	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result="plc_single";

		if($lv==3 && $r_state==1)
		{
			$result="plc_son";
		}
		else if($r_state==2)
		{
		}
//		else
//		{
//			$result="plc_no";
//		}
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{

			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{

			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

/*			for ($i=0; $i < (16-$total); $i++)
			{
				$inp = '0'.$inp;
			}
			*/
			//$inp=sprintf("%016d",$inp);

		$total = strlen($inp);

		if($total == 16)
		{
			if($inp=="0001111100011111")
			{
				$result="plc_comm_err";
			}
			else if($lv==1)
			{
				if($inp[10] == 1) // 제어기 고장
				{
					$result="plc_c_error";
				}
				else if ($inp[0] == 1) // 쌍등
				{
					$result="plc_twins";
				}
				else if ($inp[0] == 0)
				{
					$result="plc_single";
				}
			}
			else if($lv==2 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[4] == 1) // 고장
					{
						$result="plc_error1";
					}
					else if ($inp[3] == 1) // 점등
					{
						$result="plc_on1";
					}
				}
			}
			else if($lv==3 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[4] == 1) // 고장
					{
						$result="plc_serror";
					}
/*					else if ($inp[6] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[7] == 1) // 등주 누전 경보
					{
						$result="plc_leakage_alert2";
					}*/
					else if ($inp[3] == 1) // 점등
					{
						$result="plc_son";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[12] == 1) // 고장
					{
						$result="plc_error2";
					}
		/*			else if ($inp[14] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[15] == 1) // 등주 누전 경보 
					{
						$result="plc_leakage_alert2";
					}*/
					else if ($inp[11] == 1) // 점등
					{
						$result="plc_on2";
					}
				}

			}
			else if($lv==4 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[5] == 1) // 안전기 고장
					{
						$result="plc_s_error1";
					}
				}

			}
			else if($lv==5 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[5] == 1) // 안전기 고장
					{
						$result="plc_s_error2";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[13] == 1) // 안전기 고장
					{
						$result="plc_s_error2";
					}
				}
			}
			else if($lv==6 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[6] == 1) // 등주 누전
					{
						$result="plc_leakage1";
					}
					else if ($inp[7] == 1) // 등주 누전 경보
					{
						$result="plc_leakage_alert1";
					}
				}

			}
			else if($lv==7 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[6] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[7] == 1) // 등주 누전 경보
					{
						$result="plc_leakage_alert2";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[14] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[15] == 1) // 등주 누전 경보 
					{
						$result="plc_leakage_alert2";
					}
				}
			}
			else if($lv==8 && $inp[10] == 0)
			{
				if ($inp[8] == 1) // 선로 누전
				{
					$result="plc_l_leakage";
				}
				else if ($inp[9] == 1) // 선로 누전 경보
				{
					$result="plc_l_leakage_alert";
				}
			}
			else if($lv==9 && $inp[10] == 0)
			{
				if ($inp[2] == 1) // 수동
				{
				//	$result="plc_manual";
				}
			}
		}
/*				if ($inp[$i] == 1)
				{
					if($i < 8)
					{
						$result[] = $data[$i];
					}
					else if ($i >= 8 && $inp[0]=='1')
					{
						$result[] = $data[$i];
					}
				}
				else if($inp[$i] == 0)
				{
					if($i == 3)
					{
						$result[] = "램프1 OFF";
					}
					else if($i == 11 && $inp[0]=='1')
					{
						$result[] = "램프2 OFF";
					}
				}
			}
		}*/
	}
	return $result;
}

function mz_get_plc_status_gn3 ($lv,$v_hex,$r_state)
{
	$result="empty";
//	$v_hex="1F1F";

//	$inp = decbin(hexdec($v_hex));
	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result="plc_single";

		if($lv==3 && $r_state==1)
		{
			$result="plc_son";
		}
		else if($r_state==2)
		{
		}
		//else
		//{
		//	$result="plc_no";
		//}
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{

			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{

			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

/*			for ($i=0; $i < (16-$total); $i++)
			{
				$inp = '0'.$inp;
			}
			*/
			//$inp=sprintf("%016d",$inp);

		$total = strlen($inp);

		if($total == 16)
		{
			if($inp=="0001111100011111")
			{
				$result="plc_comm_err";
			}
			else if($lv==1)
			{
				if($inp[10] == 1) // 제어기 고장
				{
					$result="plc_c_error";
				}
				else if ($inp[0] == 1) // 쌍등
				{
					$result="plc_twins";
				}
				else if ($inp[0] == 0)
				{
					$result="plc_single";
				}
			}
			else if($lv==2 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[3] == 1) // 점등
					{
						$result="plc_on1";
					}
				}
			}
			else if($lv==3 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[3] == 1) // 점등
					{
						$result="plc_son";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[11] == 1) // 점등
					{
						$result="plc_on2";
					}
				}

			}
			else if($lv==4 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[5] == 1) // 안전기 고장
					{
						$result="plc_s_error1";
					}
				}

			}
			else if($lv==5 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[5] == 1) // 안전기 고장
					{
						$result="plc_s_error2";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[13] == 1) // 안전기 고장
					{
						$result="plc_s_error2";
					}
				}
			}
			else if($lv==6 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[6] == 1) // 등주 누전
					{
						$result="plc_leakage1";
					}
					else if ($inp[7] == 1) // 등주 누전 경보
					{
						$result="plc_leakage_alert1";
					}
				}

			}
			else if($lv==7 && $inp[10] == 0)
			{
				if ($inp[0] == 0) // 외등
				{
					if ($inp[6] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[7] == 1) // 등주 누전 경보
					{
						$result="plc_leakage_alert2";
					}
				}
				else if ($inp[0] == 1) // 쌍등
				{
					if ($inp[14] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[15] == 1) // 등주 누전 경보 
					{
						$result="plc_leakage_alert2";
					}
				}
			}
			else if($lv==8 && $inp[10] == 0)
			{
			}
			else if($lv==9 && $inp[10] == 0)
			{
				if ($inp[2] == 1) // 수동
				{
					//$result="plc_manual";
				}
			}
		}
	}
	return $result;
}

function mz_get_plc_status_gj ($lv,$v_hex)
{
	$result="empty";
//	$v_hex="1F1F";

//	$inp = decbin(hexdec($v_hex));
	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result="plc_no";
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{

			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{

			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

/*			for ($i=0; $i < (16-$total); $i++)
			{
				$inp = '0'.$inp;
			}
			*/
			//$inp=sprintf("%016d",$inp);

		$total = strlen($inp);

		if($total == 16)
		{
			if($inp=="0001111100011111")
			{
				$result="plc_comm_err";
			}
			else if($lv==1)
			{
				if($inp[10] == 1) // 제어기 고장
				{
					$result="plc_c_error";
				}
				else if ($inp[0] == 1) // 쌍등
				{
					$result="plc_twins";
				}
				else if ($inp[0] == 0)
				{
					$result="plc_single_gj";
				}
			}
			else if($lv==2 && $inp[10] == 0)
			{
//				if ($inp[0] == 1) // 쌍등
//				{
					if ($inp[4] == 1) // 고장
					{
						$result="plc_error1";
					}
					else if ($inp[3] == 1) // 점등
					{
						$result="plc_on1";
					}
//				}
			}
			else if($lv==3 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[12] == 1) // 고장
					{
						$result="plc_error2";
					}
		/*			else if ($inp[14] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[15] == 1) // 등주 누전 경보 
					{
						$result="plc_leakage_alert2";
					}*/
					else if ($inp[11] == 1) // 점등
					{
						$result="plc_on2";
					}
				}

			}
			else if($lv==4 && $inp[10] == 0)
			{
				if ($inp[5] == 1) // 안전기 고장
				{
						$result="plc_s_error1";
				}
			}
			else if($lv==5 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[13] == 1) // 안전기 고장
					{
						$result="plc_s_error2";
					}
				}
			}
			else if($lv==6 && $inp[10] == 0)
			{
				if ($inp[6] == 1) // 등주 누전
				{
					$result="plc_leakage1";
				}
				else if ($inp[7] == 1) // 등주 누전 경보
				{
					$result="plc_leakage_alert1";
				}
			}
			else if($lv==7 && $inp[10] == 0)
			{
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[14] == 1) // 등주 누전
					{
						$result="plc_leakage2";
					}
					else if ($inp[15] == 1) // 등주 누전 경보 
					{
						$result="plc_leakage_alert2";
					}
				}
			}
			else if($lv==8 && $inp[10] == 0)
			{
				if ($inp[8] == 1) // 선로 누전
				{
					$result="plc_l_leakage";
				}
				else if ($inp[9] == 1) // 선로 누전 경보
				{
					$result="plc_l_leakage_alert";
				}
			}
			else if($lv==9 && $inp[10] == 0)
			{
				if ($inp[2] == 1) // 수동
				{
					$result="plc_manual";
				}
			}
		}
	}
	return $result;
}

function mz_get_plc_err_status ($v_hex)
{
	$result=2;

	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result=1;
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{
			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{
			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

		$total = strlen($inp);
		
		if($inp=="0001111100011111")
		{
			$result=1;
		}
		else if($total == 16)
		{
			if( $inp[0] == 1)
			{
				if( $inp[4] == 1 || $inp[5] == 1 || $inp[6] == 1 || $inp[7] == 1 || $inp[8] == 1 || $inp[9] == 1 || $inp[10] == 1 || $inp[12] == 1 || 	$inp[13] == 1 || $inp[14] == 1 || $inp[15] == 1 )
				{
					$result=3;
				}
			}
			else
			{
				if( $inp[4] == 1 || $inp[5] == 1 || $inp[6] == 1 || $inp[7] == 1 || $inp[8] == 1 || $inp[9] == 1 || $inp[10] == 1)
				{
					$result=3;
				}
			}
		}
	}

	return $result;
}

function mz_is_plc_bre($v_hex)
{
	$result=false;

	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result=false;
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{
			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{
			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

		$total = strlen($inp);
		
		if($inp=="0001111100011111")
		{
			$result=true;
		}
	}

	return $result;
}

//new
function mz_get_plc_cnt_err ($v_hex)
{
 $tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
 $tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
 $inp=$tmp_inp1.$tmp_inp2;

 for($i=0; $i < 5; $i++)
 {
  $result[$i]=0;
 }

// if($inp==decbin(65535))
 if($inp=="1111111111111111")
 {
//  $result=1;
 }
 else if($inp)
 {
  $total1 = strlen($tmp_inp1);
  $total2 = strlen($tmp_inp2);

  if($total1 < 8)
  {
   for ($i=0; $i < (8-$total1); $i++)
   {
    $tmp_inp1 = '0'.$tmp_inp1;
   }
  }

  if($total2 < 8)
  {
   for ($i=0; $i < (8-$total2); $i++)
   {
    $tmp_inp2 = '0'.$tmp_inp2;
   }
  }

  $inp=$tmp_inp1.$tmp_inp2;

  $total = strlen($inp);
  
  if($inp=="0001111100011111")
  {
   $result[5]+=1;
  }
  else if($total == 16)
  {
   if( $inp[0] == 1) //dl
   {
    if($inp[4] == 1) // 램프이상1
    {
		 $result[0]+=1;
    }
	if($inp[12] == 1) //램프이상2
    {
	     $result[0]+=1;
    }
    
    if($inp[5] == 1) // 안정기1
    {
         $result[1]+=1;
    }
	else if($inp[13] == 1) // 안정기2
    {
         $result[1]+=1;
    }

    if($inp[10] == 1) // 제어기
    {
         $result[2]=1;
    }

    if($inp[8] == 1 || $inp[9] == 1) // 선로누전 / 경보
    {
         $result[3]=1;
    }

	if($inp[6] == 1 || $inp[7] == 1) // 램프1등주누전/경보
    {
         $result[4]+=1;
    }
    if($inp[14] == 1 || $inp[15] == 1) // 램프2등주누전/경보
    {
         $result[4]+=1;
    }

   }
   else
   {
    if($inp[4] == 1) // 램프
    {
         $result[0]=1;
    }
    
    if($inp[5] == 1) // 안정기
    {
         $result[1]=1;
    }

    if($inp[10] == 1) // 제어기
    {
         $result[2]=1;
    }

    if($inp[8] == 1 || $inp[9] == 1) // 선로누전 / 경보
    {
         $result[3]=1;
    }

	if($inp[6] == 1 || $inp[7] == 1) // 등주누전
    {
         $result[4]=1;
    }
   }
  }
 }


 return $result;
}

function mz_get_plc_lea_status ($v_hex)
{
	$result=2;

	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result=0;
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{
			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{
			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

		$total = strlen($inp);
		
		if($inp=="0001111100011111")
		{
			$result=1;
		}
		else if($total == 16)
		{
			if( $inp[0] == 1)
			{
				if( $inp[6] == 1 || $inp[14] == 1 || $inp[8] == 1 )
				{
					$result=1;
				}
				else if( $inp[7] == 1 || $inp[15] == 1 || $inp[9] == 1 )
				{
					$result=2;
				}
				else
				{
					$result=0;
				}
			}
			else
			{
				if( $inp[6] == 1 || $inp[8] == 1 )
				{
					$result=1;
				}
				else if( $inp[7] == 1 || $inp[9] == 1 )
				{
					$result=2;
				}
				else
				{
					$result=0;
				}
			}
		}
	}

	return $result;
}

function mz_get_plc_err_status_gn ($v_hex,$r_state)
{
	$result=2;

	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		//$result=1;
		$result=2;
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{
			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{
			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

		$total = strlen($inp);
		
		if($inp=="0001111100011111")
		{
			$result=1;
		}
		else if($total == 16)
		{
			if( $inp[0] == 1)
			{
				if( $inp[4] == 1 || $inp[5] == 1 || $inp[6] == 1 || $inp[7] == 1 || $inp[8] == 1 || $inp[9] == 1 || $inp[10] == 1 || $inp[12] == 1 || 	$inp[13] == 1 || $inp[14] == 1 || $inp[15] == 1 )
				{
					$result=3;
				}
			}
			else
			{
				if( $inp[4] == 1 || $inp[5] == 1 || $inp[6] == 1 || $inp[7] == 1 || $inp[8] == 1 || $inp[9] == 1 || $inp[10] == 1)
				{
					$result=3;
				}
			}
		}
	}

	return $result;
}

function mz_get_plc_err_status_for_errcnt ($v_hex)
{
	$result=0;

	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result=1;
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{
			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{
			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

		$total = strlen($inp);
		
		if($inp=="0001111100011111")
		{
			$result=1;
		}
		else if($total == 16)
		{
			if( $inp[0] == 1)
			{
				if( $inp[4] == 1 || $inp[5] == 1 || $inp[6] == 1 || $inp[10] == 1 || $inp[12] == 1 || 	$inp[13] == 1 || $inp[14] == 1 )
				{
					$result=1;
				}
			}
			else
			{
				if( $inp[4] == 1 || $inp[5] == 1 || $inp[6] == 1 || $inp[10] == 1 )
				{
					$result=1;
				}
			}
		}
	}

	return $result;
}

function mz_get_plc_err_status_for_errcnt2 ($v_hex)
{
	$result=0;

	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result=1;
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{
			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{
			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

		$total = strlen($inp);
		
		if($inp=="0001111100011111")
		{
			$result=0;
		}
		else if($total == 16)
		{
			if( $inp[0] == 1)
			{
				if( $inp[4] == 1 || $inp[5] == 1 || $inp[6] == 1 || $inp[10] == 1 || $inp[12] == 1 || 	$inp[13] == 1 || $inp[14] == 1 )
				{
					$result=1;
				}
			}
			else
			{
				if( $inp[4] == 1 || $inp[5] == 1 || $inp[6] == 1 || $inp[10] == 1 )
				{
					$result=1;
				}
			}
		}
	}

	return $result;
}

function mz_get_plc_running_status ($v_hex)
{
	$result=2;

	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result=2;
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{
			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{
			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

		$total = strlen($inp);

		if($total == 16) // 2 bytes
		{
			if($inp=="0001111100011111")
			{
				$result=2;
			}
			/*else if($inp[1] == 1)
			{
				if($inp[0] == 1)
				{
					if($inp[3] == 1 || $inp[11] == 1)
					{
						$result=13;	
					}
					else
					{
						$result=14;	
					}
				}
				else
				{
					if($inp[3] == 1)
					{
						$result=13;	
					}
					else
					{
						$result=14;	
					}
				}
				//$result=3;
			}*/
			else if($inp[0] == 1 && ($inp[3] == 1 || $inp[11] == 1))
			{
				$result=1;
			}
			else if($inp[0] == 0 && $inp[3] == 1)
			{
				$result=1;
			}
		}
	}

	return $result;
}

function mz_get_plc_running_status_gn ($v_hex,$r_state)
{
	$result=2;

	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
//		$result=2;
		$result=$r_state;
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{
			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{
			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

		$total = strlen($inp);

		if($total == 16) // 2 bytes
		{
			if($inp=="0001111100011111")
			{
				$result=2;
			}
			else if($inp[1] == 1)
			{
				if($inp[0] == 1)
				{
					if($inp[3] == 1 || $inp[11] == 1)
					{
						$result=13;	
					}
					else
					{
						$result=14;	
					}
				}
				else
				{
					if($inp[3] == 1)
					{
						$result=13;	
					}
					else
					{
						$result=14;	
					}
				}
				//$result=3;
			}
			else if($inp[0] == 1 && ($inp[3] == 1 || $inp[11] == 1))
			{
				$result=1;
			}
			else if($inp[0] == 0 && $inp[3] == 1)
			{
				$result=1;
			}
		}
	}

	return $result;
}

function mz_get_lea_status ($v_hex)
{
	$result=0;

	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;


	if($inp=="1111111111111111") // 설치 안됨
	{
		$result=0;
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{

			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{

			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

		$total = strlen($inp);

		if($total == 16)
		{
			if($inp=="0001111100011111")
			{
				$result=0;
			}
			else if($inp[10] == 0)
			{
				if ($inp[8] == 1) // 선로 누전				
				{
					$result='9';
				}
/*				else if ($inp[9] == 1) // 선로 누전 경보
				{

				}*/
			}
		}
	}

	return $result;
}


function mz_get_lea_bre_status ($v_hex)
{
	$result=0;

	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;


	if($inp=="1111111111111111") // 설치 안됨
	{
		$result='8';
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{

			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{

			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

		$total = strlen($inp);

		if($total == 16)
		{
			if($inp=="0001111100011111")
			{
				$result='2';
			}
			else if($inp[10] == 0)
			{

				if ($inp[8] == 1) // 선로 누전
				{
					$result='9';
				}
/*				else if ($inp[9] == 1) // 선로 누전 경보
				{

				}*/
			}
		}
	}

	return $result;
}

function mz_get_plc_status_txt ($v_hex)
{
	$result=array();
	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

	$result[0]="설치됨";

	for($i=1; $i < 10 ; $i++)
	{
		$result[$i]="&nbsp;";	
	}

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result[0]="<b><font color='red'>설치안됨</font></b>";
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{

			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{

			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

/*			for ($i=0; $i < (16-$total); $i++)
			{
				$inp = '0'.$inp;
			}
			*/
			//$inp=sprintf("%016d",$inp);

		$total = strlen($inp);

		if($total == 16)
		{
			if($inp=="0001111100011111")
			{
				$result[1]="<b><font color='red'>통신두절</font></b>";
			}
			else if($inp[10] == 1) // 제어기 고장
			{
				$result[1]="정상";
				$result[2]="<b><font color='red'>고장</font></b>";
			}
			else
			{
				for($i=1; $i < 10 ; $i++)
				{
					$result[$i]="정상";	
				}

				if ($inp[8] == 1) // 선로 누전				
				{
					$result[3]="<b><font color='red'>발생</font></b>";
				}
				else if ($inp[9] == 1) // 선로 누전 경보
				{
					$result[3]="<b><font color='red'>경보</font></b>";
				}

				if ($inp[4] == 1) // 램프1고장
				{
					$result[4]="<b><font color='red'>고장</font></b>";
				}
				if ($inp[6] == 1) // 등주 누전
				{
					$result[5]="<b><font color='red'>발생</font></b>";
				}
				else if ($inp[7] == 1) // 등주 누전 경보
				{
					$result[5]="<b><font color='red'>경보</font></b>";
				}
				if ($inp[5] == 1) // 안전기 고장
				{
					$result[6]="<b><font color='red'>고장</font></b>";
				}
				
				if ($inp[0] == 1) // 쌍등
				{
					if ($inp[12] == 1) // 램프2고장
					{
						$result[7]="<b><font color='red'>고장</font></b>";
					}
					if ($inp[14] == 1) // 등주 누전
					{
						$result[8]="<b><font color='red'>발생</font></b>";
					}
					else if ($inp[15] == 1) // 등주 누전 경보
					{
						$result[8]="<b><font color='red'>경보</font></b>";
					}
					if ($inp[13] == 1) // 안전기 고장
					{
						$result[9]="<b><font color='red'>고장</font></b>";
					}
				}
				else
				{
					for($i=7; $i < 10; $i++)
					{
						$result[$i]="&nbsp;"; // 고장이 아닌 경우
					}
				}
			}
		}

	}

	return $result;
}

function mz_update_plc_err_status ($v_hex)
{
	$result="x";

	$tmp_inp1=decbin(hexdec(substr($v_hex,0,2)));
	$tmp_inp2=decbin(hexdec(substr($v_hex,2,2)));
	$inp=$tmp_inp1.$tmp_inp2;

//	if($inp==decbin(65535))
	if($inp=="1111111111111111")
	{
		$result="x";
	}
	else if($inp)
	{
		$total1 = strlen($tmp_inp1);
		$total2 = strlen($tmp_inp2);

		if($total1 < 8)
		{
			for ($i=0; $i < (8-$total1); $i++)
			{
				$tmp_inp1 = '0'.$tmp_inp1;
			}
		}

		if($total2 < 8)
		{
			for ($i=0; $i < (8-$total2); $i++)
			{
				$tmp_inp2 = '0'.$tmp_inp2;
			}
		}

		$inp=$tmp_inp1.$tmp_inp2;

		$total = strlen($inp);
		
		if($inp=="0001111100011111")
		{
			$result="x";
		}
		else if($total == 16)
		{
			if( $inp[0] == 1)
			{
				$inp[6] = 0;
				$inp[7] = 0;
				$inp[8] = 0;
				$inp[9] = 0;
				$inp[14] = 0;
				$inp[15] = 0;
			}
			else
			{
				$inp[6] = 0;
				$inp[7] = 0;
				$inp[8] = 0;
				$inp[9] = 0;
			}

			$tmp1=sprintf("%02X",bindec(substr($inp,0,8)));
			$tmp2=sprintf("%02X",bindec(substr($inp,8,8)));

			$result=$tmp1.$tmp2;
		}
		else
		{
			$result="x";
		}
	}

	return $result;
}

function _key_find_del($array,$data)
{ 
	foreach ($array as $key => $val)
	{ 
	   unset($data[$key]);
	} 

	return 	$data;
}

function _user_level($level,$type)
{
	$R_LvQuary = "";

	/*switch ($level)
	{
		case 0 :
			$R_LvQuary = "";
		break;
		case 1 :
			$R_LvQuary = "and level_type=".$level;
		break;
		case 2 :
			$R_LvQuary = "";
		break;
		case 3 :
			$R_LvQuary = "";
		break;
		case 4 :
			$R_LvQuary = "";
		break;
	}*/
if($type==1)
{
	if($level==0)
	{
		$R_LvQuary = "";
	}
	else if($level!=0)
	{
		$R_LvQuary = "and level_type=".$level;
	}
}
else if($type==2)
{
	if($level==0)
	{
		$R_LvQuary = "";
	}
	else if($level!=0)
	{
		$R_LvQuary = "where level_type=".$level;
	}
}
else if($type==3)
{
	if($level==0)
	{
		$R_LvQuary = "";
	}
	else if($level!=0)
	{
		$R_LvQuary = "and d.level_type=".$level;
	}
}
		return $R_LvQuary;

}

//=================================================================================================================================
function iconvCHA($data, $enc)
{
	if($enc == "euc")
	{
		$data = iconv("UTF-8", "EUC-KR", $data);
	}
	if($enc == "utf")
	{
		$data = iconv("EUC-KR", "UTF-8", $data);
	}
	return $data;
}

function phone_substr($num)
{
	$data = "";
	
	$p1 = substr($num,0,3);
	$p2 = substr($num,3,4);
	$p3 = substr($num,7,4);
	$data = $p1."-".$p2."-".$p3;
	
	return $data;
}


function Timegap_cal($time,$gap)
{
	$h=substr($time,0,2);
	$m=substr($time,2,2);
	$hm_sum =  $h * 60 + $m + $gap;
	$cal_h = (int)($hm_sum/60);
	$cal_m = $hm_sum % 60;
	
	if(strlen($cal_h) < 2) $cal_h = "0".$cal_h;
	if(strlen($cal_m) < 2) $cal_m = "0".$cal_m;
	return $cal_h.":".$cal_m;
}
//32 


function DateTable()
{
	$tableName = "minwon_".date("Y");
	return $tableName;
}

function OperType($type)
{
	$temp = "";
	switch($type)
	{
		case UI_COMM_TURN_ON_ALL: $temp = "1"; break;
		case UI_COMM_TURN_OFF_ALL: $temp = "2"; break;
	}
	return $temp;
}

function db_sub($host,$database){
	
    $user = "root";
    $pass = "windjbh";
    $connect = @mysql_connect($host, $user, $pass) or die("ERROR!");
    mysql_query(" SET NAMES 'utf8' ");
	mysql_select_db($database,$connect);
    return $connect;
}


function SendSMS($ip, $to, $content)
{
    $sock=socket_create(AF_INET,SOCK_DGRAM,SOL_UDP);
    $remote_host=$ip;
    if(socket_connect($sock,$remote_host,20005))
    {
        $sms_msg = $content;
        
        $to_pn = ltrim($to,'0');
        //$from_pn = ltrim($from,'0');
    
        $sms_body = pack("CCCCCNCCNCCCCC",0x10,0x01,0x00,//ver
                    0x20,0x04,$to_pn,//to_no
                    0x27,0x04,0xffffffff,//0xffffffff, //$from_pn,//from_no
					0x30,0x01,0x01,  //마지막 index 모뎀 넘버
                    0x25,strlen($sms_msg));//smg
        
        $sms_body .= $sms_msg;
    
        $sms_header = pack("CCCCn",0x0A,0x04,0x21,0x01,strlen($sms_body));
    
        $sms_data = $sms_header.$sms_body;
    
        $sock_data = socket_write($sock, $sms_data, strlen($sms_data)); //Send data
       
        return true;
    }
    else return false;
    
    socket_close($sock);
}


function SendOperDelay($svr_addr,$svr_port,$PHPSESSID, $messageID , $arr, $end_Data ){
	
	$sleep_time = 15; // 대기시간
	$send_cnt = 5; // 한번에 실행개수
	$rsult_return = 0;
	
	$s_data_str =  "";
	$dids = array_chunk($arr, $send_cnt, false);
	
	$i = 0;
	$c = 0;
	
	foreach ($dids as $inner_array) {
		//$s_data_s = "dcount=".count($dids[$i])."&";
		$i++;
		while (list($key, $value) = each($inner_array)) {   
			$s_data_s .= "did".($key+1)."=".$value."&"; 
			$c++;
		}
		//echo $s_data_s.$end_Data."<br>";
	
	
	$s_data_s .= $end_Data;
	
	$uiSock = new ilcs_Client_Socket($svr_addr,$svr_port);
	$out = To_Server($PHPSESSID,'00', $messageID , $s_data_s, $uiSock);
	
	outputProgress($c, count($arr));
	if($c !=  count($arr)) sleep($sleep_time);
	
		if ($out) {
			if($c == count($did)){
				return $rsult_return = 0;
			}
		}else
		{
			$rsult_return++;
		}
	}
	
	return $rsult_return;
}

function outputProgress($current, $total) {
	$avg = ($current * 100) / $total;
	
    echo "
	<style>
	body, table, tr, td, select,div,form
{font-family:verdana; font-size:9pt;  color:#333333; line-height:150%}
.size_07{font-family:verdana; font-size:7pt;  color:#000000; line-height:150%}
	</style>
		<div style='background:#FFF; width:300px; position:absolute; top:40%; bottom:0; left:0; right:0;margin: auto;' align='center'>
				명령 전송중 입니다. 잠시만 기다려 주세요 (".$current."/".$total.")<br>
				<div style='position:relative; float:left; width:100%; margin-top:20px; border:1px solid gray' align='left'>
					<div style='position:relative; float:left; background-color:#CCC; width:".$avg."%;'>&nbsp;</div>
				</div>
	</div>";
			
    myFlush();
	sleep(1);
}

/**
 * Flush output buffer
 */
function myFlush() {
    echo(str_repeat(' ', 4096));
    if (@ob_get_contents()) {
        @ob_end_flush();
    }
    flush();
}


function dateParsing($dateTime)
{
	$date_y = substr($dateTime,0,4);
    $date_m = substr($dateTime,4,2);
    $date_d = substr($dateTime,6,2);
    
    $time_h = substr($dateTime,9,2);
    $time_m = substr($dateTime,11,2);
    $time_s = substr($dateTime,13,2);
    return $date_y."-".$date_m."-".$date_d." ".$time_h.":".$time_m.":".$time_s;
}


function _count($count)
{
	$cnt="";
	for($i=0; $i < $count; $i++)	
	{
		$cnt.=sprintf('<option value="%s">%s</option>',$i,$i);
		
		/*
		if($i==0)
		{
			$cnt.=sprintf('<option value="%s" selected>%s</option>',$i,$i);
		}
		else
		{
			$cnt.=sprintf('<option value="%s">%s</option>',$i,$i);
		}*/
	}
	return $cnt;
}
function _count2d($count)
{
	$cnt="";
	for($i=0; $i < $count; $i++)	
	{
		$cnt.=sprintf('<option value="%02d">%s</option>',$i,$i);
		
		/*
		if($i==0)
		{
			$cnt.=sprintf('<option value="%s" selected>%s</option>',$i,$i);
		}
		else
		{
			$cnt.=sprintf('<option value="%s">%s</option>',$i,$i);
		}*/
	}
	return $cnt;
}


function messageCut($data1, $data2, $type){
	$result = "";
	if($type == "date")
	{
		if($data1 == "null" || $data2 == "null")
		{
			$result = "null";
		}
		else
		{
			$returnDate = "";
			$returnDate = str_replace("-","",$data1);
			$result .= substr($returnDate,4,4);
			
			$returnDate = str_replace("-","",$data2);
			$result .= substr($returnDate,4,4);
		}
	}
	
	if($type == "time")
	{
		
		if(strlen($data1.$data2) > 15){
			$result = "null";
		}
		else if(substr($data1,0,2) == "xx")
		{
			$result = "XX000000";
		}else
		{
			$result = $data1.$data2;
		}
	}
	return $result;
}

function time_parsing($data, $type){
	$result = "";
	
	if($type == "time")
	{
		$on_h = substr($data, 0, 2);
		$on_m = substr($data, 2, 2);
		$off_h = substr($data, 4, 2);
		$off_m = substr($data, 6, 2);
		
		if($on_h == "XX") $result = "사용안함";
		else $result = $on_h.":".$on_m." ~ ".$off_h.":".$off_m;
	}
	
	if($type == "date")
	{
		$on_m = substr($data, 0, 2);
		$on_d = substr($data, 2, 2);
		$off_m = substr($data, 4, 2);
		$off_d = substr($data, 6, 2);
		
		$result = $on_m."/".$on_d." ~ ".$off_m."/".$off_d;
	}
	
	return $result;
}

function strToHex($string)
{
	$hex = '';
	for ($i=0; $i<strlen($string); $i++)
	{				        
		$ord = ord($string[$i]);
		$hexCode = dechex($ord);
		$hex .= substr('0'.$hexCode, -2);

		if(($i+1) < strlen($string)) $hex .=",";
	}
	 return strToUpper($hex);
}


function encrypt_seed($bszIV, $bszUser_key, $str, $mode) 
{
	// $mode  0:base64 1: bin 2:asciihex
	// $result[0] : len $result[1] : str_enc

	$result = array();

	$bszIV = strToHex($bszIV);
	$bszUser_key = strToHex($bszUser_key);
	$str = strToHex($str);

	$planBytes = split(",",$str);
	$keyBytes = split(",",$bszUser_key);
	$IVBytes = split(",",$bszIV);
	
	for($i = 0; $i < 16; $i++)
	{
		$keyBytes[$i] = hexdec($keyBytes[$i]);
		$IVBytes[$i] = hexdec($IVBytes[$i]);
	}
	for ($i = 0; $i < count($planBytes); $i++) 
	{
		$planBytes[$i] = hexdec($planBytes[$i]);
	}

	if (count($planBytes) == 0) 
	{
		return $str;
	}
	$ret = null;
	$bszChiperText = null;
	$pdwRoundKey = array_pad(array(),32,0);

	$bszChiperText = KISA_SEED_CBC::SEED_CBC_Encrypt($keyBytes, $IVBytes, $planBytes, 0, count($planBytes));

	$r = count($bszChiperText);

	$ret="";

	for($i=0;$i< $r;$i++) 
	{
		switch($mode)
		{
			case 0:
			case 1:
				$ret .=  sprintf("%c", $bszChiperText[$i]);
				break;
			case 2:
				$ret .=  sprintf("%02X", $bszChiperText[$i]);
				break;
		}

	}

	$result[0] = $r;

	switch($mode)
	{
		case 0:
			$result[1] = base64_encode($ret);
			break;
		case 1:
		case 2:
			$result[1] = $ret;
			break;
	}

	return $result;

}

function decrypt_seed($bszIV, $bszUser_key, $str, $mode) 
{
	// $mode  0:base64 1: bin 2:asciihex

	$bszIV = strToHex($bszIV);
	$bszUser_key = strToHex($bszUser_key);

	$planBytes = array();
	$planBytresMessage = "";

	if($mode == 0)			$str = strToHex(base64_decode($str));
	else if($mode == 1)		$str = strToHex($str);
	else if($mode == 2)
	{
		for($i = 0, $j = 0 ; $i < strlen($str) ; $i += 2, $j++)
		{
			$planBytes[$j] = substr($str,$i,2);
		}
	}


	if($mode < 2)		$planBytes = split(",",$str);

	$keyBytes = split(",",$bszUser_key);
	$IVBytes = split(",",$bszIV);

	
	for($i = 0; $i < 16; $i++)
	{
		$keyBytes[$i] = hexdec($keyBytes[$i]);
		$IVBytes[$i] = hexdec($IVBytes[$i]);
	}

	for ($i = 0; $i < count($planBytes); $i++) {
		$planBytes[$i] = hexdec($planBytes[$i]);
	}

	if (count($planBytes) == 0) {
		return $str;
	}

	$pdwRoundKey = array_pad(array(),32,0);

	$bszPlainText = null;

	$bszPlainText = KISA_SEED_CBC::SEED_CBC_Decrypt($keyBytes, $IVBytes, $planBytes, 0, count($planBytes));

	
	for($i=0;$i< sizeof($bszPlainText);$i++) 
	{
		//$planBytresMessage .=  sprintf("%02X", $bszPlainText[$i]).",";
		$planBytresMessage .=  sprintf("%c", $bszPlainText[$i]);
	}

	return $planBytresMessage;
}


function change_date_form($date){	//년월일 표시형식에서 y-m-d 표시형식으로 변경

	$date_int_year = substr($date,0,4);
	$date_int_month = substr($date,7,2);
	$date_int_day = substr($date,12,2);
	
	
	$date_int =  date('Y-m-d',mktime(0,0,0,$date_int_month,$date_int_day,$date_int_year));
	
	return $date_int;	
}
function date_sum($date1,$date2){	// 연차기간 체크
	$date_int1 = change_date_form($date1);
	
	$date_int2 = change_date_form($date2);
	
	$total = (strtotime($date_int2) - strtotime($date_int1)) / 86400;
	$total = $total+1;
	return $total;
}
function chage_date_form_slush($date){	// 날짜표시형식 변경


		
		$date_y = substr($date,0,4);
		$date_m = substr($date,7,2);
		$date_d = substr($date,12,2);
		
		$date =  date('Y/m/d',mktime(0,0,0,$date_m,$date_d,$date_y));
		return $date;

}
function chk_week_sum($date1,$date2){	//날짜중에 토요일, 일요일이 섞여있을경우 카운트 2만큼 감소
	
	$all_count = date_sum($date1,$date2);
	
	$sl_date1 = chage_date_form_slush($date1);
	$sl_date2= chage_date_form_slush($date2);
	
	$cnt_date1 = date("N",strtotime($sl_date1));
	$cnt_date2 = date("N",strtotime($sl_date2));
	
	
	
	if($cnt_date2 < $cnt_date1 || $all_count > 7){
		$cnt = 1;
		while($cnt < $all_count){ ;
			$all_count = $all_count-2;
			$cnt = $cnt+7;
		}
	}
	return $all_count;
}

function length_chk($data){
	if(strlen($data)<1){
		_messagebox_nomal("데이터가 없습니다",1);
	}
}

function chk_lv_option($lv){
	switch($lv){
		case 0: 
			return "Master";
			break;
		case 1:
			return "Manager";
			break;
		case 2:
			return "User";
			break;	
	}

}
?>