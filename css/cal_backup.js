// JavaScript Document

var year_in_count = 0;
var half_in_count = 0;
var public_in_count = 0;
var year_count = 0;
var half_count = 0;
var public_count = 0;



function date_in(box_date, view_date, last_day){
	
	var va_type = document.getElementById('va_type').value;
	var year_start = document.getElementById('year_va_start');
	var year_end = document.getElementById('year_va_end');
	var half_start = document.getElementById('half_va_start');
	var half_end = document.getElementById('half_va_end');
	var public_start = document.getElementById('public_va_start');	
	var public_end = document.getElementById('public_va_end');
	var year_in_count = document.getElementById('year_in_count');
	var half_in_count = document.getElementById('half_in_count');
	var public_in_count = document.getElementById('public_in_count');
	var y_start_year = document.getElementById('y_start_year');
	var y_end_year = document.getElementById('y_end_year');
	var h_start_year = document.getElementById('h_start_year');
	var h_end_year = document.getElementById('h_end_year');
	var p_start_year = document.getElementById('p_start_year');
	var p_end_year = document.getElementById('p_end_year');
	var page_year = window.frames['vaca_cal'].document.getElementById('page_year').value;	
	var value_start_end = "";
	
//	var html_str = "<div class='cal_border' id='"+box_date+"'>"+va_type+"<br/>"+view_date+"</div>";
	if(va_type == ""){
		alert("연차 종류를 선택해주세요!!");
		return;
	}
	if(va_type == "year"){
		if(year_in_count.value == 0){	//연차 시작일 입력
			y_start_year.value = page_year
			 
			value_anther_va = chk_another_va(view_date, 'y_start');
			if(value_anther_va == 1){
				alert("다른연차와 기간이 겹칩니다.");
				return;	
			}
			value_start_end = chk_start_end(view_date,year_end.value,y_start_year.value,y_end_year.value);	// 연차 시작값과 종료값 비교 
			if(value_start_end == 0){
				year_start.value = view_date;
				box_coloring(box_date,last_day,year_in_count.value,va_type);
				year_in_count.value = 1 ;
				textbox_color_change('year_va_end');
			}else{
				alert("시작일은 종료일보다 클수 없습니다.");
				return;		
			}
			
		}else{							// 연차 종료일 입력
			y_end_year.value = page_year;
			value_anther_va = chk_another_va(view_date, 'y_end');
			if(value_anther_va == 1){
				alert("다른연차와 기간이 겹칩니다.");
				return;	
			}
			value_start_end = chk_start_end(year_start.value,view_date,y_start_year.value,y_end_year.value);
			if(value_start_end == 0){
				year_end.value = view_date;
				box_coloring(box_date,last_day,year_in_count.value,va_type);
				year_in_count.value = 0;
				textbox_color_change('year_va_start');
			}else{
				alert("시작일은 종료일보다 클수 없습니다.");
				return;			
			}
		}
	}else if(va_type == "half"){			//반차 시작일 입력
		if(half_in_count.value == 0){	
			h_start_year.value = page_year
			value_anther_va = chk_another_va(view_date, 'h_start');
			if(value_anther_va == 1){
				alert("다른연차와 기간이 겹칩니다.");
				return;	
			}
			value_start_end = chk_start_end(view_date,half_end.value,h_start_year.value,h_end_year.value);
			if(value_start_end == 0){
				half_start.value = view_date;;
				box_coloring(box_date,last_day,year_in_count.value,va_type);
				half_in_count.value = 1 ;
				textbox_color_change('half_va_end');
			}else{
				alert("시작일은 종료일보다 클수 없습니다.");
				return;	
			}
		}else{								//반차 종료일 입력
			h_end_year.value = page_year;
			value_anther_va = chk_another_va(view_date, 'h_end');
			if(value_anther_va == 1){
				alert("다른연차와 기간이 겹칩니다.");
				return;	
			}
			value_start_end = chk_start_end(half_start.value,view_date,h_start_year.value,h_end_year.value);
			if(value_start_end == 0){
				half_end.value = view_date;
				box_coloring(box_date,last_day,year_in_count.value,va_type);
				half_in_count.value = 0;
				textbox_color_change('half_va_start');
			}else{
				alert("시작일은 종료일보다 클수 없습니다.");
				return;	
			}
		}
	}else{
		if(public_in_count.value == 0){		//공가 시작일 입력
			p_start_year.value = page_year;
			value_anther_va = chk_another_va(view_date, 'p_start');
			if(value_anther_va == 1){
				alert("다른연차와 기간이 겹칩니다.");
				return;	
			}
			value_start_end = chk_start_end(view_date,public_end.value,p_start_year.value,p_end_year.value);
			if(value_start_end == 0){
				public_start.value = view_date;
				box_coloring(box_date,last_day,year_in_count.value,va_type);
				public_in_count.value = 1 ;
				textbox_color_change('public_va_start');
			}else{
				alert("시작일은 종료일보다 클수 없습니다.");
				return;	
			}
		}else{								//공가 종료일 입력
			p_end_year.value = page_year;
			value_anther_va = chk_another_va(view_date, 'p_end');
			if(value_anther_va == 1){
				alert("다른연차와 기간이 겹칩니다.");
				return;	
			}
			value_start_end = chk_start_end(public_start.value,view_date,p_start_year.value,p_end_year.value);
			if(value_start_end == 0){
				public_end.value = view_date;
				box_coloring(box_date,last_day,year_in_count.value,va_type);
				public_in_count.value = 0;
				textbox_color_change('public_va_end');
			}else{
				alert("시작일은 종료일보다 클수 없습니다.");
				return;	
			}
		}
	}
}	
function vac_change(type){
	if(type=='y'){
		if(year_count == 0){
			document.getElementById('va_type').value = "year";
			document.getElementById('year_period').style.display = "block";
			document.getElementById('type_year').style.backgroundColor = "#CCCCCC";
			textbox_color_change('year_va_start');
			year_count = 1;
		}else{
			document.getElementById('va_type').value = "";
			document.getElementById('year_period').style.display = "none";
			document.getElementById('type_year').style.backgroundColor = "#FFFFFF";
			year_count = 0;
			year_reset();
		}
	}else if(type == 'h'){
		if(half_count == 0){
			document.getElementById('va_type').value = "half";
			document.getElementById('half_period').style.display = "block";
			document.getElementById('type_half').style.backgroundColor = "#CCCCCC"; 
			textbox_color_change('half_va_start');
			half_count = 1;
		}else{
			document.getElementById('va_type').value = "";
			document.getElementById('half_period').style.display = "none";
			document.getElementById('type_half').style.backgroundColor = "#FFFFFF";
			half_count = 0;
			half_reset();
		}
	}else{
		if(public_count == 0){
			textbox_color_change('public_va_start');
			document.getElementById('va_type').value = "public";
			document.getElementById('public_period').style.display = "block";
			document.getElementById('type_public').style.backgroundColor = "#CCCCCC"; 
			public_count = 1;
		}else{
			document.getElementById('va_type').value = "";
			document.getElementById('public_period').style.display = "none";
			document.getElementById('type_public').style.backgroundColor = "#FFFFFF";
			public_count = 0;
			public_reset();
		}
	}
	
}
function chk_start_end(start_value,end_value,year_start,year_end){
	if(start_value <= end_value || start_value == "" || end_value == "" || (year_end > year_start)){
		return '0';
	}else{
		return '1';
	}
}	
function chk_another_va(chk_val, type){
	var year_start = document.getElementById('year_va_start').value;
	var year_end = document.getElementById('year_va_end').value;
	var half_start  = document.getElementById('half_va_start').value;
	var half_end = document.getElementById('half_va_end').value;
	var public_start = document.getElementById('public_va_start').value;
	var public_end = document.getElementById('public_va_end').value;
	
	var y_start_year = document.getElementById('y_start_year').value*1;
	var y_end_year = document.getElementById('y_end_year').value*1;
	var h_start_year  = document.getElementById('h_start_year').value*1;
	var h_end_year  = document.getElementById('h_end_year').value*1;
	var p_start_year = document.getElementById('p_start_year').value*1;
	var p_start_year = document.getElementById('p_end_year').value*1;
	
	var result_val = 1;
	
	

	
	
	chk_val = integer_string(chk_val)*1;
	year_start = integer_string(year_start)*1;
	year_end = integer_string(year_end)*1;
	half_start = integer_string(half_start)*1;
	half_end = integer_string(half_end)*1;
	public_start = integer_string(public_start)*1;
	public_end = integer_string(public_end)*1;

	if(y_start_year < y_end_year){
		year_end = year_end + 1231;	
	}
	if(h_start_year < h_end_year){
		half_end = year_end + 1231;		
	}
	if(p_start_year < p_end_year){
		public_end = year_end + 1231;		
	}


	if(type == 'y_start'){
			if((chk_val > half_end || chk_val < half_start) && (chk_val < public_start || chk_val > public_end )){
				result_val = 0;	
			}
		
	}else if(type == 'y_end'){
		if((chk_val > half_end || chk_val < half_start) && (chk_val < public_start || chk_val > public_end )){
			result_val = 0;	
		}
	}else if(type == 'h_start'){
		
		if((chk_val > year_end || chk_val < year_start) && (chk_val < public_start || chk_val > public_end )){
			result_val = 0;	
		}
	}else if(type == 'h_end'){
		if((chk_val > year_end || chk_val < year_start) && (chk_val < public_start || chk_val > public_end )){
			result_val = 0;	
		}
	}else if(type == 'p_start'){
		if((chk_val > year_end || chk_val < year_start) && (chk_val < half_start || chk_val > half_end )){
			result_val = 0;	
		}
	}else{
		if((chk_val > year_end || chk_val < year_start) && (chk_val < half_start || chk_val > half_end )){
			result_val = 0;	
		}
	}
	return result_val;
}
function integer_string(string_data){
	var string_data_m = string_data.substring(0,2);
	var string_data_d = string_data.substring(3,5);
	
	string_data = string_data_m+string_data_d;
	string_data = string_data*1;
	return string_data;
}
						

function change_period(type,start_end){
	var text_box = "";
	document.getElementById('va_type').value = type;
	if(type == 'year'){
		document.getElementById('year_in_count').value = start_end;
		if(start_end==0){
			text_box = "year_va_start";
		}else{
			text_box = "year_va_end";
		} 
	}else if(type=='half'){
		document.getElementById('half_in_count').value = start_end;
		if(start_end==0){
			text_box = "half_va_start";
		}else{
			text_box = "half_va_end";
		} 
	}else{
		document.getElementById('public_in_count').value = start_end;
		if(start_end==0){
			text_box = "public_va_start";
		}else{
			text_box = "public_va_end";
		} 
	}
	textbox_color_change(text_box);
}
function textbox_color_change(box_name){
	document.getElementById('year_va_start').style.backgroundColor = '#FFFFFF';
	document.getElementById('year_va_end').style.backgroundColor = '#FFFFFF';
	document.getElementById('half_va_start').style.backgroundColor = '#FFFFFF';
	document.getElementById('half_va_end').style.backgroundColor = '#FFFFFF';
	document.getElementById('public_va_start').style.backgroundColor = '#FFFFFF';
	document.getElementById('public_va_end').style.backgroundColor = '#FFFFFF';
	document.getElementById(box_name).style.backgroundColor = '#EEEEEE';
}
function box_coloring(box_date,last_day,start_end,type){
	
	var mon = box_date.substr(0,2);
	document.getElementById('last_day').value = last_day;
	var chk_type = "";
	
	if(type == 'year'){
		chk_type = "year_";
	}else if(type == "half"){
		chk_type = "half_";
	}else{
		chk_type = "public_";
	}
	var chk_type_date = chk_type+box_date;
	var frm = document.getElementById("vaca_cal");
	var fDoc = frm.contentWindow || frm.contentDocument;
	if(fDoc.document){
		fDoc = fDoc.document;
	}
	
	for(var i=1; i<last_day; i++){
		if(i < 10){
			i = String('0'+i);
		}
		var box_reset = chk_type+mon+"/"+i;
		fDoc.getElementById(box_reset).style.display = "none";
	}


	fDoc.getElementById(chk_type_date).style.display = "block";
	buffer_coloring(type);
	
}
function buffer_coloring(type){

	var year_start = document.getElementById('year_va_start');
	var year_end = document.getElementById('year_va_end');
	var half_start = document.getElementById('half_va_start');
	var half_end = document.getElementById('half_va_end');
	var public_start = document.getElementById('public_va_start');	
	var public_end = document.getElementById('public_va_end');
	var page_month = window.frames['vaca_cal'].document.getElementById('page_month').value;
	var page_year = window.frames['vaca_cal'].document.getElementById('page_year').value;
	var page_end_day = window.frames['vaca_cal'].document.getElementById('page_end_day').value;
	var y_start_year = document.getElementById('y_start_year');
	var y_end_year = document.getElementById('y_end_year');
	var h_start_year = document.getElementById('h_start_year');
	var h_end_year = document.getElementById('h_end_year');
	var p_start_year = document.getElementById('p_start_year');
	var p_end_year = document.getElementById('p_end_year');

	var start_month = "";
	var start_day = "";
	var end_month = "";
	var end_day = "";

	var start = "";
	var end = "";
	var month = "";
	
	
	
	if(type == "year"){
		start = year_start.value;
		end = year_end.value;
		start_year = y_start_year.value;
		end_year = y_end_year.value;
	}else if(type == "half"){
		start = half_start.value;
		end = half_end.value;
		start_year = h_start_year.value;
		end_year = h_end_year.value;
	}else{
		start = public_start.value;
		end = public_end.value;
		start_year = p_start_year.value;
		end_year = p_end_year.value;
	}
	
	if(start != "" && end !=""){
		start_month = start.substr(0,2);
		start_day = start.substr(3,2)*1;
		end_month = end.substr(0,2);
		end_day = end.substr(3,2)*1;
	
		if(start_month != end_month){
			
			if(page_month == end_month){
				start_day = 1;	
			}else if(page_month == start_month){
				end_day = page_end_day;
			}else if((page_month > start_month) && (page_month < end_month)){
				start_day = 1;
				end_day = page_end_day;
			}else if((end_year > start_year) && page_month > start_month && page_month > end_month){
				start_day = 1;
				end_day = page_end_day;
			}
			
		}
		if((start_month <= page_month && end_month >= page_month) || end_year > start_year){ 	
			for(var i=start_day; i<=end_day; i++){	
				if(i < 10){
					i = String('0'+i);
				}
				var colorbox = type+"_"+page_month+"/"+i;
				
				window.frames['vaca_cal'].document.getElementById(colorbox).style.display = "block";
				
			}
		}
	}
}
function erase_coloring(start,end,type){
	
	
	var page_month = window.frames['vaca_cal'].document.getElementById('page_month').value;
	var page_year = window.frames['vaca_cal'].document.getElementById('page_year').value;
	var page_end_day = window.frames['vaca_cal'].document.getElementById('page_end_day').value;

	if(start != "" && end !=""){
		start_month = start.substr(0,2);
		start_day = start.substr(3,2)*1;
		end_month = end.substr(0,2);
		end_day = end.substr(3,2)*1;
		
		if(start_month != end_month){
				
				if(page_month == end_month){
					start_day = 1;	
				}else if(page_month == start_month){
					end_day = page_end_day;
				}else if((page_month > start_month) && (page_month < end_month)){
					start_day = 1;
					end_day = page_end_day;
				}
				
		}
		
		
		for(var i=start_day; i<=end_day; i++){	
			if(i < 10){
				i = String('0'+i);
			}
			var colorbox = type+"_"+page_month+"/"+i;
			window.frames['vaca_cal'].document.getElementById(colorbox).style.display = "none";
		} 
	}
	
}

function year_reset(){
	
	var year_va_start = document.getElementById('year_va_start');
	var year_va_end = document.getElementById('year_va_end');
	
	erase_coloring(year_va_start.value,year_va_end.value,'year');
	
	year_va_start.value = ""
	year_va_end.value = "";
	document.getElementById('year_va_start').style.backgroundColor = '#FFFFFF';
	document.getElementById('year_va_end').style.backgroundColor = '#FFFFFF';
	document.getElementById('year_in_count').value = "0";
	
}
function half_reset(){
	var half_va_start = document.getElementById('half_va_start');
	var half_va_end = document.getElementById('half_va_end');
	
	erase_coloring(half_va_start.value,half_va_end.value,'half');
	
	half_va_start.value = "";
	half_va_end.value = "";
	document.getElementById('half_va_start').style.backgroundColor = '#FFFFFF';
	document.getElementById('half_va_end').style.backgroundColor = '#FFFFFF';
	document.getElementById('half_in_count').value = "0";

}
function public_reset(){
	var public_va_start = document.getElementById('public_va_start');
	var public_va_end = document.getElementById('public_va_end');
	
	erase_coloring(public_va_start.value,public_va_end.value,'public');
	
	public_va_start.value = "";
	public_va_end.value = "";
	
	document.getElementById('public_va_start').style.backgroundColor = '#FFFFFF';
	document.getElementById('public_va_end').style.backgroundColor = '#FFFFFF';
	document.getElementById('public_in_count').value = "0";
}
function type_color_reset(){
	document.getElementById('type_year').style.backgroundColor = "#FFFFFF";
	document.getElementById('type_half').style.backgroundColor = "#FFFFFF";
	document.getElementById('type_public').style.backgroundColor = "#FFFFFF";
}
function change_mon(mon){
	var fDoc = window.frames['vaca_cal'].document;
	var page_year =  fDoc.getElementById('page_year').value;
	
	fDoc.location.href='vaca_cal.php?month='+mon+'&year='+page_year;

}

function change_year(year){
	var fDoc = window.frames['vaca_cal'].document;
	var mon =  fDoc.getElementById('page_month').value;
	
	fDoc.location.href='vaca_cal.php?month='+mon+'&year='+year;

}

function cal_right(){

	var fDoc = window.frames['vaca_cal'].document;
	var page_month = fDoc.getElementById('page_month').value;
	var page_year =  fDoc.getElementById('page_year').value;
	page_month = page_month*1;
	page_year = page_year *1;
	if(page_month < 12){
		page_month = page_month+1;
		if(page_month < 10){
				page_month = String('0'+page_month);
		}
	}else{
		page_year = page_year+1;
		page_month = '01';
	}
	
	
	fDoc.location.href='vaca_cal.php?month='+page_month+'&year='+page_year;
}
function check_line(){
		var year_start = document.getElementById('year_va_start');
		var year_end = document.getElementById('year_va_end');
		var half_start = document.getElementById('half_va_start');
		var half_end = document.getElementById('half_va_end');
		var public_start = document.getElementById('public_va_start');	
		var public_end = document.getElementById('public_va_end');
		
		if(year_start.value != "" && year_end.value != ""){ 		
			buffer_coloring('year');
		}
		if(half_start.value != "" && half_end.value != ""){ 
			buffer_coloring('half');
		}
		if(public_start.value != "" && public_end.value != ""){ 
			buffer_coloring('public');
		}
}

function cal_left(){
	var frm = document.getElementById("vaca_cal");
	var fDoc = frm.contentWindow || frm.contentDocument;
	if(fDoc.document){
		fDoc = fDoc.document;
	}
	var page_month = fDoc.getElementById('page_month').value;
	var page_year =  fDoc.getElementById('page_year').value;
	page_month = page_month*1;
	page_year = page_year *1;
	if(page_month > 1){
		page_month = page_month-1;
		if(page_month < 10){
				page_month = String('0'+page_month);
		}
	}else{
		page_year = page_year-1;
		page_month = '12';
	}
	fDoc.location.href='vaca_cal.php?month='+page_month+'&year='+page_year;
}