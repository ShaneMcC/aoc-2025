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

		$target = json_encode($targetJoltages);

		$buttonLines = [];
		foreach ($buttons as $button) {
			$button = strrev(decbin($button));
			$button .= str_repeat('0', count($targetJoltages) - strlen($button));

			$buttonLines[] = 'buttons.append([' . implode(',', str_split($button)) . "])";
		}
		$buttonLines = implode("\n", $buttonLines);

		$lines = [];
		$lines[] = <<<Z3CODE
		#!/usr/bin/python
		from z3 import Int, Optimize, Sum, sat
		total = 0
		target = {$target}

		buttons = []
		{$buttonLines}

		presses = [Int(f'B{i}') for i in range(len(buttons))]
		opt = Optimize()

		for p in presses:
			opt.add(p >= 0)

		for pos in range(len(target)):
			contribution = Sum([presses[i] for i, b in enumerate(buttons) if b[pos]])
			opt.add(contribution == target[pos])

		total_presses = Sum(presses)
		opt.minimize(total_presses)

		if opt.check() == sat:
			model = opt.model()
			total = model.eval(total_presses)

		print(total)
		Z3CODE;

		$code = implode("\n", $lines);
		$tempFile = tempnam(sys_get_temp_dir(), 'AOC25');
		file_put_contents($tempFile, $code);
		chmod($tempFile, 0700);
		$minCost = exec($tempFile);
		unlink($tempFile);

		return $minCost;
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
