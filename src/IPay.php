<?php

namespace MobileMoney;

use MobileMoney\Objects\PayIn;

interface IPay
{
	public function payIn(PayIn $payDetails);
}