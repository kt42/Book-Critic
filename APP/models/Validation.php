<?php
class Validation 
{
	// check whether the email string is a valid email address using a regular expression, @param $emailStr - the input email string, @return boolean indicating whether it is a valid email or not
	public function isEmailValid($email)
	{
		if (is_string ( $email )) 
		{
			// oldpattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^"
			$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i";
			return preg_match ( $pattern, $email, $matches );
		}
		
		return false;
	}
	
	// @param $number - the input number, @param $min - the minimum value for the input number, @param $max - the maximum value for the input number, @return boolean indicating whether it is a valid number in the input range
	public function isNumberInRangeValid ($number, $min, $max)
	{
		if (is_numeric ( $number ) && is_numeric ( $max ) && is_numeric ( $min ) && ($min<=$max) && ($number >= $min) && ($number <= $max))
			return true;
		
		return false;
	}
	
	// @param $string - the input string, @param $maxchars - the maximum length of the input string, @return boolean indicating whether it is a valid string of the right max length
	public function isLengthStringValid($string, $maxchars) 
	{
		if (is_int ( $maxchars ) && is_string ( $string ) && (strlen ( $string ) < $maxchars))
			return true;
		return false;
	}
}
?>