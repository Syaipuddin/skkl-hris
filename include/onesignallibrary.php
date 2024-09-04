<?php

function sendMessage($type,$AndroidID,$message, $photo){

		$content = array(
			"en" => $message
			);

		$heading = array(
		   "en" => $type
		);

		$fields = array(
			'app_id' => "c7715e70-939f-4980-9a93-59b27d39e747",
			'include_player_ids' => array($AndroidID),
			'contents' => $content,
			'headings' => $heading,
			'large_icon' => $photo,
			'template_id' => 'dacb14dd-53f2-4392-b15a-63d640d7a984'
		);
		
		$fields = json_encode($fields);
		// var_dump($fields);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic NGEwMGZmMjItY2NkNy0xMWUzLTk5ZDUtMDAwYzI5NDBlNjJj'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}

	function sendMessageWithID($type,$AndroidID,$message, $photo, $requestID){

		$content = array(
			"en" => $message
			);

		$heading = array(
		   "en" => $type
		);
		
		$hashes_array = array();

		if($type == 'Leave Request' || $type == 'Absence Request' || ($type == 'Attendance Request' && ($requestID != NULL OR $requestID != ""))){

		    array_push($hashes_array, array(
		        "id" => "accept-button",
		        "text" => "Approve"
		    ));
		    
		    array_push($hashes_array, array(
		        "id" => "reject-button",
		        "text" => "Reject"
		    ));

		}

		$fields = array(
			'app_id' => "c7715e70-939f-4980-9a93-59b27d39e747",
			'include_player_ids' => array($AndroidID),
			'data' => array("type" => $type, "requestID" => $requestID),
			'contents' => $content,
			'buttons' => $hashes_array,
			'headings' => $heading,
			'large_icon' => $photo,
			'template_id' => 'dacb14dd-53f2-4392-b15a-63d640d7a984'
		);
		
		$fields = json_encode($fields);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic NGEwMGZmMjItY2NkNy0xMWUzLTk5ZDUtMDAwYzI5NDBlNjJj'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}