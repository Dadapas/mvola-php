<?php

namespace MobileMoney;

interface ISender
{
	public function sendMoney(Money $amount, $to);
}