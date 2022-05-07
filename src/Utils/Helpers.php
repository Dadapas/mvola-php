<?php

namespace MVolaphp\Utils;

class Helpers
{
	private static $alphabet = "abcdefghijklmnopqrstvuxwyz0123456789";

	public static function isMadaNumber($number)
	{
		return 1 === preg_match('/^03[2-48]\d{7}$/', $number);
	}

	public static function alpha()
	{
		return \random_int(0, 34);
	}

	public static function size()
	{
		return \random_int(4, 12);
	}

	public static function one($size = 0)
	{
		$str = "";
		if (!$size)
			$size = self::size();

		for($i = 0; $i < $size; $i++) {
			$str .= self::$alphabet[self::alpha()];
		}
		return $str;
	}

	public static function correlationID()
	{
		$str = "";
		$end = "-";
		for($i = 0; $i < 5; $i++) {
			if ($i == 4){
				$end = "";
			}
			$str .= self::one() . $end;
		}

		return $str;
	}

	public static function uuid()
	{
		$str  = self::one(8) . "-";
		$str .= self::one(4) . "-";
		$str .= self::one(4) . "-";
		$str .= self::one(4) . "-";
		$str .= self::one(12);
		
		return $str;
	}

	public static function ref()
	{
		$num = "9865743210";
		
		$str = "";

		for($i = 0; $i < 7; $i++) {
			$str .= $num[\random_int(0,9)];
		}
		return $str;
	}

	public static function transRef()
	{
		$str = "";
		for($i = 0; $i < 50; $i++)
		{
			$str .= self::$alphabet[self::alpha()];
		}
		return $str;
	}

	public static function removeExtraChar($str)
	{
		return \preg_replace('/,|-|_|\./', '', $str);
	}
}