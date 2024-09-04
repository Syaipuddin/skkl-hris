<?php
	function hitungJmlInput($data, $dataLama){
		$jmlData=0;
		for ($i=0;$i<count($data);$i++) {
			if ($data[$i] != null || $data[$i] != '') {
				if ($data[$i]!=$dataLama[$i]) {
					$jmlData++;
				}
			}
		}
		return $jmlData;
	}

	function messageProfile($status, $label){
		$firstDayNextMonth = date('d F Y', strtotime('first day of +1 month'));

		switch ($status) {
			case 'success':
				return "<center><label class='alert alert-success'>Submit data ".$label." success. Please wait for HR Admin Approval.<br>You can Request again at $firstDayNextMonth<br><h2>Thank You</h2></label></center>";
				break;
			case 'failed':
				return "<center><label class='alert alert-error'>Submit data ".$label." failed!</label></center>";
				break;
			case 'rendundan':
				return "<center><label class='alert alert-error'>You have already requested ".$label." before. Please wait until HR proccess your previous request.</label></center>";
				break;
		}
	}
?>