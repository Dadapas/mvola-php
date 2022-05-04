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
	protected $folder;


	public function __construct(\DateTime $expire, $path)
	{
		$this->folder = $path;
		$this->expire = $expire;
	}

	public function genFileName()
	{
		$y = date('Y');
		$m = date('m');
		$d = date('d');

		return "Cached-{$y}-{$m}-{$d}.php";
	}

	public function expire()
	{
		return (new DateTime) > $this->expire;
	}

	public function isWritable()
	{
		$filename = $this->folder . "/". $this->genFileName();
		var_dump($filename);die;
		return is_writable($filename);
	}

	public function clear()
	{
		/*rmdir()*/
	}


	public function write($content)
	{

		$file = $this->folder . "/". $this->genFileName();

		/*if ( ! Helpers::writeFile($file, $content))
		{
			throw new Exception('File is not writtable.');
		}*/

		file_put_contents($file, '<?php exit; ?>'.$content);
	}

	//public function read()
}