<?php

function calculate_age($age)
	{
			$age = save_format_date($age);
			//echo $age;
			list($year_now, $month_now, $day_now) = explode('-', date('Y-m-d'));
			list($year_age, $month_age, $day_age) = explode('-', date('Y-m-d', strtotime($age)));
			$my_age = $year_now - $year_age;
					
			if($month_now < $month_age)
			 $my_age--;
			else if(($month_now == $month_now) && ($day_now < $day_age))
			 $my_age--;
			echo $my_age;
	}


?>