<?php
function save_page_urls($conn, $server, $urls, $nik, $session_id, $ip_address)
{
	$date_visit = date('Y-m-d h:i:s');
	$query_insert = "INSERT INTO tb_pages_counter (server, urls, nik, session_id, ip_address, view_date) VALUES ('$server','$urls', '$nik', '$session_id', '$ip_address', '$date_visit')";
	if($nik!='')
	{		
		$query_insert_ex = odbc_exec($conn, $query_insert);
		return  $query_insert_ex;
	}
}	

?>
