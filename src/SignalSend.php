<?php

namespace Ofcold\Component\Process;

use function usleep;
use function time;
use Swoole\Process;

class SignalSend
{
	public static function make(int $pid, int $signal, int $timeout = 0): bool
	{
		if ($pid <= 0) {
			return false;
		}

		// do send
		if ($ret = Process::kill($pid, $signal)) {
			return true;
		}

		// don't want retry
		if ($timeout <= 0) {
			return $ret;
		}

		// failed, try again ...
		$timeout   = $timeout > 0 && $timeout < 10 ? $timeout : 3;

		$startTime = time();

		// retry stop if not stopped.
		while (true) {
			// success
			if (!$isRunning = Process::kill($pid, 0)) {
				break;
			}

			// have been timeout
			if ((time() - $startTime) >= $timeout) {
				return false;
			}

			// try again kill
			$result = Process::kill($pid, $signal);
			usleep(10000);
		}

		return $result;
	}
}
