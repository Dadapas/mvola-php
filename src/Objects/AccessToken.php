<?php

namespace Dadapas\MobileMoney\Objects;

/**
 * This file is part of the dadapas/mvola-php library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) TOVOHERY Z. Pascal <tovoherypascal@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

use Serializable;

class AccessToken extends Objects implements Serializable
{
	public $access_token;

	public $expires_in;

	public function serialize()
	{
		return serialize([
			'access_token'	=> $this->access_token,
			'expires_in'	=> $this->expires_in
		]);
	}

	public function unserialize($data)
	{
		$data = unserialize($data);
		$this->access_token = $data['access_token'];
		$this->expires_in = $data['expires_in'];
	}
}