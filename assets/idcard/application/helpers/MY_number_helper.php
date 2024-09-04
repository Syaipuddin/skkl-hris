<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

if (! function_exists('number_to_words')) {
	/**
	 * [merubah bilangan ke kata-kata atau terbilang]
	 * @param  integer $number [bilangan]
	 * @param  string  $lang   [bahasa en/id]
	 * @return [string]        [terbilang]
	 */
	function number_to_words($number=0,$lang='en')
	{
		$lang = strtolower($lang);
		switch ($lang) {
			case 'en':
				$hyphen      = '-';
				$conjunction = ' and ';
				$separator   = ', ';
				$negative    = 'negative ';
				$decimal     = ' point ';
				$dictionary  = array(
						0                   => 'zero',
						1                   => 'one',
						2                   => 'two',
						3                   => 'three',
						4                   => 'four',
						5                   => 'five',
						6                   => 'six',
						7                   => 'seven',
						8                   => 'eight',
						9                   => 'nine',
						10                  => 'ten',
						11                  => 'eleven',
						12                  => 'twelve',
						13                  => 'thirteen',
						14                  => 'fourteen',
						15                  => 'fifteen',
						16                  => 'sixteen',
						17                  => 'seventeen',
						18                  => 'eighteen',
						19                  => 'nineteen',
						20                  => 'twenty',
						30                  => 'thirty',
						40                  => 'fourty',
						50                  => 'fifty',
						60                  => 'sixty',
						70                  => 'seventy',
						80                  => 'eighty',
						90                  => 'ninety',
						100                 => 'hundred',
						1000                => 'thousand',
						1000000             => 'million',
						1000000000          => 'billion',
						1000000000000       => 'trillion',
						1000000000000000    => 'quadrillion',
						1000000000000000000 => 'quintillion'
				);

				if (!is_numeric($number)) {
						return false;
				}

				if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
						// overflow
						trigger_error(
								'convert_number_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
								E_USER_WARNING
						);
						return false;
				}

				if ($number < 0) {
						return $negative . number_to_words(abs($number),'en');
				}

				$string = $fraction = null;

				if (strpos($number, '.') !== false) {
						list($number, $fraction) = explode('.', $number);
				}

				switch (true) {
						case $number < 21:
								$string = $dictionary[$number];
								break;
						case $number < 100:
								$tens   = ((int) ($number / 10)) * 10;
								$units  = $number % 10;
								$string = $dictionary[$tens];
								if ($units) {
										$string .= $hyphen . $dictionary[$units];
								}
								break;
						case $number < 1000:
								$hundreds  = $number / 100;
								$remainder = $number % 100;
								$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
								if ($remainder) {
										$string .= $conjunction . number_to_words($remainder,'en');
								}
								break;
						default:
								$baseUnit = pow(1000, floor(log($number, 1000)));
								$numBaseUnits = (int) ($number / $baseUnit);
								$remainder = $number % $baseUnit;
								$string = number_to_words($numBaseUnits,'en') . ' ' . $dictionary[$baseUnit];
								if ($remainder) {
										$string .= $remainder < 100 ? $conjunction : $separator;
										$string .= number_to_words($remainder,'en');
								}
								break;
				}

				if (null !== $fraction && is_numeric($fraction)) {
						$string .= $decimal;
						$words = array();
						foreach (str_split((string) $fraction) as $number) {
								$words[] = $dictionary[$number];
						}
						$string .= implode(' ', $words);
				}

				break;
			case 'id':
				$hyphen      = ' ';
				$conjunction = ' ';
				$separator   = ' ';
				$negative    = 'negatif ';
				$decimal     = ' koma ';
				$dictionary = array(
					0                   => '',
					1                   => 'satu',
					2                   => 'dua',
					3                   => 'tiga',
					4                   => 'empat',
					5                   => 'lima',
					6                   => 'enam',
					7                   => 'tujuh',
					8                   => 'delapan',
					9                   => 'sembilan',
					10                  => 'puluh',
					11                  => 'belas',
					100                 => 'ratus',
					1000                => 'ribu',
					1000000             => 'juta',
					1000000000          => 'miliar',
					1000000000000       => 'triliun',
					1000000000000000    => 'kuadriliun',
					1000000000000000000 => 'kuintiliun'
				);
				if (!is_numeric($number)) {
						return false;
				}

				if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
						// overflow
						trigger_error(
								'convert_number_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
								E_USER_WARNING
						);
						return false;
				}

				if ($number < 0) {
						return $negative . number_to_words(abs($number),'id');
				}

				$string = $fraction = null;

				if (strpos($number, '.') !== false) {
						list($number, $fraction) = explode('.', $number);
				}

				switch (true) {
						case $number < 10:
								$string = $dictionary[$number];
								break;
						case $number == 10:
								$string = 'sepuluh';
								break;
						case $number < 20:
								$satuan = $number - 10;
								if(substr($number, -1,1)==1){
									$string = 'se'.$dictionary[11] ;

								} else {
									$string = $dictionary[$satuan] .$dictionary[11];

								}
								break;
						case $number < 100:

								$tens   = ((int) ($number / 10));
								$units  = $number % 10;
								if ($tens == 1) {
									$string = 'se';
								} else {
									$string = $dictionary[$tens] .$hyphen;

								}
								$string .= $dictionary[10];
								if ($units) {
									$string .= $hyphen . $dictionary[$units];
								}
								break;
						case $number < 1000:
								$hundreds  = $number / 100;
								$remainder = $number % 100;
								if (floor($hundreds) == 1) {
									$string = 'se' . $dictionary[100];
								} else {
									$string = $dictionary[$hundreds] . ' ' . $dictionary[100];

								}
								if ($remainder) {
										$string .= $conjunction . number_to_words($remainder,'id');
								}
								break;
						default:
								$baseUnit = pow(1000, floor(log($number, 1000)));
								$numBaseUnits = (int) ($number / $baseUnit);
								$remainder = $number % $baseUnit;
								if ($numBaseUnits==1 && $baseUnit < 1000000) {
									$string = 'se' . $dictionary[$baseUnit];
								} else {
									$string = number_to_words($numBaseUnits,'id') . ' ' . $dictionary[$baseUnit];

								}
								if ($remainder) {
										$string .= $remainder < 100 ? $conjunction : $separator;
										$string .= number_to_words($remainder,'id');
								}
								break;
				}

				if (null !== $fraction && is_numeric($fraction)) {
						$string .= $decimal;
						$words = array();
						foreach (str_split((string) $fraction) as $number) {
								$words[] = $dictionary[$number];
						}
						$string .= implode(' ', $words);
				}
				break;
		}
		return $string;
	}
}

if (! function_exists('is_prime')) {
	/**
	 * [mengecek apakah bilangan yang dimaksud adalah prima atau bukan]
	 * @param  [int]  $number [bilangan bulat positif]
	 * @return boolean         [description]
	 */
	function is_prime($number)
	{
		if (is_numeric($number) == false) {
			return false;
		}

		if (is_integer($number) == false) {
			return false;
		}

		if ($number < 2) {
			return false;
		}

		if ($number == 2) {
			return true;
		}

		if ($number % 2 == 0) {
			return false;
		}

		for ($i=3; $i <= ceil(sqrt($number)) ; $i+= 2) {
			if ($number % $i == 0) {
				return false;
			}
		}

		return true;
	}
}

if ( ! function_exists('factorial')) {
	/**
	 * [menghitung hasil faktorial dari angka yang dimasukkan]
	 * @param  [int] $number [description]
	 * @return [int]         [description]
	 */
	function factorial($number)
	{
		if ($number < 2) {
        return 1;
    } else {
        return ($number * factorial($number-1));
    }
	}
}

if ( ! function_exists('prime_factor')) {
	/**
	 * [mencari faktor-faktor prima sebuah bilangan]
	 * @param  [int] $number [bilangan yang dicari faktornya]
	 * @return [array]       [faktor - faktor prima dalam array]
	 */
	function prime_factor($number)
	{
		$sqrt = sqrt($number);
		for ($i = 2; $i <= $sqrt; $i++) {
			if ($number % $i == 0) {
				return array_merge(prime_factor($number/$i), array($i));
			}
		}
		return array($number);
	}
}
/* End of file MY_number.php */
