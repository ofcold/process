<?php

namespace Ofcold\Component\Process\Contracts;

use Swoole\Process\Pool;

interface ProcessInterface
{
	/**
	 * Run
	 *
	 * @param Pool $pool
	 *
	 * @param int  $workerId
	 */
	public function run(Pool $pool, int $workerId): void;
}

