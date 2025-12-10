#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$entries = [];
	foreach ($input as $line) {
		preg_match('/\[([.#]+)\] (.*) \{(.*)\}/ADi', $line, $m);
		[$all, $lights, $allButtons, $joltages] = $m;

		// Convert lights to a binary value for the target
		$lights = bindec(strrev(implode('', array_map(fn($a) => ($a == '#' ? 1 : 0), str_split($lights)))));

		// Convert buttons to a number representing which bits they change.
		$buttons = [];
		$allButtons = explode(' ', $allButtons);
		foreach ($allButtons as $b) {
			$value = 0;
			foreach (explode(',', trim($b, '()')) as $b) {
				$value = $value | (1 << $b);
			}
			$buttons[] = $value;
		}

		$entries[] = [$lights, $buttons, explode(',', $joltages)];
	}

	function getButtonPresses($machine) {
		[$targetLights, $buttons, $targetJoltages] = $machine;

		$startLights = 0;

		$queue = new SplPriorityQueue();
		$queue->setExtractFlags(SplPriorityQueue::EXTR_DATA);
		$queue->insert(['lights' => $startLights, 'presses' => 0], 0);

		while (!$queue->isEmpty()) {
			['lights' => $lights, 'presses' => $presses] = $queue->extract();

			$newPresses = $presses + 1;
			foreach ($buttons as $button) {
				$newLights = $lights ^ $button;
				$queue->insert(['lights' => $newLights, 'presses' => $newPresses], 0 - $newPresses);

				if ($newLights == $targetLights) {
					return $newPresses;
				}
			}
		}

		return 0;
	}

	$part1 = $part2 = 0;
	$i = 0;
	foreach ($entries as $machine) {
		if (isDebug()) { echo $i++, ' / ', count($entries), "\n"; }
		$part1 += getButtonPresses($machine);
	}

	echo 'Part 1: ', $part1, "\n";
	// echo 'Part 2: ', $part2, "\n";
