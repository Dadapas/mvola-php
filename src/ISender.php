<?php

namespace MobileMoney;

interface ISender
{
	public function send(Money $amount, $to);
}