<?php

function format_date($value_date='')
{
			if($value_date=='')
				return '';
		date_default_timezone_set('Asia/Jakarta');		
			$date= date('d-M-Y',strtotime($value_date));
			return $date;
}

function save_format_date($value_date='')
{
			if($value_date=='')
				return '';
				date_default_timezone_set('Asia/Jakarta');
			$date= date('Y-m-d',strtotime($value_date));
			return $date;
}

function current_datetime()
{
		date_default_timezone_set('Asia/Jakarta');
		
		return date("Y-m-d H:i:s");
}

?>