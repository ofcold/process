<?php

namespace Ofcold\Component\Process;

use function time;
use function sprintf;

class KillAndWait
{
	public static function make(int $pid, int $signal = 15, string $name = 'process', int $waitTime = 10): bool
	{
		// Do stop
		if (! SignalSend::make($pid, $signal)) {
			echo sprintf('Send stop signal to the $s(PID:%s) failed!' . PHP_EOL, $name, $pid);
			return false;
		}

		// not wait, only send signal
		if ($waitTime <= 0) {
			echo sprintf('The %s process stopped.' . PHP_EOL, $name);
			return true;
		}

		$errorMessage  = '';
		$startTime = time();
		echo 'Stopping .';

		// wait exit
		while (true) {
			if (! self::isRunning($pid)) {
				break;
			}

			if (time() - $startTime > $waitTime) {
				$errorMessage = sprintf('Stop the %s(PID:%s) failed(timeout:{%s}s)!', $name, $pid, $waitTime);
				break;
			}

			echo '.';
			sleep(1);
		}

		if ($errorMessage) {
			echo PHP_EOL . $errorMessage . PHP_EOL;
			return false;
		}

		echo ' Successful!' . PHP_EOL;

		return true;
	}
}
