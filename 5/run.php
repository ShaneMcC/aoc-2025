#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLineGroups();

	$freshRanges = [];
	foreach ($input[0] as $line) {
		preg_match('#(.*)-(.*)#ADi', $line, $m);
		[$all, $start, $end] = $m;
		$freshRanges[] = [$start, $end];
	}

	function isFresh($freshRanges, $ingredient) {
		foreach ($freshRanges as [$start, $end]) {
			if ($start <= $ingredient && $ingredient <= $end) {
				return true;
			}
		}
		return false;
	}

	$ingredients = $input[1];

	$part1 = 0;

	foreach ($ingredients as $ingredient) {
		if (isFresh($freshRanges, $ingredient)) {
			$part1++;
		}
	}

	echo 'Part 1: ', $part1, "\n";

	// $part2 = 0;
	// echo 'Part 2: ', $part2, "\n";
