<?php

namespace MobileMoney;

interface IReceiver
{
	public function receiveMoney(Money $amount, $agent);
}