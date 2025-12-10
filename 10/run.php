#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$entries = [];
	foreach ($input as $line) {
		preg_match('/\[([.#]+)\] (.*) \{(.*)\}/ADi', $line, $m);
		[$all, $lights, $allButtons, $joltages] = $m;

		$buttons = [];
		$allButtons = explode(' ', $allButtons);
		foreach ($allButtons as $b) {
			$buttons[] = explode(',', trim($b, '()'));
		}

		$entries[] = [str_split($lights), $buttons, explode(',', $joltages)];
	}

	function getButtonPresses($machine) {
		[$targetLights, $buttons, $joltages] = $machine;

		$startLights = array_fill(0, count($targetLights), '.');

		$queue = new SplPriorityQueue();
		$queue->setExtractFlags(SplPriorityQueue::EXTR_DATA);

		$queue->insert(['lights' => $startLights, 'presses' => []], 0);

		while (!$queue->isEmpty()) {
			['lights' => $lights, 'presses' => $presses] = $queue->extract();

			foreach ($buttons as $buttonId => $button) {
				$newLights = $lights;

				foreach ($button as $light) {
					if ($newLights[$light] == '#') {
						$newLights[$light] = '.';
					} else {
						$newLights[$light] = '#';
					}
				}

				$newPresses = array_merge($presses, [$buttonId]);
				$queue->insert(['lights' => $newLights, 'presses' => $newPresses], 0 - count($newPresses));

				if ($newLights == $targetLights) {
					return count($newPresses);
				}
			}
		}

		return 0;
	}

	$part1 = 0;
	$i = 0;
	foreach ($entries as $machine) {
		// echo $i++, ' / ', count($entries), "\n";
		$part1 += getButtonPresses($machine);
	}

	echo 'Part 1: ', $part1, "\n";

	// $part2 = 0;
	// echo 'Part 2: ', $part2, "\n";
