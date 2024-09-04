<?php

function decimal_number($value_number='')
	{
		if ($value_number == '')
		{
			return '';
		}

		// Remove anything that isn't a number or decimal point.
		//$value_number = trim(preg_replace('/([^0-9\.])/i', '', $value_number));
		$value_number = str_replace(',', '', $value_number);

		return number_format($value_number, 0, '.', ',');
	
	} //END OF control_group function


function save_decimal_number($value_number='')
	{
		if ($value_number == '')
		{
			return '';
		}

		// Remove anything that isn't a number or decimal point.
		$value_number = str_replace(',', '', $value_number);

		return $value_number;
	
	} //END OF control_group function

?>