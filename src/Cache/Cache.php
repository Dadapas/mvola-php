<?php

namespace MobileMoney\Cache;

use MobileMoney\Exception;
use MobileMoney\Utils\Helpers;

/**
 * Creation de fichier cache
 * pour seauvegarder le token
*/
class Cache
{
	protected static $folder;

	protected $exit;


	public function __construct()
	{
		$this->exit = '<?php exit; ?>';

		if (self::$folder == null)
			throw new Exception("Invalid folder.");

	}

	public static function setPath($path)
	{
		self::$folder = $path;
	}

	public function genFileName()
	{
		return "MobileMoney.sdk.php";
	}

	public function getFullName()
	{
		return self::$folder . "/". $this->genFileName();
	}

	public function isFileExist()
	{
		$filename = $this->getFullName();
		return file_exists($filename);
	}

	public function clear()
	{
		unlink($this->getFullName());
	}


	public function write($content)
	{

		$file = $this->getFullName();

		file_put_contents($file, $this->exit . $content);
	}

	public function read()
	{
		$file = $this->getFullName();

		if ( ! file_exists($file))
			$this->write("");
		
		$contents = \file_get_contents($file);

		return \str_replace($this->exit, '', $contents);
	}
}