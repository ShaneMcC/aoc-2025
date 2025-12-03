#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	if (!function_exists('array_find_key')) {
		function array_find_key($array, $callback) {
			foreach ($array as $key => $x) {
				if (call_user_func($callback, $x) === true)
				return $key;
			}
			return null;
		}
	}

	function getJoltage($line, $batteries) {
		$line = str_split($line);
		$result = 0;

		$startPos = 0;
		for ($i = 1; $i <= $batteries; $i++) {
			$maxEndPos = count($line) - ($batteries - $i);
			$allowedValues = array_slice($line, $startPos, $maxEndPos - $startPos);
			$thisValue = max($allowedValues);
			$startPos += array_find_key($allowedValues, fn ($x) => $x == $thisValue) + 1;

			$result += $thisValue * pow(10, $batteries - $i);
		}

		return $result;
	}

	$part1 = 0;
	$part2 = 0;
	foreach ($input as $line) {
		$part1 += getJoltage($line, 2);
		$part2 += getJoltage($line, 12);
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
