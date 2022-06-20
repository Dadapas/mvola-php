<?php

namespace MVolaphp\Utils;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Helpers
{
	private static $alphabet = "abcdefghijklmnopqrstvuxwyz0123456789";

	public static function isMadaNumber($number)
	{
		return 1 === preg_match('/^03[2-48]\d{7}$/', $number);
	}

	public static function alpha()
	{
		return random_int(0, 34);
	}

	public static function size()
	{
		return random_int(4, 12);
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

	/**
	 * Gererate UUID with php
	 * 
	 * solution from
	 * https://www.uuidgenerator.net/dev-corner/php
	*/ 
	private static function uuidNative()
	{
		// Generate 16 bytes (128 bits) of random data or use the data passed into the function.
	    $data = $data ?? random_bytes(16);
	    assert(strlen($data) == 16);

	    // Set version to 0100
	    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
	    // Set bits 6-7 to 10
	    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

	    // Output the 36 character UUID.
	    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

	public static function uuid()
	{
		try {

			$uuid1 = Uuid::uuid4();
			
			return $uuid1->toString();

		} catch (UnsatisfiedDependencyException $e) {

			return self::uuidNative();
		}
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
		for($i = 0; $i < 35; $i++)
		{
			$str .= self::$alphabet[self::alpha()];
		}
		return $str;
	}

	public static function removeExtraChar($str)
	{
		return preg_replace('/,|-|_|\./', '', $str);
	}

	public static function isUrl($url)
	{
		return 1 === preg_match('/(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/', $url);
	}
}