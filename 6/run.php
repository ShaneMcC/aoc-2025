#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');

	// Obviously this is a map... totally.
	$input = getInputMap();

	function calculateTotal($problems) {
		$result = 0;

		foreach ($problems as $problem) {
			if ($problem['operation'] == '+') {
				$result += array_sum($problem['values']);
			} else if ($problem['operation'] == '*') {
				$result += array_product($problem['values']);
			}
		}

		return $result;
	}

	$part1 = 0;
	$problems = [];
	foreach ($input as $line) {
		$bits = preg_split('/\s+/', trim(implode('', $line)));
		for ($i = 0; $i < count($bits); $i++) {
			if (is_numeric($bits[$i])) {
				$problems[$i]['values'][] = $bits[$i];
			} else {
				$problems[$i]['operation'] = $bits[$i];
			}
		}
	}

	$part1 = calculateTotal($problems);
	echo 'Part 1: ', $part1, "\n";

	$problems = [];
	$problemId = -1;
	$lastRow = array_pop($input);
	[$minX, $minY, $maxX, $maxY] = getBoundingBox($input);
	for ($i = 0; $i <= $maxX; $i++) {
		$last = $lastRow[$i] ?? '';
		if ($last == '+' || $last == '*') {
			$problemId++;
			$problems[$problemId]['operation'] = $lastRow[$i];
		}

		$col = array_column($input, $i);
		$val = trim(implode('', $col));
		if ($val !== '') {
			$problems[$problemId]['values'][] = $val;
		}
	}

	$part2 = calculateTotal($problems);
	echo 'Part 2: ', $part2, "\n";
