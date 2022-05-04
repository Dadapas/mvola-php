<?php

namespace MobileMoney;

interface IReceiver
{
	public function receiveMoney($amount, $from);
}