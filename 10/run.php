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

		$joltages = array_map(fn($a) => (int)$a, explode(',', $joltages));

		$entries[] = [$lights, $buttons, $joltages];
	}

	function getButtonPresses($machine) {
		[$targetLights, $buttons, $targetJoltages] = $machine;

		// Apparently, each button only has to be pushed a maximum of 1 time,
		// so we just need to find the valid combination of pushes.
		$allPushes = 1 << count($buttons);
		$result = PHP_INT_MAX;

		for ($combo = 0; $combo < $allPushes; $combo++) {
			$newLights = 0;

			// Push the buttons for this combo and see if that is the target.
			$newPresses = 0;
			foreach ($buttons as $i => $button) {
				// Is this a button we need to press this time?
				if (($combo >> $i) & 1) {
					$newLights = $newLights ^ $button;
					$newPresses++;
				}
			}

			if ($newLights == $targetLights) {
				$result = min($result, $newPresses);
			}
		}

		return $result;
	}

	function calculateJoltagePresses($machine) {
		[$targetLights, $buttons, $targetJoltages] = $machine;

		$lines = [];
		$lines[] = '(set-option :produce-models true)';
		$lines[] = '(declare-fun total () Int)';

		// Each button, and how many minimum presses they need
		for ($i = 0; $i < count($buttons); $i++) {
			$lines[] = "(declare-fun Button{$i} () Int)";
			$lines[] = "(assert (>= Button{$i} 0))";
		}

		// Which buttons modify which joltages
		$buttonModifiers = array_fill(0, count($targetJoltages), []);

		foreach ($buttons as $i => $button) {
			for ($pos = 0; $pos < count($targetJoltages); $pos++) {
				if ($button & (1 << $pos)) {
					$buttonModifiers[$pos][] = "Button{$i}";
				}
			}
		}

		// And then assert that pressing them updates the target.
		foreach ($targetJoltages as $pos => $target) {
			$terms = implode(' ', $buttonModifiers[$pos]);
			$lines[] = "(assert (= (+ {$terms}) {$target}))";
		}

		// Calculate the total presses.
		$allButtons = implode(' ', array_map(fn($i) => "Button{$i}", range(0, count($buttons) - 1)));
		$lines[] = "(assert (= total (+ {$allButtons})))";
		$lines[] = "(minimize total)";
		$lines[] = '(check-sat)';
		$lines[] = '(get-value (total))';

		$code = implode("\n", $lines);

		$proc = proc_open('z3 -in', [0 => ['pipe', 'r'], 1 => ['pipe', 'w']], $pipes);
		fwrite($pipes[0], $code);
		fclose($pipes[0]);
		$minCost = stream_get_contents($pipes[1]);
		fclose($pipes[1]);
		proc_close($proc);

		if (preg_match('#\(\(total (.*)\)\)#', $minCost, $m)) {
			return $m[1];
		} else {
			return 0;
		}
	}


	$part1 = $part2 = 0;
	$i = 0;
	foreach ($entries as $machine) {
		if (isDebug()) { echo $i++, ' / ', count($entries), "\n"; }
		$part1 += getButtonPresses($machine);
		$part2 += calculateJoltagePresses($machine);
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
