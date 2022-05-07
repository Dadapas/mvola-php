<?php

namespace MVolaphp;

use MVolaphp\Objects\PayIn;

interface IPay
{
	public function payIn(PayIn $payDetails);
}